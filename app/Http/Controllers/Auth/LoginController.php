<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // ✅ IDAGDAG ITO PARA GUMANA ANG Hash::check()
use Illuminate\View\View;
use App\Models\Student; // ✅ IDAGDAG DIN ITO PARA MA-CALL ANG Student::where()

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = strtolower(trim($credentials['email']));
        $credentials['email'] = $email;
        // Staff login is independent of student email verification.
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
        $student = Student::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($student && Hash::check($credentials['password'], $student->password ?? '')) {
            if ($student->status === 'rejected') {
                return back()->withErrors(['email' => 'This account is disabled. Please contact the clinic.']);
            }
            if (!$student->email_verified_at || $student->status !== 'active') {
                $token = app(\App\Services\StudentEmailOtp::class)->start($email, ['student_id' => $student->id]);
                $request->session()->put('student_otp', ['email' => $email, 'token' => $token]);
                return redirect()->route('student.otp.show')->with('status', 'Please verify your email to continue.');
            }
            Auth::guard('student')->login($student);
            $request->session()->regenerate();
            return redirect()->route('student.dashboard');
        }

        // 3. INVALID CREDENTIALS
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}