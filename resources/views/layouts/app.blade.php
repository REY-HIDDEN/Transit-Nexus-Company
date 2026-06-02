<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', __('messages.dashboard')) | Transit Nexus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }

        html { scroll-behavior: smooth; }

        .app-shell {
            animation: adminFadeIn 0.5s ease-out both;
        }

        @keyframes adminFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        :root {
            --tn-ink: #0b1a33;
            --tn-muted: #64748b;
            --tn-line: #e2e8f0;
            --tn-panel: #ffffff;
            --tn-bg: #f1f5f9;
            --tn-blue: #2563eb;
            --tn-teal: #0f766e;
            --tn-gold: #d97706;
            --tn-sidebar: #0f172a;
            --tn-sidebar-hover: rgba(37, 99, 235, 0.15);
            --tn-sidebar-active: rgba(37, 99, 235, 0.25);
            --tn-gradient: linear-gradient(135deg, #1e40af, #0f766e);
            --tn-input-bg: #ffffff;
            --tn-table-header: #f8fafc;
            --tn-table-hover: #f8fafc;
            --tn-alert-bg: #d1fae5;
            --tn-alert-color: #065f46;
            --tn-alert-danger-bg: #fee2e2;
            --tn-alert-danger-color: #991b1b;
        }

        html.dark {
            --tn-ink: #e2e8f0;
            --tn-muted: #94a3b8;
            --tn-line: #334155;
            --tn-panel: #1e293b;
            --tn-bg: #0f172a;
            --tn-blue: #60a5fa;
            --tn-teal: #2dd4bf;
            --tn-gold: #fbbf24;
            --tn-sidebar: #020617;
            --tn-sidebar-hover: rgba(96, 165, 250, 0.12);
            --tn-sidebar-active: rgba(96, 165, 250, 0.2);
            --tn-gradient: linear-gradient(135deg, #1e40af, #0f766e);
            --tn-input-bg: #1e293b;
            --tn-table-header: #1e293b;
            --tn-table-hover: #1e293b;
            --tn-alert-bg: #064e3b;
            --tn-alert-color: #a7f3d0;
            --tn-alert-danger-bg: #7f1d1d;
            --tn-alert-danger-color: #fca5a5;
        }

        body {
            background: var(--tn-bg);
            color: var(--tn-ink);
            font-size: 0.925rem;
            min-height: 100vh;
            transition: background 0.3s ease, color 0.3s ease;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                linear-gradient(135deg, rgba(11, 26, 51, 0.8) 0%, rgba(15, 118, 110, 0.5) 60%, rgba(11, 26, 51, 0.75) 100%),
                url('https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=1920&q=80') center/cover no-repeat fixed;
            pointer-events: none;
        }

        .app-shell { position: relative; z-index: 1; }

        .app-shell {
            display: grid;
            grid-template-columns: 270px minmax(0, 1fr);
            min-height: 100vh;
        }

        /* ── Sidebar ───────────────────────────────────────────── */
        .sidebar {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.97) 0%, rgba(15, 23, 42, 0.93) 100%);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #e2e8f0;
            padding: 1.5rem 1rem;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid rgba(255,255,255,0.06);
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        .brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 0 0.5rem 1.5rem 0.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 1.25rem;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--tn-gradient);
            color: white;
            font-size: 1.15rem;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
            flex-shrink: 0;
        }

        .nav-section-label {
            color: rgba(148, 163, 184, 0.5);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.5rem 1rem 0.25rem;
            margin-top: 0.75rem;
        }

        .nav-link {
            color: #94a3b8;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1rem;
            margin-bottom: .15rem;
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .nav-link i { font-size: 1.05rem; width: 1.3rem; text-align: center; flex-shrink: 0; }

        .nav-link:hover {
            color: #ffffff;
            background: var(--tn-sidebar-hover);
            transform: translateX(3px);
        }

        .nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.35), rgba(15, 118, 110, 0.2));
            box-shadow: inset 3px 0 0 var(--tn-blue);
        }

        .sidebar .nav-link {
            position: relative;
            padding-left: 2rem;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--tn-blue);
            opacity: 0;
            animation: pulseDot 2s ease-in-out infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 0.3; }
            50%      { opacity: 1; }
        }

        /* Language switcher in sidebar */
        .lang-switcher {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .lang-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid rgba(255,255,255,0.12);
            color: #94a3b8;
        }

        .lang-btn:hover { background: rgba(255,255,255,0.08); color: #fff; border-color: rgba(255,255,255,0.25); }
        .lang-btn.active-lang { background: rgba(37,99,235,0.25); color: #93c5fd; border-color: rgba(37,99,235,0.4); }

        /* ── Content ───────────────────────────────────────────── */
        .content { min-width: 0; }

        .topbar {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--tn-line);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }

        html.dark .topbar {
            background: rgba(15, 23, 42, 0.85);
            border-bottom: 1px solid var(--tn-line);
        }

        .topbar h1 { font-weight: 700; font-size: 1.2rem; letter-spacing: -0.01em; }

        .page { padding: 1.75rem 2rem; animation: pageContentIn 0.4s ease-out 0.15s both; }

        @keyframes pageContentIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Cards / Panels ────────────────────────────────────── */
        .panel, .metric-card {
            background: var(--tn-panel);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--tn-line);
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .panel:hover { box-shadow: 0 10px 36px rgba(0,0,0,0.13); }

        html.dark .panel, html.dark .metric-card {
            box-shadow: 0 4px 24px rgba(0,0,0,0.3);
        }

        .metric-card {
            padding: 1.35rem;
            border-radius: 14px;
            position: relative;
            overflow: hidden;
            animation: metricFadeIn 0.5s ease-out both;
        }

        .metric-card:nth-child(1) { animation-delay: 0.05s; }
        .metric-card:nth-child(2) { animation-delay: 0.12s; }
        .metric-card:nth-child(3) { animation-delay: 0.19s; }
        .metric-card:nth-child(4) { animation-delay: 0.26s; }

        @keyframes metricFadeIn {
            from { opacity: 0; transform: translateY(16px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .metric-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 14px;
            opacity: 0.04;
            background: var(--tn-gradient);
            pointer-events: none;
        }

        .metric-card .fs-3 { font-weight: 800; letter-spacing: -0.02em; }

        .metric-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .metric-card:nth-child(1) .metric-icon { background: linear-gradient(135deg,#dbeafe,#bfdbfe); color: #1d4ed8; }
        .metric-card:nth-child(2) .metric-icon { background: linear-gradient(135deg,#d1fae5,#a7f3d0); color: #047857; }
        .metric-card:nth-child(3) .metric-icon { background: linear-gradient(135deg,#fef3c7,#fde68a); color: #b45309; }
        .metric-card:nth-child(4) .metric-icon { background: linear-gradient(135deg,#fce7f3,#fbcfe8); color: #be185d; }

        /* ── Tables ────────────────────────────────────────────── */
        .table thead th {
            color: var(--tn-muted);
            font-size: .72rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            background: var(--tn-table-header);
            border-bottom: 2px solid var(--tn-line);
            padding: 0.85rem 1rem;
            font-weight: 700;
        }

        .table td { padding: 0.85rem 1rem; vertical-align: middle; }
        .table tbody tr {
            transition: background 0.15s ease;
            animation: rowFadeIn 0.4s ease-out both;
        }

        .table tbody tr:nth-child(1) { animation-delay: 0.02s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.06s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.10s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.14s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.18s; }
        .table tbody tr:nth-child(6) { animation-delay: 0.22s; }
        .table tbody tr:nth-child(7) { animation-delay: 0.26s; }
        .table tbody tr:nth-child(8) { animation-delay: 0.30s; }

        @keyframes rowFadeIn {
            from { opacity: 0; transform: translateX(-8px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .table tbody tr:hover { background: var(--tn-table-hover); }

        /* ── Buttons ───────────────────────────────────────────── */
        .btn-icon { display: inline-flex; align-items: center; gap: .4rem; font-weight: 600; }

        .btn { border-radius: 8px; font-weight: 600; padding: 0.5rem 1rem; transition: all 0.2s ease; }
        .btn-sm { border-radius: 6px; padding: 0.35rem 0.7rem; }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; box-shadow: 0 2px 8px rgba(37,99,235,0.25); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(37,99,235,0.35); }
        .btn-outline-primary { border: 1.5px solid #2563eb; color: #2563eb; }
        .btn-outline-primary:hover { background: #2563eb; color: white; transform: translateY(-1px); }
        .btn-outline-danger:hover { background: #dc2626; color: white; border-color: #dc2626; }
        .btn-outline-secondary { border: 1.5px solid var(--tn-line); color: var(--tn-muted); }
        .btn-outline-secondary:hover { background: var(--tn-panel); }

        .theme-toggle {
            display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 10px;
            border: 1.5px solid var(--tn-line);
            background: transparent; color: var(--tn-muted);
            font-size: 1.1rem; cursor: pointer;
            transition: all 0.2s ease;
        }
        .theme-toggle:hover { background: var(--tn-panel); color: var(--tn-blue); }

        /* ── Dark mode overrides ──────────────────────────────────── */
        html.dark body::before {
            opacity: 0.7;
        }

        html.dark .sidebar .brand .small {
            color: rgba(148, 163, 184, 0.5) !important;
        }

        html.dark .btn-outline-danger:hover { background: #991b1b; border-color: #dc2626; color: white; }

        html.dark .metric-card:nth-child(1) .metric-icon { background: linear-gradient(135deg,#1e3a5f,#1e40af); color: #93c5fd; }
        html.dark .metric-card:nth-child(2) .metric-icon { background: linear-gradient(135deg,#064e3b,#047857); color: #6ee7b7; }
        html.dark .metric-card:nth-child(3) .metric-icon { background: linear-gradient(135deg,#78350f,#92400e); color: #fcd34d; }
        html.dark .metric-card:nth-child(4) .metric-icon { background: linear-gradient(135deg,#831843,#9d174d); color: #f9a8d4; }

        html.dark .nav-link { color: #64748b; }
        html.dark .nav-link:hover { color: #e2e8f0; }
        html.dark .nav-link.active { color: #ffffff; }

        /* ── Forms ─────────────────────────────────────────────── */
        .form-label { font-weight: 600; color: var(--tn-ink); font-size: 0.875rem; margin-bottom: 0.35rem; }
        .form-control, .form-select {
            border-radius: 8px; border: 1.5px solid var(--tn-line);
            padding: 0.55rem 0.85rem; font-size: 0.9rem;
            background: var(--tn-input-bg);
            color: var(--tn-ink);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--tn-blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        }

        .badge-soft {
            border: 1px solid var(--tn-line);
            background: var(--tn-panel);
            color: var(--tn-muted);
            padding: 0.35em 0.75em;
            font-weight: 500;
            font-size: 0.8rem;
            border-radius: 6px;
        }

        .alert { border-radius: 10px; border: none; }
        .alert-success { background: var(--tn-alert-bg); color: var(--tn-alert-color); }
        .alert-danger { background: var(--tn-alert-danger-bg); color: var(--tn-alert-danger-color); }

        /* ── Reveal animations ─────────────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-stagger > * {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease-out, transform 0.4s ease-out;
        }

        .reveal-stagger.visible > *:nth-child(1) { transition-delay: 0.04s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(2) { transition-delay: 0.08s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(3) { transition-delay: 0.12s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(4) { transition-delay: 0.16s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(5) { transition-delay: 0.20s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(6) { transition-delay: 0.24s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(7) { transition-delay: 0.28s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(8) { transition-delay: 0.32s; opacity: 1; transform: translateY(0); }

        /* ── Responsive ────────────────────────────────────────── */
        @media (max-width: 991.98px) {
            .app-shell { grid-template-columns: 1fr; }
            .sidebar { position: static; height: auto; }
            .page { padding: 1rem; }
            .topbar { padding: 0.75rem 1rem; }
        }

        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .app-shell { display: block; }
            .page { padding: 0; }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <span class="brand-mark"><i class="bi bi-bus-front"></i></span>
            <div>
                <div class="fw-bold" style="font-size: 0.95rem;">Transit Nexus</div>
                <div class="small" style="color: rgba(148,163,184,0.7); font-size: 0.72rem;">{{ __('messages.ticket_operations') }}</div>
            </div>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i><span>{{ __('messages.dashboard') }}</span>
            </a>
            <a class="nav-link {{ request()->routeIs('buses.*') ? 'active' : '' }}" href="{{ route('buses.index') }}">
                <i class="bi bi-bus-front"></i><span>{{ __('messages.buses') }}</span>
            </a>
            <a class="nav-link {{ request()->routeIs('routes.*') ? 'active' : '' }}" href="{{ route('routes.index') }}">
                <i class="bi bi-signpost-2"></i><span>{{ __('messages.routes') }}</span>
            </a>
            <a class="nav-link {{ request()->routeIs('trips.*') ? 'active' : '' }}" href="{{ route('trips.index') }}">
                <i class="bi bi-calendar2-week"></i><span>{{ __('messages.trips') }}</span>
            </a>
            <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                <i class="bi bi-ticket-perforated"></i><span>{{ __('messages.bookings') }}</span>
            </a>
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.payments') }}">
                <i class="bi bi-bar-chart"></i><span>reports</span>
            </a>
        </nav>

        {{-- Language Switcher --}}
        <div class="lang-switcher">
            <div class="d-flex align-items-center gap-1 flex-wrap mb-2">
                <i class="bi bi-translate" style="color: rgba(148,163,184,0.6); font-size:0.8rem;"></i>
                <span style="color: rgba(148,163,184,0.5); font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.08em;">Language</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('lang.switch', 'en') }}"
                   class="lang-btn {{ app()->getLocale() === 'en' ? 'active-lang' : '' }}">
                    🇬🇧 EN
                </a>
                <a href="{{ route('lang.switch', 'rw') }}"
                   class="lang-btn {{ app()->getLocale() === 'rw' ? 'active-lang' : '' }}">
                    🇷🇼 RW
                </a>
            </div>
        </div>
    </aside>

    <main class="content">
        <header class="topbar d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="h4 mb-0">@yield('title', __('messages.dashboard'))</h1>
                @hasSection('subtitle')
                    <div class="text-secondary small mt-1">@yield('subtitle')</div>
                @endif
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                @yield('actions')
                <button class="theme-toggle" id="themeToggle" title="Toggle theme">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-icon">
                    <i class="bi bi-house"></i><span>{{ __('messages.public_site') }}</span>
                </a>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger btn-icon">
                        <i class="bi bi-box-arrow-right"></i><span>{{ __('messages.logout') }}</span>
                    </button>
                </form>
            </div>
        </header>

        <div class="page">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger d-flex gap-2">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                    <div>
                        <strong>{{ __('messages.check_form') }}</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    const html = document.documentElement;
    const toggle = document.getElementById('themeToggle');
    const icon = toggle.querySelector('i');

    function setTheme(theme) {
        if (theme === 'dark') {
            html.classList.add('dark');
            icon.className = 'bi bi-sun-fill';
        } else {
            html.classList.remove('dark');
            icon.className = 'bi bi-moon-stars-fill';
        }
        localStorage.setItem('theme', theme);
    }

    // Initialise from localStorage or system preference
    const stored = localStorage.getItem('theme');
    if (stored) {
        setTheme(stored);
    } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        setTheme('dark');
    }

    toggle.addEventListener('click', function() {
        setTheme(html.classList.contains('dark') ? 'light' : 'dark');
    });
})();

/* ── Scroll-triggered reveal animations ─────────────────────── */
(function() {
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });

    document.querySelectorAll('.reveal, .reveal-stagger').forEach(function(el) {
        observer.observe(el);
    });
})();
</script>
</body>
</html>
