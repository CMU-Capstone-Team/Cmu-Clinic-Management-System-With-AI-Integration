<section class="mt-6 rounded-2xl bg-white p-6 shadow-sm">
    @if ($clinicVisit->status === 'in_progress')
        <div class="mb-6 border-b border-slate-200 pb-4">
            <h2 class="text-lg font-bold text-blue-900">
                Staff Clinical Review
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                The final decision must be entered by the clinic staff,
                nurse, or doctor.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-300 bg-red-50 p-4">
                <p class="font-semibold text-red-700">
                    Please correct the following:
                </p>

                <ul class="mt-2 list-disc pl-5 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('clinic-visits.review', $clinicVisit) }}"
            class="space-y-5"
        >
            @csrf
            @method('PATCH')

            <div>
                <label
                    for="final_assessment"
                    class="mb-2 block font-semibold text-slate-700"
                >
                    Final Clinical Assessment
                    <span class="text-red-500">*</span>
                </label>

                <textarea
                    id="final_assessment"
                    name="final_assessment"
                    rows="4"
                    required
                    class="w-full rounded-xl border border-slate-300
                           px-4 py-3 focus:border-blue-700
                           focus:outline-none focus:ring-2
                           focus:ring-blue-100"
                    placeholder="Enter the clinic staff's final assessment..."
                >{{ old('final_assessment',
                    $clinicVisit->triageResult?->ai_summary
) }}</textarea>
            </div>

            <div>
                <label
                    for="final_triage_level"
                    class="mb-2 block font-semibold text-slate-700"
                >
                    Final Triage Level
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="final_triage_level"
                    name="final_triage_level"
                    required
                    class="w-full rounded-xl border border-slate-300
                           bg-white px-4 py-3 focus:border-blue-700
                           focus:outline-none focus:ring-2
                           focus:ring-blue-100"
                >
                    <option value="">Select triage level</option>

                    <option
                        value="green"
                        @selected(
                            old(
                                'final_triage_level',
                                $clinicVisit->triageResult?->ai_triage_level
                            ) === 'green'
                        )
                    >
                        Green — Mild or non-urgent
                    </option>

                    <option
                        value="yellow"
                        @selected(
                            old(
                                'final_triage_level',
                                $clinicVisit->triageResult?->ai_triage_level
                                ) === 'yellow'
                            )
                    >
                        Yellow — Needs prompt assessment
                    </option>

                    <option
                        value="red"
                        @selected(
                            old(
                                'final_triage_level',
                                $clinicVisit->triageResult?->ai_triage_level
                            ) === 'red'
                        )
                    >
                        Red — Urgent or emergency
                    </option>
                </select>
            </div>

            <div>
                <label
                    for="final_action"
                    class="mb-2 block font-semibold text-slate-700"
                >
                    Final Action
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="final_action"
                    name="final_action"
                    required
                    class="w-full rounded-xl border border-slate-300
                           bg-white px-4 py-3 focus:border-blue-700
                           focus:outline-none focus:ring-2
                           focus:ring-blue-100"
                >
                    <option value="">Select final action</option>

                    <option value="return_to_class">
                        Return to class
                    </option>

                    <option value="rest_observe">
                        Rest and observe in clinic
                    </option>

                    <option value="send_home">
                        Send student home
                    </option>

                    <option value="contact_guardian">
                        Contact parent/guardian
                    </option>

                    <option value="refer_to_hospital">
                        Refer to hospital
                    </option>

                    <option value="emergency_transfer">
                        Emergency hospital transfer
                    </option>
                </select>
            </div>

            <div>
                <label
                    for="final_recommendation"
                    class="mb-2 block font-semibold text-slate-700"
                >
                    Final Care Recommendation
                    <span class="text-red-500">*</span>
                </label>

                <textarea
                    id="final_recommendation"
                    name="final_recommendation"
                    rows="3"
                    required
                    class="w-full rounded-xl border border-slate-300
                           px-4 py-3 focus:border-blue-700
                           focus:outline-none focus:ring-2
                           focus:ring-blue-100"
                    placeholder="Enter staff-approved care instructions..."
                >{{ old(
                    'final_recommendation',
                    $clinicVisit->triageResult?->ai_recommendations
            ) }}</textarea>
            </div>

            <div>
                <label
                    for="final_notes"
                    class="mb-2 block font-semibold text-slate-700"
                >
                    Additional Notes
                </label>

                <textarea
                    id="final_notes"
                    name="final_notes"
                    rows="3"
                    class="w-full rounded-xl border border-slate-300
                           px-4 py-3 focus:border-blue-700
                           focus:outline-none focus:ring-2
                           focus:ring-blue-100"
                    placeholder="Optional additional notes..."
                >{{ old('final_notes') }}</textarea>
            </div>

            <label
                class="flex items-center gap-3 rounded-xl
                       border border-slate-200 bg-slate-50 p-4"
            >
                <input
                    type="hidden"
                    name="guardian_contacted"
                    value="0"
                >

                <input
                    type="checkbox"
                    name="guardian_contacted"
                    value="1"
                    class="h-4 w-4"
                    @checked(old('guardian_contacted'))
                >

                <span class="text-sm font-medium text-slate-700">
                    Parent or guardian was contacted
                </span>
            </label>

            <div class="rounded-xl border border-amber-300 bg-amber-50 p-4">
                <p class="text-sm text-amber-800">
                    Hospital referral actions will change the status to
                    <strong>Referred</strong>. All other final actions will
                    change it to <strong>Completed</strong>.
                </p>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="rounded-xl bg-blue-900 px-6 py-3
                           font-semibold text-white hover:bg-blue-800"
                >
                    Finalize Clinic Visit
                </button>
            </div>
        </form>
    @else
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-blue-900">
                    Final Clinical Decision
                </h2>

                <p class="mt-1 text-slate-600">
                    {{ $clinicVisit->final_assessment }}
                </p>
            </div>

            <span
                @class([
                    'rounded-full px-4 py-2 text-sm font-semibold',
                    'bg-green-100 text-green-700' =>
                        $clinicVisit->status === 'completed',
                    'bg-red-100 text-red-700' =>
                        $clinicVisit->status === 'referred',
                ])
            >
                {{ str($clinicVisit->status)->replace('_', ' ')->title() }}
            </span>
        </div>
    @endif
</section>