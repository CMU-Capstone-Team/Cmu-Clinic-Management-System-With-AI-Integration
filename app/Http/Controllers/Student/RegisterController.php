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
        return view('student.pre-register'); 
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
            
            // Optional: I-validate din yung ibang fields kung required sa form mo
            // 'first_name' => 'required|string|max:255',
            // 'last_name' => 'required|string|max:255',
        ]);

        // 2. Create Student with PENDING Status
        Student::create([
            'name' => $validated['name'],
            'full_name' => $validated['full_name'] ?? null,
            'student_number' => $validated['student_number'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending', // <--- AUTOMATICALLY PENDING
        ]);

        // 3. Redirect with Success Message
        return redirect()->route('login')->with('success', 'Registration successful! Your account is pending admin approval.');
    }
}