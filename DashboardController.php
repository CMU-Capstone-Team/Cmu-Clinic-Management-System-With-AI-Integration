<?php

namespace App\Http\Controllers\Admin;

use App\Models\ClinicVisit;
use App\Models\MedicalProfile;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Total Active Students
        $totalStudents = Student::query()
            ->where('is_active', true)
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

        // 5. Course Distribution (for chart)
        $studentsByCourse = Student::query()
            ->selectRaw('course, COUNT(*) AS total')
            ->where('is_active', true)
            ->whereNotNull('course')
            ->groupBy('course')
            ->orderBy('course')
            ->get();

        // NEW: Pending Approvals Count & List
        $pendingApprovalsCount = Student::query()
            ->where('is_approved', false) // Adjust column name if different
            ->count();

        $pendingStudents = Student::query()
            ->where('is_approved', false)
            ->latest('created_at')
            ->take(5) // Show latest 5 pending
            ->get(['id', 'unique_id', 'full_name', 'student_number', 'created_at']);

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'visitsToday' => $visitsToday,
            'studentsWithAllergies' => $studentsWithAllergies,
            'specialConditions' => $specialConditions,
            'courseLabels' => $studentsByCourse->pluck('course')->values(),
            'courseData' => $studentsByCourse->pluck('total')->values(),
            
            // New variables for blade
            'pendingApprovalsCount' => $pendingApprovalsCount,
            'pendingStudents' => $pendingStudents,
        ]);
    }
}