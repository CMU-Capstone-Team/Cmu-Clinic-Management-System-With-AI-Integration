<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalExcuse extends Model
{
    protected $fillable = [
        'clinic_visit_id',
        'issued_by',
        'excuse_number',
        'verification_code',
        'excused_from',
        'excused_until',
        'reason',
        'activity_restrictions',
        'remarks',
        'status',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'excused_from' => 'date',
            'excused_until' => 'date',
            'issued_at' => 'datetime',
        ];
    }

    public function clinicVisit(): BelongsTo
    {
        return $this->belongsTo(ClinicVisit::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}