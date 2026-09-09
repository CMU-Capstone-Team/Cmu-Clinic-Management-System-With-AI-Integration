@extends('layouts.app')

@section('title', 'New Clinic Visit | CMU ClinicAssist AI')

@section('content')
    @php
        $profile = $student->medicalProfile;
    @endphp

    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
        <section class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
                 style="width: min(950px, 100%); max-height: calc(100vh - 32px);">

            <header class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <div>
                    <h1 class="text-xl font-bold text-blue-900">
                        New Clinic Visit
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Nurse interview and vital-sign recording
                    </p>
                </div>

                <a href="{{ route('students.show', $student) }}"
                   class="flex h-9 w-9 items-center justify-center rounded-full text-2xl text-slate-500 hover:bg-slate-100">
                    ×
                </a>
            </header>
                <form id="clinicVisitForm"
                method="POST"
                action="{{ route('clinic-visits.store', $student) }}"
                novalidate
                class="flex min-h-0 flex-1 flex-col">

                @csrf

                <div class="overflow-y-auto px-6 py-5">
                    @if($errors->any())
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
                            <p class="font-semibold text-red-700">
                                Please correct the following:
                            </p>

                            <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Selected Student --}}
                    <div class="mb-5 rounded-xl border border-blue-200 bg-blue-50 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-blue-600">
                                    Selected Student
                                </p>

                                <h2 class="mt-1 text-lg font-bold text-blue-950">
                                    {{ $student->full_name }}
                                </h2>

                                <p class="text-sm text-blue-700">
                                    {{ $student->student_number }} ·
                                    {{ $student->course }} ·
                                    Year {{ $student->year_level }}
                                    @if($student->section)
                                        – {{ $student->section }}
                                    @endif
                                </p>
                            </div>

                            @if($profile?->allergy_status === 'has_allergies')
                                <div class="rounded-lg bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">
                                    Allergy: {{ $profile->allergies }}
                                </div>
                            @elseif($profile?->allergy_status === 'none_known')
                                <div class="rounded-lg bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">
                                    No known allergies
                                </div>
                            @else
                                <div class="rounded-lg bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">
                                    Allergies unconfirmed
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Steps --}}
                    <div class="mb-7 grid grid-cols-3 gap-3">
                        @foreach([
                            1 => 'Interview',
                            2 => 'Vital Signs',
                            3 => 'Notes & Review',
                        ] as $number => $label)
                            <div class="text-center">
                                <div data-indicator="{{ $number }}"
                                     class="mx-auto flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold">
                                    {{ $number }}
                                </div>

                                <p class="mt-2 text-xs font-semibold text-slate-500">
                                    {{ $label }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Step 1 --}}
                    <section data-panel="1">
                        <h2 class="mb-5 border-b border-slate-200 pb-3 font-bold text-blue-900">
                            Student Interview
                        </h2>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Chief Complaint <span class="text-red-500">*</span>
                                </label>

                                <input type="text"
                                       name="chief_complaint"
                                       value="{{ old('chief_complaint') }}"
                                       required
                                       placeholder="Main reason for visiting the clinic"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-3 block text-sm font-semibold text-slate-700">
                                    Symptoms <span class="text-red-500">*</span>
                                </label>

                                <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
                                    @foreach([
                                        'headache' => 'Headache',
                                        'fever' => 'Fever',
                                        'dizziness' => 'Dizziness',
                                        'nausea' => 'Nausea',
                                        'vomiting' => 'Vomiting',
                                        'cough' => 'Cough',
                                        'colds' => 'Colds',
                                        'sore_throat' => 'Sore Throat',
                                        'abdominal_pain' => 'Abdominal Pain',
                                        'diarrhea' => 'Diarrhea',
                                        'body_weakness' => 'Body Weakness',
                                        'muscle_pain' => 'Muscle Pain',
                                        'injury' => 'Injury',
                                        'difficulty_breathing' => 'Difficulty Breathing',
                                        'chest_pain' => 'Chest Pain',
                                        'other' => 'Other',
                                    ] as $value => $label)
                                        <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 p-3 hover:bg-slate-50">
                                            <input type="checkbox"
                                                   name="symptoms[]"
                                                   value="{{ $value }}"
                                                   @checked(in_array($value, old('symptoms', [])))
                                                   class="rounded border-slate-300 text-blue-700">

                                            <span class="text-sm font-medium text-slate-700">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <p id="symptomError"
                                   class="mt-2 hidden text-sm text-red-600">
                                    Select at least one symptom.
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Symptom Details
                                </label>

                                <textarea name="symptom_details"
                                          rows="3"
                                          placeholder="Describe what the student is feeling..."
                                          class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('symptom_details') }}</textarea>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    When did it start?
                                </label>

                                <input type="datetime-local"
                                       name="symptom_started_at"
                                       value="{{ old('symptom_started_at') }}"
                                       max="{{ now()->format('Y-m-d\TH:i') }}"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Duration
                                </label>

                                <input type="text"
                                       name="symptom_duration"
                                       value="{{ old('symptom_duration') }}"
                                       placeholder="Example: 2 days"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Pain Scale
                                </label>

                                <select name="pain_scale"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <option value="">Not applicable</option>

                                    @foreach(range(0, 10) as $pain)
                                        <option value="{{ $pain }}"
                                            @selected((string) old('pain_scale') === (string) $pain)>
                                            {{ $pain }} / 10
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Medications Already Taken
                                </label>

                                <input type="text"
                                       name="medications_taken_before_visit"
                                       value="{{ old('medications_taken_before_visit') }}"
                                       placeholder="Medicine taken before coming"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>
                        </div>
                    </section>

                                    {{-- Step 2 --}}
                                    {{-- Step 2: Basic Vital Signs --}}
                <section data-panel="2" class="hidden">
                    <h2 class="mb-5 border-b border-slate-200 pb-3 font-bold text-blue-900">
                        Basic Vital Signs
                    </h2>

                    <p class="mb-5 text-sm text-slate-500">
                        Record only the measurements available at the clinic.
                    </p>

                    <div class="grid gap-5 md:grid-cols-2">
                        {{-- Temperature --}}
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">
                                Temperature (°C)
                            </label>

                            <input type="number"
                                name="vital_signs[temperature_celsius]"
                                value="{{ old('vital_signs.temperature_celsius') }}"
                                step="0.1"
                                min="30"
                                max="45"
                                placeholder="Example: 36.5"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>

                        {{-- Blood Pressure --}}
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">
                                Blood Pressure (mmHg)
                            </label>

                            <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-2">
                                <input type="number"
                                    name="vital_signs[blood_pressure_systolic]"
                                    value="{{ old('vital_signs.blood_pressure_systolic') }}"
                                    min="50"
                                    max="250"
                                    placeholder="Systolic"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">

                                <span class="font-bold text-slate-500">/</span>

                                <input type="number"
                                    name="vital_signs[blood_pressure_diastolic]"
                                    value="{{ old('vital_signs.blood_pressure_diastolic') }}"
                                    min="30"
                                    max="150"
                                    placeholder="Diastolic"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>

                            <p class="mt-1 text-xs text-slate-400">
                                Example: 120 / 80
                            </p>
                        </div>

                        {{-- Height --}}
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">
                                Height (cm)
                            </label>

                            <input type="number"
                                name="vital_signs[height_cm]"
                                value="{{ old('vital_signs.height_cm') }}"
                                step="0.01"
                                min="50"
                                max="250"
                                placeholder="Example: 165"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>

                        {{-- Weight --}}
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">
                                Weight (kg)
                            </label>

                            <input type="number"
                                name="vital_signs[weight_kg]"
                                value="{{ old('vital_signs.weight_kg') }}"
                                step="0.01"
                                min="2"
                                max="300"
                                placeholder="Example: 60"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                    </div>

                    <div class="mt-5 rounded-lg border border-blue-100 bg-blue-50 p-4 text-sm text-blue-700">
                        BMI will be calculated automatically when both height and weight
                        are provided.
                    </div>
                </section>
                    {{-- Step 3 --}}
                    <section data-panel="3" class="hidden">
                        <h2 class="mb-5 border-b border-slate-200 pb-3 font-bold text-blue-900">
                            Notes and Review
                        </h2>

                        <div class="space-y-5">
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Relevant Medical History
                                </label>

                                <textarea name="relevant_medical_history"
                                          rows="3"
                                          class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('relevant_medical_history') }}</textarea>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">
                                    Nurse Interview Notes
                                </label>

                                <textarea name="nurse_notes"
                                          rows="4"
                                          placeholder="Additional observations and interview notes..."
                                          class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('nurse_notes') }}</textarea>
                            </div>

                            <label class="flex items-center gap-3 rounded-lg border border-slate-200 p-4">
                                <input type="checkbox"
                                       name="guardian_contacted"
                                       value="1"
                                       @checked(old('guardian_contacted'))
                                       class="rounded border-slate-300 text-blue-700">

                                <span class="font-medium text-slate-700">
                                    Parent/guardian has been contacted
                                </span>
                            </label>

                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                                Saving creates an in-progress visit. The AI result
                                and final clinical decision will be reviewed separately.
                            </div>
                        </div>
                    </section>
                </div>

                <footer class="flex items-center justify-between border-t border-slate-200 bg-white px-6 py-4">
                    <a id="cancelButton"
                       href="{{ route('students.show', $student) }}"
                       class="rounded-lg border border-slate-300 px-5 py-2.5 font-semibold text-slate-700 hover:bg-slate-100">
                        Cancel
                    </a>

                    <button id="previousButton"
                            type="button"
                            class="hidden rounded-lg border border-slate-300 px-5 py-2.5 font-semibold text-slate-700">
                        Previous
                    </button>

                    <div class="ml-auto">
                        <button id="nextButton"
                                type="button"
                                class="rounded-lg bg-blue-900 px-6 py-2.5 font-semibold text-white">
                            Next
                        </button>

                        <button id="saveButton"
                                type="submit"
                                class="hidden rounded-lg bg-emerald-600 px-6 py-2.5 font-semibold text-white">
                            Save Clinic Visit
                        </button>
                    </div>
                </footer>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const panels = [...document.querySelectorAll('[data-panel]')];
            const indicators = [...document.querySelectorAll('[data-indicator]')];
            const previousButton = document.getElementById('previousButton');
            const nextButton = document.getElementById('nextButton');
            const saveButton = document.getElementById('saveButton');
            const form = document.getElementById('clinicVisitForm');
            const cancelButton = document.getElementById('cancelButton');
            const symptomError = document.getElementById('symptomError');

            let currentStep = 1;

            function render() {
                panels.forEach(function (panel) {
                    panel.classList.toggle(
                        'hidden',
                        Number(panel.dataset.panel) !== currentStep
                    );
                });

                indicators.forEach(function (indicator) {
                    const active =
                        Number(indicator.dataset.indicator) <= currentStep;

                    indicator.style.backgroundColor =
                        active ? '#1e3a8a' : '#e2e8f0';

                    indicator.style.color =
                        active ? '#ffffff' : '#64748b';
                });

                previousButton.classList.toggle('hidden', currentStep === 1);
                cancelButton.classList.toggle('hidden', currentStep !== 1);
                nextButton.classList.toggle('hidden', currentStep === 3);
                saveButton.classList.toggle('hidden', currentStep !== 3);
            }

            function validateStep() {
                const panel = document.querySelector(
                    `[data-panel="${currentStep}"]`
                );

                for (const field of panel.querySelectorAll('[required]')) {
                    if (!field.checkValidity()) {
                        field.reportValidity();
                        return false;
                    }
                }

                if (currentStep === 1) {
                    const selectedSymptoms = document.querySelectorAll(
                        'input[name="symptoms[]"]:checked'
                    );

                    if (selectedSymptoms.length === 0) {
                        symptomError.classList.remove('hidden');
                        return false;
                    }

                    symptomError.classList.add('hidden');
                }

                return true;
            }

            nextButton.addEventListener('click', function () {
                if (validateStep() && currentStep < 3) {
                    currentStep++;
                    render();
                }
            });

            
         previousButton.addEventListener('click', function () {
            if (currentStep > 1) {
            currentStep--;
        render();
    }
});


            form.addEventListener('submit', function () {
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
            saveButton.classList.add('opacity-60', 'cursor-not-allowed');
        });

            render();
        });
    </script>
@endsection