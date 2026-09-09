@php
    $hasAiResult = filled($triage?->ai_summary);
    $aiLevel = $triage?->ai_triage_level;

    $levelStyles = match ($aiLevel) {
        'red' => 'bg-red-100 text-red-800 border-red-300',
        'yellow' => 'bg-amber-100 text-amber-800 border-amber-300',
        'green' => 'bg-green-100 text-green-800 border-green-300',
        default => 'bg-slate-100 text-slate-700 border-slate-300',
    };
@endphp

<section class="rounded-xl border border-violet-200 bg-violet-50 p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-violet-900">
                AI-Assisted Triage
            </h2>

            <p class="mt-1 text-sm text-violet-700">
                Local AI summary with rule-based safety screening.
            </p>
        </div>

        @if ($clinicVisit->status === 'in_progress')
            <form
                method="POST"
                action="{{ route('clinic-visits.generate-ai', $clinicVisit) }}"
                onsubmit="
                    const button = this.querySelector('button');
                    button.disabled = true;
                    button.classList.add('cursor-not-allowed', 'opacity-70');
                    this.querySelector('[data-spinner]').classList.remove('hidden');
                    this.querySelector('[data-label]').textContent = 'Generating...';
                "
            >
                @csrf
                <button
                    type="submit"
                    style="
                        background-color: #1e3a8a !important;
                        color: #ffffff !important;
                        min-width: 220px;
                    "
                    class="inline-flex items-center justify-center gap-2
                           rounded-xl px-5 py-3 text-sm font-semibold
                           shadow-sm transition hover:opacity-90
                           disabled:cursor-not-allowed disabled:opacity-70"
                >
                    <svg
                        data-spinner
                        class="hidden h-4 w-4 animate-spin"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>

                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                        ></path>
                    </svg>

                    <span data-label>
                        {{ $hasAiResult
                            ? 'Regenerate AI Summary'
                            : 'Generate AI Summary' }}
                    </span>
                </button>
            </form>
        @endif
    </div>

    @if ($hasAiResult)
        <div class="mt-6 space-y-5">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-semibold text-slate-600">
                    Suggested Triage:
                </span>

                <span
                    class="rounded-full border px-4 py-1.5 text-sm
                           font-bold uppercase {{ $levelStyles }}"
                >
                    {{ $aiLevel ?? 'Pending' }}
                </span>

                <span class="rounded-full bg-white px-3 py-1.5 text-xs font-medium text-violet-700">
                    Staff review required
                </span>
            </div>

            <div class="rounded-xl border border-violet-200 bg-white p-5">
                <h3 class="font-bold text-slate-900">
                    Clinical Summary
                </h3>

                <p class="mt-2 whitespace-pre-line leading-7 text-slate-700">
                    {{ $triage->ai_summary }}
                </p>
            </div>

            <div class="rounded-xl border border-blue-200 bg-blue-50 p-5">
                <h3 class="font-bold text-blue-900">
                    Rule-Based Recommendation
                </h3>

                <p class="mt-2 leading-7 text-blue-800">
                    {{ $triage->ai_recommendations }}
                </p>
            </div>

            @if (! empty($triage->red_flags))
                <div class="rounded-xl border border-red-200 bg-red-50 p-5">
                    <h3 class="font-bold text-red-900">
                        Detected Safety Flags
                    </h3>

                    <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-red-800">
                        @foreach ($triage->red_flags as $flag)
                            <li>{{ $flag }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (! empty($triage->missing_questions))
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                    <h3 class="font-bold text-amber-900">
                        Suggested Follow-up Questions
                    </h3>

                    <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-amber-800">
                        @foreach ($triage->missing_questions as $question)
                            <li>{{ $question }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-violet-600">
                <span>
                    Model:
                    <strong>{{ $triage->ai_model }}</strong>
                </span>

                @if ($triage->ai_generated_at)
                    <span>
                        Generated:
                        <strong>
                            {{ $triage->ai_generated_at->format('M d, Y h:i A') }}
                        </strong>
                    </span>
                @endif
            </div>
        </div>
    @else
        <div class="mt-5 rounded-xl border border-dashed border-violet-300 bg-white/60 p-5">
            <p class="font-medium text-violet-800">
                No AI analysis generated yet.
            </p>

            <p class="mt-1 text-sm text-violet-600">
                Click “Generate AI Summary” to create a local summary,
                suggested triage level, safety flags, and follow-up questions.
            </p>
        </div>
    @endif

    <div class="mt-5 rounded-xl border border-violet-200 bg-violet-100/60 p-4">
        <p class="text-sm leading-6 text-violet-800">
            <strong>Important:</strong>
            This output is decision support only. It does not provide a
            diagnosis or prescription and will not replace the final decision
            of the clinic nurse or doctor.
        </p>
    </div>
</section>