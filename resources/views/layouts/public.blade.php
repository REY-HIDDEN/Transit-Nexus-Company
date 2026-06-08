<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', 'Transit Nexus')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }

        html { scroll-behavior: smooth; }

        .app-container {
            animation: pageFadeIn 0.6s ease-out;
        }

        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        :root {
            --tn-ink: #0b1a33;
            --tn-muted: #64748b;
            --tn-line: #e2e8f0;
            --tn-bg: #f0f4f8;
            --tn-blue: #2563eb;
            --tn-teal: #0f766e;
            --tn-gradient: linear-gradient(135deg, #1e40af, #0f766e);
            --tn-panel: #ffffff;
            --tn-input-bg: #ffffff;
            --tn-alert-bg: #d1fae5;
            --tn-alert-color: #065f46;
            --tn-alert-danger-bg: #fee2e2;
            --tn-alert-danger-color: #991b1b;
            --tn-footer-bg: rgba(255,255,255,0.88);
        }

        html.dark {
            --tn-ink: #e2e8f0;
            --tn-muted: #94a3b8;
            --tn-line: #334155;
            --tn-bg: #0f172a;
            --tn-blue: #60a5fa;
            --tn-teal: #2dd4bf;
            --tn-gradient: linear-gradient(135deg, #1e40af, #0f766e);
            --tn-panel: #1e293b;
            --tn-input-bg: #1e293b;
            --tn-alert-bg: #064e3b;
            --tn-alert-color: #a7f3d0;
            --tn-alert-danger-bg: #7f1d1d;
            --tn-alert-danger-color: #fca5a5;
            --tn-footer-bg: rgba(15, 23, 42, 0.88);
        }

        body {
            background: var(--tn-bg);
            color: var(--tn-ink);
            font-size: 0.95rem;
            min-height: 100vh;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* ── Background image slideshow ─────────────────────────── */
        .bg-slideshow {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .bg-slideshow .slide {
            position: absolute;
            inset: -4px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 1.8s ease-in-out;
        }

        .bg-slideshow .slide.active {
            opacity: 1;
        }

        .bg-slideshow .slide::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(11, 26, 51, 0.85) 0%, rgba(15, 118, 110, 0.65) 50%, rgba(11, 26, 51, 0.8) 100%);
        }

        html.dark .bg-slideshow { display: none; }

        .site-nav {
            background: rgba(255, 255, 255, .9);
            border-bottom: 1px solid var(--tn-line);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 1px 6px rgba(0,0,0,0.07);
        }

        html.dark .site-nav {
            background: rgba(15, 23, 42, 0.9);
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--tn-gradient);
            color: white;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(15, 118, 110, 0.25);
            animation: brandPulse 3s ease-in-out infinite;
        }

        @keyframes brandPulse {
            0%, 100% { box-shadow: 0 4px 10px rgba(15, 118, 110, 0.25); }
            50%      { box-shadow: 0 4px 20px rgba(37, 99, 235, 0.35); }
        }

        /* Language switcher pills */
        .lang-pills { display: flex; align-items: center; gap: 0.3rem; }
        .lang-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            border: 1.5px solid var(--tn-line);
            color: var(--tn-muted);
            transition: all 0.2s ease;
            letter-spacing: 0.03em;
        }
        .lang-pill:hover { border-color: var(--tn-blue); color: var(--tn-blue); background: rgba(37,99,235,0.05); }
        .lang-pill.active-lang { border-color: var(--tn-blue); color: var(--tn-blue); background: rgba(37,99,235,0.08); }

        /* Divider between nav items */
        .nav-divider { width: 1px; height: 20px; background: var(--tn-line); margin: 0 0.25rem; }

        .hero {
            min-height: 640px;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0f766e 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(37, 99, 235, 0.25) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(15, 118, 110, 0.2) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Animated dots */
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
        }

        .hero > .container { position: relative; z-index: 1; }

        .hero h1 {
            font-size: clamp(2.6rem, 5.5vw, 5rem);
            line-height: 1.05;
            letter-spacing: -0.02em;
            font-weight: 800;
        }

        .search-panel,
        .content-panel,
        .trip-card,
        .auth-panel {
            background: var(--tn-panel);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--tn-line);
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .trip-card:hover {
            box-shadow: 0 16px 48px rgba(0,0,0,0.18);
            transform: translateY(-4px);
        }

        html.dark .search-panel,
        html.dark .content-panel,
        html.dark .trip-card,
        html.dark .auth-panel {
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }

        .search-panel { max-width: 760px; border-radius: 14px; }
        .trip-card { height: 100%; border-radius: 14px; transition: all 0.25s ease; }
        .content-panel { border-radius: 14px; }
        .auth-panel { border-radius: 16px; }

        .badge-soft {
            border: 1px solid var(--tn-line);
            background: var(--tn-panel);
            color: var(--tn-muted);
            padding: 0.35em 0.75em;
            font-weight: 500;
            font-size: 0.8rem;
            border-radius: 6px;
        }

        .section-pad { padding: 5rem 0; }

        .btn-icon { display: inline-flex; align-items: center; gap: .4rem; font-weight: 600; }
        .btn { border-radius: 8px; font-weight: 600; padding: 0.5rem 1.1rem; transition: all 0.2s ease; position: relative; overflow: hidden; }
        .btn-sm { border-radius: 6px; padding: 0.35rem 0.75rem; }

        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.15);
            transform: translateX(-100%) skewX(-15deg);
            transition: transform 0.5s ease;
            pointer-events: none;
        }

        .btn-primary:hover::after,
        .btn-outline-primary:hover::after {
            transform: translateX(100%) skewX(-15deg);
        }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none; box-shadow: 0 2px 8px rgba(37,99,235,0.25); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(37,99,235,0.35); }
        .btn-outline-primary { border: 1.5px solid #2563eb; color: #2563eb; }
        .btn-outline-primary:hover { background: #2563eb; color: white; transform: translateY(-1px); }
        .btn-outline-secondary { border: 1.5px solid var(--tn-line); color: var(--tn-muted); }
        .btn-outline-secondary:hover { background: var(--tn-panel); }

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
        .form-control-lg { border-radius: 10px; padding: 0.75rem 1rem; }

        .alert { border-radius: 10px; border: none; }
        .alert-success { background: var(--tn-alert-bg); color: var(--tn-alert-color); }
        .alert-danger { background: var(--tn-alert-danger-bg); color: var(--tn-alert-danger-color); }

        .nav-link {
            font-weight: 500; color: var(--tn-muted); border-radius: 8px;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 4px; left: 50%;
            width: 0; height: 2px;
            background: var(--tn-blue);
            border-radius: 2px;
            transition: all 0.25s ease;
            transform: translateX(-50%);
            opacity: 0;
        }

        .nav-link:hover::after {
            width: calc(100% - 2rem);
            opacity: 1;
        }

        .nav-link:hover { color: var(--tn-blue); background: rgba(37,99,235,0.06); }

        html.dark .nav-link:hover { color: var(--tn-blue); background: rgba(96,165,250,0.1); }

        footer { position: relative; z-index: 1; }

        .theme-toggle {
            display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 10px;
            border: 1.5px solid var(--tn-line);
            background: transparent; color: var(--tn-muted);
            font-size: 1.1rem; cursor: pointer;
            transition: all 0.2s ease;
            padding: 0; line-height: 1;
        }
        .theme-toggle:hover { background: var(--tn-panel); color: var(--tn-blue); }

        html.dark body::before { opacity: 0.7; }
        html.dark .badge-soft { background: var(--tn-panel); color: var(--tn-muted); border-color: var(--tn-line); }
        html.dark .lang-pill { border-color: var(--tn-line); color: var(--tn-muted); }
        html.dark .lang-pill:hover { border-color: var(--tn-blue); color: var(--tn-blue); background: rgba(96,165,250,0.1); }
        html.dark .lang-pill.active-lang { border-color: var(--tn-blue); color: var(--tn-blue); background: rgba(96,165,250,0.08); }

        /* ── Scroll-reveal animations ──────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .reveal-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .reveal-scale.visible {
            opacity: 1;
            transform: scale(1);
        }

        .reveal-stagger > * {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .reveal-stagger.visible > *:nth-child(1) { transition-delay: 0.05s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(2) { transition-delay: 0.12s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(3) { transition-delay: 0.19s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(4) { transition-delay: 0.26s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(5) { transition-delay: 0.33s; opacity: 1; transform: translateY(0); }
        .reveal-stagger.visible > *:nth-child(6) { transition-delay: 0.40s; opacity: 1; transform: translateY(0); }

        /* ── Floating hero elements ────────────────────────────── */
        .hero-floater {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0.15;
        }

        .hero-floater-1 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.3), transparent);
            top: -60px; right: 5%;
            animation: floatA 12s ease-in-out infinite;
        }

        .hero-floater-2 {
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(15, 118, 110, 0.25), transparent);
            bottom: 10%; left: 60%;
            animation: floatB 10s ease-in-out infinite;
        }

        .hero-floater-3 {
            width: 150px; height: 150px;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.2), transparent);
            top: 30%; left: 70%;
            animation: floatC 14s ease-in-out infinite;
        }

        @keyframes floatA {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(30px, -40px) scale(1.05); }
            66%      { transform: translate(-20px, 20px) scale(0.95); }
        }

        @keyframes floatB {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%      { transform: translate(-40px, -30px) scale(1.08); }
        }

        @keyframes floatC {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%      { transform: translate(20px, -50px) scale(1.1); }
        }

        /* ── Trip card enhancements ────────────────────────────── */
        .trip-card {
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .trip-card:hover {
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            transform: translateY(-6px);
        }

        .trip-card .card-glow {
            position: absolute;
            inset: 0;
            border-radius: 14px;
            opacity: 0;
            background: linear-gradient(135deg, rgba(37,99,235,0.04), rgba(15,118,110,0.04));
            transition: opacity 0.35s ease;
            pointer-events: none;
        }

        .trip-card:hover .card-glow {
            opacity: 1;
        }

        .trip-card .btn-primary {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .trip-card:hover .btn-primary {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }

        /* ── Trip card stagger entrance ───────────────────────── */
        .trip-card-wrapper {
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .trip-card-container .trip-card-wrapper {
            opacity: 0;
            transform: translateY(24px);
        }

        .trip-card-entered .trip-card-wrapper {
            opacity: 1;
            transform: translateY(0);
        }

        .trip-card-entered .trip-card-wrapper:nth-child(1) { transition-delay: 0.03s; }
        .trip-card-entered .trip-card-wrapper:nth-child(2) { transition-delay: 0.08s; }
        .trip-card-entered .trip-card-wrapper:nth-child(3) { transition-delay: 0.13s; }
        .trip-card-entered .trip-card-wrapper:nth-child(4) { transition-delay: 0.18s; }
        .trip-card-entered .trip-card-wrapper:nth-child(5) { transition-delay: 0.23s; }
        .trip-card-entered .trip-card-wrapper:nth-child(6) { transition-delay: 0.28s; }

        @media (prefers-reduced-motion: reduce) {
            .trip-card-wrapper { opacity: 1 !important; transform: none !important; }
            footer { opacity: 1 !important; transform: none !important; }
            .app-container { animation: none !important; }
        }

        /* ── Hero animated gradient border on search panel ─────── */
        .search-panel {
            position: relative;
        }

        .search-panel::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 16px;
            background: linear-gradient(135deg, #2563eb, #0f766e, #2563eb, #0f766e);
            background-size: 300% 300%;
            z-index: -1;
            animation: borderGlow 4s ease-in-out infinite;
            opacity: 0.4;
        }

        @keyframes borderGlow {
            0%, 100% { background-position: 0% 50%; }
            50%      { background-position: 100% 50%; }
        }

        /* ── Footer fade-in on scroll ─────────────────────────── */
        footer {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        footer.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── Portal cards (login selection) ────────────────────── */
        .portal-card {
            display: block;
            text-decoration: none;
            background: var(--tn-panel);
            border: 2px solid var(--tn-line);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .portal-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .portal-card-customer::before {
            background: radial-gradient(ellipse at 50% 0%, rgba(37, 99, 235, 0.06), transparent 70%);
        }

        .portal-card-admin::before {
            background: radial-gradient(ellipse at 50% 0%, rgba(15, 118, 110, 0.06), transparent 70%);
        }

        .portal-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 80px rgba(0,0,0,0.1);
        }

        .portal-card:hover::before {
            opacity: 1;
        }

        .portal-card-customer:hover {
            border-color: #2563eb;
            box-shadow: 0 24px 80px rgba(37, 99, 235, 0.15);
        }

        .portal-card-admin:hover {
            border-color: #0f766e;
            box-shadow: 0 24px 80px rgba(15, 118, 110, 0.15);
        }

        .portal-card:active {
            transform: translateY(-3px) scale(0.99);
        }

        .portal-icon {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 2rem;
            color: white;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }

        .portal-card:hover .portal-icon {
            transform: scale(1.06) rotate(-2deg);
        }

        .portal-icon-customer {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.25);
        }

        .portal-icon-admin {
            background: linear-gradient(135deg, #0f766e, #0d5e57);
            box-shadow: 0 8px 24px rgba(15, 118, 110, 0.25);
        }

        .portal-card:hover .portal-icon-customer {
            box-shadow: 0 12px 36px rgba(37, 99, 235, 0.35);
        }

        .portal-card:hover .portal-icon-admin {
            box-shadow: 0 12px 36px rgba(15, 118, 110, 0.35);
        }

        .portal-btn-admin {
            background: linear-gradient(135deg, #0f766e, #0d5e57);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.3rem;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .portal-btn-admin:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(15, 118, 110, 0.4);
            color: white;
        }

        .portal-btn-admin:active {
            transform: translateY(-1px) scale(0.98);
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.3rem 0.9rem;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: white;
        }

        .portal-badge-admin {
            background: linear-gradient(135deg, #0f766e, #0d5e57);
        }

        .portal-badge-customer {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        /* ── Auth page (portal selection & login form) ─────────── */
        .auth-section {
            min-height: calc(100vh - 160px);
            display: flex;
            align-items: center;
            padding: 4rem 0;
        }

        .auth-brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--tn-gradient);
            color: white;
            font-size: 1.4rem;
            box-shadow: 0 6px 16px rgba(15, 118, 110, 0.25);
            animation: brandPulse 3s ease-in-out infinite;
        }

        .auth-brand-icon-sm {
            width: 44px;
            height: 44px;
            font-size: 1.15rem;
        }

        .auth-heading {
            font-size: clamp(1.5rem, 3vw, 1.8rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.15;
            color: var(--tn-ink);
        }

        .auth-subtext {
            color: var(--tn-muted);
            font-size: 0.95rem;
            max-width: 360px;
            margin: 0 auto;
        }

        .auth-panel {
            background: var(--tn-panel);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--tn-line);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .auth-panel-narrow {
            max-width: 440px;
        }

        .auth-panel-wide {
            max-width: 500px;
        }

        html.dark .auth-panel {
            box-shadow: 0 8px 32px rgba(0,0,0,0.35);
        }

        .auth-panel:hover {
            box-shadow: 0 12px 48px rgba(0,0,0,0.12);
        }

        html.dark .auth-panel:hover {
            box-shadow: 0 12px 48px rgba(0,0,0,0.4);
        }

        /* ── Auth Links ─────────────────────────────────────────── */
        .auth-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            color: var(--tn-blue);
            transition: color 0.2s ease;
        }

        .auth-link::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0;
            height: 1.5px;
            background: currentColor;
            border-radius: 2px;
            transition: width 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .auth-link:hover {
            color: #1d4ed8;
        }

        html.dark .auth-link:hover {
            color: #93c5fd;
        }

        .auth-link:hover::after {
            width: 100%;
        }

        /* Secondary / muted link */
        .auth-link-muted {
            color: var(--tn-muted);
            font-weight: 500;
        }

        .auth-link-muted::after {
            height: 1px;
            background: currentColor;
        }

        .auth-link-muted:hover {
            color: var(--tn-ink);
        }

        /* Portal switch link (go back) */
        .auth-link-switch {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 1rem;
            border: 1.5px solid var(--tn-line);
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--tn-muted);
            transition: all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .auth-link-switch:hover {
            color: var(--tn-blue);
            border-color: var(--tn-blue);
            background: rgba(37, 99, 235, 0.04);
            transform: translateY(-1px);
        }

        .auth-link-switch:active {
            transform: translateY(0) scale(0.97);
        }

        .auth-link-switch i {
            font-size: 0.75rem;
            transition: transform 0.25s ease;
        }

        .auth-link-switch:hover i {
            transform: translateX(-3px);
        }

        /* Home back link (pill) */
        .auth-link-home {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--tn-muted);
            padding: 0.4rem 1.1rem;
            border-radius: 100px;
            border: 1.5px solid var(--tn-line);
            transition: all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .auth-link-home:hover {
            color: var(--tn-ink);
            border-color: var(--tn-ink);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .auth-link-home i {
            transition: transform 0.25s ease;
            font-size: 0.8rem;
        }

        .auth-link-home:hover i {
            transform: translateX(-3px);
        }

        /* Register / Create account link at bottom of form */
        .auth-link-register {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-weight: 700;
            font-size: 0.88rem;
            text-decoration: none;
            color: var(--tn-blue);
            padding: 0.25rem 0;
            position: relative;
            transition: color 0.2s ease;
        }

        .auth-link-register::after {
            content: '';
            position: absolute;
            bottom: 1px;
            left: 0;
            width: 100%;
            height: 1.5px;
            background: currentColor;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border-radius: 2px;
        }

        .auth-link-register:hover {
            color: #1d4ed8;
        }

        html.dark .auth-link-register:hover {
            color: #93c5fd;
        }

        .auth-link-register:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* ── Portal selection heading ────────────────────────── */
        .portal-select-heading {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.08;
            color: var(--tn-ink);
        }

        .portal-select-sub {
            font-size: clamp(1rem, 1.5vw, 1.15rem);
            color: var(--tn-muted);
        }

        /* ── Register link inside portal card ──────────────────── */
        .portal-card-row {
            max-width: 720px;
            margin: 0 auto;
        }

        .portal-card-title {
            color: var(--tn-ink);
        }

        .portal-card-desc {
            font-size: 0.95rem;
        }

        .portal-card-link {
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--tn-blue);
            text-decoration: none;
            position: relative;
            transition: color 0.2s ease;
        }

        .portal-card-link::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0;
            height: 1.5px;
            background: currentColor;
            border-radius: 2px;
            transition: width 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .portal-card-link:hover {
            color: var(--tn-blue);
        }

        html.dark .portal-card-link:hover {
            color: #93c5fd;
        }

        .portal-card-link:hover::after {
            width: 100%;
        }

        /* ── Forgot password link ──────────────────────────────── */
        .auth-link-forgot {
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--tn-muted);
            position: relative;
            transition: color 0.2s ease;
        }

        .auth-link-forgot::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--tn-blue);
            border-radius: 2px;
            transition: width 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .auth-link-forgot:hover {
            color: var(--tn-blue);
        }

        .auth-link-forgot:hover::after {
            width: 100%;
        }

        /* ── Login form enhancements ────────────────────────────── */
        .auth-form .form-control {
            border-radius: 10px;
            border: 1.5px solid var(--tn-line);
            padding: 0.65rem 0.9rem;
            font-size: 0.9rem;
            background: var(--tn-input-bg);
            color: var(--tn-ink);
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .auth-form .form-control:focus {
            border-color: var(--tn-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .auth-form .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--tn-ink);
            margin-bottom: 0.4rem;
        }

        .auth-form .form-check-input:checked {
            background-color: var(--tn-blue);
            border-color: var(--tn-blue);
        }

        .auth-form .form-check-label {
            font-size: 0.85rem;
            color: var(--tn-muted);
        }

        .auth-form .btn-login {
            border-radius: 10px;
            padding: 0.7rem 1.1rem;
            font-weight: 700;
            font-size: 0.95rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }

        .auth-form .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.15);
            transform: translateX(-100%) skewX(-15deg);
            transition: transform 0.5s ease;
            pointer-events: none;
        }

        .auth-form .btn-login:hover::after {
            transform: translateX(100%) skewX(-15deg);
        }

        .auth-form .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
        }

        .auth-form .btn-login:active {
            transform: translateY(0) scale(0.98);
        }

        /* ── Portal form specific ────────────────────────────── */
        .auth-form-admin .btn-login {
            background: linear-gradient(135deg, #0f766e, #0d5e57);
            box-shadow: 0 4px 14px rgba(15, 118, 110, 0.25);
        }

        .auth-form-admin .btn-login:hover {
            box-shadow: 0 8px 24px rgba(15, 118, 110, 0.35);
        }

        /* ── Auth divider ───────────────────────────────────────── */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--tn-muted);
            font-size: 0.78rem;
            font-weight: 500;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--tn-line);
        }

        @media (max-width: 767.98px) {
            .hero { min-height: 500px; }
            .lang-pills { margin-top: 0.5rem; }
            .auth-section { padding: 2.5rem 0; }
            .portal-card { padding: 2rem 1.5rem; }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="bg-slideshow" id="bgSlideshow">
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1920&q=80')"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=1920&q=80')"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1464037866556-6812c9d1c72e?w=1920&q=80')"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=1920&q=80')"></div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=1920&q=80')"></div>
</div>
<nav class="navbar navbar-expand-lg site-nav sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('home') }}">
            <span class="brand-mark"><i class="bi bi-bus-front"></i></span>
            <span>Transit Nexus</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNav">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <a class="nav-link px-3" href="{{ route('home') }}#trips">{{ __('messages.trips') }}</a>

                @auth
                    <a class="nav-link px-3" href="{{ route('account') }}">{{ __('messages.my_tickets') }}</a>
                    @if (auth()->user()->role === 'admin')
                        <a class="nav-link px-3" href="{{ route('dashboard') }}">{{ __('messages.admin') }}</a>
                    @endif
                    <form method="post" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm btn-icon">
                            <i class="bi bi-box-arrow-right"></i><span>{{ __('messages.logout') }}</span>
                        </button>
                    </form>
                @else
                    <a class="nav-link px-3" href="{{ route('login') }}">{{ __('messages.login') }}</a>
                    <a class="btn btn-primary btn-sm btn-icon" href="{{ route('register') }}">
                        <i class="bi bi-person-plus"></i><span>{{ __('messages.register') }}</span>
                    </a>
                @endauth

                <button class="theme-toggle" id="themeToggle" title="Toggle theme">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>
                {{-- Language switcher --}}
                <div class="nav-divider d-none d-lg-block"></div>
                <div class="lang-pills">
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="lang-pill {{ app()->getLocale() === 'en' ? 'active-lang' : '' }}"
                       title="English">
                        🇬🇧 EN
                    </a>
                    <a href="{{ route('lang.switch', 'rw') }}"
                       class="lang-pill {{ app()->getLocale() === 'rw' ? 'active-lang' : '' }}"
                       title="Kinyarwanda">
                        🇷🇼 RW
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

