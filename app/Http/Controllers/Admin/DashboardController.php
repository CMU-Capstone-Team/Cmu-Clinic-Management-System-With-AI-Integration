<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicVisit;
use App\Models\MedicalProfile;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Total Active Students
        $totalStudents = Student::query()
            ->where('status', 'active')
            ->count();

        // 2. Visits Today
        $visitsToday = ClinicVisit::query()
            ->whereDate('visited_at', today())
            ->count();

        // 3. Students with Allergies
        $studentsWithAllergies = MedicalProfile::query()
            ->where('allergy_status', 'has_allergies')
            ->count();

        // 4. Special Conditions
        $specialConditions = MedicalProfile::query()
            ->whereNotNull('existing_conditions')
            ->whereRaw("TRIM(existing_conditions) <> ''")
            ->whereRaw(
                "LOWER(TRIM(existing_conditions)) NOT IN ('n/a', 'none', 'none recorded')"
            )
            ->count();

        // 5. Course Distribution (DISABLED: No 'course' column in DB yet)
        // Kung gusto mong gamitin ito, kailangan mo munang mag-add ng 'course' column via migration
        $courseLabels = []; 
        $courseData = [];

        /* 
        $studentsByCourse = Student::query()
            ->select('course', DB::raw('COUNT(*) as total'))
            ->where('status', 'active')
            ->whereNotNull('course')
            ->groupBy('course')
            ->orderBy('total', 'desc')
            ->get();

        $courseLabels = $studentsByCourse->pluck('course')->values();
        $courseData = $studentsByCourse->pluck('total')->values();
        */

        // 6. Pending Approvals Count & List
        $pendingApprovalsCount = Student::query()
            ->where('status', 'pending')
            ->count();

        $pendingStudents = Student::query()
            ->where('status', 'pending')
            ->latest('created_at')
            ->take(5)
            ->get(['id', 'name', 'full_name', 'student_number', 'created_at']);

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'visitsToday' => $visitsToday,
            'studentsWithAllergies' => $studentsWithAllergies,
            'specialConditions' => $specialConditions,
            
            // Chart Data (Empty arrays for now)
            'courseLabels' => $courseLabels,
            'courseData' => $courseData,
            
            // Pending Approvals Data
            'pendingApprovalsCount' => $pendingApprovalsCount,
            'pendingStudents' => $pendingStudents,
        ]);
    }
}