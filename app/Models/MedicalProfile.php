<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'allergy_status',
        'existing_conditions',
    ];

    // Relationship to Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}