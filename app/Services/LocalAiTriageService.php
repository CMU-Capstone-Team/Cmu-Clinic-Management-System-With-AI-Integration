<?php

namespace App\Services;

use App\Models\ClinicVisit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;
use UnexpectedValueException;

class LocalAiTriageService
{
    private const RED_KEYWORDS = [
        'Possible respiratory emergency' => [
            'respiratory distress',
            'cannot breathe',
            'difficulty breathing',
            'central cyanosis',
            'bluish lips',
            'stridor',
            'hirap huminga',
            'hindi makahinga',
        ],

        'Unresponsive or active convulsion' => [
            'unresponsive',
            'unconscious',
            'active seizure',
            'active convulsion',
            'seizing',
            'walang malay',
            'nangingisay',
        ],

        'Heavy or uncontrolled bleeding' => [
            'heavy bleeding',
            'uncontrolled bleeding',
            'severe bleeding',
            'malakas na pagdurugo',
        ],

        'Possible poisoning or dangerous exposure' => [
            'poisoning',
            'overdose',
            'chemical exposure',
            'dangerous ingestion',
            'snake bite',
            'snakebite',
        ],

        'Immediate mental-health safety concern' => [
            'suicidal',
            'suicide attempt',
            'self-harm',
            'wants to die',
        ],
    ];

    private const YELLOW_KEYWORDS = [
        'Breathing concern' => [
            'wheezing',
            'shortness of breath',
            'chest pain',
            'wheezes',
        ],

        'Circulation or hydration concern' => [
            'ongoing diarrhea',
            'persistent vomiting',
            'vomits everything',
            'unable to drink',
            'recent fainting',
            'fainted',
        ],

        'Injury or time-sensitive exposure' => [
            'open fracture',
            'suspected dislocation',
            'animal bite',
            'needlestick',
            'burn injury',
            'acute trauma',
        ],

        'Neurologic or visual concern' => [
            'altered mental status',
            'confusion',
            'acute weakness',
            'one-sided weakness',
            'acute visual disturbance',
            'blurred vision',
        ],

        'Other prompt-assessment concern' => [
            'severe pain',
            'unable to urinate',
            'testicular pain',
            'worsening rash',
        ],
    ];

