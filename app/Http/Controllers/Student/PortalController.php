<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;



class PortalController extends Controller
{
    // Check Status Page
    public function checkStatus()
    {
        // Dito mo ilalagay ang logic kung paano che-checkin ng student ang status niya
        // For now, return muna ang view
        return view('student.status'); // Gawan mo ng file na resources/views/student/status.blade.php
    }
}