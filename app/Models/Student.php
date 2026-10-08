<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ IDAGDAG ITO PARA MAKAPAG-LOGIN ANG STUDENT

class Student extends Authenticatable // ✅ BAGUHIN MULA 'Model' PAPUNTA 'Authenticatable'
{
    use HasFactory;

    protected $fillable = [
        'name',
        'student_number',
        'full_name',     // ✅ IDAGDAG (Base sa migration)
        'email',
        'password',      // ✅ IDAGDAG (Kailangan para sa login/auth)
        'status',        // Legacy status retained for compatibility.
        
        // Existing fields mo
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
        'address',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'year_level' => 'integer',
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    public function medicalProfile(): HasOne
    {
        return $this->hasOne(MedicalProfile::class);
    }

    public function clinicVisits(): HasMany
    {
        return $this->hasMany(ClinicVisit::class);
    }

    // Accessor for full name (Optional, kung gusto mong i-combine ang first/middle/last)
    public function getFullNameAttribute($value): string
    {
    if (filled($value)) {
        return $value;
    }

    return collect([
        $this->first_name,
        $this->middle_name,
        $this->last_name,
        $this->suffix,
    ])->filter()->implode(' ');
    }
}
