<?php
namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StudentEmailOtp
{
    public function start(string $email, array $payload): string
    {
        \Illuminate\Support\Facades\Validator::make(['email' => $email], [
            'email' => ['required', 'string', 'email', 'max:255'],
        ])->validate();
        DB::table('student_email_otps')->where('locked_until', '<', now()->subDay())->delete();
        // One challenge per address. Restarting does not reset guessing/send limits.
        DB::table('student_email_otps')->insertOrIgnore([
            'email' => $email, 'token' => Str::random(64), 'payload' => '', 'code_hash' => '',
            'expires_at' => now(), 'locked_until' => now(), 'attempts' => 0, 'sends' => 0,
        ]);
        return $this->issue($email, $payload);
    }

    public function issue(string $email, ?array $payload = null, ?string $token = null): string
    {
        return DB::transaction(function () use ($email, $payload, $token) {
            // Writing first also serializes concurrent requests on SQLite.
            DB::table('student_email_otps')->where('email', $email)->increment('attempts', 0);
            $row = DB::table('student_email_otps')->where('email', $email)->lockForUpdate()->first();
            if (!$row || ($token !== null && !hash_equals($row->token, $token))) {
                throw ValidationException::withMessages(['email' => 'This verification session expired. Sign in or register again.']);
            }
            $freshWindow = now()->gte($row->locked_until);
            if (!$freshWindow && ($row->attempts >= 5 || $row->sends >= 5)) {
                throw ValidationException::withMessages(['email' => 'Too many attempts. Please try again in 30 minutes.']);
            }
            if ($row->sent_at && now()->lt(\Illuminate\Support\Carbon::parse($row->sent_at)->addSeconds(60))) {
                throw ValidationException::withMessages(['email' => 'Please wait 60 seconds before requesting another code.']);
            }
            $newToken = $payload !== null ? Str::random(64) : $row->token;
            $code = (string) random_int(100000, 999999);
            // Do not silently claim delivery when Laravel is using its default log mailer.
            if (in_array(config('mail.default'), ['log', 'array', 'failover'], true) && !app()->runningUnitTests()) {
                throw ValidationException::withMessages(['email' => 'Email delivery is not configured yet. Please contact the clinic.']);
            }
            try {
                Mail::send('emails.student-otp', ['code' => $code], function ($message) use ($email) {
                    $message->to($email)->subject('Your CMU Alaga verification code');
                });
            } catch (\Throwable $e) {
                // Never return or log SMTP credentials or the OTP in an error response.
                throw ValidationException::withMessages(['email' => 'We could not send your code. Please try again later.']);
            }
            DB::table('student_email_otps')->where('email', $email)->update([
                'token' => $newToken,
                'payload' => $payload !== null ? Crypt::encryptString(json_encode($payload)) : $row->payload,
                'code_hash' => Hash::make($code), 'sent_at' => now(),
                'expires_at' => now()->addMinutes(10),
                'locked_until' => $freshWindow ? now()->addMinutes(30) : $row->locked_until,
                'attempts' => $freshWindow ? 0 : $row->attempts,
                'sends' => $freshWindow ? 1 : $row->sends + 1,
            ]);
            return $newToken;
        });
    }

    public function verify(string $email, string $token, string $code): Student
    {
        $result = DB::transaction(function () use ($email, $token, $code) {
            DB::table('student_email_otps')->where('email', $email)->increment('attempts', 0);
            $row = DB::table('student_email_otps')->where('email', $email)->lockForUpdate()->first();
            if (!$row || !hash_equals($row->token, $token)) return 'This verification session expired. Please start again.';
            if ($row->attempts >= 5) return 'Too many incorrect codes. Try again after 30 minutes.';
            if (now()->gte($row->expires_at)) return 'Your code has expired. Please request a new code.';
            if (!Hash::check($code, $row->code_hash)) {
                DB::table('student_email_otps')->where('email', $email)->increment('attempts');
                return 'Incorrect code. Please check your email and try again.';
            }
            $data = json_decode(Crypt::decryptString($row->payload), true);
            if (isset($data['student_id'])) {
                $student = Student::whereKey($data['student_id'])->lockForUpdate()->first();
                if (!$student || strtolower($student->email) !== $email || $student->status === 'rejected') {
                    return 'This account cannot be verified. Please contact the clinic.';
                }
            } else {
                if (Student::whereRaw('LOWER(email) = ?', [$email])->exists() ||
                    Student::where('student_number', $data['student_number'])->exists()) {
                    return 'This email or student number is already registered. Please sign in.';
                }
                $student = new Student();
                $student->full_name = $data['full_name'];
                // Support both historical schemas, including installations without name.
                if (Schema::hasColumn('students', 'name')) $student->name = $data['full_name'];
                $student->student_number = $data['student_number'];
                $student->email = $email;
                $student->password = $data['password'];
            }
            $student->email_verified_at = now();
            $student->status = 'active';
            if (Schema::hasColumn('students', 'is_active')) $student->is_active = true;
            $student->save();
            DB::table('student_email_otps')->where('email', $email)->delete();
            return $student;
        });
        if (is_string($result)) throw ValidationException::withMessages(['code' => $result]);
        return $result;
    }
}
