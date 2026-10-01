@extends('layouts.app')

@section('title', 'Student Records')

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-950">
                Student Records
            </h1>
            <p class="mt-1 text-slate-500">
                Search and manage registered student profiles.
            </p>
        </div>

        <a href="{{ route('students.create') }}"
           class="rounded-xl bg-blue-900 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800 transition">
            Register Student
        </a>
    </div>

    <section class="rounded-2xl bg-white shadow-sm">
        {{-- Filter & Search Bar --}}
        <div class="border-b border-slate-200 p-6">
            <form method="GET" action="{{ route('students.index') }}" class="flex flex-wrap gap-3">
                
                {{-- Status Filter Dropdown --}}
                <select name="status" 
                        class="rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-600 focus:ring-blue-600 min-w-[160px]">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                {{-- Search Input --}}
                <input type="search" name="search" value="{{ $search }}"
                       placeholder="Student number or name"
                       class="min-w-64 flex-1 rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-600 focus:ring-blue-600">

                <button type="submit"
                        class="rounded-xl bg-blue-900 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800 transition">
                    Search
                </button>

                @if ($search !== '' || request('status'))
                    <a href="{{ route('students.index') }}"
                       class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if ($students->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-2xl text-slate-500">?</div>
                <h2 class="mt-4 text-lg font-semibold">
                    {{ $search !== '' || request('status') ? 'No matching students found' : 'No student records yet' }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $search !== '' || request('status') ? 'Try adjusting your filters.' : 'Register the first student to begin.' }}
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Course</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($students as $student)
                            <tr class="hover:bg-slate-50 transition">
                                {{-- Student Info --}}
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $student->name ?? $student->full_name }}</p>
                                    <p class="text-sm text-slate-500">{{ $student->student_number }}</p>
                                </td>

                                {{-- Course Info --}}
                                <td class="px-6 py-4 text-sm">
                                    <p class="font-medium">{{ $student->course ?? '—' }}</p>
                                    <p class="text-slate-500">
                                        Year {{ $student->year_level ?? 'N/A' }}
                                        @if ($student->section) – {{ $student->section }} @endif
                                    </p>
                                </td>

                                {{-- Contact --}}
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $student->contact_number ?? '—' }}
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'active'  => 'bg-emerald-100 text-emerald-700',
                                            'rejected'=> 'bg-red-100 text-red-700',
                                        ];
                                        $badgeClass = $statusColors[$student->status] ?? 'bg-slate-100 text-slate-600';
                                    @endphp
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>

                                {{-- Actions Column --}}
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if($student->status === 'pending')
                                        {{-- APPROVE BUTTON --}}
                                        <form action="{{ route('students.approve', $student->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Approve this student registration?')"
                                                    class="rounded-lg bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-600 transition">
                                                Approve
                                            </button>
                                        </form>

                                        {{-- REJECT BUTTON --}}
                                        <form action="{{ route('students.reject', $student->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Reject this student registration?')"
                                                    class="rounded-lg bg-red-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-600 transition">
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('students.show', $student->id) }}"
                                           class="text-sm font-semibold text-blue-800 hover:text-blue-600">
                                            View Profile
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($students->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $students->links() }}
                </div>
            @endif
        @endif
    </section>
@endsection