@if (session('success') || session('error') || $errors->any())
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>{{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif

<div class="app-container">
    @yield('content')
</div>

{{-- Idle Session Timeout Modal (authenticated users only) --}}
@auth
<div class="modal fade" id="idleTimeoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="idleTimeoutLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-body text-center p-4">
                <div class="mb-3" style="font-size: 3rem; line-height: 1;">
                    <span class="d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309;">
                        <i class="bi bi-clock-history"></i>
                    </span>
                </div>
                <h5 class="fw-bold mb-2" id="idleTimeoutLabel" style="letter-spacing: -0.02em;">{{ __('messages.idle_title') }}</h5>
                <p class="text-secondary mb-3" style="font-size: 0.9rem;">{{ __('messages.idle_message') }}</p>
                <div id="idleCountdown" class="mb-3" style="font-size: 1.75rem; font-weight: 800; color: #dc2626; letter-spacing: -0.02em; font-variant-numeric: tabular-nums;"></div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-icon" id="idleLogoutBtn">
                        <i class="bi bi-box-arrow-right"></i><span>{{ __('messages.idle_logout') }}</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-icon" id="idleStayBtn">
                        <i class="bi bi-person-check"></i><span>{{ __('messages.idle_stay') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endauth

<footer class="py-4" style="background: var(--tn-footer-bg); backdrop-filter: blur(12px); border-top: 1px solid var(--tn-line);">
    <div class="container d-flex flex-wrap justify-content-between gap-2 small text-secondary">
        <span class="fw-semibold" style="color: var(--tn-ink);">Transit Nexus</span>
        <span>{{ __('messages.footer_credit') }}</span>
    </div>
</footer>

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

/* ── Background image slideshow ─────────────────────────────── */
(function() {
    const slides = document.querySelectorAll('#bgSlideshow .slide');
    if (!slides.length) return;
    let current = 0;
    slides[0].classList.add('active');

    setInterval(function() {
        slides[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
    }, 6000);
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
    }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });

    document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-stagger').forEach(function(el) {
        observer.observe(el);
    });
})();

