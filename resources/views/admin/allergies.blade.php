@extends('layouts.app')

@section('title', 'Students With Allergies | CMU Alaga')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">

    <a
        href="{{ route('dashboard') }}"
        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-800 hover:underline"
    >
        <span aria-hidden="true">&larr;</span>
        Back to Dashboard
    </a>

    <section class="rounded-xl border-l-4 border-red-500 bg-white p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
                <svg width="27" height="27" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 4.3L2.6 18a2 2 0 001.7 3h15.4a2 2 0 001.7-3L13.7 4.3a2 2 0 00-3.4 0z"/>
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Students With Allergies
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ number_format($students->total()) }}
                    {{ $students->total() === 1 ? 'student has' : 'students have' }}
                    recorded allergies.
                </p>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="font-bold text-slate-800">
                Student Records
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Select View Record to open the student's details.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">
                            Student Number
                        </th>
                        <th scope="col" class="px-6 py-4 font-semibold">
                            Full Name
                        </th>
                        <th scope="col" class="px-6 py-4 font-semibold">
                            Course
                        </th>
                        <th scope="col" class="px-6 py-4 text-right font-semibold">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                {{ $student->student_number ?: 'Not provided' }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-slate-900">
                                {{ $student->full_name ?: 'Not provided' }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $student->course ?: 'Not provided' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('students.show', $student) }}"
                                    aria-label="View record for {{ $student->full_name }}"
                                    class="inline-flex whitespace-nowrap rounded-lg bg-blue-800 px-4 py-2 font-semibold text-white transition hover:bg-blue-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-800 focus-visible:ring-offset-2"
                                >
                                    View Record
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <p class="font-semibold text-slate-600">
                                    No students with recorded allergies.
                                </p>
                                <p class="mt-1 text-sm text-slate-400">
                                    Students will appear here once an allergy is recorded.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $students->links() }}
            </div>
        @endif
    </section>

</div>
@endsection