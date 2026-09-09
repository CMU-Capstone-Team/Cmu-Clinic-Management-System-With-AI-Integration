<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_number',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birth_date',
        'sex',
        'course',
        'year_level',
        'section',
        'contact_number',
        'email',
        'address',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'year_level' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function medicalProfile(): HasOne
    {
        return $this->hasOne(MedicalProfile::class);
    }

    public function getFullNameAttribute(): string
    {
        return collect([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])->filter()->implode(' ');
    }
    public function clinicVisits(): HasMany
{
    return $this->hasMany(ClinicVisit::class);
}

public function checkIns(): HasMany
{
    return $this->hasMany(StudentCheckIn::class, 'student_id');
}
} 