/* ── Navbar shrink on scroll ────────────────────────────────── */
(function() {
    const nav = document.querySelector('.site-nav');
    if (!nav) return;

    function handleScroll() {
        if (window.scrollY > 60) {
            nav.style.paddingTop = '0.25rem';
            nav.style.paddingBottom = '0.25rem';
            nav.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
        } else {
            nav.style.paddingTop = '';
            nav.style.paddingBottom = '';
            nav.style.boxShadow = '';
        }
    }

    window.addEventListener('scroll', handleScroll, { passive: true });
})();

/* ── Trip card stagger entrance ─────────────────────────────── */
(function() {
    const container = document.querySelector('.trip-card-container');
    if (!container) return;
    const cardObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('trip-card-entered');
                cardObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    cardObserver.observe(container);
})();

/* ── Footer fade-in ─────────────────────────────────────────── */
(function() {
    const footer = document.querySelector('footer');
    if (!footer) return;
    const footerObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                footerObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.05 });
    footerObserver.observe(footer);
})();

/* ── Idle Session Timeout (authenticated users only) ──────── */
@auth
(function() {
    var modalEl = document.getElementById('idleTimeoutModal');
    if (!modalEl) return;

    const IDLE_TIMEOUT_MS = 50000;
    const GRACE_PERIOD_MS = 15000;
    const CSRF_TOKEN = '{{ csrf_token() }}';

    var idleTimer = null;
    var graceInterval = null;
    var graceTimeout = null;
    var isWarningShown = false;

    var modal = new bootstrap.Modal(modalEl);
    var countdownEl = document.getElementById('idleCountdown');

    function resetIdleTimer() {
        if (isWarningShown) return;
        clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarning, IDLE_TIMEOUT_MS);
    }

    function showWarning() {
        // Don't interrupt if another modal is already open
        if (document.querySelectorAll('.modal.show').length > 0) {
            resetIdleTimer();
            return;
        }

        isWarningShown = true;
        var remaining = Math.ceil(GRACE_PERIOD_MS / 1000);
        countdownEl.textContent = '{{ __('messages.idle_countdown') }}' + ' ' + remaining + 's';
        modal.show();

        graceInterval = setInterval(function() {
            remaining--;
            countdownEl.textContent = '{{ __('messages.idle_countdown') }}' + ' ' + remaining + 's';
            if (remaining <= 0) {
                clearInterval(graceInterval);
                performLogout();
            }
        }, 1000);

        graceTimeout = setTimeout(function() {
            performLogout();
        }, GRACE_PERIOD_MS);
    }

    function stayActive() {
        clearInterval(graceInterval);
        clearTimeout(graceTimeout);
        isWarningShown = false;
        modal.hide();

        fetch('{{ route('session.ping') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            }
        }).catch(function() {});

        resetIdleTimer();
    }

    function performLogout() {
        clearInterval(graceInterval);
        clearTimeout(graceTimeout);
        modal.hide();

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('logout') }}';
        form.style.display = 'none';

        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = CSRF_TOKEN;
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }

    document.getElementById('idleStayBtn').addEventListener('click', stayActive);
    document.getElementById('idleLogoutBtn').addEventListener('click', performLogout);

    var activityEvents = ['mousedown', 'mousemove', 'keydown', 'scroll', 'touchstart', 'click', 'wheel'];
    for (var i = 0; i < activityEvents.length; i++) {
        document.addEventListener(activityEvents[i], resetIdleTimer, { passive: true });
    }

    resetIdleTimer();
})();
@endauth
</script>
</body>
</html>
