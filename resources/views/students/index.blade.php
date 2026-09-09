@extends('layouts.app')

@section('title', 'Student Records')

@section('content')
    <div
        class="mb-8 flex flex-wrap items-end
               justify-between gap-4"
    >
        <div>
            <h1 class="text-3xl font-bold text-slate-950">
                Student Records
            </h1>

            <p class="mt-1 text-slate-500">
                Search and manage registered student profiles.
            </p>
        </div>

        <a
            href="{{ route('students.create') }}"
            class="rounded-xl bg-blue-900 px-5 py-3
                   text-sm font-semibold text-white
                   hover:bg-blue-800"
        >
            Register Student
        </a>
    </div>

    <section class="rounded-2xl bg-white shadow-sm">
        <div class="border-b border-slate-200 p-6">
            <form
                method="GET"
                action="{{ route('students.index') }}"
                class="flex flex-wrap gap-3"
            >
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Student number or name"
                    class="min-w-64 flex-1 rounded-xl border
                           border-slate-300 px-4 py-3
                           focus:border-blue-600 focus:ring-blue-600"
                >

                <button
                    type="submit"
                    class="rounded-xl bg-blue-900 px-5 py-3
                           text-sm font-semibold text-white
                           hover:bg-blue-800"
                >
                    Search
                </button>

                @if ($search !== '')
                    <a
                        href="{{ route('students.index') }}"
                        class="rounded-xl border border-slate-300
                               px-5 py-3 text-sm font-medium
                               text-slate-700 hover:bg-slate-50"
                    >
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if ($students->isEmpty())
            <div class="px-6 py-16 text-center">
                <div
                    class="mx-auto flex h-14 w-14 items-center
                           justify-center rounded-full bg-slate-100
                           text-2xl text-slate-500"
                >
                    ?
                </div>

                <h2 class="mt-4 text-lg font-semibold">
                    {{ $search !== ''
                        ? 'No matching students found'
                        : 'No student records yet' }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $search !== ''
                        ? 'Try another student number or name.'
                        : 'Register the first student to begin.' }}
                </p>

                @if ($search === '')
                    <a
                        href="{{ route('students.create') }}"
                        class="mt-5 inline-block rounded-xl bg-blue-900
                               px-5 py-3 text-sm font-semibold
                               text-white hover:bg-blue-800"
                    >
                        Register Student
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs
                                       font-semibold uppercase
                                       tracking-wide text-slate-500"
                            >
                                Student
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs
                                       font-semibold uppercase
                                       tracking-wide text-slate-500"
                            >
                                Course
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs
                                       font-semibold uppercase
                                       tracking-wide text-slate-500"
                            >
                                Contact
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs
                                       font-semibold uppercase
                                       tracking-wide text-slate-500"
                            >
                                Status
                            </th>

                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($students as $student)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">
                                        {{ $student->full_name }}
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        {{ $student->student_number }}
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <p class="font-medium">
                                        {{ $student->course }}
                                    </p>

                                    <p class="text-slate-500">
                                        Year {{ $student->year_level }}

                                        @if ($student->section)
                                            – {{ $student->section }}
                                        @endif
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $student->contact_number ?? '—' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($student->is_active)
                                        <span
                                            class="rounded-full bg-emerald-100
                                                   px-3 py-1 text-xs
                                                   font-semibold
                                                   text-emerald-700"
                                        >
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-slate-200
                                                   px-3 py-1 text-xs
                                                   font-semibold
                                                   text-slate-600"
                                        >
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route(
                                            'students.show',
                                            $student
                                        ) }}"
                                        class="text-sm font-semibold
                                               text-blue-800
                                               hover:text-blue-600"
                                    >
                                        View profile
                                    </a>
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