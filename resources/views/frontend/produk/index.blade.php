@extends('frontend.layouts.app')

@section('title', 'Katalog - Republik Casual')

@push('styles')
<style>
    /* ========================
        RESET & DARK VARIABLES (Diselaraskan dengan Beranda)
    ======================== */
    :root {
        --rc-bg-dark: #0a0a0a;
        --rc-card-dark: rgba(18, 18, 18, 0.5); /* Semi-transparan agar video tembus */
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
        z-index: -2;
        overflow: hidden;
        pointer-events: none;
    }

    .video-bg-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: blur(1px) brightness(1); 
    }

    .video-bg-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -1;
        background: radial-gradient(circle, rgba(0,0,0,0.2) 0%, rgba(10,10,10,0.85) 80%);
        pointer-events: none;
    }

    /* ========================
        CATALOG COMPONENTS
    ======================== */
    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 20px;
        margin-bottom: 50px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--rc-card-border);
    }

    .brand-statement h2 {
        font-size: 42px;
        font-weight: 900;
        color: var(--rc-text-primary);
        margin-bottom: 8px;
    }

    .brand-statement p {
        color: var(--rc-text-secondary);
        max-width: 500px;
    }

    /* FILTER BOX (GLASSMORPHISM EFFECT) */
    .filter-box {
        margin-bottom: 35px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 24px;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 180px;
        gap: 16px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--rc-text-secondary);
        font-size: 13px;
    }

    .filter-group select,
    .filter-group input {
        width: 100%;
        height: 52px;
        background: rgba(17, 17, 17, 0.7);
        color: #ffffff;
        border: 1px solid var(--rc-card-border);
        border-radius: 14px;
        padding: 0 16px;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        border-color: rgba(var(--rc-accent-rgb), 0.5);
    }

    .filter-group select option {
        background: #111111;
        color: #ffffff;
    }

    .btn-filter {
        border: none;
        border-radius: 14px;
        background: var(--rc-accent);
        color: #000;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s var(--transition-smooth);
        box-shadow: 0 4px 12px rgba(var(--rc-accent-rgb), 0.2);
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        background: var(--rc-text-primary);
        box-shadow: 0 6px 20px rgba(var(--rc-accent-rgb), 0.4);
    }

    /* EDITORIAL PRODUCT GRID */
    .editorial-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 28px;
    }

    .lookbook-card {
        overflow: hidden;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        transition: all 0.4s var(--transition-smooth);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .lookbook-card:hover {
        transform: translateY(-8px);
        border-color: rgba(var(--rc-accent-rgb), 0.4);
        box-shadow: 0 25px 40px rgba(0, 0, 0, 0.6);
        background: rgba(20, 20, 20, 0.6);
    }

    .portrait-frame {
        aspect-ratio: 3/4;
        overflow: hidden;
        position: relative;
        background: #1a1a1a;
    }

    .portrait-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s var(--transition-smooth), filter 0.4s ease;
    }

    .lookbook-card:hover img {
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
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent 60%);
    }

    .lookbook-card:hover .quick-add-overlay {
        opacity: 1;
    }

    .btn-quick-bag {
        width: 100%;
        border: none;
        padding: 16px;
        border-radius: 14px;
        background: var(--rc-text-primary);
        color: #000000;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        transform: translateY(20px);
        transition: all 0.4s var(--transition-smooth);
    }

    .lookbook-card:hover .btn-quick-bag {
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

    .price-tag {
        font-size: 21px;
        font-weight: 800;
        color: var(--rc-accent);
        letter-spacing: -0.5px;
    }

    .glass-card {
        padding: 60px 40px;
        text-align: center;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        color: var(--rc-text-secondary);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    /* ========================
        REPUBLIK CASUAL CUSTOM PAGINATION (PURE CSS FIX)
    ======================== */
    .rc-custom-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 60px;
        user-select: none;
    }

    .rc-page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 46px;
        height: 46px;
        padding: 0 14px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--rc-card-border);
        color: var(--rc-text-primary) !important;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.3s var(--transition-smooth);
        backdrop-filter: blur(8px);
    }

    .rc-page-btn:hover:not(.disabled) {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(var(--rc-accent-rgb), 0.4);
        transform: translateY(-2px);
    }

    .rc-page-btn.active {
        background: var(--rc-accent);
        border-color: var(--rc-accent);
        color: #0a0a0a !important;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(var(--rc-accent-rgb), 0.15);
    }

    .rc-page-btn.disabled {
        background: rgba(255, 255, 255, 0.005);
        border-color: rgba(255, 255, 255, 0.02);
        color: rgba(255, 255, 255, 0.15) !important;
        cursor: not-allowed;
    }

    /* ========================
        RESPONSIVE BREAKPOINTS
    ======================== */
    @media(max-width: 992px) {
        .filter-form {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 768px) {
        .catalog-header {
            flex-direction: column;
            align-items: start;
            gap: 12px;
        }

        .brand-statement h2 {
            font-size: 32px;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .editorial-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media(max-width: 480px) {
        .editorial-grid {
            grid-template-columns: 1fr;
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

<div class="catalog-header">
    <div class="brand-statement">
        <h2>Katalog Republik Casual</h2>
        <p>Temukan koleksi streetwear dan casual premium untuk menunjang gaya hidup modern.</p>
    </div>
</div>

<div class="filter-box">
    <form method="GET" class="filter-form">
        <div class="filter-group">
            <label>Kategori</label>
            <select name="kategori">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>Brand</label>
            <select name="brand">
                <option value="">Semua Brand</option>
                @foreach($brand as $br)
                    <option value="{{ $br->id_brand }}" {{ request('brand') == $br->id_brand ? 'selected' : '' }}>
                        {{ $br->nama_brand }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>Cari Produk</label>
            <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
        </div>

        <button type="submit" class="btn-filter">
            <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>
    </form>
</div>

<div class="editorial-grid">
    @forelse($produk as $item)
    <div class="lookbook-card">
        <div class="portrait-frame">
            <img src="{{ asset('storage/' . $item->foto_produk) }}" alt="{{ $item->nama_produk }}">
            <div class="quick-add-overlay">
                <a href="{{ route('products.show', $item->id_produk) }}" class="btn-quick-bag" style="text-decoration:none; display:flex; justify-content:center; align-items:center; gap:8px;">
                    <i class="fa-solid fa-eye"></i> Lihat Produk
                </a>
            </div>
        </div>

        <div class="item-details">
            <span class="tag-category">
                {{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}
            </span>

            <a href="{{ route('products.show', $item->id_produk) }}" class="item-name">
                {{ $item->nama_produk }}
            </a>

            <div style="color: var(--rc-text-secondary); font-size: 13px; margin-bottom: 16px; font-weight: 500;">
                {{ $item->brand->nama_brand ?? '-' }}
            </div>

            <div class="price-tag">
                Rp {{ number_format($item->harga, 0, ',', '.') }}
            </div>
        </div>
    </div>
    @empty
    <div class="glass-card" style="grid-column: 1 / -1;">
        <i class="fa-solid fa-box-open" style="font-size: 40px; color: var(--rc-accent); margin-bottom: 16px; display: block;"></i>
        Belum ada produk tersedia yang cocok dengan filter.
    </div>
    @endforelse
</div>

@if ($produk->hasPages())
<div class="rc-custom-pagination">
    {{-- Tombol Previous --}}
    @if ($produk->onFirstPage())
        <span class="rc-page-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
    @else
        <a href="{{ $produk->previousPageUrl() }}" class="rc-page-btn"><i class="fa-solid fa-chevron-left"></i></a>
    @endif

    {{-- Daftar Nomor Halaman Dinamis --}}
    @foreach ($produk->getUrlRange(1, $produk->lastPage()) as $page => $url)
        @if ($page == $produk->currentPage())
            <span class="rc-page-btn active">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="rc-page-btn">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Tombol Next --}}
    @if ($produk->hasMorePages())
        <a href="{{ $produk->nextPageUrl() }}" class="rc-page-btn"><i class="fa-solid fa-chevron-right"></i></a>
    @else
        <span class="rc-page-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
    @endif
</div>
@endif

@endsection