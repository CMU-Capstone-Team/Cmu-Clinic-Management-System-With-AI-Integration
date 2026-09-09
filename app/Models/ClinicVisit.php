<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClinicVisit extends Model
{
    protected $fillable = [
        'student_check_in_id',
        'visit_number',
        'student_id',
        'attended_by',
        'visited_at',
        'status',
        'chief_complaint',
        'symptoms',
        'symptom_details',
        'symptom_started_at',
        'symptom_duration',
        'pain_scale',
        'medications_taken_before_visit',
        'relevant_medical_history',
        'nurse_notes',
        'final_assessment',
        'final_action',
        'final_notes',
        'guardian_contacted',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
            'symptoms' => 'array',
            'symptom_started_at' => 'datetime',
            'pain_scale' => 'integer',
            'guardian_contacted' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function attendingStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attended_by');
    }

    public function vitalSign(): HasOne
    {
        return $this->hasOne(VitalSign::class);
    }

    public function triageResult(): HasOne
    {
        return $this->hasOne(TriageResult::class);
    }
        public function medicalExcuse(): HasOne
    {
        return $this->hasOne(MedicalExcuse::class);
    }
    public function studentCheckIn(): BelongsTo
    {
        return $this->belongsTo(
            StudentCheckIn::class,
            'student_check_in_id'
        );
    }
}