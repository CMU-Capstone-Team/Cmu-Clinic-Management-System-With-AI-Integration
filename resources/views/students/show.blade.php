@extends('layouts.app')

@section('title', $student->full_name . ' | Student Profile')

@section('content')
    @php
        $profile = $student->medicalProfile;

        $display = fn ($value) =>
            filled($value) ? $value : 'None Recorded';
    @endphp

    <div class="space-y-5"
         style="width: calc(100% - 32px);
                max-width: 960px;
                margin-left: auto;
                margin-right: auto;">

        <a href="{{ route('students.index') }}"
           class="inline-flex items-center text-sm font-semibold text-blue-800 hover:underline">
            ← Back to Student Records
        </a>

        {{-- Profile Header --}}
        <section class="flex flex-col gap-4 rounded-xl border-l-4 border-blue-900 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-900 text-white">
                    <svg width="28"
                         height="28"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-8v5c3 2 9 2 12 0v-5"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-blue-900">
                        {{ $student->full_name }}
                    </h1>

                    <p class="text-sm text-slate-500">
                        Student ID: {{ $student->student_number }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if($student->is_active)
                    <a href="{{ route('clinic-visits.create', $student) }}"
                       class="rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                        + Start Clinic Visit
                    </a>
                @endif

                <span class="w-fit rounded-full px-4 py-2 text-sm font-semibold
                    {{ $student->is_active
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-slate-200 text-slate-600' }}">
                    {{ $student->is_active
                        ? 'Active Student'
                        : 'Inactive Student' }}
                </span>
            </div>
        </section>

        {{-- Student and Medical Information --}}
        <div class="grid gap-5 md:grid-cols-2">
            {{-- Student Info --}}
            <section class="rounded-xl bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <svg width="20"
                         height="20"
                         class="text-blue-900"
                         fill="currentColor"
                         viewBox="0 0 20 20">
                        <path d="M10 2a4 4 0 100 8 4 4 0 000-8zM3 17a7 7 0 0114 0H3z"/>
                    </svg>

                    <h2 class="font-bold uppercase tracking-wide text-blue-900">
                        Student Info
                    </h2>
                </div>

                <dl class="space-y-4">
                    <div class="flex justify-between gap-4">
                        <dt class="text-sm text-slate-500">Course</dt>
                        <dd class="text-right font-semibold text-slate-900">
                            {{ $display($student->course) }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-sm text-slate-500">
                            Year & Section
                        </dt>

                        <dd class="text-right font-semibold text-slate-900">
                            Year {{ $student->year_level }}

                            @if($student->section)
                                – {{ $student->section }}
                            @endif
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-sm text-slate-500">Age</dt>

                        <dd class="text-right font-semibold text-slate-900">
                            {{ $student->birth_date
                                ? $student->birth_date->age . ' years old'
                                : 'None Recorded' }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-sm text-slate-500">Sex</dt>

                        <dd class="text-right font-semibold text-slate-900">
                            {{ $student->sex
                                ? ucwords(str_replace('_', ' ', $student->sex))
                                : 'None Recorded' }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-sm text-slate-500">Contact</dt>

                        <dd class="text-right font-semibold text-slate-900">
                            {{ $display($student->contact_number) }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-sm text-slate-500">Email</dt>

                        <dd class="break-all text-right font-semibold text-slate-900">
                            {{ $display($student->email) }}
                        </dd>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <dt class="text-sm text-slate-500">Address</dt>

                        <dd class="mt-1 whitespace-pre-line font-semibold text-slate-900">
                            {{ $display($student->address) }}
                        </dd>
                    </div>
                </dl>
            </section>

            {{-- Medical Records --}}
            <section class="rounded-xl bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <svg width="20"
                         height="20"
                         class="text-blue-900"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12h6m-3-3v6m8-3a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>

                    <h2 class="font-bold uppercase tracking-wide text-blue-900">
                        Medical Records
                    </h2>
                </div>

                <dl class="space-y-5">
                    <div class="flex items-start justify-between gap-4">
                        <dt class="text-sm text-slate-500">Allergies</dt>

                        <dd class="max-w-xs text-right">
                            @if($profile?->allergy_status === 'has_allergies')
                                <span class="rounded bg-red-50 px-3 py-1 text-sm font-semibold text-red-700">
                                    {{ $display($profile->allergies) }}
                                </span>
                            @elseif($profile?->allergy_status === 'none_known')
                                <span class="rounded bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">
                                    None Known
                                </span>
                            @else
                                <span class="rounded bg-amber-50 px-3 py-1 text-sm font-semibold text-amber-700">
                                    Unconfirmed
                                </span>
                            @endif
                        </dd>
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <dt class="text-sm text-slate-500">Blood Type</dt>

                        <dd class="text-right font-semibold text-slate-900">
                            {{ $display($profile?->blood_type) }}
                        </dd>
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <dt class="text-sm text-slate-500">Condition</dt>

                        <dd class="max-w-xs whitespace-pre-line text-right font-semibold text-slate-900">
                            {{ $display($profile?->existing_conditions) }}
                        </dd>
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <dt class="text-sm text-slate-500">
                            Current Medications
                        </dt>

                        <dd class="max-w-xs whitespace-pre-line text-right font-semibold text-slate-900">
                            {{ $display($profile?->current_medications) }}
                        </dd>
                    </div>
                </dl>
            </section>
        </div>

        {{-- Emergency Contact --}}
        <section class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                <svg width="20"
                     height="20"
                     class="text-blue-900"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 5a2 2 0 012-2h3l2 5-2 1a16 16 0 007 7l1-2 5 2v3a2 2 0 01-2 2C10 21 3 14 3 5z"/>
                </svg>

                <h2 class="font-bold uppercase tracking-wide text-blue-900">
                    Emergency Contact
                </h2>
            </div>

            <dl class="grid gap-6 sm:grid-cols-3">
                <div>
                    <dt class="text-sm text-slate-500">
                        Parent/Guardian
                    </dt>

                    <dd class="mt-1 font-semibold text-slate-900">
                        {{ $display($student->emergency_contact_name) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">
                        Relationship
                    </dt>

                    <dd class="mt-1 font-semibold text-slate-900">
                        {{ $display($student->emergency_contact_relationship) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">
                        Contact Number
                    </dt>

                    <dd class="mt-1 font-semibold text-slate-900">
                        {{ $display($student->emergency_contact_number) }}
                    </dd>
                </div>
            </dl>
        </section>

        {{-- Additional Medical Information --}}
        <section class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-5 border-b border-slate-200 pb-3">
                <h2 class="font-bold uppercase tracking-wide text-blue-900">
                    Additional Medical Information
                </h2>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    'Past Surgeries' =>
                        $profile?->past_surgeries,

                    'Family Medical History' =>
                        $profile?->family_medical_history,

                    'Immunization Notes' =>
                        $profile?->immunization_notes,
                ] as $label => $value)
                    <article class="rounded-lg border border-slate-200 p-4">
                        <h3 class="text-sm font-semibold text-slate-500">
                            {{ $label }}
                        </h3>

                        <p class="mt-2 whitespace-pre-line font-medium text-slate-900">
                            {{ $display($value) }}
                        </p>
                    </article>
                @endforeach

                <article class="rounded-lg border border-slate-200 p-4 md:col-span-2 lg:col-span-3">
                    <h3 class="text-sm font-semibold text-slate-500">
                        Additional Notes
                    </h3>

                    <p class="mt-2 whitespace-pre-line font-medium text-slate-900">
                        {{ $display($profile?->additional_notes) }}
                    </p>
                </article>
            </div>
        </section>

        {{-- Dynamic Clinic Visit History --}}
        <section class="rounded-xl bg-white p-5 shadow-sm">
            <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
                <svg width="20"
                     height="20"
                     class="text-blue-900"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>

                <h2 class="font-bold text-blue-900">
                    Clinic Visit History
                </h2>
            </div>

            @forelse($student->clinicVisits as $visit)
                @php
                    $triageLevel =
                        $visit->triageResult?->final_triage_level
                        ?? $visit->triageResult?->ai_triage_level;
                @endphp

                <a href="{{ route('clinic-visits.show', $visit) }}"
                   class="mt-4 flex flex-col gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-blue-300 hover:bg-blue-50 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-bold text-slate-950">
                                {{ $visit->chief_complaint }}
                            </p>

                            <span class="rounded-full px-3 py-1 text-xs font-semibold
                                @if($visit->status === 'completed')
                                    bg-emerald-100 text-emerald-700
                                @elseif($visit->status === 'referred')
                                    bg-red-100 text-red-700
                                @elseif($visit->status === 'cancelled')
                                    bg-slate-200 text-slate-600
                                @else
                                    bg-amber-100 text-amber-700
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $visit->status)) }}
                            </span>

                            @if($triageLevel)
                                <span class="rounded-full px-3 py-1 text-xs font-semibold
                                    @if($triageLevel === 'red')
                                        bg-red-100 text-red-700
                                    @elseif($triageLevel === 'yellow')
                                        bg-amber-100 text-amber-700
                                    @else
                                        bg-emerald-100 text-emerald-700
                                    @endif">
                                    {{ ucfirst($triageLevel) }} Triage
                                </span>
                            @endif
                        </div>

                        <p class="mt-2 text-sm text-slate-500">
                            {{ $visit->visit_number }} ·
                            {{ $visit->visited_at->format('F d, Y h:i A') }}
                        </p>

                        @if(!empty($visit->symptoms))
                            <p class="mt-2 text-sm text-slate-600">
                                Symptoms:
                                {{ collect($visit->symptoms)
                                    ->map(
                                        fn ($symptom) =>
                                            ucwords(str_replace('_', ' ', $symptom))
                                    )
                                    ->implode(', ') }}
                            </p>
                        @endif
                    </div>

                    <span class="shrink-0 text-sm font-semibold text-blue-700">
                        View Visit →
                    </span>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center py-7 text-center">
                    <svg width="42"
                         height="42"
                         class="mb-3 text-slate-400"
                         fill="currentColor"
                         viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>

                    <p class="text-sm font-medium text-slate-500">
                        No clinic visits recorded yet.
                    </p>

                    @if($student->is_active)
                        <a href="{{ route('clinic-visits.create', $student) }}"
                           class="mt-4 rounded-lg bg-blue-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                            Start First Clinic Visit
                        </a>
                    @endif
                </div>
            @endforelse
        </section>
    </div>
@endsection