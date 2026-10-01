<?php

namespace App\Http\Controllers\Student;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Display the student status page (Pending/Approved)
     */
    public function checkStatus()
    {
        return view('students.status');
    }

    /**
     * Display the student profile page
     */
    public function profile()
    {
        // Pwede ring gamitin ang Auth::user() kung gusto mong iwas warning sa Intelephense
        $student = Auth::user(); 
        return view('students.profile', compact('student'));
    }

    /**
     * Display the clinic visits history page
     */
    public function visits()
    {
        $student = Auth::user();
        
        // Placeholder data - i-connect sa ClinicVisit model later
        // Halimbawa: $visits = ClinicVisit::where('student_id', $student->id)->latest()->get();
        $visits = collect([]); 
        
        return view('students.visits', compact('visits'));
    }

    /**
     * Display the medical records page
     */
    public function records()
    {
        $student = Auth::user();
        return view('students.records', compact('student'));
    }
}