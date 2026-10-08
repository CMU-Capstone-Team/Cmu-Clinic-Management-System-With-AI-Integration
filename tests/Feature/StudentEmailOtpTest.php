<?php
namespace Tests\Feature;

use App\Models\Student;
use App\Services\StudentEmailOtp;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StudentEmailOtpTest extends TestCase
{
    private string $code = '';
    protected function setUp(): void {
        parent::setUp();
        // Isolated in-memory connection; never touches the user's clinic database.
        config(['database.default' => 'otp_test', 'database.connections.otp_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ], 'cache.default' => 'array', 'session.driver' => 'array']);
        DB::purge('otp_test');
        Schema::create('students', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('full_name');
            $t->string('email')->unique(); $t->string('student_number')->unique();
            $t->string('password'); $t->string('status'); $t->boolean('is_active')->default(false);
            $t->timestamps();
        });
        (require database_path('migrations/2026_10_04_190000_add_student_email_otp.php'))->up();
        Mail::shouldReceive('send')->andReturnUsing(function ($view, $data, $callback) { $this->code = $data['code']; });
    }
    private function start(): string {
        return app(StudentEmailOtp::class)->start('student@gmail.com', [
            'full_name' => 'Test Student', 'student_number' => 'OTP-TEST-1', 'password' => Hash::make('password123'),
        ]);
    }
    private function fails(callable $callback): void {
        try { $callback(); $this->fail('Expected validation rejection.'); }
        catch (ValidationException $e) { $this->assertNotEmpty($e->errors()); }
    }
    public function test_account_is_created_only_after_correct_otp_and_code_is_single_use(): void {
        $token = $this->start(); $code = $this->code;
        $this->assertSame(0, Student::count());
        $student = app(StudentEmailOtp::class)->verify('student@gmail.com', $token, $code);
        $this->assertNotNull($student->email_verified_at);
        $this->assertSame('active', $student->status);
        $this->assertTrue(Hash::check('password123', $student->password));
        $this->fails(fn () => app(StudentEmailOtp::class)->verify('student@gmail.com', $token, $code));
        $this->assertSame(1, Student::count());
    }
    public function test_wrong_codes_lock_out_even_when_the_correct_code_is_then_supplied(): void {
        $token = $this->start(); $code = $this->code;
        for ($i = 0; $i < 5; $i++) $this->fails(fn () => app(StudentEmailOtp::class)->verify('student@gmail.com', $token, '000000'));
        $this->fails(fn () => app(StudentEmailOtp::class)->verify('student@gmail.com', $token, $code));
        $this->assertSame(0, Student::count());
    }
    public function test_expiration_and_resend_cooldown(): void {
        $token = $this->start();
        $this->fails(fn () => app(StudentEmailOtp::class)->issue('student@gmail.com', null, $token));
        $this->travel(11)->minutes();
        $this->fails(fn () => app(StudentEmailOtp::class)->verify('student@gmail.com', $token, $this->code));
        app(StudentEmailOtp::class)->issue('student@gmail.com', null, $token);
        $this->assertSame(2, DB::table('student_email_otps')->value('sends'));
        $this->assertNotNull(app(StudentEmailOtp::class)->verify('student@gmail.com', $token, $this->code));
    }
    public function test_malformed_email_and_wrong_session_tokens_are_rejected(): void {
        $this->fails(fn () => app(StudentEmailOtp::class)->start('not-an-email', []));
        $this->start();
        $this->fails(fn () => app(StudentEmailOtp::class)->verify('student@gmail.com', 'wrong-session', $this->code));
        $this->assertSame(0, Student::count());
    }
    public function test_existing_pending_account_is_verified_without_duplicate_record(): void {
        $student = Student::create(['name' => 'Existing', 'full_name' => 'Existing', 'email' => 'student@gmail.com',
            'student_number' => 'EXISTING', 'password' => Hash::make('password123'), 'status' => 'pending']);
        $token = app(StudentEmailOtp::class)->start($student->email, ['student_id' => $student->id]);
        app(StudentEmailOtp::class)->verify($student->email, $token, $this->code);
        $this->assertSame(1, Student::count());
        $this->assertSame('active', $student->fresh()->status);
    }
    public function test_unverified_student_cannot_open_portal(): void {
        $student = Student::create(['name' => 'Existing', 'full_name' => 'Existing', 'email' => 'student@gmail.com',
            'student_number' => 'EXISTING', 'password' => Hash::make('password123'), 'status' => 'active']);
        $this->actingAs($student, 'student')->get('/student/profile')->assertRedirect(route('login'));
    }
}
