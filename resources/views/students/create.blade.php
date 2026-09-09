@extends('layouts.app')

@section('title', 'Register Student | CMU ClinicAssist AI')

@section('content')
    @php
        $inputClass = 'mt-2 block w-full rounded-xl border border-slate-300 bg-white
            px-4 py-3 text-slate-900 focus:border-blue-700 focus:outline-none
            focus:ring-2 focus:ring-blue-100';

        $labelClass = 'block text-sm font-semibold text-slate-700';

        $medicalErrors = $errors->hasAny([
            'medical_profile.*',
        ]);

        $contactErrors = $errors->hasAny([
            'course',
            'year_level',
            'section',
            'contact_number',
            'email',
            'address',
            'emergency_contact_name',
            'emergency_contact_relationship',
            'emergency_contact_number',
        ]);
    @endphp

    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
        <section
            class="flex w-full flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
            style="max-width: 1050px; max-height: calc(100vh - 32px);"
        >
            <header class="border-b border-slate-200 bg-white px-6 py-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-blue-700">
                            Student Medical Records
                        </p>

                        <h1 class="mt-1 text-2xl font-bold text-blue-950">
                            Register Student
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Create the student profile and record known medical information.
                        </p>
                    </div>

                    <a
                        href="{{ route('students.index') }}"
                        aria-label="Close registration form"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full
                               text-2xl text-slate-500 transition hover:bg-slate-100"
                    >
                        &times;
                    </a>
                </div>

                <div class="mt-5 grid grid-cols-4 gap-2 sm:gap-4">
                    @foreach ([
                        1 => 'Student',
                        2 => 'Contact',
                        3 => 'Medical',
                        4 => 'Review',
                    ] as $number => $label)
                        <div class="text-center">
                            <div
                                data-indicator="{{ $number }}"
                                class="mx-auto flex h-9 w-9 items-center justify-center rounded-full
                                       text-sm font-bold transition"
                            >
                                {{ $number }}
                            </div>

                            <p class="mt-2 text-xs font-semibold text-slate-500">
                                {{ $label }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </header>

            <form
                id="studentRegistrationForm"
                method="POST"
                action="{{ route('students.store') }}"
                class="flex min-h-0 flex-1 flex-col"
            >
                @csrf

                <div class="min-h-0 flex-1 overflow-y-auto px-6 py-5">
                    @if ($errors->any())
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                            <p class="font-semibold">
                                Please correct the following:
                            </p>

                            <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Step 1: Student Information --}}
                    <section data-panel="1">
                        <div class="mb-6 border-b border-slate-200 pb-4">
                            <h2 class="text-lg font-bold text-blue-950">
                                Student Information
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Enter the student's basic identity and personal information.
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                            <div class="lg:col-span-2">
                                <label for="student_number" class="{{ $labelClass }}">
                                    Student Number <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="student_number"
                                    name="student_number"
                                    type="text"
                                    value="{{ old('student_number') }}"
                                    required
                                    maxlength="50"
                                    pattern="[A-Za-z0-9-]+"
                                    placeholder="Example: 202600001"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="first_name" class="{{ $labelClass }}">
                                    First Name <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="first_name"
                                    name="first_name"
                                    type="text"
                                    value="{{ old('first_name') }}"
                                    required
                                    maxlength="100"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="middle_name" class="{{ $labelClass }}">
                                    Middle Name
                                </label>

                                <input
                                    id="middle_name"
                                    name="middle_name"
                                    type="text"
                                    value="{{ old('middle_name') }}"
                                    maxlength="100"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="last_name" class="{{ $labelClass }}">
                                    Last Name <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="last_name"
                                    name="last_name"
                                    type="text"
                                    value="{{ old('last_name') }}"
                                    required
                                    maxlength="100"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="suffix" class="{{ $labelClass }}">
                                    Suffix
                                </label>

                                <input
                                    id="suffix"
                                    name="suffix"
                                    type="text"
                                    value="{{ old('suffix') }}"
                                    maxlength="20"
                                    placeholder="Jr., III"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="birth_date" class="{{ $labelClass }}">
                                    Birth Date
                                </label>

                                <input
                                    id="birth_date"
                                    name="birth_date"
                                    type="date"
                                    value="{{ old('birth_date') }}"
                                    max="{{ now()->toDateString() }}"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="sex" class="{{ $labelClass }}">
                                    Sex
                                </label>

                                <select id="sex" name="sex" class="{{ $inputClass }}">
                                    <option value="">Select</option>
                                    <option value="male" @selected(old('sex') === 'male')>
                                        Male
                                    </option>
                                    <option value="female" @selected(old('sex') === 'female')>
                                        Female
                                    </option>
                                    <option value="intersex" @selected(old('sex') === 'intersex')>
                                        Intersex
                                    </option>
                                    <option value="not_disclosed" @selected(old('sex') === 'not_disclosed')>
                                        Prefer not to disclose
                                    </option>
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- Step 2: Academic, Contact, and Emergency Information --}}
                    <section data-panel="2" class="hidden">
                        <div class="mb-6 border-b border-slate-200 pb-4">
                            <h2 class="text-lg font-bold text-blue-950">
                                Academic and Contact Information
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Record the student's program, contact details, and emergency contact.
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <label for="course" class="{{ $labelClass }}">
                                    Course/Program <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="course"
                                    name="course"
                                    type="text"
                                    value="{{ old('course') }}"
                                    required
                                    maxlength="100"
                                    placeholder="Example: BSIT"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="year_level" class="{{ $labelClass }}">
                                    Year Level <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="year_level"
                                    name="year_level"
                                    required
                                    class="{{ $inputClass }}"
                                >
                                    <option value="">Select</option>

                                    @for ($year = 1; $year <= 6; $year++)
                                        <option
                                            value="{{ $year }}"
                                            @selected((string) old('year_level') === (string) $year)
                                        >
                                            Year {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="section" class="{{ $labelClass }}">
                                    Section
                                </label>

                                <input
                                    id="section"
                                    name="section"
                                    type="text"
                                    value="{{ old('section') }}"
                                    maxlength="50"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="contact_number" class="{{ $labelClass }}">
                                    Student Contact Number
                                </label>

                                <input
                                    id="contact_number"
                                    name="contact_number"
                                    type="tel"
                                    value="{{ old('contact_number') }}"
                                    maxlength="30"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label for="email" class="{{ $labelClass }}">
                                    Email Address
                                </label>

                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    maxlength="255"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div class="md:col-span-2 lg:col-span-3">
                                <label for="address" class="{{ $labelClass }}">
                                    Address
                                </label>

                                <textarea
                                    id="address"
                                    name="address"
                                    rows="2"
                                    maxlength="1000"
                                    class="{{ $inputClass }}"
                                >{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="my-7 border-t border-slate-200"></div>

                        <h3 class="mb-4 font-bold text-blue-950">
                            Emergency Contact
                        </h3>

                        <div class="grid gap-5 md:grid-cols-3">
                            <div>
                                <label for="emergency_contact_name" class="{{ $labelClass }}">
                                    Contact Person
                                </label>

                                <input
                                    id="emergency_contact_name"
                                    name="emergency_contact_name"
                                    type="text"
                                    value="{{ old('emergency_contact_name') }}"
                                    maxlength="255"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="emergency_contact_relationship" class="{{ $labelClass }}">
                                    Relationship
                                </label>

                                <input
                                    id="emergency_contact_relationship"
                                    name="emergency_contact_relationship"
                                    type="text"
                                    value="{{ old('emergency_contact_relationship') }}"
                                    maxlength="100"
                                    class="{{ $inputClass }}"
                                >
                            </div>

                            <div>
                                <label for="emergency_contact_number" class="{{ $labelClass }}">
                                    Contact Number
                                </label>

                                <input
                                    id="emergency_contact_number"
                                    name="emergency_contact_number"
                                    type="tel"
                                    value="{{ old('emergency_contact_number') }}"
                                    maxlength="30"
                                    class="{{ $inputClass }}"
                                >
                            </div>
                        </div>
                    </section>

                    {{-- Step 3: Medical Profile --}}
                    <section data-panel="3" class="hidden">
                        <div class="mb-6 border-b border-slate-200 pb-4">
                            <h2 class="text-lg font-bold text-blue-950">
                                Medical Profile
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Record only information confirmed by the student or available records.
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="blood_type" class="{{ $labelClass }}">
                                    Blood Type
                                </label>

                                <select
                                    id="blood_type"
                                    name="medical_profile[blood_type]"
                                    class="{{ $inputClass }}"
                                >
                                    <option value="">Unknown/Not recorded</option>

                                    @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bloodType)
                                        <option
                                            value="{{ $bloodType }}"
                                            @selected(old('medical_profile.blood_type') === $bloodType)
                                        >
                                            {{ $bloodType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="allergy_status" class="{{ $labelClass }}">
                                    Allergy Status <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="allergy_status"
                                    name="medical_profile[allergy_status]"
                                    required
                                    class="{{ $inputClass }}"
                                >
                                    <option
                                        value="unknown"
                                        @selected(old('medical_profile.allergy_status', 'unknown') === 'unknown')
                                    >
                                        Unknown
                                    </option>
                                    <option
                                        value="none_known"
                                        @selected(old('medical_profile.allergy_status') === 'none_known')
                                    >
                                        No known allergies
                                    </option>
                                    <option
                                        value="has_allergies"
                                        @selected(old('medical_profile.allergy_status') === 'has_allergies')
                                    >
                                        Has known allergies
                                    </option>
                                </select>
                            </div>

                            <div id="allergiesContainer" class="hidden md:col-span-2">
                                <label for="allergies" class="{{ $labelClass }}">
                                    Known Allergies <span class="text-red-500">*</span>
                                </label>

                                <textarea
                                    id="allergies"
                                    name="medical_profile[allergies]"
                                    rows="3"
                                    maxlength="2000"
                                    placeholder="Describe the known allergies and reactions"
                                    class="{{ $inputClass }}"
                                >{{ old('medical_profile.allergies') }}</textarea>
                            </div>

                            <div>
                                <label for="current_medications" class="{{ $labelClass }}">
                                    Current Medications
                                </label>

                                <textarea
                                    id="current_medications"
                                    name="medical_profile[current_medications]"
                                    rows="3"
                                    maxlength="2000"
                                    class="{{ $inputClass }}"
                                >{{ old('medical_profile.current_medications') }}</textarea>
                            </div>

                            <div>
                                <label for="existing_conditions" class="{{ $labelClass }}">
                                    Existing Conditions
                                </label>

                                <textarea
                                    id="existing_conditions"
                                    name="medical_profile[existing_conditions]"
                                    rows="3"
                                    maxlength="2000"
                                    class="{{ $inputClass }}"
                                >{{ old('medical_profile.existing_conditions') }}</textarea>
                            </div>

                            <div>
                                <label for="past_surgeries" class="{{ $labelClass }}">
                                    Past Surgeries
                                </label>

                                <textarea
                                    id="past_surgeries"
                                    name="medical_profile[past_surgeries]"
                                    rows="3"
                                    maxlength="2000"
                                    class="{{ $inputClass }}"
                                >{{ old('medical_profile.past_surgeries') }}</textarea>
                            </div>

                            <div>
                                <label for="family_medical_history" class="{{ $labelClass }}">
                                    Family Medical History
                                </label>

                                <textarea
                                    id="family_medical_history"
                                    name="medical_profile[family_medical_history]"
                                    rows="3"
                                    maxlength="2000"
                                    class="{{ $inputClass }}"
                                >{{ old('medical_profile.family_medical_history') }}</textarea>
                            </div>

                            <div>
                                <label for="immunization_notes" class="{{ $labelClass }}">
                                    Immunization Notes
                                </label>

                                <textarea
                                    id="immunization_notes"
                                    name="medical_profile[immunization_notes]"
                                    rows="3"
                                    maxlength="2000"
                                    class="{{ $inputClass }}"
                                >{{ old('medical_profile.immunization_notes') }}</textarea>
                            </div>

                            <div>
                                <label for="additional_notes" class="{{ $labelClass }}">
                                    Additional Notes
                                </label>

                                <textarea
                                    id="additional_notes"
                                    name="medical_profile[additional_notes]"
                                    rows="3"
                                    maxlength="2000"
                                    class="{{ $inputClass }}"
                                >{{ old('medical_profile.additional_notes') }}</textarea>
                            </div>
                        </div>
                    </section>

                    {{-- Step 4: Review --}}
                    <section data-panel="4" class="hidden">
                        <div class="mb-6 border-b border-slate-200 pb-4">
                            <h2 class="text-lg font-bold text-blue-950">
                                Review Student Profile
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Verify the information before saving. Use Previous to make corrections.
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <article class="rounded-xl border border-blue-200 bg-blue-50 p-5">
                                <h3 class="font-bold text-blue-950">
                                    Student
                                </h3>

                                <dl class="mt-4 space-y-3 text-sm">
                                    <div>
                                        <dt class="text-slate-500">Student Number</dt>
                                        <dd data-review="student_number" class="font-semibold text-slate-900">—</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-500">Full Name</dt>
                                        <dd data-review="full_name" class="font-semibold text-slate-900">—</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-500">Birth Date / Sex</dt>
                                        <dd data-review="personal" class="font-semibold text-slate-900">—</dd>
                                    </div>
                                </dl>
                            </article>

                            <article class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                                <h3 class="font-bold text-slate-900">
                                    Academic and Contact
                                </h3>

                                <dl class="mt-4 space-y-3 text-sm">
                                    <div>
                                        <dt class="text-slate-500">Course / Year / Section</dt>
                                        <dd data-review="academic" class="font-semibold text-slate-900">—</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-500">Contact</dt>
                                        <dd data-review="contact" class="font-semibold text-slate-900">—</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-500">Emergency Contact</dt>
                                        <dd data-review="emergency" class="font-semibold text-slate-900">—</dd>
                                    </div>
                                </dl>
                            </article>

                            <article class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 md:col-span-2">
                                <h3 class="font-bold text-emerald-950">
                                    Medical Profile
                                </h3>

                                <dl class="mt-4 grid gap-4 text-sm md:grid-cols-3">
                                    <div>
                                        <dt class="text-slate-500">Blood Type</dt>
                                        <dd data-review="blood_type" class="font-semibold text-slate-900">—</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-500">Allergy Status</dt>
                                        <dd data-review="allergy_status" class="font-semibold text-slate-900">—</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-500">Known Allergies</dt>
                                        <dd data-review="allergies" class="font-semibold text-slate-900">—</dd>
                                    </div>
                                </dl>
                            </article>
                        </div>

                        <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4">
                            <p class="text-sm leading-6 text-amber-800">
                                Save only information provided by the student or supported by available records.
                                The clinic staff can review the complete profile after registration.
                            </p>
                        </div>
                    </section>
                </div>

                <footer class="flex items-center gap-3 border-t border-slate-200 bg-white px-6 py-4">
                    <a
                        id="cancelButton"
                        href="{{ route('students.index') }}"
                        class="rounded-xl border border-slate-300 px-5 py-2.5
                               font-semibold text-slate-700 transition hover:bg-slate-100"
                    >
                        Cancel
                    </a>

                    <button
                        id="previousButton"
                        type="button"
                        class="hidden rounded-xl border border-slate-300 px-5 py-2.5
                               font-semibold text-slate-700 transition hover:bg-slate-100"
                    >
                        Previous
                    </button>

                    <div class="ml-auto flex items-center gap-3">
                        <span id="stepText" class="hidden text-sm font-medium text-slate-500 sm:inline">
                            Step 1 of 4
                        </span>

                        <button
                            id="nextButton"
                            type="button"
                            style="background-color: #1e3a8a; color: #ffffff;"
                            class="rounded-xl px-6 py-2.5 font-semibold shadow-sm transition hover:opacity-90"
                        >
                            Next
                        </button>

                        <button
                            id="saveButton"
                            type="submit"
                            style="background-color: #047857; color: #ffffff;"
                            class="hidden rounded-xl px-6 py-2.5 font-semibold shadow-sm transition hover:opacity-90"
                        >
                            Save Student Profile
                        </button>
                    </div>
                </footer>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('studentRegistrationForm');
            const panels = [...document.querySelectorAll('[data-panel]')];
            const indicators = [...document.querySelectorAll('[data-indicator]')];
            const previousButton = document.getElementById('previousButton');
            const nextButton = document.getElementById('nextButton');
            const saveButton = document.getElementById('saveButton');
            const cancelButton = document.getElementById('cancelButton');
            const stepText = document.getElementById('stepText');
            const allergyStatus = document.getElementById('allergy_status');
            const allergiesContainer = document.getElementById('allergiesContainer');
            const allergies = document.getElementById('allergies');

            let currentStep = @if ($medicalErrors) 3 @elseif ($contactErrors) 2 @else 1 @endif;

            function setReviewValue(key, value) {
                const element = document.querySelector(`[data-review="${key}"]`);

                if (element) {
                    element.textContent = value && value.trim() !== '' ? value : 'Not recorded';
                }
            }

            function formValue(data, key) {
                const value = data.get(key);
                return typeof value === 'string' ? value.trim() : '';
            }

            function selectedLabel(id) {
                const field = document.getElementById(id);

                if (!field || field.selectedIndex < 0) {
                    return '';
                }

                return field.options[field.selectedIndex].text.trim();
            }

            function updateReview() {
                const data = new FormData(form);
                const firstName = formValue(data, 'first_name');
                const middleName = formValue(data, 'middle_name');
                const lastName = formValue(data, 'last_name');
                const suffix = formValue(data, 'suffix');

                const fullName = [firstName, middleName, lastName, suffix]
                    .filter(Boolean)
                    .join(' ');

                const birthDate = formValue(data, 'birth_date');
                const sex = selectedLabel('sex');
                const personal = [birthDate, sex !== 'Select' ? sex : '']
                    .filter(Boolean)
                    .join(' / ');

                const course = formValue(data, 'course');
                const year = selectedLabel('year_level');
                const section = formValue(data, 'section');
                const academic = [course, year !== 'Select' ? year : '', section]
                    .filter(Boolean)
                    .join(' / ');

                const phone = formValue(data, 'contact_number');
                const email = formValue(data, 'email');
                const contact = [phone, email].filter(Boolean).join(' / ');

                const emergencyName = formValue(data, 'emergency_contact_name');
                const emergencyRelationship = formValue(data, 'emergency_contact_relationship');
                const emergencyNumber = formValue(data, 'emergency_contact_number');
                const emergency = [emergencyName, emergencyRelationship, emergencyNumber]
                    .filter(Boolean)
                    .join(' / ');

                setReviewValue('student_number', formValue(data, 'student_number'));
                setReviewValue('full_name', fullName);
                setReviewValue('personal', personal);
                setReviewValue('academic', academic);
                setReviewValue('contact', contact);
                setReviewValue('emergency', emergency);
                setReviewValue('blood_type', selectedLabel('blood_type'));
                setReviewValue('allergy_status', selectedLabel('allergy_status'));
                setReviewValue('allergies', formValue(data, 'medical_profile[allergies]'));
            }

            function updateAllergyField() {
                const hasAllergies = allergyStatus.value === 'has_allergies';

                allergiesContainer.classList.toggle('hidden', !hasAllergies);
                allergies.required = hasAllergies;

                if (!hasAllergies) {
                    allergies.setCustomValidity('');
                }
            }

            function validateStep() {
                const currentPanel = document.querySelector(`[data-panel="${currentStep}"]`);

                for (const field of currentPanel.querySelectorAll('[required]')) {
                    if (!field.checkValidity()) {
                        field.reportValidity();
                        return false;
                    }
                }

                return true;
            }

            function render() {
                panels.forEach(function (panel) {
                    panel.classList.toggle(
                        'hidden',
                        Number(panel.dataset.panel) !== currentStep
                    );
                });

                indicators.forEach(function (indicator) {
                    const step = Number(indicator.dataset.indicator);
                    const active = step <= currentStep;

                    indicator.style.backgroundColor = active ? '#1e3a8a' : '#e2e8f0';
                    indicator.style.color = active ? '#ffffff' : '#64748b';
                });

                previousButton.classList.toggle('hidden', currentStep === 1);
                cancelButton.classList.toggle('hidden', currentStep !== 1);
                nextButton.classList.toggle('hidden', currentStep === 4);
                saveButton.classList.toggle('hidden', currentStep !== 4);
                stepText.textContent = `Step ${currentStep} of 4`;

                if (currentStep === 4) {
                    updateReview();
                }
            }

            allergyStatus.addEventListener('change', updateAllergyField);

            nextButton.addEventListener('click', function () {
                if (validateStep() && currentStep < 4) {
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

            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();

                    const invalidField = form.querySelector(':invalid');
                    const invalidPanel = invalidField?.closest('[data-panel]');

                    if (invalidPanel) {
                        currentStep = Number(invalidPanel.dataset.panel);
                        render();
                        invalidField.reportValidity();
                    }

                    return;
                }

                saveButton.disabled = true;
                saveButton.classList.add('cursor-not-allowed', 'opacity-70');
                saveButton.textContent = 'Saving...';
            });

            updateAllergyField();
            render();
        });
    </script>
@endsection
