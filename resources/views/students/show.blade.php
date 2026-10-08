@extends('layouts.app')

@section('title', $student->full_name . ' | Student Profile')

@section('content')
@php
    $profile = $student->medicalProfile;
    $display = fn ($value) => filled($value) ? $value : 'None Recorded';

    $personal = [
        'Course' => $student->course,
        'Year & Section' => collect([
            filled($student->year_level) ? 'Year ' . $student->year_level : null,
            $student->section,
        ])->filter()->implode(' – '),
        'Birthday' => $student->birth_date?->format('F j, Y'),
        'Age' => $student->birth_date
            ? $student->birth_date->age . ' years old'
            : null,
        'Sex' => $student->sex
            ? ucwords(str_replace('_', ' ', $student->sex))
            : null,
        'Contact' => $student->contact_number,
        'Email' => $student->email,
    ];

    $medical = [
        'Blood Type' => $profile?->blood_type,
        'Condition' => $profile?->existing_conditions,
        'Current Medications' => $profile?->current_medications,
    ];

    $emergency = [
        'Parent/Guardian' => $student->emergency_contact_name,
        'Relationship' => $student->emergency_contact_relationship,
        'Contact Number' => $student->emergency_contact_number,
    ];

    $additional = [
        'Past Surgeries' => $profile?->past_surgeries,
        'Family Medical History' => $profile?->family_medical_history,
        'Immunization Notes' => $profile?->immunization_notes,
    ];
@endphp

