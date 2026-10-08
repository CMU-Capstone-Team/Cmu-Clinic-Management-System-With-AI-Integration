<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Services\StudentEmailOtp;
use Illuminate\Http\Request;

class EmailOtpController extends Controller
{
    public function show(Request $request) {
        if (!$request->session()->has('student_otp')) return redirect()->route('login');
        return response()->view('students.verify-otp', ['email' => $request->session()->get('student_otp.email')])
            ->header('Cache-Control', 'no-store');
    }
    public function verify(Request $request, StudentEmailOtp $otp) {
        $request->validate(['code' => ['required', 'digits:6']]);
        $state = $request->session()->get('student_otp');
        if (!$state) return redirect()->route('login');
        $otp->verify($state['email'], $state['token'], $request->string('code')->toString());
        $request->session()->forget('student_otp');
        $request->session()->regenerate();
        return redirect()->route('login')->with('status', 'Email verified! You can now sign in to your account.');
    }
    public function resend(Request $request, StudentEmailOtp $otp) {
        $state = $request->session()->get('student_otp');
        if (!$state) return redirect()->route('login');
        $otp->issue($state['email'], null, $state['token']);
        return back()->with('status', 'A new code has been sent. Use the latest code in your email.');
    }
}
