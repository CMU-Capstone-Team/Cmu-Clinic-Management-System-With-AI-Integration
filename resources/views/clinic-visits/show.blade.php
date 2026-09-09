@extends('layouts.app')

@section('title', $clinicVisit->visit_number . ' | Clinic Visit')

@section('content')
    @php
        $student = $clinicVisit->student;
        $vitals = $clinicVisit->vitalSign;
        $triage = $clinicVisit->triageResult;
        $display = fn ($value) => filled($value) ? $value : 'Not recorded';
    @endphp

    <div class="mx-auto space-y-5" style="max-width: 1050px;">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('students.show', $student) }}"
                   class="text-sm font-semibold text-blue-700 hover:underline">
                    ← Back to Student Profile
                </a>

                <h1 class="mt-3 text-3xl font-bold text-slate-950">
                    Clinic Visit
                </h1>

                <p class="text-slate-500">
                    {{ $clinicVisit->visit_number }}
                </p>
            </div>

            <span class="w-fit rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">
                {{ ucwords(str_replace('_', ' ', $clinicVisit->status)) }}
            </span>
        </div>

        <section class="rounded-xl border-l-4 border-blue-900 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-blue-900">
                {{ $student->full_name }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ $student->student_number }} · {{ $student->course }} ·
                {{ $clinicVisit->visited_at->format('F d, Y h:i A') }}
            </p>
        </section>

        <div class="grid gap-5 lg:grid-cols-2">
            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 border-b border-slate-200 pb-3 font-bold text-blue-900">
                    Interview Summary
                </h2>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-slate-500">Chief Complaint</dt>
                        <dd class="mt-1 font-semibold">
                            {{ $clinicVisit->chief_complaint }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">Symptoms</dt>
                        <dd class="mt-2 flex flex-wrap gap-2">
                            @foreach($clinicVisit->symptoms ?? [] as $symptom)
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-sm text-blue-700">
                                    {{ ucwords(str_replace('_', ' ', $symptom)) }}
                                </span>
                            @endforeach
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">Details</dt>
                        <dd class="mt-1 whitespace-pre-line">
                            {{ $display($clinicVisit->symptom_details) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">Pain Scale</dt>
                        <dd class="mt-1 font-semibold">
                            {{ $clinicVisit->pain_scale !== null
                                ? $clinicVisit->pain_scale . '/10'
                                : 'Not recorded' }}
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 border-b border-slate-200 pb-3 font-bold text-blue-900">
                    Vital Signs
                </h2>

                @if($vitals)
                <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-slate-500">Temperature</dt>
                <dd class="font-semibold">
                    {{ filled($vitals->temperature_celsius)
                        ? $vitals->temperature_celsius . ' °C'
                        : 'Not recorded' }}
                </dd>
            </div>

                <div>
                    <dt class="text-sm text-slate-500">Blood Pressure</dt>
                    <dd class="font-semibold">
                        @if(
                            filled($vitals->blood_pressure_systolic) &&
                            filled($vitals->blood_pressure_diastolic)
                        )
                            {{ $vitals->blood_pressure_systolic }}/{{ $vitals->blood_pressure_diastolic }}
                            mmHg
                        @else
                            Not recorded
                        @endif
                    </dd>
            </div>

                <div>
                    <dt class="text-sm text-slate-500">Height</dt>
                    <dd class="font-semibold">
                        {{ filled($vitals->height_cm)
                            ? $vitals->height_cm . ' cm'
                            : 'Not recorded' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Weight</dt>
                    <dd class="font-semibold">
                        {{ filled($vitals->weight_kg)
                            ? $vitals->weight_kg . ' kg'
                            : 'Not recorded' }}
                    </dd>
                </div>


                <div>
                    <dt class="text-sm text-slate-500">BMI</dt>
                    <dd class="font-semibold">
                        {{ filled($vitals->bmi)
                            ? $vitals->bmi
                            : 'Not recorded' }}
                    </dd>
                </div>
</dl>
                @else
                    <p class="text-slate-500">No vital signs recorded.</p>
                @endif
            </section>
        </div>

        <section class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 border-b border-slate-200 pb-3 font-bold text-blue-900">
                Staff Notes
            </h2>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Medical History</p>
                    <p class="mt-1 whitespace-pre-line">
                        {{ $display($clinicVisit->relevant_medical_history) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Nurse Notes</p>
                    <p class="mt-1 whitespace-pre-line">
                        {{ $display($clinicVisit->nurse_notes) }}
                    </p>
                </div>
            </div>
        </section>
        @include('clinic-visits.partials.ai-triage')
        @include('clinic-visits.partials.review-form')
    </div>
@endsection