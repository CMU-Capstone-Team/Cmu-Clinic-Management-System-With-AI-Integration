<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Student Portal') | CMU Alaga</title>
    <script>
        (() => {
            let collapsed = window.innerWidth <= 900;
            try {
                if (window.innerWidth > 900) {
                    collapsed = localStorage.getItem('cmu-student-sidebar') === 'collapsed';
                }
            } catch (error) {}
            document.documentElement.classList.toggle('sp-collapsed', collapsed);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">

    <style>
        :root {
            --sp-width: 280px;
        }
        html.sp-collapsed {
            --sp-width: 88px;
        }
        body {
            margin: 0;
            background: #edf2f8;
            color: #172b48;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .sp-sidebar, .sp-sidebar *, .sp-content {
            box-sizing: border-box;
        }
        .sp-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 60;
            width: var(--sp-width);
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: linear-gradient(180deg, #2341a8 0%, #203c91 40%, #1a2b59 100%);
            color: #fff;
            box-shadow: 6px 0 24px rgba(18, 35, 78, .10);
        }
        .sp-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            height: 100px;
            flex: 0 0 100px;
            padding: 0 24px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sp-logo-toggle {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            padding: 8px;
            border: 0;
            border-radius: 15px;
            background: #fff;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,.12);
        }
        .sp-logo-toggle img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .sp-brand-copy {
            flex: 0 0 175px;
            white-space: nowrap;
        }
        .sp-brand-copy strong {
            display: block;
            font-size: 21px;
            font-weight: 750;
            letter-spacing: -.6px;
        }
        .sp-brand-copy small {
            display: block;
            margin-top: 5px;
            color: #a9bce8;
            font-size: 12px;
        }
        .sp-menu {
            flex: 1;
            min-height: 0;
            padding: 24px 16px;
            overflow-x: hidden;
            overflow-y: auto;
        }
        .sp-link {
            display: flex;
            align-items: center;
            gap: 14px;
            height: 44px;
            padding: 0 16px;
            margin-bottom: 8px;
            border-radius: 11px;
            color: #e1e9ff;
            text-decoration: none;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 600;
        }
        .sp-link:hover {
            background: rgba(255,255,255,.085);
            color: #fff;
        }
        .sp-link.is-active {
            background: linear-gradient(100deg, #3780f6, #2964e9);
            color: #fff;
            box-shadow: 0 4px 12px rgba(12, 38, 115, .18);
        }
        .sp-icon {
            width: 20px;
            height: 20px;
            flex: 0 0 20px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.75;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .sp-label {
            flex: 0 0 auto;
            white-space: nowrap;
        }
        .sp-footer {
            flex: 0 0 auto;
            padding: 18px 16px;
            border-top: 1px solid rgba(255,255,255,.1);
            background: #17264e;
        }
        .sp-footer form {
            margin: 0;
        }
        .sp-logout {
            display: flex;
            align-items: center;
            gap: 14px;
            width: 100%;
            height: 44px;
            padding: 0 17px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 10px;
            background: rgba(255,255,255,.065);
            color: #e2eaff;
            font: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .sp-logout:hover {
            background: rgba(255,255,255,.14);
        }
        .sp-workspace {
            min-height: 100vh;
            margin-left: var(--sp-width);
        }
        .sp-content {
            width: 100%;
            max-width: 1440px;
            margin: 0 auto;
            padding: 42px 36px 56px;
        }
        html.sp-collapsed .sp-brand-copy,
        html.sp-collapsed .sp-label {
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
        }
        html.sp-ready .sp-sidebar {
            transition: width .22s ease;
        }
        html.sp-ready .sp-workspace {
            transition: margin-left .22s ease;
        }
        .sp-sidebar a:focus-visible,
        .sp-sidebar button:focus-visible {
            outline: 3px solid #a7c5ff;
            outline-offset: 3px;
        }
        @media (max-width: 900px) {
            .sp-workspace {
                margin-left: 88px;
            }
            .sp-content {
                padding: 28px 18px 40px;
            }
        }
        @media (max-width: 480px) {
            .sp-content {
                padding: 24px 12px 36px;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            html.sp-ready .sp-sidebar,
            html.sp-ready .sp-workspace {
                transition: none;
            }
        }
        .sp-link, .sp-logout {
            transition: background .18s ease, color .18s ease, box-shadow .18s ease;
        }
        .sp-logo-toggle { transition: box-shadow .18s ease; }
        .sp-logo-toggle:hover { box-shadow: 0 5px 16px rgba(0,0,0,.18); }
        .sp-menu { scrollbar-width: thin; scrollbar-color: #536dac transparent; }
        .sp-link.is-active:hover { background: linear-gradient(100deg, #438afa, #3270ef); }
        @media (prefers-reduced-motion: reduce) {
            .sp-link, .sp-logout, .sp-logo-toggle { transition: none; }
        }
        /* Same typography as the supplied admin layout. */
        .sp-brand-copy strong {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.5px;
        }
        .sp-brand-copy small { font-size: 16px; font-weight: 400; line-height: 24px; }
        .sp-link { font-size: 14px; font-weight: 500; line-height: 20px; }
        .sp-logout { font: 600 13px/20px 'Inter', sans-serif; }
        .sp-workspace button, .sp-workspace input, .sp-workspace select, .sp-workspace textarea { font-family: inherit; }
    </style>
    @stack('styles')
    <style>
        .sh-heading h1 { font-size: 30px; font-weight: 700; line-height: 36px; letter-spacing: normal; }
        .sh-heading p { font-size: 16px; font-weight: 400; line-height: 24px; }
    </style>
</head>
<body>
@php
    $portalLinks = [
        ['route' => 'student.home', 'label' => 'Home', 'icon' => 'home'],
        ['route' => 'student.profile', 'label' => 'My Profile', 'icon' => 'user'],
        ['route' => 'student.visits', 'label' => 'Clinic Visits', 'icon' => 'clock'],
        ['route' => 'student.records', 'label' => 'Medical Records', 'icon' => 'file'],
    ];
@endphp
<aside class="sp-sidebar" id="sp-sidebar" aria-label="Student sidebar">
    <div class="sp-brand">
        <button
            type="button"
            class="sp-logo-toggle"
            id="sp-logo-toggle"
            aria-label="Toggle sidebar"
            aria-controls="sp-sidebar"
            title="Expand or collapse sidebar"
        >
            <img
                src="{{ asset('images/cmu-alaga-logo.png') }}"
                alt="CMU Alaga"
                width="48"
                height="48"
            >
        </button>
        <div class="sp-brand-copy">
            <strong>CMU Alaga</strong>
            <small>Student Portal</small>
        </div>
    </div>
    <nav class="sp-menu" aria-label="Student pages">
        @foreach ($portalLinks as $link)
            @php
                $isActive = request()->routeIs($link['route'], $link['route'] . '.*');
            @endphp
            <a
                href="{{ route($link['route']) }}"
                class="sp-link {{ $isActive ? 'is-active' : '' }}"
                title="{{ $link['label'] }}"
                aria-label="{{ $link['label'] }}"
                @if ($isActive) aria-current="page" @endif
            >
                <svg class="sp-icon" viewBox="0 0 24 24" aria-hidden="true">
                    @if ($link['icon'] === 'home')
                        <path d="m3 10 9-7 9 7M5 9v12h14V9M9 21v-8h6v8"/>
                    @elseif ($link['icon'] === 'user')
                        <circle cx="12" cy="7" r="4"/>
                        <path d="M4 21v-2a8 8 0 0 1 16 0v2Z"/>
                    @elseif ($link['icon'] === 'clock')
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    @else
                        <path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9Z"/>
                        <path d="M14 3v6h6M8 13h8M8 17h6"/>
                    @endif
                </svg>
                <span class="sp-label">{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>
    <div class="sp-footer">
        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="sp-logout" title="Log out" aria-label="Log out">
                <svg class="sp-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4"/>
                    <path d="M9 12h12m-4-4 4 4-4 4"/>
                </svg>
                <span class="sp-label">Log out</span>
            </button>
        </form>
    </div>
</aside>
<div class="sp-workspace">
    <main class="sp-content">
        @yield('content')
    </main>
</div>
<script>
(() => {
    const root = document.documentElement;
    const toggle = document.getElementById('sp-logo-toggle');
    const sidebar = document.getElementById('sp-sidebar');
    const mobile = window.matchMedia('(max-width: 900px)');
    function syncSidebar() {
        const expanded = !root.classList.contains('sp-collapsed');
        toggle.setAttribute('aria-expanded', String(expanded));
        toggle.setAttribute(
            'aria-label',
            expanded ? 'Collapse sidebar' : 'Expand sidebar'
        );
    }
    toggle.addEventListener('click', () => {
        root.classList.toggle('sp-collapsed');
        if (!mobile.matches) {
            try {
                localStorage.setItem(
                    'cmu-student-sidebar',
                    root.classList.contains('sp-collapsed')
                        ? 'collapsed'
                        : 'expanded'
                );
            } catch (error) {}
        }
        syncSidebar();
    });
    document.addEventListener('click', event => {
        if (mobile.matches && !sidebar.contains(event.target)) {
            root.classList.add('sp-collapsed');
            syncSidebar();
        }
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !root.classList.contains('sp-collapsed')) {
            root.classList.add('sp-collapsed');
            syncSidebar();
            toggle.focus();
        }
    });
    mobile.addEventListener('change', event => {
        if (event.matches) {
            root.classList.add('sp-collapsed');
        }
        syncSidebar();
    });
    syncSidebar();
    requestAnimationFrame(() => {
        requestAnimationFrame(() => root.classList.add('sp-ready'));
    });
})();
</script>
@stack('scripts')
</body>
</html>