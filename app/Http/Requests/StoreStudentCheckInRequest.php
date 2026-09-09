<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentCheckInRequest extends FormRequest
{
    protected $redirectRoute = 'public.student-check-ins.create';

    public function authorize(): bool
    {
        // This endpoint accepts public check-ins, not staff actions.
        return true;
    }

    protected function prepareForValidation(): void
    {
        $studentNumber = $this->input('student_number');

        if (is_string($studentNumber)) {
            $this->merge([
                'student_number' => strtoupper(trim($studentNumber)),
            ]);
        }
    }

    public function rules(): array
    {
        $phoneRules = [
            'bail',
            'required',
            'string',
            'max:30',
            'regex:/\A(?=(?:\D*\d){7,15}\D*\z)\+?[0-9() .-]+\z/',
        ];

        return [
            // No unique:students rule: returning students may check in again.
            'student_number' => [
                'bail', 'required', 'string', 'max:255',
                'regex:/\A[A-Z0-9][A-Z0-9-]*\z/',
            ],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'birth_date' => [
                'bail', 'required', 'date_format:Y-m-d',
                'after_or_equal:1900-01-01', 'before_or_equal:today',
            ],
            'sex' => ['required', Rule::in(['male', 'female'])],
            'course' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'integer', 'between:1,6'],
            'section' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'contact_number' => $phoneRules,
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_relationship' => ['required', 'string', 'max:255'],
            'emergency_contact_number' => $phoneRules,
            'privacy_acknowledgement' => ['accepted'],

            // Public input cannot link, confirm, number, or timestamp a record.
            'student_id' => ['prohibited'],
            'status' => ['prohibited'],
            'queue_date' => ['prohibited'],
            'queue_number' => ['prohibited'],
            'submitted_at' => ['prohibited'],
            'confirmed_at' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_number.regex' => 'Use letters, numbers, and hyphens for the student number.',
            'birth_date.before_or_equal' => 'Birth date cannot be in the future.',
            'contact_number.regex' => 'Enter a contact number with 7 to 15 digits; spaces, +, parentheses, and hyphens are allowed.',
            'emergency_contact_number.regex' => 'Enter an emergency contact number with 7 to 15 digits; spaces, +, parentheses, and hyphens are allowed.',
            'privacy_acknowledgement.accepted' => 'Please read and acknowledge how your information will be used.',
        ];
    }
}