    public function generate(
        ClinicVisit $clinicVisit
    ): array {
        $clinicVisit->loadMissing('vitalSign');

        $ruleResult = $this->evaluateRules(
            $clinicVisit
        );

        $summary = $this->fallbackSummary(
            $clinicVisit
        );

        $questions = $this->fallbackQuestions(
            $clinicVisit
        );

        $model = (string) config(
            'services.ollama.model',
            'qwen2.5:3b'
        );

        $usedFallback = false;

        try {
            $schema = [
                'type' => 'object',

                'properties' => [
                    'summary' => [
                        'type' => 'string',
                    ],

                    'missing_questions' => [
                        'type' => 'array',

                        'items' => [
                            'type' => 'string',
                        ],
                    ],
                ],

                'required' => [
                    'summary',
                    'missing_questions',
                ],
            ];

            $response = Http::connectTimeout(5)
                ->timeout(
                    (int) config(
                        'services.ollama.timeout',
                        120
                    )
                )
                ->post(
                    rtrim(
                        (string) config(
                            'services.ollama.url',
                            'http://127.0.0.1:11434'
                        ),
                        '/'
                    ) . '/api/chat',
                    [
                        'model' => $model,
                        'stream' => false,
                        'format' => $schema,

                        'options' => [
                            'temperature' => 0,
                        ],

                        'messages' => [
                            [
                                'role' => 'system',

                                'content' =>
                                    'You are a documentation assistant '
                                    . 'for a university clinic. '
                                    . 'Summarize only the provided facts. '
                                    . 'Do not diagnose any illness. '
                                    . 'Do not prescribe or recommend '
                                    . 'medicine. Do not assign a triage '
                                    . 'level. Clearly state when important '
                                    . 'information was not recorded. '
                                    . 'Return JSON matching the schema.',
                            ],

                            [
                                'role' => 'user',

                                'content' =>
                                    'Create a concise clinical visit '
                                    . 'summary and up to five important '
                                    . 'follow-up questions from this data: '
                                    . json_encode(
                                        $this->caseData(
                                            $clinicVisit
                                        ),
                                        JSON_PRETTY_PRINT
                                        | JSON_UNESCAPED_SLASHES
                                    ),
                            ],
                        ],
                    ]
                )
                ->throw();

            $content = (string) $response->json(
                'message.content'
            );

            $decoded = json_decode(
                $content,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            $generatedSummary = trim(
                (string) ($decoded['summary'] ?? '')
            );

            if ($generatedSummary === '') {
                throw new UnexpectedValueException(
                    'Ollama returned an empty summary.'
                );
            }

            $summary = $generatedSummary;

            $generatedQuestions =
                $this->cleanQuestions(
                    $decoded['missing_questions'] ?? []
                );

            if ($generatedQuestions !== []) {
                $questions = $generatedQuestions;
            }
        } catch (Throwable $exception) {
            report($exception);

            $usedFallback = true;
            $model = 'rule-based-fallback';
        }

        return [
            'ai_summary' => Str::limit(
                $summary,
                4000,
                ''
            ),

            'ai_triage_level' =>
                $ruleResult['level'],

            'ai_recommendations' =>
                $ruleResult['recommendation'],

            'red_flags' =>
                $ruleResult['flags'],

            'missing_questions' =>
                $questions,

            'ai_model' =>
                $model,

            'ai_generated_at' =>
                now(),

            'review_status' =>
                'pending',

            'used_fallback' =>
                $usedFallback,
        ];
    }

    private function evaluateRules(
        ClinicVisit $clinicVisit
    ): array {
        $redFlags = [];
        $yellowFlags = [];

        $text = Str::lower(
            implode(' ', array_filter([
                $clinicVisit->chief_complaint,

                implode(
                    ' ',
                    (array) $clinicVisit->symptoms
                ),

                $clinicVisit->symptom_details,
                $clinicVisit->nurse_notes,
            ]))
        );

        foreach (
            self::RED_KEYWORDS
            as $label => $keywords
        ) {
            if (Str::contains($text, $keywords)) {
                $redFlags[] = $label;
            }
        }

        foreach (
            self::YELLOW_KEYWORDS
            as $label => $keywords
        ) {
            if (Str::contains($text, $keywords)) {
                $yellowFlags[] = $label;
            }
        }

        $vitalSign = $clinicVisit->vitalSign;

        if ($vitalSign) {
            $heartRate = $vitalSign->heart_rate;
            $respiratoryRate =
                $vitalSign->respiratory_rate;
            $temperature =
                $vitalSign->temperature_celsius;
            $oxygen =
                $vitalSign->oxygen_saturation;

            if (
                $heartRate !== null
                && ($heartRate < 50 || $heartRate > 150)
            ) {
                $redFlags[] =
                    "Critical heart rate: {$heartRate} bpm";
            } elseif (
                $heartRate !== null
                && ($heartRate < 60 || $heartRate > 130)
            ) {
                $yellowFlags[] =
                    "High-risk heart rate: {$heartRate} bpm";
            }

            if (
                $respiratoryRate !== null
                && (
                    $respiratoryRate < 10
                    || $respiratoryRate > 30
                )
            ) {
                $yellowFlags[] =
                    'High-risk respiratory rate: '
                    . "{$respiratoryRate}/min";
            }

            if (
                $temperature !== null
                && (
                    (float) $temperature < 36
                    || (float) $temperature > 39
                )
            ) {
                $yellowFlags[] =
                    "High-risk temperature: {$temperature}°C";
            }

            if (
                $oxygen !== null
                && (float) $oxygen < 92
            ) {
                $yellowFlags[] =
                    "Low oxygen saturation: {$oxygen}%";
            }

            $consciousness = Str::lower(
                (string) $vitalSign
                    ->consciousness_level
            );

            if (
                Str::contains(
                    $consciousness,
                    ['unresponsive', 'unconscious']
                )
            ) {
                $redFlags[] =
                    'Patient recorded as unresponsive';
            } elseif (
                $consciousness !== ''
                && ! Str::contains(
                    $consciousness,
                    ['alert', 'awake']
                )
            ) {
                $yellowFlags[] =
                    'Consciousness level requires review: '
                    . $vitalSign->consciousness_level;
            }
        }

        if (
            $clinicVisit->pain_scale !== null
            && $clinicVisit->pain_scale >= 7
        ) {
            $yellowFlags[] =
                'Severe pain score: '
                . $clinicVisit->pain_scale
                . '/10';
        }

        $redFlags = array_values(
            array_unique($redFlags)
        );

        $yellowFlags = array_values(
            array_unique($yellowFlags)
        );

        if ($redFlags !== []) {
            return [
                'level' => 'red',

                'flags' => $redFlags,

                'recommendation' =>
                    'Immediate assessment by clinic staff is '
                    . 'required. Activate the clinic emergency '
                    . 'or hospital-referral protocol when '
                    . 'confirmed. Do not delay action while '
                    . 'waiting for AI output.',
            ];
        }

        if ($yellowFlags !== []) {
            return [
                'level' => 'yellow',

                'flags' => $yellowFlags,

                'recommendation' =>
                    'Prompt assessment by the nurse or doctor '
                    . 'is recommended. Confirm abnormal vital '
                    . 'signs, continue observation, and follow '
                    . 'the approved clinic protocol.',
            ];
        }

        return [
            'level' => 'green',

            'flags' => [],

            'recommendation' =>
                'Proceed with standard clinical assessment '
                . 'and observation. Clinic staff must still '
                . 'confirm the final triage level and action.',
        ];
    }

    private function caseData(
        ClinicVisit $clinicVisit
    ): array {
        $vitalSign = $clinicVisit->vitalSign;

        return [
            'chief_complaint' =>
                $clinicVisit->chief_complaint,

            'symptoms' =>
                $clinicVisit->symptoms,

            'symptom_details' =>
                $clinicVisit->symptom_details,

            'symptom_started_at' =>
                optional(
                    $clinicVisit->symptom_started_at
                )?->toDateTimeString(),

            'symptom_duration' =>
                $clinicVisit->symptom_duration,

            'pain_scale' =>
                $clinicVisit->pain_scale,

            'medications_taken_before_visit' =>
                $clinicVisit
                    ->medications_taken_before_visit,

            'relevant_medical_history' =>
                $clinicVisit
                    ->relevant_medical_history,

            'nurse_notes' =>
                $clinicVisit->nurse_notes,

            'vital_signs' => $vitalSign
                ? [
                    'temperature_celsius' =>
                        $vitalSign
                            ->temperature_celsius,

                    'blood_pressure' =>
                        $vitalSign
                            ->blood_pressure_systolic
                        . '/'
                        . $vitalSign
                            ->blood_pressure_diastolic,

                    'heart_rate' =>
                        $vitalSign->heart_rate,

                    'respiratory_rate' =>
                        $vitalSign
                            ->respiratory_rate,

                    'oxygen_saturation' =>
                        $vitalSign
                            ->oxygen_saturation,

                    'consciousness_level' =>
                        $vitalSign
                            ->consciousness_level,
                ]
                : null,
        ];
    }

    private function fallbackSummary(
        ClinicVisit $clinicVisit
    ): string {
        $parts = [];

        if (filled($clinicVisit->chief_complaint)) {
            $parts[] =
                'Chief complaint: '
                . $clinicVisit->chief_complaint
                . '.';
        }

        if (! empty($clinicVisit->symptoms)) {
            $parts[] =
                'Reported symptoms: '
                . implode(
                    ', ',
                    (array) $clinicVisit->symptoms
                )
                . '.';
        }

        if (filled($clinicVisit->symptom_details)) {
            $parts[] =
                'Additional details: '
                . $clinicVisit->symptom_details
                . '.';
        }

        if ($clinicVisit->pain_scale !== null) {
            $parts[] =
                'Pain score: '
                . $clinicVisit->pain_scale
                . '/10.';
        }

        if ($clinicVisit->vitalSign) {
            $vitalSign = $clinicVisit->vitalSign;

            $parts[] =
                'Recorded vital signs include temperature '
                . ($vitalSign->temperature_celsius ?? 'N/A')
                . '°C, heart rate '
                . ($vitalSign->heart_rate ?? 'N/A')
                . ' bpm, respiratory rate '
                . ($vitalSign->respiratory_rate ?? 'N/A')
                . '/min, and oxygen saturation '
                . ($vitalSign->oxygen_saturation ?? 'N/A')
                . '%.';
        }

        return $parts !== []
            ? implode(' ', $parts)
            : 'The clinic visit was recorded, but detailed '
                . 'clinical information was not provided.';
    }

    private function fallbackQuestions(
        ClinicVisit $clinicVisit
    ): array {
        $questions = [];

        if (
            blank($clinicVisit->symptom_started_at)
            && blank($clinicVisit->symptom_duration)
        ) {
            $questions[] =
                'When did the symptoms begin?';
        }

        if (
            blank(
                $clinicVisit
                    ->medications_taken_before_visit
            )
        ) {
            $questions[] =
                'Has the student taken any medication '
                . 'before this visit?';
        }

        if (
            blank(
                $clinicVisit
                    ->relevant_medical_history
            )
        ) {
            $questions[] =
                'Is there any relevant medical history?';
        }

        if (
            ! Str::contains(
                Str::lower(
                    (string) $clinicVisit->nurse_notes
                ),
                'allerg'
            )
        ) {
            $questions[] =
                'Does the student have any known allergies?';
        }

        if (! $clinicVisit->vitalSign) {
            $questions[] =
                'Can the current vital signs be recorded?';
        }

        return array_slice($questions, 0, 5);
    }

    private function cleanQuestions(
        mixed $questions
    ): array {
        if (! is_array($questions)) {
            return [];
        }

        return collect($questions)
            ->filter(
                fn ($question) =>
                    is_string($question)
                    && trim($question) !== ''
            )
            ->map(
                fn ($question) =>
                    Str::limit(
                        trim($question),
                        250,
                        ''
                    )
            )
            ->unique()
            ->take(5)
            ->values()
            ->all();
    }
}