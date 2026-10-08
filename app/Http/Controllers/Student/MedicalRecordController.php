<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MedicalProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user('student');

        abort_unless($student, 403);

        $profile = MedicalProfile::query()
            ->where('student_id', $student->id)
            ->first();

        return view('students.records', [
            'student' => $student,
            'medical' => $profile?->getAttributes() ?? [],
        ]);
    }

    public function update(Request $request)
    {
        $student = $request->user('student');

        abort_unless($student, 403);

        $validated = $request->validate([
            'allergy_status' => [
                'required',
                Rule::in([
                    'unknown',
                    'none_known',
                    'has_allergies',
                ]),
            ],
            'allergies' => [
                'exclude_unless:allergy_status,has_allergies',
                'required_if:allergy_status,has_allergies',
                'nullable',
                'string',
                'max:2000',
            ],
            'blood_type' => [
                'present',
                'nullable',
                Rule::in([
                    'A+', 'A-',
                    'B+', 'B-',
                    'AB+', 'AB-',
                    'O+', 'O-',
                ]),
            ],
            'existing_conditions' => [
                'present', 'nullable', 'string', 'max:2000',
            ],
            'current_medications' => [
                'present', 'nullable', 'string', 'max:2000',
            ],
            'past_surgeries' => [
                'present', 'nullable', 'string', 'max:2000',
            ],
            'family_medical_history' => [
                'present', 'nullable', 'string', 'max:2000',
            ],
            'immunization_notes' => [
                'present', 'nullable', 'string', 'max:2000',
            ],
        ], [
            'allergies.required_if' =>
                'Please list your allergies when selecting Has allergies.',
        ], [
            'allergy_status' => 'allergy status',
            'blood_type' => 'blood type',
            'existing_conditions' => 'condition',
            'current_medications' => 'current medication',
            'past_surgeries' => 'past surgeries',
            'family_medical_history' => 'family medical history',
            'immunization_notes' => 'immunization',
        ]);

        $validated['allergies'] =
            $validated['allergy_status'] === 'has_allergies'
                ? ($validated['allergies'] ?? null)
                : null;

        // Only the signed-in student's medical profile can be updated.
        // Other fields, including additional_notes, are preserved.
        MedicalProfile::updateOrCreate(
            ['student_id' => $student->id],
            $validated
        );

        return redirect()
            ->route('student.records')
            ->with(
                'medical_success',
                'Medical information saved. Your updates are available to the clinic staff.'
            );
    }
}