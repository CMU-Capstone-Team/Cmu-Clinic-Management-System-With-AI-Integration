<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // Ipakita ang Pre-Registration Form
    public function create()
    {
        return view('students.pre-register');
    }

    // Process ng Registration
    public function store(Request $request, \App\Services\StudentEmailOtp $otp)
    {
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        // Validate input bago gumawa ng student account
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'full_name' => ['nullable', 'string', 'max:255'],

            'student_number' => [
                'required',
                'string',
                'max:50',
                'unique:students,student_number',
            ],

            'email' => [
                'bail',
                'required',
                'string',
                'email',
                'max:255',
                'unique:students,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8),
                'max:72',
            ],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
        ]);

        $token = $otp->start($validated['email'], [
            'full_name' => $validated['full_name'] ?? $validated['name'],
            'student_number' => $validated['student_number'],
            'password' => Hash::make($validated['password']),
        ]);
        $request->session()->put('student_otp', ['email' => $validated['email'], 'token' => $token]);
        return redirect()->route('student.otp.show')->with('status', 'We sent a verification code to your email.');
    }
}
