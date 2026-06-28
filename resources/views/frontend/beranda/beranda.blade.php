@extends('frontend.layouts.app')

@section('title', 'Republik Casual')

@push('styles')
<style>
    /* ========================
        RESET & DARK VARIABLES
    ======================== */
    :root {
        --rc-bg-dark: #0a0a0a;
        --rc-card-dark: rgba(18, 18, 18, 0.5); /* Dibuat lebih transparan agar video di belakangnya tembus */
        --rc-card-border: rgba(255, 255, 255, 0.08);
        --rc-accent: #EAE6DF;
        --rc-accent-rgb: 234, 230, 223;
        --rc-text-primary: #ffffff;
        --rc-text-secondary: #999999;
        --transition-smooth: cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* ========================
        VIDEO BACKGROUND LOGIC
    ======================== */
    body {
        background-color: var(--rc-bg-dark);
        position: relative;
        overflow-x: hidden;
    }

    .video-bg-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -2; /* Di paling bawah */
        overflow: hidden;
        pointer-events: none;
    }

    .video-bg-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* ATUR TINGKAT BLUR DI SINI (Ubah angka 15px jika ingin lebih/kurang blur) */
        filter: blur(1px) brightness(1); 
    }

    /* Overlay gelap tambahan agar video tidak mengganggu keterbacaan teks */
    .video-bg-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -1; /* Di atas video, di bawah konten */
        background: radial-gradient(circle, rgba(0,0,0,0.2) 0%, rgba(10,10,10,0.85) 80%);
        pointer-events: none;
    }

    /* ========================
        HERO SLIDER
    ======================== */
    .hero-slider {
        position: relative;
        height: 600px;
        overflow: hidden;
        border-radius: 24px;
        margin-bottom: 80px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }

    .slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        transition: opacity 1.2s var(--transition-smooth), visibility 1.2s;
    }

    .slide.active {
        opacity: 1;
        visibility: visible;
        z-index: 2;
    }

    .slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1.05);
        transition: transform 1.2s var(--transition-smooth);
    }

    .slide.active img {
        transform: scale(1);
    }

    .slide-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(75deg, rgba(10,10,10,0.9) 0%, rgba(10,10,10,0.4) 60%, rgba(10,10,10,0.2) 100%);
        display: flex;
        align-items: center;
    }

    .hero-content {
        padding: 0 80px;
        max-width: 680px;
    }

    .hero-tag {
        color: var(--rc-accent);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 3px;
        margin-bottom: 24px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .hero-tag::before {
        content: '';
        width: 20px;
        height: 1px;
        background: var(--rc-accent);
        display: inline-block;
    }

    .hero-content h1 {
        color: var(--rc-text-primary);
        font-size: 64px;
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .hero-content p {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 40px;
        line-height: 1.7;
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 36px;
        background: var(--rc-accent);
        color: #000000;
        border-radius: 50px;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
        transition: all 0.4s var(--transition-smooth);
        box-shadow: 0 8px 24px rgba(var(--rc-accent-rgb), 0.3);
    }

    .hero-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(var(--rc-accent-rgb), 0.5);
        background: var(--rc-text-primary);
        color: #000000;
    }

    /* ========================
        SLIDER MANUAL BUTTONS
    ======================== */
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: rgba(18, 18, 18, 0.3);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--rc-text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s var(--transition-smooth);
    }

    .slider-nav:hover {
        background: var(--rc-accent);
        color: #000000;
        border-color: var(--rc-accent);
        transform: translateY(-50%) scale(1.05);
    }

    .slider-nav.prev { left: 24px; }
    .slider-nav.next { right: 24px; }

    .slider-dots {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
        z-index: 10;
    }

    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        cursor: pointer;
        transition: all 0.3s var(--transition-smooth);
    }

    .dot.active {
        background: var(--rc-accent);
        width: 28px;
        border-radius: 999px;
    }

    /* ========================
        SECTION HEADERS
    ======================== */
    .section {
        margin-bottom: 90px;
    }

    .section-title {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--rc-text-primary);
        letter-spacing: -0.5px;
    }

    .section-subtitle {
        color: var(--rc-text-secondary);
        margin-bottom: 40px;
        font-size: 16px;
    }

    /* ========================
        CATEGORY & BRAND GRID
    ======================== */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    .category-card {
        padding: 32px 24px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        text-align: center;
        transition: all 0.4s var(--transition-smooth);
        backdrop-filter: blur(12px); /* Efek kaca tebal tembus ke video background */
        -webkit-backdrop-filter: blur(12px);
    }

    .category-card:hover {
        transform: translateY(-6px);
        border-color: rgba(var(--rc-accent-rgb), 0.4);
        box-shadow: 0 12px 30px rgba(var(--rc-accent-rgb), 0.1);
        background: rgba(255, 255, 255, 0.04);
    }

    .category-card i {
        font-size: 28px;
        color: var(--rc-accent);
        margin-bottom: 16px;
        transition: transform 0.3s ease;
    }

    .category-card:hover i {
        transform: scale(1.1);
    }

    .category-card h4 {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: var(--rc-text-primary);
    }

    /* ========================
        PRODUCT GRID & CARDS (GLASSMORPHISM)
    ======================== */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 32px;
    }

    .product-card {
        overflow: hidden;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 28px;
        transition: all 0.4s var(--transition-smooth);
        backdrop-filter: blur(12px); /* Mengaburkan video background di bawah kartu */
        -webkit-backdrop-filter: blur(12px);
    }

    .product-card:hover {
        transform: translateY(-8px);
        border-color: rgba(var(--rc-accent-rgb), 0.3);
        box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        background: rgba(20, 20, 20, 0.6);
    }

    .product-image {
        aspect-ratio: 3/4;
        overflow: hidden;
        position: relative;
        background: #1a1a1a;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s var(--transition-smooth), filter 0.4s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.04);
        filter: blur(4px) brightness(0.7);
    }

    .quick-add-overlay {
        position: absolute;
        inset: 0;
        padding: 24px;
        opacity: 0;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        transition: all 0.4s var(--transition-smooth);
        z-index: 3;
    }

    .product-card:hover .quick-add-overlay {
        opacity: 1;
    }

    .btn-quick-bag {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px;
        border-radius: 16px;
        background: var(--rc-text-primary);
        color: #000000;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        transform: translateY(20px);
        transition: all 0.4s var(--transition-smooth);
    }

    .product-card:hover .btn-quick-bag {
        transform: translateY(0);
    }

    .btn-quick-bag:hover {
        background: var(--rc-accent);
        color: #000000;
        transform: scale(1.02);
    }

    .item-details {
        padding: 26px 24px;
    }

    .tag-category {
        display: inline-block;
        color: var(--rc-accent);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }

    .item-name {
        display: block;
        color: var(--rc-text-primary) !important;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 6px;
        text-decoration: none;
        transition: color 0.2s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-name:hover {
        color: var(--rc-accent) !important;
    }

    .product-brand {
        color: var(--rc-text-secondary);
        font-size: 13px;
        margin-bottom: 16px;
        font-weight: 500;
    }

    .price-tag {
        font-size: 21px;
        font-weight: 800;
        color: var(--rc-accent);
        letter-spacing: -0.5px;
    }

    /* ========================
        PROMO BANNER
    ======================== */
    .promo-banner {
        padding: 90px 40px;
        text-align: center;
        border-radius: 32px;
        background: linear-gradient(135deg, rgba(var(--rc-accent-rgb), 0.1) 0%, rgba(255,255,255,0.01) 100%);
        border: 1px solid rgba(var(--rc-accent-rgb), 0.15);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
    }

    .promo-banner h2 {
        font-size: 44px;
        font-weight: 800;
        letter-spacing: 1px;
        margin-bottom: 16px;
        color: var(--rc-text-primary);
    }

    .promo-banner p {
        color: var(--rc-text-secondary);
        margin-bottom: 36px;
        font-size: 16px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.7;
    }

    /* ========================
        RESPONSIVE BREAKPOINTS
    ======================== */
    @media(max-width: 992px){
        .hero-content {
            padding: 0 40px;
        }
        .hero-content h1 {
            font-size: 48px;
        }
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media(max-width: 768px){
        .hero-slider {
            height: 520px;
            margin-bottom: 60px;
        }
        .hero-content {
            padding: 24px;
        }
        .hero-content h1 {
            font-size: 36px;
        }
        .hero-content p {
            font-size: 16px;
            margin-bottom: 28px;
        }
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .product-grid {
            grid-template-columns: 1fr;
        }
        .promo-banner {
            padding: 60px 24px;
        }
        .promo-banner h2 {
            font-size: 32px;
        }
    }
</style>
@endpush

@section('content')

<div class="video-bg-container">
    <video autoplay loop muted playsinline>
        <source src="{{ asset('storage/video/background.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>
<div class="video-bg-overlay"></div>


<div class="hero-slider">
    
    <div class="slide active">
        <img src="{{ asset('storage/banners/banner1.jpg') }}" alt="New Streetwear Collection">
        <div class="slide-overlay">
            <div class="hero-content">
                <div class="hero-tag">Republik Casual</div>
                <h1>New Streetwear Collection</h1>
                <p>Koleksi fashion kasual modern dengan kualitas premium untuk menunjang gaya hidup aktif dan percaya diri.</p>
                <a href="{{ route('products') }}" class="hero-btn">
                    Belanja Sekarang <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="slide">
        <img src="{{ asset('storage/banners/banner2.jpg') }}" alt="Urban Style Essential">
        <div class="slide-overlay">
            <div class="hero-content">
                <div class="hero-tag">Edisi Terbatas</div>
                <h1>Urban Style Essential</h1>
                <p>Tingkatkan penampilan harianmu dengan material anti-gerah dan desain ergonomis khas perkotaan.</p>
                <a href="{{ route('products') }}" class="hero-btn">
                    Lihat Katalog <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="slide">
        <img src="{{ asset('storage/banners/banner3.jpg') }}" alt="Exclusive Outfit">
        <div class="slide-overlay">
            <div class="hero-content">
                <div class="hero-tag">Premium Quality</div>
                <h1>Exclusive Daily Outfit</h1>
                <p>Temukan perpaduan kenyamanan maksimal dan estetika visual modern yang dirancang khusus untuk Anda.</p>
                <a href="{{ route('products') }}" class="hero-btn">
                    Jelajahi Sekarang <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <button class="slider-nav prev" id="sliderPrev"><i class="fa-solid fa-chevron-left"></i></button>
    <button class="slider-nav next" id="sliderNext"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="slider-dots">
        <div class="dot active" data-index="0"></div>
        <div class="dot" data-index="1"></div>
        <div class="dot" data-index="2"></div>
    </div>

</div>

<section class="section">
    <h2 class="section-title">Kategori Populer</h2>
    <p class="section-subtitle">Pilihan fashion favorit pelanggan</p>

    <div class="category-grid">
        @foreach($kategori as $item)
        <a href="{{ route('products', ['kategori' => $item->id_kategori]) }}" class="category-card" style="text-decoration: none; color: inherit;">
            <i class="fa-solid fa-shirt"></i>
            <h4>{{ $item->nama_kategori }}</h4>
        </a>
        @endforeach
    </div>
</section>

<section class="section">
    <h2 class="section-title">Brand Populer</h2>
    <p class="section-subtitle">Brand pilihan pelanggan Republik Casual</p>

    <div class="category-grid">
        @foreach($brand as $item)
        <a href="{{ route('products', ['brand' => $item->id_brand]) }}" class="category-card" style="text-decoration: none; color: inherit;">
            <i class="fa-solid fa-tag"></i>
            <h4>{{ $item->nama_brand }}</h4>
        </a>
        @endforeach
    </div>
</section>

<section class="section">
    <h2 class="section-title">Best Seller</h2>
    <p class="section-subtitle">Produk terlaris minggu ini</p>

    <div class="product-grid">
        @foreach($produkTerbaru as $item)
        <div class="product-card">
            <div class="product-image">
                <img src="{{ asset('storage/'.$item->foto_produk) }}" alt="{{ $item->nama_produk }}">
                <div class="quick-add-overlay">
                    <a href="{{ route('products.show', $item->id_produk) }}" class="btn-quick-bag">
                        <i class="fa-solid fa-eye"></i> Lihat Produk
                    </a>
                </div>
            </div>
            
            <div class="item-details">
                <span class="tag-category">{{ $item->kategori->nama_kategori ?? 'Fashion' }}</span>
                <a href="{{ route('products.show', $item->id_produk) }}" class="item-name">
                    {{ $item->nama_produk }}
                </a>
                <div class="product-brand">{{ $item->brand->nama_brand ?? '-' }}</div>
                <div class="price-tag">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section class="promo-banner">
    <h2>BUILT FOR EVERYDAY WEAR</h2>
    <p>Fashion kasual modern yang dirancang untuk menemani aktivitas sehari-hari tanpa mengorbankan kenyamanan.</p>
    <a href="{{ route('products') }}" class="hero-btn">
        Lihat Katalog <i class="fa-solid fa-arrow-right"></i>
    </a>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const btnNext = document.getElementById('sliderNext');
        const btnPrev = document.getElementById('sliderPrev');
        
        let current = 0;
        const totalSlides = slides.length;
        let sliderInterval;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            current = index;
            
            if (current >= totalSlides) {
                current = 0;
            } else if (current < 0) {
                current = totalSlides - 1;
            }

            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }

        function nextSlide() { showSlide(current + 1); }
        function prevSlide() { showSlide(current - 1); }

        btnNext.addEventListener('click', () => {
            nextSlide();
            resetInterval();
        });

        btnPrev.addEventListener('click', () => {
            prevSlide();
            resetInterval();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                resetInterval();
            });
        });

        function startInterval() {
            sliderInterval = setInterval(nextSlide, 5000);
        }

        function resetInterval() {
            clearInterval(sliderInterval);
            startInterval();
        }

        startInterval();
    });
</script>
@endpush