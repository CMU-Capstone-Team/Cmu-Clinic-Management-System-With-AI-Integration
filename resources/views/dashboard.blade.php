@extends('layouts.app')

@section('title', 'Dashboard | CMU Alaga')

@section('content')
    <div class="mx-auto space-y-6" style="max-width: 1600px;">
        
        {{-- Header Section --}}
        <div>
            <h1 class="text-3xl font-bold text-slate-950">
                Dashboard Overview
            </h1>
            <p class="mt-1 text-slate-500">
                Welcome, {{ auth()->user()->name }}.
            </p>
        </div>

        {{-- Quick Check-in Search Bar --}}
        <section class="rounded-xl bg-white p-4 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-2 rounded-full text-blue-800">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Quick Patient Check-in</h3>
                    <p class="text-xs text-slate-500">Search by Student Number to start consultation</p>
                </div>
            </div>

            <form action="{{ route('clinic.search') }}" method="GET" class="flex w-full md:w-96">
                <input type="text" name="unique_id" required
                       placeholder="Enter Student Number (e.g. 202400047)" 
                       class="w-full border border-slate-300 rounded-l-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                <button type="submit" class="bg-blue-800 text-white px-6 py-2 rounded-r-lg hover:bg-blue-900 transition font-medium text-sm whitespace-nowrap">
                    Find Patient
                </button>
            </form>
        </section>

        {{-- Statistic Cards --}}
        <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            {{-- Total Students --}}
            <article class="rounded-xl border-l-4 border-blue-800 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-800">
                        <svg width="27" height="27" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-8v5c3 2 9 2 12 0v-5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-950">{{ number_format($totalStudents) }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Total Students</p>
                    </div>
                </div>
            </article>

            {{-- Visits Today --}}
            <article class="rounded-xl border-l-4 border-emerald-500 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <svg width="27" height="27" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-950">{{ number_format($visitsToday) }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Visits Today</p>
                    </div>
                </div>
            </article>

            {{-- Students With Allergies --}}
            <article class="rounded-xl border-l-4 border-red-500 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600">
                        <svg width="27" height="27" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 4.3L2.6 18a2 2 0 001.7 3h15.4a2 2 0 001.7-3L13.7 4.3a2 2 0 00-3.4 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-950">{{ number_format($studentsWithAllergies) }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">With Allergies</p>
                    </div>
                </div>
            </article>

            {{-- PENDING APPROVALS WIDGET (CLICKABLE) --}}
            <a href="{{ route('students.index', ['status' => 'pending']) }}" 
               class="rounded-xl border-l-4 border-yellow-500 bg-white p-6 shadow-sm relative overflow-hidden hover:shadow-md transition cursor-pointer group">
                <div class="flex items-center gap-4 mb-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50 text-yellow-600 group-hover:bg-yellow-100 transition">
                        <svg width="27" height="27" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-950">{{ number_format($pendingApprovalsCount) }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Pending Approvals</p>
                    </div>
                </div>
                
                @if(isset($pendingStudents) && $pendingStudents->count() > 0)
                    <div class="space-y-2 mt-2 border-t border-slate-100 pt-3">
                        @foreach($pendingStudents as $student)
                            <div class="flex items-center justify-between text-xs bg-slate-50 p-2 rounded hover:bg-slate-100 transition">
                                <div class="truncate pr-2">
                                    <!-- Updated: Using 'name' instead of 'full_name' based on DB schema -->
                                    <span class="font-semibold text-slate-700 block truncate">{{ $student->name ?? $student->full_name }}</span>
                                    <!-- Updated: Using 'student_number' instead of 'unique_id' -->
                                    <span class="text-slate-400">{{ $student->student_number }}</span>
                                </div>
                                <span class="shrink-0 bg-yellow-500 text-white px-2 py-1 rounded font-medium">Review</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 mt-2 italic">No pending registrations.</p>
                @endif
            </a>
        </section>

        {{-- Chart Row --}}
        <section class="grid gap-6 xl:grid-cols-3">
            {{-- Visit Trends --}}
            <article class="rounded-xl bg-white p-6 shadow-sm xl:col-span-2">
                <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-800">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8"/>
                    </svg>
                    <h2 class="font-bold text-blue-900">Visitation Trends</h2>
                </div>
                <div class="flex h-72 flex-col items-center justify-center text-center">
                    <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="mb-3 text-slate-300">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8M3 21h18"/>
                    </svg>
                    <p class="font-semibold text-slate-500">No clinic visit data available yet.</p>
                    <p class="mt-1 text-sm text-slate-400">The trend chart will appear after recording clinic visits.</p>
                </div>
            </article>

            {{-- Top Complaints --}}
            <article class="rounded-xl bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-red-600">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M4 12a8 8 0 1116 0 8 8 0 01-16 0z"/>
                    </svg>
                    <h2 class="font-bold text-red-600">Top Complaints</h2>
                </div>
                <div class="flex h-72 flex-col items-center justify-center text-center">
                    <div class="mb-4 flex h-28 w-28 items-center justify-center rounded-full border-8 border-slate-100">
                        <span class="text-sm font-semibold text-slate-400">No data</span>
                    </div>
                    <p class="text-sm text-slate-400">Complaints will appear after visits are recorded.</p>
                </div>
            </article>
        </section>

        {{-- Student Distribution --}}
        <section class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center gap-2 border-b border-slate-200 pb-3">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-violet-600">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19V9m5 10V5m5 14v-7m5 7V3"/>
                </svg>
                <h2 class="font-bold text-violet-700">Registered Students by Course</h2>
            </div>
            @if(count($courseLabels) > 0)
                <div style="height: 300px;">
                    <canvas id="courseChart" data-labels='@json($courseLabels)' data-values='@json($courseData)'></canvas>
                </div>
            @else
                <div class="flex h-64 items-center justify-center text-slate-400">
                    No registered-student data available.
                </div>
            @endif
        </section>
    </div>

    {{-- Floating Add Button --}}
    <a href="{{ route('students.create') }}" title="Register a new student"
       class="fixed bottom-7 right-7 flex items-center justify-center rounded-full bg-emerald-500 text-3xl font-light text-white shadow-xl transition hover:bg-emerald-600"
       style="width: 60px; height: 60px;">
        +
    </a>

    @if(count($courseLabels) > 0)
        <script>
            window.addEventListener('load', function () {
                const canvas = document.getElementById('courseChart');
                if (!canvas || !window.Chart) return;

                new window.Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: JSON.parse(canvas.dataset.labels || '[]'),
                        datasets: [{
                            label: 'Registered Students',
                            data: JSON.parse(canvas.dataset.values || '[]'),
                            backgroundColor: '#8b5cf6',
                            borderRadius: 8,
                            maxBarThickness: 90
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#e2e8f0' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            });
        </script>
    @endif
@endsection