<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Republik Casual — Core Backend</title>

    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('image/icon_rc.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32:wght@400;500;600;700&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        /* ===== THEME VARIABLES ===== */
        :root, [data-theme="dark"] {
            --rc-bg: #0c0c0e;
            --rc-surface: rgba(255,255,255,0.02);
            --rc-glass-bg: rgba(22,22,28,0.55);
            --rc-glass-border: rgba(255,255,255,0.05);
            --rc-glass-hover: rgba(255,255,255,0.04);
            --rc-glass-hover-heavy: rgba(255,255,255,0.07);
            --rc-text: #ffffff;
            --rc-text-muted: #8e8e93;
            --rc-terracotta: #C85A32;
            --rc-terracotta-soft: rgba(200,90,50,0.10);
            --rc-terracotta-glow: rgba(200,90,50,0.15);
            --rc-accent: #4A8BD9;
            --rc-accent-soft: rgba(74,139,217,0.08);
            --rc-success: #30D158;
            --rc-success-soft: rgba(48,209,88,0.08);
            --rc-warning: #FFD60A;
            --rc-warning-soft: rgba(255,214,10,0.08);
            --rc-danger: #FF453A;
            --rc-danger-soft: rgba(255,69,58,0.07);
            --rc-shadow: rgba(0,0,0,0.55);
            --rc-glow: rgba(200,90,50,0.045);
            --sidebar-width: 280px;
            --glass-blur: 32px;
        }



        /* ===== BASE ===== */
        * { box-sizing: border-box; }

        body {
            background: var(--rc-bg);
            color: var(--rc-text);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
            transition: background 0.4s ease, color 0.4s ease;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        *:focus-visible {
            outline: 2px solid var(--rc-terracotta);
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(128,128,128,0.2); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(128,128,128,0.35); }

        /* ===== AMBIENT FLUID GLOW ===== */
        .rc-dashboard-glow {
            position: fixed;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle at 30% 20%, var(--rc-glow) 0%, transparent 65%);
            top: -250px;
            right: -200px;
            z-index: 0;
            pointer-events: none;
            transition: background 0.8s ease;
        }

        .rc-dashboard-glow::after {
            content: '';
            position: absolute;
            bottom: -350px;
            left: -450px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--rc-glow) 0%, transparent 65%);
            pointer-events: none;
        }

        @media (prefers-reduced-motion: no-preference) {
            .rc-dashboard-glow {
                animation: rcGlowDrift 25s ease-in-out infinite;
            }
            @keyframes rcGlowDrift {
                0%, 100% { transform: translate(0, 0); }
                25% { transform: translate(40px, -30px); }
                50% { transform: translate(-30px, 20px); }
                75% { transform: translate(20px, -10px); }
            }
        }

        /* ===== SIDEBAR ===== */
        .rc-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: var(--sidebar-width);
            background: var(--rc-glass-bg);
            backdrop-filter: blur(var(--glass-blur));
            -webkit-backdrop-filter: blur(var(--glass-blur));
            border-right: 1px solid var(--rc-glass-border);
            padding: 1.5rem 1rem;
            z-index: 100;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: background 0.4s ease, border-color 0.4s ease;
        }

        .rc-sidebar::-webkit-scrollbar { width: 3px; }

        .rc-brand-wrapper {
            padding: 0 0.5rem 1.25rem 0.5rem;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid var(--rc-glass-border);
            transition: border-color 0.4s ease;
        }

        .rc-brand-wrapper a {
            gap: 0.85rem !important;
        }

        .rc-brand-wrapper img {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            object-fit: contain;
        }

        .rc-brand-wrapper .brand-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--rc-text);
            transition: color 0.4s ease;
        }

        .rc-brand-wrapper .brand-sub {
            font-size: 0.66rem;
            color: var(--rc-text-muted);
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: color 0.4s ease;
        }

        .rc-nav-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--rc-text-muted);
            margin: 1rem 0 0.3rem 0.85rem;
            font-weight: 600;
            opacity: 0.6;
            transition: color 0.4s ease;
        }

        .rc-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: var(--rc-text-muted);
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 0.15rem;
        }

        .rc-nav-link:hover {
            background: var(--rc-glass-hover);
            color: var(--rc-text);
        }

        .rc-nav-link.active {
            background: var(--rc-terracotta-soft);
            color: var(--rc-text);
            font-weight: 600;
        }

        .rc-nav-link.active svg {
            color: var(--rc-terracotta);
        }

        /* ===== MAIN PANEL ===== */
        .rc-main-panel {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        .rc-topnav {
            height: 72px;
            background: var(--rc-surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--rc-glass-border);
            padding: 0 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.4s ease, border-color 0.4s ease;
        }

        .rc-content-body {
            padding: 2.5rem;
            flex: 1;
        }

        /* ===== GLASS CARD (Apple-style) ===== */
        .rc-glass-card {
            background: var(--rc-glass-bg);
            border: 1px solid var(--rc-glass-border);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow:
                0 1px 2px rgba(0,0,0,0.03),
                0 4px 12px rgba(0,0,0,0.04),
                0 12px 36px var(--rc-shadow);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            position: relative;
            transition: background 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease, transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .rc-glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 1px;
            right: 1px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08) 30%, rgba(255,255,255,0.08) 70%, transparent);
            border-radius: 20px 20px 0 0;
            pointer-events: none;
        }



        .rc-glass-card:hover {
            box-shadow:
                0 1px 3px rgba(0,0,0,0.04),
                0 6px 16px rgba(0,0,0,0.05),
                0 16px 48px var(--rc-shadow);
        }

        @media (prefers-reduced-motion: reduce) {
            .rc-glass-card { transition: none; }
            .rc-glass-card:hover { transform: none; }
            .rc-dashboard-glow { animation: none; }
        }

        /* ===== SIDEBAR NAV SCROLL ===== */
        .rc-sidebar nav {
            overflow-y: auto;
            flex: 1;
            padding-right: 0.25rem;
        }

        /* ===== BADGE ===== */
        .rc-badge-role {
            padding: 0.3rem 0.8rem;
            border-radius: 100px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .bg-manager {
            background: var(--rc-terracotta-soft);
            color: var(--rc-terracotta);
            border: 1px solid rgba(200,90,50,0.15);
        }
        .bg-admin {
            background: var(--rc-accent-soft);
            color: var(--rc-accent);
            border: 1px solid rgba(74,139,217,0.15);
        }

        /* ===== VIEW STORE BUTTON ===== */
        .rc-view-store-btn {
            background: var(--rc-surface);
            border: 1px solid var(--rc-glass-border);
            color: var(--rc-text-muted);
            border-radius: 8px;
            padding: 0.45rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .rc-view-store-btn:hover {
            background: var(--rc-glass-hover);
            border-color: var(--rc-terracotta);
            color: var(--rc-terracotta);
        }

        /* ===== LOGOUT ===== */
        .rc-logout-btn {
            background: transparent;
            border: 1px solid var(--rc-glass-border);
            color: var(--rc-danger);
            border-radius: 10px;
            padding: 0.55rem 0.85rem;
            font-size: 0.82rem;
            font-weight: 500;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: all 0.25s ease;
        }

        .rc-logout-btn:hover {
            background: var(--rc-danger-soft);
            border-color: var(--rc-danger);
        }

        /* ===== STAT CARD ===== */
        .rc-stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rc-stat-label {
            font-size: 0.72rem;
            color: var(--rc-text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: color 0.4s ease;
        }

        .rc-stat-value {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            line-height: 1.15;
            margin-bottom: 0.2rem;
            color: var(--rc-text);
            transition: color 0.4s ease;
        }

        .rc-stat-change {
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
        }

        /* ===== SECTION EYEBROW ===== */
        .rc-eyebrow {
            font-family: 'Poppins', sans-serif;
            font-size: 0.68rem;
            letter-spacing: 3px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--rc-terracotta);
            margin-bottom: 0.25rem;
            transition: color 0.4s ease;
        }

        .rc-section-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0;
            color: var(--rc-text);
            transition: color 0.4s ease;
        }

        .rc-section-desc {
            color: var(--rc-text-muted);
            font-size: 0.9rem;
            margin: 0.15rem 0 0 0;
            line-height: 1.4;
            transition: color 0.4s ease;
        }

        /* ===== CUSTOM TABLE ===== */
        .rc-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .rc-table thead th {
            border-bottom: 1px solid var(--rc-glass-border);
            font-size: 0.7rem;
            color: var(--rc-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
            padding: 0 0.5rem 0.75rem 0.5rem;
            white-space: nowrap;
            transition: color 0.4s ease, border-color 0.4s ease;
        }

        .rc-table tbody td {
            padding: 0.85rem 0.5rem;
            font-size: 0.88rem;
            border-bottom: 1px solid var(--rc-glass-border);
            vertical-align: middle;
            transition: border-color 0.4s ease;
        }

        .rc-table tbody tr:last-child td {
            border-bottom: none;
        }

        .rc-table tbody tr {
            transition: background 0.2s ease;
        }

        .rc-table tbody tr:hover td {
            background: var(--rc-glass-hover);
        }

        .rc-badge-status {
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }

        .rc-badge-status.paid {
            background: var(--rc-success-soft);
            color: var(--rc-success);
            border: 1px solid rgba(48,209,88,0.12);
        }

        .rc-badge-status.pending {
            background: var(--rc-warning-soft);
            color: var(--rc-warning);
            border: 1px solid rgba(255,214,10,0.12);
        }

        /* ===== ROLE BANNER ===== */
        .rc-role-banner {
            border-left: 4px solid var(--rc-terracotta);
            background: linear-gradient(90deg, var(--rc-terracotta-soft) 0%, transparent 100%);
            transition: background 0.4s ease, border-color 0.4s ease;
        }

        .rc-btn-primary {
            background: var(--rc-terracotta);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.82rem;
            padding: 0.55rem 1.1rem;
            color: #fff;
            transition: all 0.25s ease;
        }

        .rc-btn-primary:hover {
            background: #B04D2A;
            color: #fff;
            transform: translateY(-1px);
        }

        .rc-btn-outline {
            background: transparent;
            border: 1px solid var(--rc-glass-border);
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.82rem;
            padding: 0.55rem 1.1rem;
            color: var(--rc-text-muted);
            transition: all 0.25s ease;
        }

        .rc-btn-outline:hover {
            background: var(--rc-glass-hover);
            border-color: var(--rc-terracotta);
            color: var(--rc-terracotta);
        }

        /* ===== PERIOD PILL ===== */
        .rc-period-pill {
            background: var(--rc-glass-bg);
            border: 1px solid var(--rc-glass-border);
            border-radius: 100px;
            padding: 0.45rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--rc-text);
            transition: background 0.4s ease, border-color 0.4s ease, color 0.4s ease;
        }

        .rc-period-pill svg {
            color: var(--rc-terracotta);
        }

        /* ===== FORM INPUTS ===== */
        .rc-input,
        .rc-select {
            background: var(--rc-surface) !important;
            border: 1px solid var(--rc-glass-border) !important;
            color: var(--rc-text) !important;
            border-radius: 10px !important;
            padding: 0.65rem 1rem;
            font-size: 0.88rem;
            transition: all 0.25s ease, color 0.4s ease, border-color 0.4s ease;
        }
        .rc-input:focus,
        .rc-select:focus {
            border-color: var(--rc-terracotta) !important;
            box-shadow: 0 0 0 3px var(--rc-terracotta-soft) !important;
            outline: none;
        }
        .rc-input::placeholder { color: var(--rc-text-muted); }
        .rc-select option { background: #121214; color: #fff; }

        .rc-input-group-text {
            background: var(--rc-surface);
            border: 1px solid var(--rc-glass-border) !important;
            border-right: none !important;
            color: var(--rc-text-muted);
            border-radius: 10px 0 0 10px !important;
            padding: 0.65rem 0.85rem;
            transition: all 0.4s ease;
        }

        /* ===== ALERTS ===== */
        .rc-alert {
            border: none;
            border-radius: 12px;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
        }
        .rc-alert.success {
            background: var(--rc-success-soft);
            color: var(--rc-success);
        }
        .rc-alert.error {
            background: var(--rc-danger-soft);
            color: var(--rc-danger);
        }

        /* ===== MODAL STYLE PERBAIKAN Z-INDEX ===== */
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }

        .rc-modal-content {
            background: #121214 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 20px;
            box-shadow: 0 25px 60px var(--rc-shadow);
        }

        .rc-modal-content .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.5;
        }

        /* ===== FORM LABEL ===== */
        .rc-label {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--rc-text-muted);
            margin-bottom: 0.35rem;
            transition: color 0.4s ease;
        }

        /* ===== USER AVATAR ===== */
        .rc-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 100px;
            background: var(--rc-terracotta-soft);
            border: 1px solid rgba(200,90,50,0.15);
            color: var(--rc-terracotta);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.82rem;
            transition: background 0.4s ease, border-color 0.4s ease;
            flex-shrink: 0;
        }

        /* ===== STAGGERED ENTRY ===== */
        @media (prefers-reduced-motion: no-preference) {
            .rc-stagger > * {
                opacity: 0;
                animation: rcFadeUp 0.55s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .rc-stagger > *:nth-child(1) { animation-delay: 0s; }
            .rc-stagger > *:nth-child(2) { animation-delay: 0.06s; }
            .rc-stagger > *:nth-child(3) { animation-delay: 0.12s; }
            .rc-stagger > *:nth-child(4) { animation-delay: 0.18s; }

            @keyframes rcFadeUp {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0); }
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .rc-sidebar {
                width: 100%;
                position: relative;
                backdrop-filter: none;
                padding: 1rem;
                border-right: none;
                border-bottom: 1px solid var(--rc-glass-border);
            }
            .rc-main-panel { margin-left: 0; }
            .rc-topnav { padding: 0 1.25rem; height: 60px; }
            .rc-content-body { padding: 1.25rem; }
            .rc-glass-card { padding: 1.25rem; }
            .rc-glass-card::before { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="rc-dashboard-glow" aria-hidden="true"></div>

    {{-- SIDEBAR --}}
    <aside class="rc-sidebar">
        <div style="display: flex; flex-direction: column; height: 100%;">
            <div class="rc-brand-wrapper">
                <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('image/logo.png') }}" alt="Republik Casual">
                    <div style="line-height: 1.2;">
                        <div class="brand-name">Republik Casual</div>
                        <div class="brand-sub">Premium Fashion</div>
                    </div>
                </a>
            </div>

            <nav class="nav flex-column">
                <div class="rc-nav-label">Menu Utama</div>
                <a class="rc-nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                    Beranda
                </a>

                <div class="rc-nav-label">Data Master</div>
                <a class="rc-nav-link {{ Route::is('admin.user.*') ? 'active' : '' }}" href="{{ route('admin.user.index') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Kelola User
                </a>

                <a class="rc-nav-link {{ Route::is('admin.produk.*') ? 'active' : '' }}" href="{{ route('admin.produk.index') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    Data Produk
                </a>

                <a class="rc-nav-link {{ Route::is('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                    Kelola Kategori
                </a>

                <a class="rc-nav-link {{ Route::is('admin.brand.*') ? 'active' : '' }}" href="{{ route('admin.brand.index') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    Kelola Brand
                </a>

                @if(Auth::check() && Auth::user()->id_role == 2)
                <a class="rc-nav-link {{ Route::is('admin.pemasukan-barang.*') ? 'active' : '' }}" href="{{ route('admin.pemasukan-barang.index') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Pemasukan Barang
                </a>
                @endif

                <div class="rc-nav-label">Bisnis & Penjualan</div>
                <a class="rc-nav-link {{ Route::is('admin.pesanan.*') ? 'active' : '' }}" href="{{ route('admin.pesanan.index') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Pesanan
                </a>

                <a class="rc-nav-link {{ Route::is('admin.komplain.*') ? 'active' : '' }}" href="{{ route('admin.komplain.index') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Komplain
                </a>

                <a class="rc-nav-link {{ Route::is('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Laporan
                </a>
            </nav>
        </div>

        <form action="{{ route('admin.logout') }}" method="POST" style="margin-top: 0.75rem; flex-shrink: 0;">
            @csrf
            <button type="submit" class="rc-logout-btn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Keluar Sistem
            </button>
        </form>
    </aside>

    {{-- MAIN PANEL --}}
    <div class="rc-main-panel">
        <header class="rc-topnav">
            <div class="d-flex align-items-center gap-2">
                <span style="color: var(--rc-text-muted); font-size: 0.8rem; font-weight: 500;">Sesi Akses:</span>
                @if(Auth::check())
                    <span class="rc-badge-role {{ Auth::user()->id_role == 2 ? 'bg-manager' : 'bg-admin' }}">
                        {{ Auth::user()->id_role == 1 ? 'Admin' : (Auth::user()->id_role == 2 ? 'Manajer Toko' : 'Customer') }}
                    </span>
                @else
                    <span class="rc-badge-role bg-secondary text-white">Guest</span>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ url('/') }}" target="_blank" class="rc-view-store-btn d-none d-md-flex">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    Buka Beranda Utama
                </a>

                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-sm-block">
                        <div style="font-size: 0.85rem; font-weight: 600; color: var(--rc-text); line-height: 1.2;">
                            {{ Auth::check() ? Auth::user()->nama : 'Staff Republik' }}
                        </div>
                        <div style="font-size: 0.7rem; color: var(--rc-text-muted);">
                            {{ Auth::check() ? Auth::user()->email : 'staff@republikcasual.com' }}
                        </div>
                    </div>

                    <div class="rc-user-avatar">
                        @if(Auth::check())
                            {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
                        @else
                            RC
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <main class="rc-content-body">
            @yield('content_backend')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- TEMPAT PENAMPUNG MODAL UNTUK MENGHINDARI BUG LAYER BACKDROP --}}
    @stack('modals')

    @stack('scripts')

</body>
</html>