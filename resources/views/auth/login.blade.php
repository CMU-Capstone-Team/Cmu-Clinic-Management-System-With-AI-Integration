<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Login | CMU Alaga</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-slate-900">
    <main class="flex min-h-screen items-center justify-center p-6">
        <section class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center overflow-hidden rounded-xl shadow-sm">
                    <img
                        src="{{ asset('images/cmu-alaga-logo.png') }}"
                        alt="CMU Alaga Logo"
                        class="h-full w-full object-contain"
                    >
                </div>

                <h1 class="text-2xl font-bold text-slate-900">
                    CMU Alaga
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Authorized clinic personnel only
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                        Staff email
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="name@clinic.test"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-700 focus:ring-2 focus:ring-blue-200"
                    >
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                        Password
                    </label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-700 focus:ring-2 focus:ring-blue-200"
                    >
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-slate-300 text-blue-700 focus:ring-blue-600"
                    >
                    Remember me
                </label>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-800 px-4 py-3 font-semibold text-white transition hover:bg-blue-900 focus:ring-4 focus:ring-blue-200"
                >
                    Sign in to Clinic System
                </button>
            </form>

            <p class="mt-8 text-center text-xs text-slate-400">
                Access is monitored and restricted to authorized staff.
            </p>
        </section>
    </main>
</body>
</html>