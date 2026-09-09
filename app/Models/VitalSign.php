<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VitalSign extends Model
{
    protected $fillable = [
        'clinic_visit_id',
        'recorded_by',
        'temperature_celsius',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'heart_rate',
        'respiratory_rate',
        'oxygen_saturation',
        'height_cm',
        'weight_kg',
        'bmi',
        'blood_glucose',
        'consciousness_level',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'temperature_celsius' => 'decimal:1',
            'blood_pressure_systolic' => 'integer',
            'blood_pressure_diastolic' => 'integer',
            'heart_rate' => 'integer',
            'respiratory_rate' => 'integer',
            'oxygen_saturation' => 'decimal:2',
            'height_cm' => 'decimal:2',
            'weight_kg' => 'decimal:2',
            'bmi' => 'decimal:2',
            'blood_glucose' => 'decimal:2',
            'recorded_at' => 'datetime',
        ];
    }

    public function clinicVisit(): BelongsTo
    {
        return $this->belongsTo(ClinicVisit::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}