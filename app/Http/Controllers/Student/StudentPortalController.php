<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentPortalController extends Controller
{
    public function profile()
    {
        $student = auth()->user();
        return view('students.profile', compact('student'));
    }

    public function visits()
    {
        // Placeholder for now - i-connect mo sa ClinicVisit model later
        $visits = collect([]); 
        return view('students.visits', compact('visits'));
    }

    public function records()
    {
        // Placeholder for now - i-connect mo sa MedicalProfile model later
        return view('students.records');
    }
}