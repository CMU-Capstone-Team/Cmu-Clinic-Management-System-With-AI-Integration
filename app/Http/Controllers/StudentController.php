<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim(
            (string) $request->query('search', '')
        );

        $students = Student::query()
            ->when(
                $search !== '',
                function ($query) use ($search): void {
                    $query->where(function ($studentQuery) use ($search): void {
                        $studentQuery
                            ->where(
                                'student_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'middle_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'last_name',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        return view('students.index', [
            'students' => $students,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('students.create');
    }

    public function store(
        StoreStudentRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $medicalProfile = $validated['medical_profile'];

        unset($validated['medical_profile']);

        $student = DB::transaction(
            function () use (
                $validated,
                $medicalProfile
            ): Student {
                $student = Student::create($validated);

                $student
                    ->medicalProfile()
                    ->create($medicalProfile);

                return $student;
            }
        );

        return to_route('students.show', $student)
            ->with('success', 'Student profile created successfully.');
        }

        public function show(Student $student)
        {
            $student->load([
                'medicalProfile',
                'clinicVisits' => function ($query) {
                    $query
                        ->with('triageResult')
                        ->latest('visited_at');
                },
            ]);
        
            return view('students.show', compact('student'));
        }
}