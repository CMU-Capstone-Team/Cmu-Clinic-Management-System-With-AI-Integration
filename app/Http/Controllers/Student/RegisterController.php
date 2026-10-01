<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // Ipakita ang Pre-Registration Form
    public function create()
    {
        return view('students.pre-register'); // May 's' para tumugma sa folder name
    }

    // Process ng Registration
    public function store(Request $request)
    {
        // 1. Validate Input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'student_number' => ['required', 'string', 'unique:students,student_number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // 2. Create Student with PENDING Status
        Student::create([
            'name' => $validated['name'],
            // Kung walang full_name, gamitin ang 'name' bilang fallback para iwas NULL error
            'full_name' => $validated['full_name'] ?? $validated['name'],
            'student_number' => $validated['student_number'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending', // <--- AUTOMATICALLY PENDING
        ]);

        // 3. Redirect to Status Page (HINDI NA DIRECT LOGIN)
        return redirect()->route('student.status')
            ->with('success', 'Registration successful! Please wait for admin approval.');
    }
}