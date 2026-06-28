<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Republik Casual') &mdash; Premium Fashion</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Prata&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --rc-cream: #FDFBF7;
            --rc-cream-dark: #F8F6F0;
            --rc-espresso: #2B1E17;
            --rc-espresso-soft: #4A3728;
            --rc-terracotta: #D4A373;
            --rc-terracotta-light: #E8C9A0;
            --rc-sage: #A3B18A;
            --rc-sage-light: #C5CDB0;
            --rc-near-black: #1A110D;
            --rc-glass-bg: rgba(253, 251, 247, 0.55);
            --rc-glass-border: rgba(255, 255, 255, 0.35);
            --rc-glass-shadow: rgba(43, 30, 23, 0.06);
            --rc-glass-inner: rgba(255, 255, 255, 0.5);
            --rc-font-display: 'Prata', Georgia, serif;
            --rc-font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --rc-container: 1200px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: var(--rc-font-body);
            background: var(--rc-cream);
            color: var(--rc-espresso);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        ::selection { background: var(--rc-terracotta); color: #fff; }

        :focus-visible {
            outline: 2px solid var(--rc-terracotta);
            outline-offset: 3px;
        }

        .rc-main {
            padding-top: 80px;
            min-height: 100vh;
        }

        .rc-display {
            font-family: var(--rc-font-display);
            font-weight: 400;
            letter-spacing: 0.02em;
        }

        .rc-section-label {
            font-family: var(--rc-font-body);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--rc-terracotta);
            margin-bottom: 0.5rem;
        }

        .rc-section-title {
            font-family: var(--rc-font-body);
            font-size: 2rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--rc-espresso);
            line-height: 1.2;
        }

        .rc-hairline {
            width: 40px;
            height: 2px;
            background: var(--rc-terracotta);
            border: 0;
            margin: 0;
            opacity: 1;
        }

        .rc-glass {
            backdrop-filter: blur(24px) saturate(1.5);
            -webkit-backdrop-filter: blur(24px) saturate(1.5);
            background: var(--rc-glass-bg);
            border: 1px solid var(--rc-glass-border);
            box-shadow: 0 4px 24px var(--rc-glass-shadow), inset 0 0 0 1px var(--rc-glass-inner);
            border-radius: 20px;
        }

        .rc-glass-strong {
            backdrop-filter: blur(32px) saturate(1.6);
            -webkit-backdrop-filter: blur(32px) saturate(1.6);
            background: rgba(253, 251, 247, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.45);
            box-shadow: 0 8px 40px var(--rc-glass-shadow), inset 0 0 0 1px var(--rc-glass-inner);
            border-radius: 24px;
        }

        .rc-glass-card {
            backdrop-filter: blur(24px) saturate(1.5);
            -webkit-backdrop-filter: blur(24px) saturate(1.5);
            background: rgba(253, 251, 247, 0.5);
            border: 1px solid var(--rc-glass-border);
            box-shadow: 0 4px 24px var(--rc-glass-shadow), inset 0 0 0 1px var(--rc-glass-inner);
            border-radius: 24px;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .rc-glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 48px rgba(43, 30, 23, 0.1), inset 0 0 0 1px var(--rc-glass-inner);
        }

        .rc-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            font-family: var(--rc-font-body);
            font-weight: 500;
            font-size: 0.9rem;
            border-radius: 100px;
            border: none;
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            cursor: pointer;
            text-decoration: none;
        }

        .rc-btn-primary {
            background: var(--rc-espresso);
            color: #fff;
        }

        .rc-btn-primary:hover {
            background: var(--rc-near-black);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(43, 30, 23, 0.2);
        }

        .rc-btn-outline {
            background: transparent;
            color: var(--rc-espresso);
            border: 1px solid rgba(43, 30, 23, 0.2);
        }

        .rc-btn-outline:hover {
            border-color: var(--rc-espresso);
            background: rgba(43, 30, 23, 0.03);
        }

        .rc-btn-glass {
            background: rgba(253, 251, 247, 0.15);
            color: #fff;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .rc-btn-glass:hover {
            background: rgba(253, 251, 247, 0.25);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.4);
        }

        .rc-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: rgba(253, 251, 247, 0.6);
            backdrop-filter: blur(28px) saturate(1.5);
            -webkit-backdrop-filter: blur(28px) saturate(1.5);
            border-bottom: 1px solid rgba(253, 251, 247, 0.15);
            transition: background 0.5s ease, box-shadow 0.5s ease;
            padding: 0;
        }

        .rc-navbar.scrolled {
            background: rgba(253, 251, 247, 0.88);
            box-shadow: 0 1px 0 rgba(43, 30, 23, 0.04);
        }

        .rc-navbar .navbar-brand {
            font-family: var(--rc-font-display);
            font-size: 1.5rem;
            color: var(--rc-espresso);
            letter-spacing: 0.03em;
            padding: 1.25rem 0;
        }

        .rc-navbar .navbar-brand:hover { color: var(--rc-espresso); }

        .rc-navbar .nav-link {
            font-family: var(--rc-font-body);
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--rc-espresso-soft);
            padding: 0.5rem 1rem;
            position: relative;
            transition: color 0.3s;
        }

        .rc-navbar .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1rem;
            right: 1rem;
            height: 2px;
            background: var(--rc-terracotta);
            transform: scaleX(0);
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .rc-navbar .nav-link:hover { color: var(--rc-espresso); }

        .rc-navbar .nav-link:hover::after { transform: scaleX(1); }

        .rc-navbar .rc-nav-cta {
            background: var(--rc-terracotta);
            color: #fff;
            border-radius: 100px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            font-size: 0.85rem;
            transition: background 0.3s;
            text-decoration: none;
        }

        .rc-navbar .rc-nav-cta:hover { background: #c49363; color: #fff; }

        .rc-navbar .rc-cart-link {
            color: var(--rc-espresso-soft);
            text-decoration: none;
            font-size: 1.25rem;
            position: relative;
            padding: 0.5rem;
            transition: color 0.3s;
        }

        .rc-navbar .rc-cart-link:hover { color: var(--rc-espresso); }

        .rc-navbar .rc-cart-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--rc-terracotta);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 600;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rc-navbar .navbar-toggler {
            border: none;
            padding: 0.5rem;
            color: var(--rc-espresso);
        }

        .rc-navbar .navbar-toggler:focus { box-shadow: none; }

        .rc-fluid-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .rc-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.07;
            will-change: transform;
        }

        .rc-shape--1 {
            width: 600px;
            height: 600px;
            background: var(--rc-terracotta);
            top: -200px;
            right: -150px;
            animation: rc-drift-1 30s ease-in-out infinite alternate;
        }

        .rc-shape--2 {
            width: 450px;
            height: 450px;
            background: var(--rc-sage);
            bottom: -150px;
            left: -100px;
            animation: rc-drift-2 25s ease-in-out infinite alternate;
            border-radius: 40% 60% 45% 55%;
        }

        .rc-shape--3 {
            width: 300px;
            height: 300px;
            background: #fff;
            top: 50%;
            left: 60%;
            opacity: 0.04;
            animation: rc-drift-3 35s ease-in-out infinite alternate;
        }

        @keyframes rc-drift-1 {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, -50px) scale(1.06); }
            66% { transform: translate(-30px, 30px) scale(0.94); }
            100% { transform: translate(50px, -30px) scale(1.03); }
        }

        @keyframes rc-drift-2 {
            0% { transform: translate(0, 0) rotate(0deg) scale(1); }
            50% { transform: translate(60px, 40px) rotate(10deg) scale(1.05); }
            100% { transform: translate(-40px, -30px) rotate(5deg) scale(0.95); }
        }

        @keyframes rc-drift-3 {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-80px, 20px) scale(1.08); }
            100% { transform: translate(30px, -40px) scale(0.92); }
        }

        .rc-footer {
            background: var(--rc-near-black);
            color: rgba(255, 255, 255, 0.7);
            padding: 5rem 0 2rem;
            margin-top: 6rem;
        }

        .rc-footer h5 {
            font-family: var(--rc-font-body);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 1.25rem;
        }

        .rc-footer a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s;
            font-size: 0.9rem;
        }

        .rc-footer a:hover { color: #fff; }

        .rc-footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .rc-footer ul li { margin-bottom: 0.6rem; }

        .rc-footer .rc-footer-brand {
            font-family: var(--rc-font-display);
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 0.75rem;
            letter-spacing: 0.03em;
        }

        .rc-footer .rc-footer-divider {
            border: 0;
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin: 2.5rem 0;
        }

        .rc-footer .rc-footer-copy {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.35);
            text-align: center;
        }

        .rc-hero {
            min-height: calc(100vh - 80px);
            border-radius: 32px;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--rc-near-black) 0%, var(--rc-espresso) 50%, #1f1510 100%);
            margin-top: 20px;
        }

        .rc-hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: var(--rc-font-body);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--rc-terracotta);
            background: rgba(212, 163, 115, 0.1);
            border: 1px solid rgba(212, 163, 115, 0.2);
            padding: 0.4rem 1rem;
            border-radius: 100px;
        }

        .rc-hero-title {
            font-family: var(--rc-font-display);
            font-size: clamp(3rem, 7vw, 5.5rem);
            color: #fff;
            line-height: 1.1;
            margin: 1.5rem 0 1rem;
            letter-spacing: 0.01em;
        }

        .rc-hero-sub {
            font-family: var(--rc-font-body);
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.6);
            max-width: 480px;
            line-height: 1.7;
            font-weight: 300;
        }

        .rc-hero-content {
            position: relative;
            z-index: 2;
            padding: 5rem 4rem;
        }

        .rc-hero-watermark {
            position: absolute;
            right: -20px;
            bottom: -30px;
            font-family: var(--rc-font-display);
            font-size: clamp(8rem, 18vw, 16rem);
            color: rgba(255, 255, 255, 0.02);
            line-height: 1;
            pointer-events: none;
            user-select: none;
            letter-spacing: 0.08em;
        }

        .rc-section {
            padding: 5rem 0;
        }

        .rc-section--first { padding-top: 4rem; }

        .rc-category-card {
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            height: 300px;
            cursor: pointer;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .rc-category-card:hover { transform: translateY(-6px); }

        .rc-category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .rc-category-card:hover img { transform: scale(1.05); }

        .rc-category-card .rc-category-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(transparent 50%, rgba(26, 17, 13, 0.6));
        }

        .rc-category-card .rc-category-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
            color: #fff;
            font-family: var(--rc-font-body);
            font-weight: 600;
            font-size: 1.2rem;
        }

        .rc-product-card {
            border: none;
            background: transparent;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
            cursor: pointer;
        }

        .rc-product-card:hover { transform: translateY(-4px); }

        .rc-product-card .rc-product-image-wrap {
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            aspect-ratio: 3/4;
            background: var(--rc-cream-dark);
        }

        .rc-product-card .rc-product-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .rc-product-card:hover .rc-product-image-wrap img { transform: scale(1.04); }

        .rc-product-card .rc-product-body {
            padding: 1rem 0.25rem 0;
        }

        .rc-product-card .rc-product-name {
            font-family: var(--rc-font-body);
            font-weight: 500;
            font-size: 1rem;
            color: var(--rc-espresso);
            margin-bottom: 0.2rem;
        }

        .rc-product-card .rc-product-category {
            font-size: 0.8rem;
            color: rgba(43, 30, 23, 0.4);
            font-weight: 400;
        }

        .rc-product-card .rc-product-price {
            font-family: var(--rc-font-body);
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--rc-terracotta);
        }

        .rc-product-card .rc-product-hover-actions {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .rc-product-card:hover .rc-product-hover-actions {
            opacity: 1;
            transform: translateY(0);
        }

        .rc-product-card .rc-product-hover-actions .rc-btn {
            flex: 1;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            justify-content: center;
        }

        .rc-value-card {
            text-align: center;
            padding: 2.5rem 2rem;
        }

        .rc-value-card .rc-value-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
            color: var(--rc-terracotta);
        }

        .rc-value-card h4 {
            font-family: var(--rc-font-body);
            font-weight: 600;
            font-size: 1.15rem;
            margin-bottom: 0.5rem;
            color: var(--rc-espresso);
        }

        .rc-value-card p {
            font-size: 0.9rem;
            color: rgba(43, 30, 23, 0.55);
            line-height: 1.6;
        }

        .rc-banner-cta {
            border-radius: 32px;
            padding: 5rem 4rem;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--rc-espresso) 0%, var(--rc-near-black) 100%);
        }

        .rc-banner-cta h2 {
            font-family: var(--rc-font-display);
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 0.75rem;
        }

        .rc-banner-cta p {
            color: rgba(255, 255, 255, 0.55);
            font-weight: 300;
            max-width: 400px;
            line-height: 1.7;
        }

        .rc-instagram-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
        }

        .rc-instagram-grid img {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 16px;
            transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
            cursor: pointer;
        }

        .rc-instagram-grid img:hover { transform: scale(1.02); }

        .rc-catalog-hero {
            min-height: 340px;
            border-radius: 32px;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--rc-near-black) 0%, var(--rc-espresso) 50%, #1f1510 100%);
            margin-top: 20px;
        }

        .rc-catalog-hero .rc-hero-content { padding: 3rem 4rem; }

        .rc-catalog-hero .rc-hero-title {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
        }

        .rc-search-glass {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .rc-search-glass input {
            flex: 1;
            border: none;
            background: transparent;
            font-family: var(--rc-font-body);
            font-size: 0.95rem;
            color: var(--rc-espresso);
            outline: none;
            padding: 0.5rem 0;
        }

        .rc-search-glass input::placeholder { color: rgba(43, 30, 23, 0.3); }

        .rc-pill {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            font-family: var(--rc-font-body);
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--rc-espresso-soft);
            background: rgba(253, 251, 247, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(43, 30, 23, 0.08);
            border-radius: 100px;
            cursor: pointer;
            transition: all 0.3s;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .rc-pill:hover {
            border-color: var(--rc-terracotta);
            color: var(--rc-espresso);
        }

        .rc-pill.active {
            background: var(--rc-espresso);
            color: #fff;
            border-color: var(--rc-espresso);
        }

        .rc-filter-section {
            margin-bottom: 1.5rem;
        }

        .rc-filter-section h6 {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(43, 30, 23, 0.4);
            margin-bottom: 1rem;
        }

        .rc-pagination .page-link {
            border: none;
            background: transparent;
            color: var(--rc-espresso-soft);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .rc-pagination .page-link:hover {
            background: rgba(43, 30, 23, 0.05);
            color: var(--rc-espresso);
        }

        .rc-pagination .page-item.active .page-link {
            background: var(--rc-espresso);
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .rc-navbar .navbar-collapse {
                background: rgba(253, 251, 247, 0.95);
                backdrop-filter: blur(28px);
                border-radius: 20px;
                padding: 1rem;
                margin-top: 0.5rem;
                box-shadow: 0 8px 40px rgba(43, 30, 23, 0.08);
            }

            .rc-hero-content { padding: 3rem 2rem; }

            .rc-hero-title { font-size: clamp(2.5rem, 8vw, 4rem); }

            .rc-banner-cta { padding: 3rem 2rem; }

            .rc-banner-cta h2 { font-size: 2rem; }

            .rc-instagram-grid { grid-template-columns: repeat(3, 1fr); }
        }

        @media (max-width: 767.98px) {
            .rc-hero { min-height: 70vh; }

            .rc-hero-content { padding: 2.5rem 1.5rem; }

            .rc-hero-title { font-size: clamp(2rem, 10vw, 3rem); }

            .rc-hero-watermark { display: none; }

            .rc-section-title { font-size: 1.6rem; }

            .rc-catalog-hero .rc-hero-content { padding: 2rem 1.5rem; }

            .rc-banner-cta { padding: 2.5rem 1.5rem; }

            .rc-banner-cta h2 { font-size: 1.6rem; }

            .rc-instagram-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 575.98px) {
            .rc-main { padding-top: 72px; }

            .rc-hero { min-height: 60vh; border-radius: 24px; }

            .rc-category-card { height: 220px; }

            .rc-section { padding: 3rem 0; }
        }

        @media (prefers-reduced-motion: reduce) {
            .rc-shape { animation: none; }

            .rc-glass-card { transition: none; }

            .rc-glass-card:hover { transform: none; }

            .rc-product-card { transition: none; }

            .rc-product-card:hover { transform: none; }

            .rc-product-card .rc-product-hover-actions { opacity: 1; transform: none; }

            .rc-category-card { transition: none; }

            .rc-category-card:hover { transform: none; }

            .rc-category-card:hover img { transform: none; }

            .rc-btn { transition: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="rc-fluid-bg" aria-hidden="true">
        <div class="rc-shape rc-shape--1"></div>
        <div class="rc-shape rc-shape--2"></div>
        <div class="rc-shape rc-shape--3"></div>
    </div>

    @include('layouts.navbar')

    <main class="rc-main">
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            var nav = document.querySelector('.rc-navbar');
            if (nav) {
                var checkScroll = function() {
                    if (window.scrollY > 40) {
                        nav.classList.add('scrolled');
                    } else {
                        nav.classList.remove('scrolled');
                    }
                };
                checkScroll();
                var ticking = false;
                window.addEventListener('scroll', function() {
                    if (!ticking) {
                        window.requestAnimationFrame(function() {
                            checkScroll();
                            ticking = false;
                        });
                        ticking = true;
                    }
                });
            }

            var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            if (prefersReducedMotion.matches) {
                var shapes = document.querySelectorAll('.rc-shape');
                for (var i = 0; i < shapes.length; i++) {
                    shapes[i].style.animation = 'none';
                    shapes[i].style.opacity = '0.04';
                }
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
