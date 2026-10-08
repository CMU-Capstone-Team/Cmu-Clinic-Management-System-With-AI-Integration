<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'blood_type',
        'allergy_status',
        'allergies',
        'current_medications',
        'existing_conditions',
        'past_surgeries',
        'family_medical_history',
        'immunization_notes',
        'additional_notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}