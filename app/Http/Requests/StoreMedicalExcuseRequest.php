<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalExcuseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'excused_from' => [
                'required',
                'date',
            ],
            'excused_until' => [
                'required',
                'date',
                'after_or_equal:excused_from',
            ],
            'reason' => [
                'required',
                'string',
                'max:2000',
            ],
            'activity_restrictions' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'excused_from.required' => 'The starting date is required.',
            'excused_until.required' => 'The ending date is required.',
            'excused_until.after_or_equal' =>
                'The ending date must be the same as or later than the starting date.',
            'reason.required' => 'Please enter the reason for the medical excuse.',
        ];
    }
}