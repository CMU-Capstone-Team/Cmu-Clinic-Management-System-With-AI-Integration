<?php

namespace App\Http\Controllers;
use App\Models\ClinicVisit;
use App\Models\MedicalProfile;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = Student::query()
            ->where('is_active', true)
            ->count();

        $studentsWithAllergies = MedicalProfile::query()
            ->where('allergy_status', 'has_allergies')
            ->count();

        $specialConditions = MedicalProfile::query()
            ->whereNotNull('existing_conditions')
            ->whereRaw("TRIM(existing_conditions) <> ''")
            ->whereRaw(
                "LOWER(TRIM(existing_conditions)) NOT IN ('n/a', 'none', 'none recorded')"
            )
            ->count();

        $studentsByCourse = Student::query()
            ->selectRaw('course, COUNT(*) AS total')
            ->where('is_active', true)
            ->whereNotNull('course')
            ->groupBy('course')
            ->orderBy('course')
            ->get();

        $visitsToday = ClinicVisit::query()
            ->whereDate('visited_at', today())
            ->count();

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'visitsToday' => $visitsToday,
            'studentsWithAllergies' => $studentsWithAllergies,
            'specialConditions' => $specialConditions,
            'courseLabels' => $studentsByCourse->pluck('course')->values(),
            'courseData' => $studentsByCourse->pluck('total')->values(),
        ]);
    }
}