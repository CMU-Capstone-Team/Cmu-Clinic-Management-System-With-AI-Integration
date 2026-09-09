<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'student_number' => strtoupper(
                trim((string) $this->input('student_number'))
            ),

            'email' => $this->filled('email')
                ? strtolower(trim((string) $this->input('email')))
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'student_number' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9-]+$/',
                Rule::unique('students', 'student_number'),
            ],

            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:20'],

            'birth_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'sex' => [
                'nullable',
                Rule::in([
                    'male',
                    'female',
                    'intersex',
                    'not_disclosed',
                ]),
            ],

            'course' => ['required', 'string', 'max:100'],
            'year_level' => ['required', 'integer', 'between:1,6'],
            'section' => ['nullable', 'string', 'max:50'],

            'contact_number' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('students', 'email'),
            ],

            'address' => ['nullable', 'string', 'max:1000'],

            'emergency_contact_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'emergency_contact_relationship' => [
                'nullable',
                'string',
                'max:100',
            ],

            'emergency_contact_number' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
            ],

            'medical_profile' => ['required', 'array'],

            'medical_profile.blood_type' => [
                'nullable',
                'string',
                'max:10',
            ],

            'medical_profile.allergy_status' => [
                'required',
                Rule::in([
                    'unknown',
                    'none_known',
                    'has_allergies',
                ]),
            ],

            'medical_profile.allergies' => [
                'nullable',
                'required_if:medical_profile.allergy_status,has_allergies',
                'string',
                'max:2000',
            ],

            'medical_profile.current_medications' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'medical_profile.existing_conditions' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'medical_profile.past_surgeries' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'medical_profile.family_medical_history' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'medical_profile.immunization_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'medical_profile.additional_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'student_number.regex' =>
                'The student number may only contain letters, numbers, and hyphens.',

            'medical_profile.allergies.required_if' =>
                'Please describe the student allergies.',
        ];
    }
}