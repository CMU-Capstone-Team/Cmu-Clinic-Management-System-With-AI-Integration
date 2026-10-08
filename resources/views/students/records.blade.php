@extends('layouts.student-layout')

@section('title', 'Medical Records')

@push('styles')
<style>
    .sp-content {
        width: 100%;
        max-width: none;
        padding: 32px clamp(18px, 2.5vw, 44px) 48px;
        box-sizing: border-box;
    }

    .mr {
        --ink: #19324e;
        --muted: #66788e;
        --line: #e3eaf3;
        --blue: #3156d3;
        width: 100%;
        margin: 0;
        color: var(--ink);
        font-family: inherit;
    }

    .mr,
    .mr *,
    .mr *::before,
    .mr *::after {
        box-sizing: border-box;
    }

    .mr h1,
    .mr h2,
    .mr p {
        margin: 0;
    }

    .mr button,
    .mr input,
    .mr select,
    .mr textarea {
        font: inherit;
    }

    .mr-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 28px;
    }

    .mr-heading h1 {
        margin: 0 0 8px;
        color: #172b48;
        font-family: inherit;
        font-size: clamp(28px, 2.4vw, 36px);
        font-weight: 750;
        letter-spacing: -1px;
        line-height: 1.2;
    }

    .mr-heading p {
        color: var(--muted);
        font-size: 14px;
        line-height: 1.7;
    }

    .mr-tag {
        flex-shrink: 0;
        padding: 8px 12px;
        border: 1px solid #dbe5f3;
        border-radius: 8px;
        background: #f7faff;
        color: #49617f;
        font-size: 12px;
        font-weight: 500;
    }

    .mr-section {
        margin-bottom: 22px;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 30px rgba(21, 47, 80, .035);
    }

    .mr-section-head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 24px 28px;
        border-bottom: 1px solid var(--line);
    }

    .mr-section-icon {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border: 1px solid #dfe8f8;
        border-radius: 11px;
        background: #f1f5ff;
        color: var(--blue);
    }

    .mr-section-icon svg {
        width: 21px;
        height: 21px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.7;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .mr-section-head h2 {
        margin-bottom: 5px;
        font-family: inherit;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -.4px;
        line-height: 1.3;
    }

    .mr-section-head p {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .mr-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 26px;
        padding: 28px;
    }

    .mr-field {
        min-width: 0;
    }

    .mr-field label {
        display: block;
        margin-bottom: 8px;
        color: #29415f;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
    }

    .mr-field select,
    .mr-field textarea {
        display: block;
        width: 100%;
        border: 1px solid #d8e1ed;
        border-radius: 10px;
        background: #fbfcfe;
        color: var(--ink);
        font-size: 14px;
        line-height: 1.7;
        transition: border-color .15s, box-shadow .15s;
    }

    .mr-field select {
        min-height: 46px;
        padding: 10px 12px;
    }

    .mr-field textarea {
        min-height: 112px;
        padding: 12px 14px;
        resize: vertical;
    }

    .mr-field textarea::placeholder {
        color: #8190a3;
        opacity: 1;
    }

    .mr-field select:focus,
    .mr-field textarea:focus {
        outline: none;
        border-color: #7296ed;
        box-shadow: 0 0 0 3px rgba(49, 86, 211, .1);
        background: #fff;
    }

    .mr-field [aria-invalid="true"] {
        border-color: #c64343;
    }

    .mr-field textarea:disabled {
        background: #f0f3f7;
        color: #78879b;
        cursor: not-allowed;
    }

    .mr-help {
        margin-top: 7px !important;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.65;
    }

    .mr-error {
        margin-top: 7px !important;
        color: #b42318;
        font-size: 12px;
        line-height: 1.6;
    }

    .mr-allergy-details {
        margin-top: 15px;
    }

    .mr-history {
        padding: 0 28px;
    }

    .mr-history-row {
        display: grid;
        grid-template-columns: minmax(180px, 30%) minmax(0, 1fr);
        gap: 28px;
        padding: 24px 0;
        align-items: start;
    }

    .mr-history-row + .mr-history-row {
        border-top: 1px solid var(--line);
    }

    .mr-history-row textarea {
        min-height: 95px;
    }

    .mr-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 4px 0;
    }

    .mr-footer p {
        max-width: 620px;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.8;
    }

    .mr-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .mr-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 11px 20px;
        border: 1px solid transparent;
        border-radius: 10px;
        font-size: 14px !important;
        font-weight: 600 !important;
        line-height: 1.5;
        text-decoration: none;
        cursor: pointer;
    }

    .mr-button-primary {
        background: #3156d3;
        color: #fff;
    }

    .mr-button-primary:hover {
        background: #2748b8;
    }

    .mr-button-secondary {
        border-color: #d8e1ed;
        background: #fff;
        color: #425975;
    }

    .mr-button:focus-visible,
    .mr-alert a:focus-visible {
        outline: 3px solid #8cb2ff;
        outline-offset: 3px;
    }

    .mr-button:disabled {
        opacity: .65;
        cursor: wait;
    }

    .mr-alert {
        margin-bottom: 22px;
        padding: 15px 18px;
        border: 1px solid;
        border-radius: 11px;
        font-size: 14px;
        line-height: 1.7;
    }

    .mr-alert-success {
        border-color: #b9e2ce;
        background: #f0faf4;
        color: #206344;
    }

    .mr-alert-error {
        border-color: #efc6c6;
        background: #fff6f6;
        color: #9a2929;
    }

    .mr-alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
        list-style: disc;
    }

    .mr-alert a {
        color: inherit;
        text-decoration: underline;
    }

    @media (max-width: 760px) {
        .mr-heading,
        .mr-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .mr-grid,
        .mr-history-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .mr-section-head,
        .mr-grid {
            padding: 20px;
        }

        .mr-history {
            padding: 0 20px;
        }

        .mr-history-row {
            gap: 12px;
        }

        .mr-actions {
            width: 100%;
        }

        .mr-actions .mr-button {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')
@php
    $textFields = [
        'existing_conditions' => [
            'label' => 'Condition',
            'hint' => 'Existing medical conditions.',
            'placeholder' => 'Enter your medical conditions, or None if applicable.',
        ],
        'current_medications' => [
            'label' => 'Current Medication',
            'hint' => 'Include the medicine name and dosage, if known.',
            'placeholder' => 'List medicines you currently take, or None if applicable.',
        ],
    ];

    $historyFields = [
        'past_surgeries' => [
            'label' => 'Past Surgeries',
            'hint' => 'Previous procedures and approximate dates.',
            'placeholder' => 'Enter previous surgeries, or None if applicable.',
        ],
        'family_medical_history' => [
            'label' => 'Family Medical History',
            'hint' => 'Relevant health conditions in your family.',
            'placeholder' => 'Enter relevant family medical history.',
        ],
        'immunization_notes' => [
            'label' => 'Immunization',
            'hint' => 'Vaccines received and dates, if known.',
            'placeholder' => 'Enter your immunization history.',
        ],
    ];

    $allergyStatus = old(
        'allergy_status',
        $medical['allergy_status'] ?? 'unknown'
    );
@endphp

<div class="mr">
    <header class="mr-heading">
        <div>
            <h1>Medical Records</h1>
            <p>Keep your medical information up to date for the university clinic.</p>
        </div>

        <span class="mr-tag">Student health profile</span>
    </header>

    @if (session('medical_success'))
        <div class="mr-alert mr-alert-success" role="status">
            {{ session('medical_success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mr-alert mr-alert-error" role="alert">
            <strong>Please check the following:</strong>
            <ul>
                @foreach ($errors->messages() as $field => $messages)
                    @foreach ($messages as $message)
                        <li>
                            <a href="#{{ $field }}">{{ $message }}</a>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif

    <form
        id="medical-form"
        method="POST"
        action="{{ route('student.records.update') }}"
    >
        @csrf
        @method('PATCH')

        <section class="mr-section" aria-labelledby="mr-overview">
            <div class="mr-section-head">
                <span class="mr-section-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9 3h6v6h6v6h-6v6H9v-6H3V9h6Z"/>
                    </svg>
                </span>

                <div>
                    <h2 id="mr-overview">Medical Overview</h2>
                    <p>Basic health information and current medication.</p>
                </div>
            </div>

            <div class="mr-grid">
                <div class="mr-field">
                    <label for="allergy_status">Allergies</label>

                    <select
                        id="allergy_status"
                        name="allergy_status"
                        required
                        aria-invalid="{{ $errors->has('allergy_status') ? 'true' : 'false' }}"
                        aria-describedby="allergy-status-help allergy-status-error"
                    >
                        <option value="unknown" @selected($allergyStatus === 'unknown')>
                            Unknown / Not sure
                        </option>
                        <option value="none_known" @selected($allergyStatus === 'none_known')>
                            No known allergies
                        </option>
                        <option value="has_allergies" @selected($allergyStatus === 'has_allergies')>
                            Has allergies
                        </option>
                    </select>

                    <p class="mr-help" id="allergy-status-help">
                        Select your allergy status.
                    </p>

                    <p class="mr-error" id="allergy-status-error">
                        @error('allergy_status') {{ $message }} @enderror
                    </p>

                    <div class="mr-allergy-details">
                        <label for="allergies">Allergy details</label>

                        <textarea
                            id="allergies"
                            name="allergies"
                            rows="3"
                            maxlength="2000"
                            placeholder="List food, medicine, or other allergies."
                            aria-invalid="{{ $errors->has('allergies') ? 'true' : 'false' }}"
                            aria-describedby="allergies-help allergies-error"
                        >{{ old('allergies', $medical['allergies'] ?? '') }}</textarea>

                        <p class="mr-help" id="allergies-help">
                            Required when you select “Has allergies.”
                        </p>

                        <p class="mr-error" id="allergies-error">
                            @error('allergies') {{ $message }} @enderror
                        </p>
                    </div>
                </div>

                <div class="mr-field">
                    <label for="blood_type">Blood Type</label>

                    <select
                        id="blood_type"
                        name="blood_type"
                        aria-invalid="{{ $errors->has('blood_type') ? 'true' : 'false' }}"
                        aria-describedby="blood-type-help blood-type-error"
                    >
                        <option value="">Unknown / Not recorded</option>

                        @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                            <option
                                value="{{ $type }}"
                                @selected(old('blood_type', $medical['blood_type'] ?? '') === $type)
                            >
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>

                    <p class="mr-help" id="blood-type-help">
                        Choose Unknown if you do not know your blood type.
                    </p>

                    <p class="mr-error" id="blood-type-error">
                        @error('blood_type') {{ $message }} @enderror
                    </p>
                </div>

                @foreach ($textFields as $key => $field)
                    <div class="mr-field">
                        <label for="{{ $key }}">{{ $field['label'] }}</label>

                        <textarea
                            id="{{ $key }}"
                            name="{{ $key }}"
                            rows="4"
                            maxlength="2000"
                            placeholder="{{ $field['placeholder'] }}"
                            aria-invalid="{{ $errors->has($key) ? 'true' : 'false' }}"
                            aria-describedby="{{ $key }}-help {{ $key }}-error"
                        >{{ old($key, $medical[$key] ?? '') }}</textarea>

                        <p class="mr-help" id="{{ $key }}-help">
                            {{ $field['hint'] }}
                        </p>

                        <p class="mr-error" id="{{ $key }}-error">
                            @error($key) {{ $message }} @enderror
                        </p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mr-section" aria-labelledby="mr-additional">
            <div class="mr-section-head">
                <span class="mr-section-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="5" y="4" width="14" height="17" rx="2"/>
                        <rect x="9" y="2" width="6" height="4" rx="1"/>
                        <path d="M9 11h6M9 15h6M9 18h3"/>
                    </svg>
                </span>

                <div>
                    <h2 id="mr-additional">Additional Medical Information</h2>
                    <p>Your medical history and immunization details.</p>
                </div>
            </div>

            <div class="mr-history">
                @foreach ($historyFields as $key => $field)
                    <div class="mr-history-row mr-field">
                        <div>
                            <label for="{{ $key }}">{{ $field['label'] }}</label>

                            <p class="mr-help" id="{{ $key }}-help">
                                {{ $field['hint'] }}
                            </p>
                        </div>

                        <div>
                            <textarea
                                id="{{ $key }}"
                                name="{{ $key }}"
                                rows="3"
                                maxlength="2000"
                                placeholder="{{ $field['placeholder'] }}"
                                aria-invalid="{{ $errors->has($key) ? 'true' : 'false' }}"
                                aria-describedby="{{ $key }}-help {{ $key }}-error"
                            >{{ old($key, $medical[$key] ?? '') }}</textarea>

                            <p class="mr-error" id="{{ $key }}-error">
                                @error($key) {{ $message }} @enderror
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <footer class="mr-footer">
            <p>
                Information you save is visible to the university clinic staff.
                Leave a field blank if the information is unknown.
                Each text field accepts up to 2,000 characters.
            </p>

            <div class="mr-actions">
                <a
                    href="{{ route('student.records') }}"
                    class="mr-button mr-button-secondary"
                >
                    Cancel
                </a>

                <button
                    id="medical-save"
                    type="submit"
                    class="mr-button mr-button-primary"
                >
                    Save Changes
                </button>
            </div>
        </footer>
    </form>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('medical-form');
    const status = document.getElementById('allergy_status');
    const allergies = document.getElementById('allergies');
    const save = document.getElementById('medical-save');

    if (!form || !status || !allergies || !save) return;

    function syncAllergies() {
        const hasAllergies = status.value === 'has_allergies';

        allergies.disabled = !hasAllergies;
        allergies.required = hasAllergies;
    }

    status.addEventListener('change', syncAllergies);
    syncAllergies();

    form.addEventListener('submit', () => {
        save.disabled = true;
        save.textContent = 'Saving…';
    });

    window.addEventListener('pageshow', () => {
        save.disabled = false;
        save.textContent = 'Save Changes';
        syncAllergies();
    });
})();
</script>
@endpush