@extends('layouts.app')

@section('title', 'Clinic Visit Logbook | CMU ClinicAssist AI')

@section('content')
    <div class="mx-auto space-y-6" style="max-width: 1600px;">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-950">
                    Clinic Visit Logbook
                </h1>

                <p class="mt-1 text-slate-500">
                    Search and review all recorded student clinic visits.
                </p>
            </div>

            <a href="{{ route('students.index') }}"
               class="w-fit rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                Start New Visit
            </a>
        </div>

        {{-- Summary Cards --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border-l-4 border-blue-700 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Visits Today
                </p>

                <p class="mt-2 text-3xl font-bold text-blue-800">
                    {{ $visitsToday }}
                </p>
            </article>

            <article class="rounded-xl border-l-4 border-amber-500 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    In Progress
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-600">
                    {{ $inProgress }}
                </p>
            </article>

            <article class="rounded-xl border-l-4 border-emerald-500 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Completed Today
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-600">
                    {{ $completedToday }}
                </p>
            </article>

            <article class="rounded-xl border-l-4 border-red-500 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Referred Today
                </p>

                <p class="mt-2 text-3xl font-bold text-red-600">
                    {{ $referredToday }}
                </p>
            </article>
        </section>

        {{-- Search and Filters --}}
        <section class="rounded-xl bg-white p-5 shadow-sm">
            <form method="GET"
                  action="{{ route('clinic-visits.index') }}"
                  class="grid gap-4 lg:grid-cols-[1fr_190px_190px_auto_auto]">

                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-600">
                        Search
                    </label>

                    <input type="search"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Student name, number, complaint, or visit number"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-600">
                        Date
                    </label>

                    <input type="date"
                           name="date"
                           value="{{ request('date') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-600">
                        Status
                    </label>

                    <select name="status"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">All statuses</option>
                        <option value="in_progress"
                            @selected(request('status') === 'in_progress')>
                            In Progress
                        </option>
                        <option value="completed"
                            @selected(request('status') === 'completed')>
                            Completed
                        </option>
                        <option value="referred"
                            @selected(request('status') === 'referred')>
                            Referred
                        </option>
                        <option value="cancelled"
                            @selected(request('status') === 'cancelled')>
                            Cancelled
                        </option>
                    </select>
                </div>

                <button type="submit"
                        class="self-end rounded-lg bg-blue-900 px-5 py-2.5 font-semibold text-white hover:bg-blue-800">
                    Filter
                </button>

                <a href="{{ route('clinic-visits.index') }}"
                   class="self-end rounded-lg border border-slate-300 px-5 py-2.5 text-center font-semibold text-slate-700 hover:bg-slate-100">
                    Clear
                </a>
            </form>
        </section>

        {{-- Logbook Table --}}
        <section class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr class="border-b border-slate-200">
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Visit
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Student
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Complaint
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Staff
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                                Triage
                            </th>

                            <th class="px-5 py-4"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($visits as $visit)
                            @php
                                $triageLevel =
                                    $visit->triageResult?->final_triage_level
                                    ?? $visit->triageResult?->ai_triage_level;
                            @endphp

                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-semibold text-slate-900">
                                        {{ $visit->visit_number }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $visit->visited_at->format('M d, Y') }}
                                        <br>
                                        {{ $visit->visited_at->format('h:i A') }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">
                                        {{ $visit->student->full_name }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $visit->student->student_number }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">
                                        {{ $visit->chief_complaint }}
                                    </p>

                                    @if(!empty($visit->symptoms))
                                        <p class="mt-1 max-w-xs text-sm text-slate-500">
                                            {{ collect($visit->symptoms)
                                                ->take(3)
                                                ->map(
                                                    fn ($symptom) =>
                                                        ucwords(str_replace('_', ' ', $symptom))
                                                )
                                                ->implode(', ') }}
                                        </p>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-medium text-slate-700">
                                        {{ $visit->attendingStaff?->name ?? 'Unknown' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
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
                                </td>

                                <td class="px-5 py-4">
                                    @if($triageLevel)
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                                            @if($triageLevel === 'red')
                                                bg-red-100 text-red-700
                                            @elseif($triageLevel === 'yellow')
                                                bg-amber-100 text-amber-700
                                            @else
                                                bg-emerald-100 text-emerald-700
                                            @endif">
                                            {{ ucfirst($triageLevel) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-slate-400">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <a href="{{ route('clinic-visits.show', $visit) }}"
                                       class="font-semibold text-blue-700 hover:underline">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <p class="font-semibold text-slate-600">
                                        No clinic visits found.
                                    </p>

                                    <a href="{{ route('students.index') }}"
                                       class="mt-4 inline-block rounded-lg bg-blue-900 px-5 py-2.5 text-sm font-semibold text-white">
                                        Find a Student
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($visits->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $visits->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection