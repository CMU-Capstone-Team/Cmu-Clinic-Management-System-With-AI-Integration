<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TriageResult extends Model
{
    protected $fillable = [
        'clinic_visit_id',
        'ai_summary',
        'ai_triage_level',
        'ai_recommendations',
        'red_flags',
        'missing_questions',
        'ai_model',
        'ai_generated_at',
        'review_status',
        'final_triage_level',
        'final_recommendation',
        'reviewer_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'red_flags' => 'array',
            'missing_questions' => 'array',
            'ai_generated_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function clinicVisit(): BelongsTo
    {
        return $this->belongsTo(ClinicVisit::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}