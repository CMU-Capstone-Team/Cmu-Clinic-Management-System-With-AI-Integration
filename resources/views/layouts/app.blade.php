<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CMU Alaga')</title>
    <script>
        // Restore BEFORE the body is rendered, including after navigation.
        try {
            document.documentElement.dataset.sidebarCollapsed =
                localStorage.getItem('sidebarCollapsed') === 'true' ? 'true' : 'false';
        } catch (error) {
            document.documentElement.dataset.sidebarCollapsed = 'false';
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 88px;
            --sidebar-duration: 280ms;
            --sidebar-ease: cubic-bezier(.4, 0, .2, 1);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .app-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
            width: var(--sidebar-width);
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: linear-gradient(180deg, #1e40af 0%, #1e3a8a 50%, #172554 100%);
            box-shadow: 4px 0 24px rgb(30 58 138 / 15%);
        }
        /* Fixed row geometry: icons never move when the outer rail shrinks. */
        .logo-container {
            flex: 0 0 auto;
            padding: 24px;
            border-bottom: 1px solid rgb(255 255 255 / 10%);
            cursor: pointer;
        }
        .logo-container:hover { background: rgb(255 255 255 / 5%); }
        .logo-wrapper {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 232px;
            height: 48px;
            gap: 14px;
        }
        .logo-icon {
            flex: 0 0 48px;
            width: 48px;
            height: 48px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 12px rgb(0 0 0 / 20%);
        }
        .logo-icon img { width: 100%; height: 100%; object-fit: contain; }
        .logo-text { flex: 0 0 170px; white-space: nowrap; }
        .logo-text h1 {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: white;
            letter-spacing: -.5px;
        }
        .nav-section {
            flex: 1 1 auto;
            min-height: 0;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
        }
        .nav-section::-webkit-scrollbar { display: none; }
        .nav-link {
            position: relative;
            flex: 0 0 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            overflow: hidden;
            white-space: nowrap;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            line-height: 20px;
            color: #e2e8f0;
        }
        .nav-link:hover { color: white; background-color: rgb(255 255 255 / 8%); }
        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            box-shadow: 0 4px 16px rgb(37 99 235 / 30%);
        }
        .nav-link svg { flex: 0 0 20px; width: 20px; height: 20px; stroke-width: 1.8; }
        .nav-link > span { flex: 0 0 auto; }
        .nav-link.disabled { opacity: .4; cursor: not-allowed; }
        .nav-link.disabled:hover { color: #e2e8f0; background: transparent; }
        .user-section {
            flex: 0 0 auto;
            padding: 18px;
            border-top: 1px solid rgb(255 255 255 / 10%);
            background: rgb(0 0 0 / 15%);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 244px;
            height: 42px;
            margin-bottom: 14px;
        }
        .user-avatar {
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            box-shadow: 0 4px 12px rgb(37 99 235 / 30%);
            color: white;
            font: 700 15px 'Plus Jakarta Sans', sans-serif;
        }
        .user-details { flex: 0 0 190px; display: flex; flex-direction: column; gap: 2px; }
        .user-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: white;
            font: 600 14px/20px 'Plus Jakarta Sans', sans-serif;
        }
        .user-role { color: #93c5fd; font-size: 12px; line-height: 18px; white-space: nowrap; }
        .user-section form { margin: 0; }
        .logout-btn {
            width: 100%;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 12px;
            padding: 0 15px;
            overflow: hidden;
            white-space: nowrap;
            border: 1px solid rgb(255 255 255 / 10%);
            border-radius: 10px;
            background: rgb(255 255 255 / 8%);
            color: #e2e8f0;
            font: 600 13px/20px 'Inter', sans-serif;
            cursor: pointer;
        }
        .logout-btn svg { flex: 0 0 18px; width: 18px; height: 18px; }
        .logout-btn span { flex: 0 0 auto; }
        .logout-btn:hover { background: rgb(239 68 68 / 20%); border-color: rgb(239 68 68 / 40%); color: #fca5a5; }
        .app-content { min-width: 0; min-height: 100vh; margin-left: var(--sidebar-width); }
        .app-content > main { width: 100%; max-width: 1600px; margin: auto; padding: 40px; }
        .mobile-header { display: none; background: rgb(255 255 255 / 95%); border-bottom: 1px solid #e2e8f0; }
        a:focus-visible, button:focus-visible, [role="button"]:focus-visible { outline: 3px solid #60a5fa; outline-offset: -3px; }
        .clinic-skip {
            position: fixed; top: 12px; left: 12px; z-index: 100;
            padding: 12px 20px; background: white; color: #1e3a8a;
            border-radius: 10px; transform: translateY(-160%);
        }
        .clinic-skip:focus { transform: translateY(0); }
        .logo-text, .nav-link > span, .user-details, .logout-btn span { opacity: 1; }
        html[data-sidebar-collapsed="true"] .app-sidebar { width: var(--sidebar-collapsed-width); }
        html[data-sidebar-collapsed="true"] .app-content { margin-left: var(--sidebar-collapsed-width); }
        html[data-sidebar-collapsed="true"] .logo-text,
        html[data-sidebar-collapsed="true"] .nav-link > span,
        html[data-sidebar-collapsed="true"] .user-details,
        html[data-sidebar-collapsed="true"] .logout-btn span { opacity: 0; pointer-events: none; }
        /* Enable animation only after the saved state has been painted. */
        html.sidebar-ready .app-sidebar { transition: width var(--sidebar-duration) var(--sidebar-ease); }
        html.sidebar-ready .app-content { transition: margin-left var(--sidebar-duration) var(--sidebar-ease); }
        html.sidebar-ready .logo-text,
        html.sidebar-ready .nav-link > span,
        html.sidebar-ready .user-details,
        html.sidebar-ready .logout-btn span { transition: opacity 160ms ease; }
        @media (max-width: 1024px) {
            .app-sidebar { display: none; }
            .app-content { margin-left: 0 !important; }
            .app-content > main { padding: 24px; }
            .mobile-header { display: block; }
        }
        @media (max-width: 640px) { .app-content > main { padding: 20px 16px; } }
        @media (prefers-reduced-motion: reduce) {
            html.sidebar-ready .app-sidebar, html.sidebar-ready .app-content,
            html.sidebar-ready .logo-text, html.sidebar-ready .nav-link > span,
            html.sidebar-ready .user-details, html.sidebar-ready .logout-btn span { transition: none; }
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased">
    <a class="clinic-skip" href="#main-content">Skip to content</a>

    <div class="min-h-screen flex">
        {{-- Desktop Sidebar --}}
        <aside class="app-sidebar" id="sidebar" role="navigation" aria-label="Main navigation">
            {{-- Logo Section --}}
            <div class="logo-container" id="sidebarToggle" role="button" tabindex="0" aria-label="Toggle sidebar collapse" aria-controls="sidebar" aria-expanded="true">
                <div class="logo-wrapper">
                    <div class="logo-icon">
                        <img src="{{ asset('images/cmu-alaga-logo.png') }}" alt="CMU Alaga Logo">
                    </div>
                    <div class="logo-text">
                        <h1>CMU Alaga</h1>
                    </div>
                </div>
            </div>

            {{-- Navigation (Workspace label removed) --}}
            <nav class="nav-section">
                <a aria-label="Dashboard" href="{{ route('dashboard') }}" 
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a aria-label="Manage Students" href="{{ route('students.index') }}" 
                   class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('students.*') ? 'page' : 'false' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-8v5c3 2 9 2 12 0v-5"/>
                    </svg>
                    <span>Manage Students</span>
                </a>

                <a aria-label="Clinic Visits" href="{{ route('clinic-visits.index') }}" 
                   class="nav-link {{ request()->routeIs('clinic-visits.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('clinic-visits.*') ? 'page' : 'false' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Clinic Visits</span>
                </a>

                <div class="nav-link disabled" aria-disabled="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m8-3a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                    <span>Medical Excuses</span>
                </div>

                <div class="nav-link disabled" aria-disabled="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h6m0 0v6m0-6L10 16l-4-4-3 3"/>
                    </svg>
                    <span>Hospital Referrals</span>
                </div>

                <div class="nav-link disabled" aria-disabled="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6m4 6V7m4 10v-3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>Reports</span>
                </div>
            </nav>

            {{-- User Section --}}
            <div class="user-section">
                <div class="user-info">
                    <div class="user-avatar" aria-hidden="true">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" aria-label="Log out">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Log out</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <div class="app-content flex-1 flex flex-col" id="appContent">
            {{-- Mobile Header --}}
            <header class="mobile-header sticky top-0 z-40 px-4 py-3">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/cmu-alaga-logo.png') }}" alt="" width="40" height="40" class="h-10 w-10 object-contain rounded-lg">
                        <div>
                            <p class="font-bold text-slate-900 font-['Plus_Jakarta_Sans']">CMU Alaga</p>
                        </div>
                    </div>
                    <details class="relative">
                        <summary class="cursor-pointer rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">Menu</summary>
                        <div class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-2 shadow-xl">
                            <a aria-label="Dashboard" href="{{ route('dashboard') }}" class="block rounded-lg px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Dashboard</a>
                            <a aria-label="Manage Students" href="{{ route('students.index') }}" class="block rounded-lg px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Manage Students</a>
                            <a aria-label="Clinic Visits" href="{{ route('clinic-visits.index') }}" class="block rounded-lg px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Clinic Visits</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-slate-200 pt-2">
                                @csrf
                                <button type="submit" class="w-full rounded-lg px-4 py-3 text-left text-sm font-semibold text-red-600 hover:bg-red-50">Log out</button>
                            </form>
                        </div>
                    </details>
                </div>
            </header>

            {{-- Page Content --}}
            <main id="main-content" tabindex="-1" class="flex-1">
                @if(session('success'))
                    <div role="status" class="mx-auto mb-6 max-w-6xl rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div role="alert" class="mx-auto mb-6 max-w-6xl rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (() => {
            const root = document.documentElement;
            const toggle = document.getElementById('sidebarToggle');
            const syncButton = () => {
                const collapsed = root.dataset.sidebarCollapsed === 'true';
                toggle.setAttribute('aria-expanded', String(!collapsed));
                toggle.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
            };
            const toggleSidebar = () => {
                const collapsed = root.dataset.sidebarCollapsed !== 'true';
                root.dataset.sidebarCollapsed = String(collapsed);
                try { localStorage.setItem('sidebarCollapsed', String(collapsed)); } catch (error) {}
                syncButton();
            };
            syncButton();
            toggle.addEventListener('click', toggleSidebar);
            toggle.addEventListener('keydown', (event) => {
                if ((event.key === 'Enter' || event.key === ' ') && !event.repeat) {
                    event.preventDefault();
                    toggleSidebar();
                }
            });
            requestAnimationFrame(() => requestAnimationFrame(() => root.classList.add('sidebar-ready')));
        })();
    </script>

    @stack('scripts')
</body>
</html>