<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ReviewClinicVisitRequest extends FormRequest
{
    public function authorize(): bool
        return Auth::check()
            && Auth::user()->is_active
            && in_array(
                Auth::user()->role,
                auth()->user()->role,
                ['admin', 'nurse', 'doctor'],
                true
            );
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'guardian_contacted' =>
                $this->boolean('guardian_contacted'),
        ]);
    }

    public function rules(): array
    {
        return [
            'final_assessment' => [
                'required',
                'string',
                'max:5000',
            ],

            'final_triage_level' => [
                'required',
                Rule::in(['green', 'yellow', 'red']),
            ],

            'final_action' => [
                'required',
                Rule::in([
                    'return_to_class',
                    'rest_observe',
                    'send_home',
                    'contact_guardian',
                    'refer_to_hospital',
                    'emergency_transfer',
                ]),
            ],

            'final_recommendation' => [
                'required',
                'string',
                'max:5000',
            ],

            'final_notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'guardian_contacted' => [
                'required',
                'boolean',
            ],
        ];
    }
}