<div class="space-y-5"
     style="width: calc(100% - 32px); max-width: 960px; margin: 0 auto;">

    <a href="{{ route('students.index') }}"
       class="inline-flex items-center text-sm font-semibold text-blue-800 hover:underline">
        &larr; Back to Student Records
    </a>

    {{-- Profile Header --}}
    <section class="rounded-xl border-l-4 border-blue-900 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex min-w-0 items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-900 text-white">
                    <svg width="28" height="28" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24"
                         aria-hidden="true">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-8v5c3 2 9 2 12 0v-5"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <h1 class="break-words text-2xl font-bold text-blue-900">
                        {{ $student->full_name }}
                    </h1>
                    <p class="text-sm text-slate-500">
                        Student ID: {{ $student->student_number }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                @if($student->is_active)
                    <a href="{{ route('clinic-visits.create', $student) }}"
                       class="inline-flex items-center whitespace-nowrap rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                        + Start Clinic Visit
                    </a>
                @endif

                <a href="{{ route('students.print', $student) }}"
                   target="_blank"
                   rel="noopener"
                   class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    <svg width="17" height="17" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24"
                         aria-hidden="true">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 9V3h12v6M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v7H6z"/>
                    </svg>
                    Print / PDF
                </a>

                <a href="{{ route('students.export-excel', $student) }}"
                   class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">
                    <svg width="17" height="17" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24"
                         aria-hidden="true">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 3v12m-4-4l4 4 4-4M4 16v4a1 1 0 001 1h14a1 1 0 001-1v-4"/>
                    </svg>
                    Export Excel
                </a>

            </div>
        </div>
    </section>

    {{-- Personal and Medical Information --}}
    <div class="grid gap-5 md:grid-cols-2">

        <section class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="mb-5 border-b border-slate-200 pb-3 font-bold uppercase tracking-wide text-blue-900">
                Personal Information
            </h2>

            <dl class="space-y-4">
                @foreach($personal as $label => $value)
                    <div class="flex justify-between gap-4">
                        <dt class="shrink-0 text-sm text-slate-500">
                            {{ $label }}
                        </dt>
                        <dd class="min-w-0 break-words text-right font-semibold text-slate-900">
                            {{ $display($value) }}
                        </dd>
                    </div>
                @endforeach

                <div class="border-t border-slate-100 pt-4">
                    <dt class="text-sm text-slate-500">Address</dt>
                    <dd class="mt-1 whitespace-pre-line break-words font-semibold text-slate-900">{{ $display($student->address) }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="mb-5 border-b border-slate-200 pb-3 font-bold uppercase tracking-wide text-blue-900">
                Medical Records
            </h2>

            <dl class="space-y-5">
                <div class="flex items-start justify-between gap-4">
                    <dt class="text-sm text-slate-500">Allergies</dt>

                    <dd class="max-w-xs break-words text-right">
                        @if($profile?->allergy_status === 'has_allergies')
                            <span class="inline-block rounded bg-red-50 px-3 py-1 text-sm font-semibold text-red-700">
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

                @foreach($medical as $label => $value)
                    <div class="flex items-start justify-between gap-4">
                        <dt class="text-sm text-slate-500">
                            {{ $label }}
                        </dt>
                        <dd class="max-w-xs whitespace-pre-line break-words text-right font-semibold text-slate-900">{{ $display($value) }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

    </div>

    {{-- Emergency Contact --}}
    <section class="rounded-xl bg-white p-6 shadow-sm">
        <h2 class="mb-5 border-b border-slate-200 pb-3 font-bold uppercase tracking-wide text-blue-900">
            Emergency Contact
        </h2>

        <dl class="grid gap-6 sm:grid-cols-3">
            @foreach($emergency as $label => $value)
                <div>
                    <dt class="text-sm text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 break-words font-semibold text-slate-900">
                        {{ $display($value) }}
                    </dd>
                </div>
            @endforeach
        </dl>
    </section>

    {{-- Additional Medical Information --}}
    <section class="rounded-xl bg-white p-6 shadow-sm">
        <h2 class="mb-5 border-b border-slate-200 pb-3 font-bold uppercase tracking-wide text-blue-900">
            Additional Medical Information
        </h2>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($additional as $label => $value)
                <article class="rounded-lg border border-slate-200 p-4">
                    <h3 class="text-sm font-semibold text-slate-500">
                        {{ $label }}
                    </h3>
                    <p class="mt-2 whitespace-pre-line break-words font-medium text-slate-900">{{ $display($value) }}</p>
                </article>
            @endforeach

            <article class="rounded-lg border border-slate-200 p-4 md:col-span-2 lg:col-span-3">
                <h3 class="text-sm font-semibold text-slate-500">
                    Additional Notes
                </h3>
                <p class="mt-2 whitespace-pre-line break-words font-medium text-slate-900">{{ $display($profile?->additional_notes) }}</p>
            </article>
        </div>
    </section>

    {{-- Clinic Visit History --}}
    <section class="rounded-xl bg-white p-5 shadow-sm">
        <h2 class="border-b border-slate-200 pb-3 font-bold text-blue-900">
            Clinic Visit History
        </h2>

        @forelse($student->clinicVisits as $visit)
            <a href="{{ route('clinic-visits.show', $visit) }}"
               class="mt-4 flex flex-col gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-blue-300 hover:bg-blue-50 sm:flex-row sm:items-center sm:justify-between">

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="break-words font-bold text-slate-950">
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
                            {{ ucwords(str_replace('_', ' ', $visit->status ?? 'Unconfirmed')) }}
                        </span>
                    </div>

                    <p class="mt-2 text-sm text-slate-500">
                        {{ $visit->visit_number }} ·
                        {{ $visit->visited_at?->format('F d, Y h:i A') ?? 'None Recorded' }}
                    </p>

                    @if(!empty($visit->symptoms))
                        <p class="mt-2 break-words text-sm text-slate-600">
                            Symptoms:
                            {{ collect($visit->symptoms)
                                ->map(fn ($symptom) => ucwords(str_replace('_', ' ', $symptom)))
                                ->implode(', ') }}
                        </p>
                    @endif
                </div>

                <span class="shrink-0 text-sm font-semibold text-blue-700">
                    View Visit &rarr;
                </span>
            </a>
        @empty
            <div class="py-7 text-center">
                <p class="text-sm font-medium text-slate-500">
                    No clinic visits recorded yet.
                </p>

                @if($student->is_active)
                    <a href="{{ route('clinic-visits.create', $student) }}"
                       class="mt-4 inline-flex rounded-lg bg-blue-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                        Start First Clinic Visit
                    </a>
                @endif
            </div>
        @endforelse
    </section>

</div>
@endsection