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

        // 1. CHECK STUDENT TABLE FIRST
        $student = Student::where('email', $credentials['email'])->first();

        if ($student && Hash::check($credentials['password'], $student->password)) {
            // Password matched! Check Status now.
            if ($student->status === 'pending') {
                return back()->withErrors(['email' => 'Your account is pending approval. Please wait for admin verification.']);
            }
            
            if ($student->status === 'rejected') {
                return back()->withErrors(['email' => 'Your registration has been rejected. Contact the clinic for details.']);
            }

            // If Active, Login as Student
            // ⚠️ WARNING: Mag-e-error ito kung wala pang 'student' guard sa config/auth.php
            auth()->guard('student')->login($student); 
            return redirect()->route('student.dashboard'); 
        }

        // 2. IF NOT STUDENT, CHECK ADMIN (USERS TABLE)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // 3. INVALID CREDENTIALS
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}