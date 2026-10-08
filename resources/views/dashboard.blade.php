@extends('layouts.app')

@section('title', 'Admin Dashboard | CMU Alaga')

@section('content')
<div class="mx-auto space-y-6" style="max-width: 1600px;">

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold text-slate-950">
            Dashboard Overview
        </h1>
        <p class="mt-1 text-slate-500">
            Welcome, {{ auth()->user()->name }}.
        </p>
    </div>

    {{-- Quick Patient Check-in --}}
    <section class="rounded-xl bg-white p-4 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 p-2 rounded-full text-blue-800">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div>
                <h3 class="font-bold text-slate-800">
                    Quick Patient Check-in
                </h3>
                <p class="text-xs text-slate-500">
                    Search by Student Number to start consultation
                </p>
            </div>
        </div>

        <form action="{{ route('clinic.search') }}" method="GET" class="flex w-full md:w-96">
            <input
                type="text"
                name="unique_id"
                required
                aria-label="Student Number"
                placeholder="Enter Student Number (e.g. 202400047)"
                class="w-full border border-slate-300 rounded-l-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm"
            >

            <button
                type="submit"
                class="bg-blue-800 text-white px-6 py-2 rounded-r-lg hover:bg-blue-900 transition font-medium text-sm whitespace-nowrap"
            >
                Find Patient
            </button>
        </form>
    </section>

    {{-- Statistic Cards --}}
    <section class="grid gap-5 md:grid-cols-3">

        {{-- Total Students --}}
        <a
            href="{{ route('students.index') }}"
            class="group block rounded-xl border-l-4 border-blue-800 bg-white p-6 shadow-sm transition duration-200 hover:bg-blue-50 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-800 focus-visible:ring-offset-2"
        >
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-800">
                    <svg width="27" height="27" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-8v5c3 2 9 2 12 0v-5"/>
                    </svg>
                </div>

                <div>
                    <p class="text-3xl font-bold text-slate-950">
                        {{ number_format($totalStudents ?? 0) }}
                    </p>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Total Students
                    </p>
                </div>
            </div>
        </a>

        {{-- Visits Today --}}
        <a
            href="{{ route('clinic-visits.index') }}"
            class="group block rounded-xl border-l-4 border-emerald-500 bg-white p-6 shadow-sm transition duration-200 hover:bg-emerald-50 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2"
        >
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <svg width="27" height="27" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-3xl font-bold text-slate-950">
                        {{ number_format($visitsToday ?? 0) }}
                    </p>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Visits Today
                    </p>
                </div>
            </div>
        </a>

        {{-- With Allergies --}}
        <a
            href="{{ route('dashboard.allergies') }}"
            aria-label="View students with allergies"
            class="group block rounded-xl border-l-4 border-red-500 bg-white p-6 shadow-sm transition duration-200 hover:bg-red-50 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
        >
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
                    <svg width="27" height="27" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 4.3L2.6 18a2 2 0 001.7 3h15.4a2 2 0 001.7-3L13.7 4.3a2 2 0 00-3.4 0z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-3xl font-bold text-slate-950">
                        {{ number_format($studentsWithAllergies ?? 0) }}
                    </p>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        With Allergies
                    </p>
                </div>
            </div>
        </a>

    </section>

    {{-- Chart Row --}}
    <section class="grid gap-6 xl:grid-cols-3">

        {{-- Visitation Trends --}}
        <article class="rounded-xl bg-white p-6 shadow-sm xl:col-span-2">
            <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-800" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8"/>
                </svg>
                <h2 class="font-bold text-blue-900">
                    Visitation Trends
                </h2>
            </div>

            <div class="flex h-72 flex-col items-center justify-center text-center">
                <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="mb-3 text-slate-300" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8M3 21h18"/>
                </svg>
                <p class="font-semibold text-slate-500">
                    No clinic visit data available yet.
                </p>
                <p class="mt-1 text-sm text-slate-400">
                    The trend chart will appear after recording clinic visits.
                </p>
            </div>
        </article>

        {{-- Top Complaints --}}
        <article class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-red-600" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M4 12a8 8 0 1116 0 8 8 0 01-16 0z"/>
                </svg>
                <h2 class="font-bold text-red-600">
                    Top Complaints
                </h2>
            </div>

            <div class="flex h-72 flex-col items-center justify-center text-center">
                <div class="mb-4 flex h-28 w-28 items-center justify-center rounded-full border-8 border-slate-100">
                    <span class="text-sm font-semibold text-slate-400">
                        No data
                    </span>
                </div>
                <p class="text-sm text-slate-400">
                    Complaints will appear after visits are recorded.
                </p>
            </div>
        </article>

    </section>
</div>
@endsection