<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
    public function clinicVisitsAttended(): HasMany
{
    return $this->hasMany(ClinicVisit::class, 'attended_by');
}

    public function vitalSignsRecorded(): HasMany
    {
        return $this->hasMany(VitalSign::class, 'recorded_by');
    }

    public function triageReviews(): HasMany
    {
        return $this->hasMany(TriageResult::class, 'reviewed_by');
    }
}