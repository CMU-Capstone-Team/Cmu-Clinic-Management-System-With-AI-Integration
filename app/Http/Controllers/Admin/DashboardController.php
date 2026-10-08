<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicVisit;
use App\Models\MedicalProfile;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = Student::query()
            ->where('is_active', true)
            ->count();

        $visitsToday = ClinicVisit::query()
            ->whereDate('visited_at', today())
            ->count();

        $studentsWithAllergies = Student::query()
            ->whereIn(
                'id',
                MedicalProfile::query()
                    ->select('student_id')
                    ->where('allergy_status', 'has_allergies')
            )
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

        $pendingApprovalsCount = Student::query()
            ->where('is_approved', false)
            ->count();

        $pendingStudents = Student::query()
            ->where('is_approved', false)
            ->latest('created_at')
            ->take(5)
            ->get([
                'id',
                'unique_id',
                'full_name',
                'student_number',
                'created_at',
            ]);

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'visitsToday' => $visitsToday,
            'studentsWithAllergies' => $studentsWithAllergies,
            'specialConditions' => $specialConditions,
            'courseLabels' => $studentsByCourse->pluck('course')->values(),
            'courseData' => $studentsByCourse->pluck('total')->values(),
            'pendingApprovalsCount' => $pendingApprovalsCount,
            'pendingStudents' => $pendingStudents,
        ]);
    }

    public function allergies(): View|RedirectResponse
    {
        $query = Student::query()
            ->whereIn(
                'id',
                MedicalProfile::query()
                    ->select('student_id')
                    ->where('allergy_status', 'has_allergies')
            )
            ->orderBy('full_name')
            ->orderBy('id');

        if ((clone $query)->count() === 1) {
            $student = (clone $query)->first();

            if ($student) {
                return redirect()->route('students.show', $student);
            }
        }

        return view('admin.allergies', [
            'students' => $query->paginate(15),
        ]);
    }
}