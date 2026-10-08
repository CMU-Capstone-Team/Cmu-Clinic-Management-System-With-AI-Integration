<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $students = Student::query()
            ->when(
                $search !== '',
                function ($query) use ($search): void {
                    $query->where(function ($studentQuery) use ($search): void {
                        $studentQuery
                            ->where('student_number', 'like', "%{$search}%")
                            ->orWhere('full_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            )
            ->orderBy('created_at', 'desc')
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_number' => 'required|string|unique:students,student_number',
            'name' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', 'unique:students,email'],
            'password' => 'required|min:8|confirmed',
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|integer',
            'section' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:20',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['status'] = 'active';
        $validated['full_name'] = $validated['full_name'] ?? $validated['name'];
        $validated['email'] = strtolower($validated['email']);
        // Staff may create a clinic record; portal access still requires email OTP.
        if (!\Illuminate\Support\Facades\Schema::hasColumn('students', 'name')) unset($validated['name']);

        $student = Student::create($validated);

        return to_route('students.show', $student)
            ->with('success', 'Student profile created successfully.');
    }

    public function show(Student $student): View
    {
        $student->load([
            'medicalProfile',
            'clinicVisits' => function ($query) {
                $query->latest('visited_at');
            },
        ]);

        return view('students.show', compact('student'));
    }

    /**
     * Search Patient by Unique ID for Quick Check-in
     */
    public function searchPatient(Request $request): RedirectResponse
    {
        $request->validate([
            'unique_id' => 'required|string|max:50'
        ]);

        // Updated: Using student_number as unique_id since that's what exists in DB
        $student = Student::where('student_number', $request->unique_id)->first();

        if (!$student || $student->status !== 'active') {
            return back()->with('error', 'Student not found or account is inactive.');
        }

        return redirect()->route('clinic-visits.create', ['student' => $student->id]);
    }
}