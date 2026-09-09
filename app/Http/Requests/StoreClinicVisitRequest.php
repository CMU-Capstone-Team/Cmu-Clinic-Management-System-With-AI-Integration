<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClinicVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->is_active;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'guardian_contacted' => $this->boolean('guardian_contacted'),
        ]);
    }

    public function rules(): array
    {
        return [
            'chief_complaint' => ['required', 'string', 'max:255'],

            'symptoms' => ['required', 'array', 'min:1'],
            'symptoms.*' => ['required', 'string', 'max:100'],

            'symptom_details' => ['nullable', 'string', 'max:5000'],
            'symptom_started_at' => [
                'nullable',
                'date',
                'before_or_equal:now',
            ],
            'symptom_duration' => ['nullable', 'string', 'max:100'],
            'pain_scale' => ['nullable', 'integer', 'between:0,10'],

            'medications_taken_before_visit' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'relevant_medical_history' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'nurse_notes' => ['nullable', 'string', 'max:5000'],
            'guardian_contacted' => ['required', 'boolean'],

            'vital_signs' => ['nullable', 'array'],

            'vital_signs.temperature_celsius' => [
                'nullable',
                'numeric',
                'between:30,45',
            ],

            'vital_signs.blood_pressure_systolic' => [
                'nullable',
                'integer',
                'between:50,250',
            ],

            'vital_signs.blood_pressure_diastolic' => [
                'nullable',
                'integer',
                'between:30,150',
            ],

            'vital_signs.heart_rate' => [
                'nullable',
                'integer',
                'between:20,250',
            ],

            'vital_signs.respiratory_rate' => [
                'nullable',
                'integer',
                'between:5,80',
            ],

            'vital_signs.oxygen_saturation' => [
                'nullable',
                'numeric',
                'between:50,100',
            ],

            'vital_signs.height_cm' => [
                'nullable',
                'numeric',
                'between:50,250',
            ],

            'vital_signs.weight_kg' => [
                'nullable',
                'numeric',
                'between:2,300',
            ],

            'vital_signs.blood_glucose' => [
                'nullable',
                'numeric',
                'between:20,600',
            ],

            'vital_signs.consciousness_level' => [
                'nullable',
                Rule::in([
                    'alert',
                    'verbal',
                    'pain',
                    'unresponsive',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'symptoms.required' => 'Select at least one symptom.',
            'symptoms.min' => 'Select at least one symptom.',
            'pain_scale.between' => 'Pain scale must be from 0 to 10.',
            'symptom_started_at.before_or_equal' =>
                'Symptom start cannot be a future date.',
        ];
    }
}