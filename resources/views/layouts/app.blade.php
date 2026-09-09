<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'CMU ClinicAssist AI')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .app-sidebar {
            width: 260px;
            background: #173f82;
        }

        .app-content {
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            .app-sidebar {
                display: flex !important;
            }

            .app-content {
                margin-left: 260px;
            }

            .mobile-header {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100 font-sans text-slate-900 antialiased">
    <div class="min-h-screen">

        {{-- Desktop Sidebar --}}
        <aside class="app-sidebar fixed inset-y-0 left-0 z-40 hidden flex-col text-white">
            {{-- Logo --}}
            <div class="border-b border-blue-700 px-6 py-7">
                <div class="flex items-center gap-3">
                                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center
                            overflow-hidden rounded-xl shadow-sm"
                    >
                        <img
                            src="{{ asset('images/cmu-alaga-logo.png') }}"
                            alt="CMU Alaga Logo"
                            class="h-full w-full object-contain"
                        >
                    </div>

                    <div>
                        <h1 class="font-bold leading-tight">
                        CMU Alaga
                        </h1>

                        <p class="mt-1 text-xs text-blue-200">
                            Clinic Management System
                        </p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-2 px-4 py-6">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition
                   {{ request()->routeIs('dashboard')
                        ? 'bg-blue-500 text-white shadow'
                        : 'text-blue-100 hover:bg-blue-800' }}">

                    <svg width="20" height="20" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"/>
                    </svg>

                    Dashboard
                </a>

                <a href="{{ route('students.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition
                   {{ request()->routeIs('students.*')
                        ? 'bg-blue-500 text-white shadow'
                        : 'text-blue-100 hover:bg-blue-800' }}">

                    <svg width="20" height="20" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-8v5c3 2 9 2 12 0v-5"/>
                    </svg>

                    Manage Students
                </a>

                {{-- Disabled until modules are created --}}
                                <a href="{{ route('clinic-visits.index') }}"
                class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition
                {{ request()->routeIs('clinic-visits.*')
                        ? 'bg-blue-500 text-white shadow'
                        : 'text-blue-100 hover:bg-blue-800' }}">

                    <svg width="20"
                        height="20"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>

                    Clinic Visits
                </a>

                <div class="flex cursor-not-allowed items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-blue-300 opacity-70"
                     title="Available after creating the Medical Excuse module">
                    <svg width="20" height="20" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12h6m-3-3v6m8-3a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>

                    Medical Excuses
                </div>

                <div class="flex cursor-not-allowed items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-blue-300 opacity-70"
                     title="Available after creating the Referral module">
                    <svg width="20" height="20" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M13 7h6m0 0v6m0-6L10 16l-4-4-3 3"/>
                    </svg>

                    Hospital Referrals
                </div>

                <div class="flex cursor-not-allowed items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-blue-300 opacity-70"
                     title="Available after creating the Reports module">
                    <svg width="20" height="20" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 17v-6m4 6V7m4 10v-3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>

                    Reports
                </div>
            </nav>

            {{-- Logged-in Staff --}}
            <div class="border-t border-blue-700 p-5">
                <div class="mb-4">
                    <p class="truncate text-sm font-semibold text-white">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="mt-1 text-xs text-blue-200">
                        {{ ucfirst(auth()->user()->role) }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="w-full rounded-lg border border-orange-400 bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600">
                        Log out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Area --}}
        <div class="app-content">

            {{-- Mobile Header --}}
            <header class="mobile-header sticky top-0 z-30 border-b border-slate-200 bg-white px-4 py-3 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-400 font-bold text-blue-950">
                            AI
                        </div>

                        <div>
                            <p class="font-bold text-blue-900">
                                CMU ClinicAssist AI
                            </p>

                            <p class="text-xs text-slate-500">
                                Staff-Only Clinic System
                            </p>
                        </div>
                    </div>

                    <details class="relative">
                        <summary class="cursor-pointer rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white">
                            Menu
                        </summary>

                        <div class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-2 shadow-xl">
                            <a href="{{ route('dashboard') }}"
                               class="block rounded-lg px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                                Dashboard
                            </a>

                            <a href="{{ route('students.index') }}"
                               class="block rounded-lg px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                                Manage Students
                            </a>
                            <a href="{{ route('clinic-visits.index') }}"
                            class="block rounded-lg px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                                Clinic Visits
                            </a>

                            <form method="POST"
                                  action="{{ route('logout') }}"
                                  class="mt-2 border-t border-slate-200 pt-2">
                                @csrf

                                <button type="submit"
                                        class="w-full rounded-lg px-4 py-3 text-left text-sm font-semibold text-red-600 hover:bg-red-50">
                                    Log out
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="mx-auto mb-6 max-w-6xl rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-auto mb-6 max-w-6xl rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>