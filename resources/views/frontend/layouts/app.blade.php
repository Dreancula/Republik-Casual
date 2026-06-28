@php
use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Republik Casual')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        /* ==========================================================================
           1. RESET & DARK VARIABLES (SYSTEM REVISED)
           ========================================================================= */
        :root {
            --rc-bg-dark: #0a0a0a;
            --rc-card-dark: rgba(18, 18, 18, 0.5); /* Lebih transparan agar video tembus */
            --rc-card-border: rgba(255, 255, 255, 0.08);
            --rc-accent: #EAE6DF;
            --rc-accent-rgb: 234, 230, 223;
            --rc-text-primary: #ffffff;
            --rc-text-secondary: #999999;
            --transition-smooth: cubic-bezier(0.25, 0.8, 0.25, 1);
            
            /* Custom utility internal tokens */
            --radius-lg: 24px;
            --radius-md: 16px;
            --shadow-luxury: 0 30px 70px rgba(0, 0, 0, 0.6);
        }

        /* ==========================================================================
           2. BASE RESETS & AMBIENT GLOW
           ========================================================================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--rc-text-primary);
            background-color: var(--rc-bg-dark);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Ambient Dynamic Orbs using new RGB token */
        body::before, body::after {
            content: '';
            position: fixed;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            z-index: -1;
            pointer-events: none;
        }

        body::before {
            top: -300px;
            left: -300px;
            background: radial-gradient(circle, rgba(var(--rc-accent-rgb), 0.06) 0%, transparent 70%);
            filter: blur(120px);
        }

        body::after {
            bottom: -300px;
            right: -300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.02) 0%, transparent 70%);
            filter: blur(120px);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        /* ==========================================================================
           3. STICKY GLASSMORPHIC NAVIGATION
           ========================================================================= */
        .navbar-wrapper {
            position: sticky;
            top: 20px;
            z-index: 1000;
            padding: 0 24px;
            width: 100%;
        }

        .navbar {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 28px;
            background: rgba(15, 15, 15, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--rc-card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-luxury);
            transition: all 0.4s var(--transition-smooth);
        }

        /* Brand Identity */
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            transition: transform 0.3s var(--transition-smooth);
        }

        .brand:hover {
            transform: scale(1.02);
        }

        .brand img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .brand span {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 1.5px;
            background: linear-gradient(135deg, #ffffff 0%, var(--rc-text-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Centered Menu Navigation */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 36px;
        }

        .nav-links li a {
            color: var(--rc-text-secondary);
            font-size: 14px;
            font-weight: 600;
            position: relative;
            padding: 8px 0;
            transition: color 0.3s var(--transition-smooth);
        }

        .nav-links li a::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            width: 0;
            height: 2px;
            background: var(--rc-accent);
            transition: all 0.3s var(--transition-smooth);
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .nav-links li:hover a,
        .nav-links li.active a {
            color: var(--rc-text-primary);
        }

        .nav-links li:hover a::after,
        .nav-links li.active a::after {
            width: 100%;
        }

        /* Right Content Actions */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Action Buttons & Badges */
        .action-btn {
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--rc-card-border);
            border-radius: var(--radius-md);
            color: var(--rc-text-primary);
            position: relative;
            cursor: pointer;
            transition: all 0.3s var(--transition-smooth);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            border-color: rgba(var(--rc-accent-rgb), 0.4);
            background: rgba(var(--rc-accent-rgb), 0.04);
            color: var(--rc-accent);
        }

        .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            border-radius: 50%;
            background: var(--rc-accent);
            color: var(--rc-bg-dark);
            font-size: 10px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(var(--rc-accent-rgb), 0.3);
        }

        /* Profile Dropdown Architecture */
        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            width: auto;
            padding: 0 16px;
            gap: 10px;
        }

        .navbar-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .profile-name {
            font-size: 13px;
            font-weight: 700;
            max-width: 90px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dropdown-menu {
            position: absolute;
            top: 56px;
            right: 0;
            min-width: 220px;
            background: #0f0f0f;
            border: 1px solid var(--rc-card-border);
            border-radius: var(--radius-md);
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(12px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: var(--shadow-luxury);
            z-index: 1000;
        }

        .profile-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .dropdown-menu a,
        .dropdown-menu button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: none;
            border: none;
            color: var(--rc-text-secondary);
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-align: left;
            transition: all 0.2s ease;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background: rgba(var(--rc-accent-rgb), 0.06);
            color: var(--rc-accent);
            padding-left: 22px;
        }

        /* Call To Action Login */
        .login-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            height: 46px;
            padding: 0 20px;
            background: var(--rc-accent);
            color: var(--rc-bg-dark);
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.5px;
            transition: all 0.3s var(--transition-smooth);
            box-shadow: 0 4px 15px rgba(var(--rc-accent-rgb), 0.15);
        }

        .login-btn:hover {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
        }

        /* ==========================================================================
           4. MAIN CONTENT AREA & CONTAINER CARDS
           ========================================================================= */
        .main-container {
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }

        .glass-card {
            background: var(--rc-card-dark);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--rc-card-border);
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        /* ==========================================================================
           5. MINIMALIST LUXURY FOOTER
           ========================================================================= */
        .footer {
            border-top: 1px solid var(--rc-card-border);
            padding: 30px 24px;
            background: rgba(8, 8, 8, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #52525b;
            font-size: 13px;
        }

        .footer-brand {
            font-weight: 700;
            color: var(--rc-text-secondary);
            letter-spacing: 0.5px;
        }

        /* ==========================================================================
           6. RESPONSIVE DESIGN INTEGRITY
           ========================================================================= */
        .mobile-toggle {
            display: none;
            color: var(--rc-text-primary);
            font-size: 22px;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .nav-links { gap: 24px; }
        }

        @media (max-width: 768px) {
            .navbar { padding: 12px 20px; }
            .brand span, .nav-links { display: none; }
            .mobile-toggle { display: block; }
            .main-container { padding-top: 24px; }
            .footer-content { flex-direction: column; gap: 12px; text-align: center; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- NAVBAR COMPONENT -->
    <div class="navbar-wrapper">
        <nav class="navbar">
            
            <a href="{{ route('beranda') }}" class="brand">
                <img src="{{ asset('image/icon_rc.png') }}" alt="Republik Casual">
                <span>REPUBLIK CASUAL</span>
            </a>

            <ul class="nav-links">
                <li class="{{ request()->routeIs('beranda') ? 'active' : '' }}">
                    <a href="{{ route('beranda') }}">Beranda</a>
                </li>
                <li class="{{ request()->routeIs('products*') ? 'active' : '' }}">
                    <a href="{{ route('products') }}">Katalog</a>
                </li>
                <li class="{{ request()->routeIs('pesanan.saya') ? 'active' : '' }}">
                    <a href="{{ route('pesanan.saya') }}">Pesanan Saya</a>
                </li>
                <li class="{{ request()->routeIs('komplain.saya') ? 'active' : '' }}">
                    <a href="{{ route('komplain.saya') }}">Komplain Saya</a>
                </li>
                <li class="{{ request()->routeIs('ai.index') ? 'active' : '' }}">
                    <a href="{{ route('ai.index') }}">AI Stylist</a>
                </li>
            </ul>

            <div class="nav-right">
                <div class="nav-actions">
                    
                    <!-- Shopping Cart Button -->
                    <a href="{{ route('keranjang.index') }}" class="action-btn">
                        <i class="fa-solid fa-bag-shopping"></i>
                        @if(session('cart_count', 0) > 0)
                            <span class="badge">{{ session('cart_count', 0) }}</span>
                        @endif
                    </a>

                    @auth
                        <!-- Authenticated User Dropdown -->
                        <div class="profile-dropdown">
                            <button class="action-btn profile-btn" type="button">
                                <img src="{{ auth()->user()->avatar }}" class="navbar-avatar" alt="Avatar">
                                <span class="profile-name">{{ Str::limit(auth()->user()->nama, 12) }}</span>
                                <i class="fa-solid fa-chevron-down" style="font-size: 10px; margin-left: 2px;"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a href="{{ route('profil.index') }}">
                                    <i class="fa-regular fa-user"></i> Akun Saya
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit">
                                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest Login CTA Button -->
                        <a href="{{ route('google.redirect') }}" class="login-btn">
                            <i class="fa-brands fa-google"></i> Login
                        </a>
                    @endauth

                    <!-- Mobile Navigation Menu Toggle Trigger -->
                    <div class="mobile-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </div>

                </div>
            </div>

        </nav>
    </div>

    <!-- MAIN APP YIELD INJECTION CONTENT -->
    <main class="main-container">
        @yield('content')
    </main>

    <!-- FOOTER COMPONENT -->
   <!-- FOOTER COMPONENT WITH PREMIUM FLUID GLASSMORPHISM -->
    <footer class="footer" style="background: rgba(10, 10, 10, 0.4); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px); border-top: 1px solid rgba(255, 255, 255, 0.05); padding: 60px 24px 30px;">
        <div style="max-width: 1400px; margin: 0 auto;">
            
            <!-- Grid Layout Footer Info -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px; margin-bottom: 40px;">
                
                <!-- Kolom 1: Brand Info -->
                <div style="display: flex; flex-column: column; gap: 16px;">
                    <div class="footer-brand" style="font-size: 1.1rem; font-weight: 800; color: var(--rc-text-primary); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                        REPUBLIK CASUAL
                    </div>
                    <p style="color: var(--rc-text-secondary); font-size: 13px; line-height: 1.6; max-width: 320px;">
                        Crafted for high-end streetwear comfort and intelligence. Elevating your daily casual wear with ultimate premium aesthetics.
                    </p>
                </div>

                <!-- Kolom 2: Lokasi Offline Store -->
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--rc-accent); letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 16px;">
                        <i class="fa-solid fa-location-dot" style="margin-right: 8px;"></i> Lokasi Kami
                    </div>
                    <p style="color: var(--rc-text-secondary); font-size: 13px; line-height: 1.6; max-width: 300px;">
                        Jl. Margonda No.8, Pondok Cina, Kecamatan Beji, Kota Depok, Jawa Barat 16424
                    </p>
                </div>

                <!-- Kolom 3: Social & Hubungi Kami -->
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--rc-accent); letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 16px;">
                        Connect With Us
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <!-- WhatsApp Link -->
                        <a href="https://wa.me/6285694520082" target="_blank" class="action-btn" style="width: 42px; height: 42px; border-radius: 12px; font-size: 16px;" title="WhatsApp Admin">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <!-- Instagram Link -->
                        <a href="https://instagram.com/dreancula" target="_blank" class="action-btn" style="width: 42px; height: 42px; border-radius: 12px; font-size: 16px;" title="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Garis Batas Bawah Sekunder -->
            <div style="width: 100%; height: 1px; background: rgba(255, 255, 255, 0.04); margin-bottom: 24px;"></div>

            <!-- Baris Hak Cipta -->
            <div class="footer-content" style="color: #52525b; font-size: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>&copy; {{ date('Y') }} REPUBLIK CASUAL. All Rights Reserved.</div>
                <div style="letter-spacing: 0.3px;">Designed with premium luxury standard.</div>
            </div>

        </div>
    </footer>

    @stack('scripts')

    @include('frontend.partials.chatbot')

</body>
</html>