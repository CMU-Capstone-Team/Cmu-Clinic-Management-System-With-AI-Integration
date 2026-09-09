<?php

namespace App\Http\Controllers;
use App\Services\LocalAiTriageService;
use App\Http\Requests\ReviewClinicVisitRequest;
use App\Http\Requests\StoreClinicVisitRequest;
use App\Models\ClinicVisit;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClinicVisitController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => [
                'nullable',
                'string',
                'max:100',
            ],

            'date' => [
                'nullable',
                'date_format:Y-m-d',
            ],

            'status' => [
                'nullable',
                'in:in_progress,completed,referred,cancelled',
            ],
        ]);

        $search = trim($filters['q'] ?? '');
        $date = $filters['date'] ?? null;
        $status = $filters['status'] ?? null;

        $visits = ClinicVisit::query()
            ->with([
                'student',
                'attendingStaff',
                'triageResult',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $searchTerm = "%{$search}%";

                $query->where(function ($visitQuery) use ($searchTerm) {
                    $visitQuery
                        ->where(
                            'visit_number',
                            'like',
                            $searchTerm
                        )
                        ->orWhere(
                            'chief_complaint',
                            'like',
                            $searchTerm
                        )
                        ->orWhereHas(
                            'student',
                            function ($studentQuery) use ($searchTerm) {
                                $studentQuery
                                    ->where(
                                        'student_number',
                                        'like',
                                        $searchTerm
                                    )
                                    ->orWhere(
                                        'first_name',
                                        'like',
                                        $searchTerm
                                    )
                                    ->orWhere(
                                        'middle_name',
                                        'like',
                                        $searchTerm
                                    )
                                    ->orWhere(
                                        'last_name',
                                        'like',
                                        $searchTerm
                                    );
                            }
                        );
                });
            })
            ->when($date, function ($query) use ($date) {
                $query->whereDate('visited_at', $date);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('visited_at')
            ->paginate(15)
            ->withQueryString();

        $visitsToday = ClinicVisit::query()
            ->whereDate('visited_at', today())
            ->count();

        $inProgress = ClinicVisit::query()
            ->where('status', 'in_progress')
            ->count();

        $completedToday = ClinicVisit::query()
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();

        $referredToday = ClinicVisit::query()
            ->where('status', 'referred')
            ->whereDate('visited_at', today())
            ->count();

        return view('clinic-visits.index', compact(
            'visits',
            'visitsToday',
            'inProgress',
            'completedToday',
            'referredToday',
        ));
    }

    public function create(
        Student $student
    ): View|RedirectResponse {
        abort_unless(
            $student->is_active,
            403,
            'Clinic visits cannot be created for an inactive student.'
        );

        /*
         * Prevent multiple unfinished visits for the same student.
         */
        $existingVisit = $student
            ->clinicVisits()
            ->where('status', 'in_progress')
            ->latest('visited_at')
            ->first();

        if ($existingVisit) {
            return to_route(
                'clinic-visits.show',
                $existingVisit
            )->with(
                'error',
                'This student already has an active clinic visit.'
            );
        }

        $student->load('medicalProfile');

        return view(
            'clinic-visits.create',
            compact('student')
        );
    }

    public function store(
        StoreClinicVisitRequest $request,
        Student $student
    ): RedirectResponse {
        abort_unless(
            $student->is_active,
            403,
            'Clinic visits cannot be created for an inactive student.'
        );

        /*
         * Check again because the form might be submitted twice.
         */
        $existingVisit = $student
            ->clinicVisits()
            ->where('status', 'in_progress')
            ->latest('visited_at')
            ->first();

        if ($existingVisit) {
            return to_route(
                'clinic-visits.show',
                $existingVisit
            )->with(
                'error',
                'This student already has an active clinic visit.'
            );
        }

        $validated = $request->validated();

        $clinicVisit = DB::transaction(function () use (
            $validated,
            $student
        ) {
            $visitData = Arr::except(
                $validated,
                ['vital_signs']
            );

            $visit = ClinicVisit::create(
                array_merge(
                    $visitData,
                    [
                        'visit_number' =>
                            $this->generateVisitNumber(),

                        'student_id' =>
                            $student->id,

                        'attended_by' =>
                            optional(auth()->user())->id,

                        'visited_at' =>
                            now(),

                        'status' =>
                            'in_progress',
                    ]
                )
            );

            $vitalSignData =
                $validated['vital_signs'] ?? [];

            $hasVitalSigns = collect($vitalSignData)
                ->contains(
                    fn ($value) => filled($value)
                );

            if ($hasVitalSigns) {
                $height =
                    $vitalSignData['height_cm'] ?? null;

                $weight =
                    $vitalSignData['weight_kg'] ?? null;

                if (filled($height) && filled($weight)) {
                    $heightInMeters = $height / 100;

                    $vitalSignData['bmi'] = round(
                        $weight / ($heightInMeters ** 2),
                        2
                    );
                }

                $visit->vitalSign()->create(
                    array_merge(
                        $vitalSignData,
                        [
                            'recorded_by' =>
                                auth()->id(),

                            'recorded_at' =>
                                now(),
                        ]
                    )
                );
            }

            /*
             * Create an empty triage record that will later
             * receive the AI summary and staff final decision.
             */
            $visit->triageResult()->create([
                'review_status' => 'pending',
            ]);

            return $visit;
        });

        return to_route(
            'clinic-visits.show',
            $clinicVisit
        )->with(
            'success',
            'Clinic visit recorded successfully.'
        );
    }

    public function show(
        ClinicVisit $clinicVisit
    ): View {
        $clinicVisit->load([
            'student.medicalProfile',
            'attendingStaff',
            'vitalSign.recordedBy',
            'triageResult.reviewer',
        ]);

        return view(
            'clinic-visits.show',
            compact('clinicVisit')
        );
    }

    public function generateAi(
        ClinicVisit $clinicVisit,
        LocalAiTriageService $aiService
    ): RedirectResponse {
        if ($clinicVisit->status !== 'in_progress') {
            return back()->with(
                'error',
                'AI triage cannot be generated because this visit has already been finalized.'
            );
        }

        $result = $aiService->generate(
            $clinicVisit
        );

        $usedFallback = (bool) (
            $result['used_fallback'] ?? false
        );

        unset($result['used_fallback']);

        $clinicVisit
            ->triageResult()
            ->updateOrCreate(
                [],
                $result
            );

        $message = $usedFallback
            ? 'Ollama was unavailable, so a rule-based fallback summary was generated.'
            : 'Local AI summary and triage suggestion generated successfully.';

        return to_route(
            'clinic-visits.show',
            $clinicVisit
        )->with(
            'success',
            $message
        );
    }

    public function review(
        ReviewClinicVisitRequest $request,
        ClinicVisit $clinicVisit
    ): RedirectResponse {
        if ($clinicVisit->status !== 'in_progress') {
            return back()->with(
                'error',
                'This clinic visit has already been finalized.'
            );
        }

        $validated = $request->validated();

        $referredActions = [
            'refer_to_hospital',
            'emergency_transfer',
        ];

        $newStatus = in_array(
            $validated['final_action'],
            $referredActions,
            true
        ) ? 'referred' : 'completed';

        DB::transaction(function () use (
            $clinicVisit,
            $validated,
            $newStatus
        ) {
            $guardianContacted =
                $validated['guardian_contacted']
                || $validated['final_action']
                    === 'contact_guardian';

            $clinicVisit->update([
                'status' =>
                    $newStatus,

                'final_assessment' =>
                    $validated['final_assessment'],

                'final_action' =>
                    $validated['final_action'],

                'final_notes' =>
                    $validated['final_notes'] ?? null,

                'guardian_contacted' =>
                    $guardianContacted,

                'completed_at' =>
                    now(),
            ]);

            /*
             * Check whether an AI result already exists.
             */
            $hasAiResult = filled(
                $clinicVisit
                    ->triageResult
                    ?->ai_summary
            );

            /*
             * Save the staff's final triage and recommendation.
             */
            $clinicVisit
                ->triageResult()
                ->updateOrCreate(
                    [],
                    [
                        'final_triage_level' =>
                            $validated['final_triage_level'],

                        'final_recommendation' =>
                            $validated['final_recommendation'],

                        'reviewer_notes' =>
                            $validated['final_notes'] ?? null,

                        'reviewed_by' =>
                            auth()->id(),

                        'reviewed_at' =>
                            now(),

                        /*
                         * If AI exists, the human-entered result
                         * is treated as a modification.
                         */
                        'review_status' =>
                            $hasAiResult
                                ? 'modified'
                                : 'approved',
                    ]
                );
        });

        $message = $newStatus === 'referred'
            ? 'Clinic visit finalized for hospital referral.'
            : 'Clinic visit completed successfully.';

        return to_route(
            'clinic-visits.show',
            $clinicVisit
        )->with(
            'success',
            $message
        );
    }

    private function generateVisitNumber(): string
    {
        do {
            $visitNumber =
                'CV-'
                . now()->format('Ymd-His')
                . '-'
                . Str::upper(Str::random(4));
        } while (
            ClinicVisit::query()
                ->where(
                    'visit_number',
                    $visitNumber
                )
                ->exists()
        );

        return $visitNumber;
    }
}