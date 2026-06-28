@extends('frontend.layouts.app')

@section('title', $produk->nama_produk . ' - Republik Casual')

@push('styles')
<style>
    /* ========================
        RESET & DARK VARIABLES
    ======================== */
    :root {
        --rc-bg-dark: #0a0a0a;
        --rc-card-dark: rgba(18, 18, 18, 0.5);
        --rc-card-border: rgba(255, 255, 255, 0.08);
        --rc-accent: #EAE6DF;
        --rc-accent-rgb: 234, 230, 223;
        --rc-text-primary: #ffffff;
        --rc-text-secondary: #999999;
        --transition-smooth: cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    body {
        background-color: var(--rc-bg-dark);
        position: relative;
        overflow-x: hidden;
    }

    /* ========================
        VIDEO BACKGROUND LOGIC
    ======================== */
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
        background: radial-gradient(circle, rgba(0,0,0,0.1) 0%, rgba(10,10,10,0.9) 85%);
        pointer-events: none;
    }

    /* ========================
        NAVIGATION & LAYOUT
    ======================== */
    .back-navigation {
        margin-bottom: 35px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 14px 22px;
        border-radius: 16px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        color: var(--rc-text-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s var(--transition-smooth);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .back-btn:hover {
        transform: translateX(-4px);
        border-color: rgba(var(--rc-accent-rgb), 0.4);
        background: rgba(var(--rc-accent-rgb), 0.1);
        color: var(--rc-accent);
    }

    .product-wrapper {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr; /* Sedikit dilebarkan area fotonya */
        gap: 60px;
        margin-bottom: 60px;
    }

    .product-gallery {
        position: sticky;
        top: 120px;
        height: fit-content;
    }

    .product-image {
        overflow: hidden;
        border-radius: 32px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    /* FIX: Diubah menjadi 3/4 agar foto produk vertikal/celana tidak kepotong */
    .product-image img {
        width: 100%;
        display: block;
        aspect-ratio: 3/4; 
        object-fit: cover;
        transition: transform 0.6s var(--transition-smooth);
    }

    .product-image:hover img {
        transform: scale(1.03);
    }

    /* ========================
        PRODUCT INFO & DETAILS
    ======================== */
    .product-info {
        display: flex;
        flex-direction: column;
    }

    .badge-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .product-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 999px;
        background: rgba(var(--rc-accent-rgb), 0.1);
        border: 1px solid rgba(var(--rc-accent-rgb), 0.25);
        color: var(--rc-accent);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* FIX: Didesain ulang agar rapi mendampingi kategori di bagian atas */
    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 999px;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.25);
        color: #22C55E;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-title {
        font-size: 46px;
        font-weight: 900;
        line-height: 1.15;
        color: var(--rc-text-primary);
        margin-bottom: 20px;
        letter-spacing: -0.5px;
    }

    .product-meta {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .meta-item {
        padding: 10px 18px;
        border-radius: 14px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        color: var(--rc-text-secondary);
        font-size: 13px;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .meta-item strong {
        color: var(--rc-text-primary);
    }

    .price {
        font-size: 40px;
        font-weight: 800;
        color: var(--rc-accent);
        margin-bottom: 25px;
        letter-spacing: -0.5px;
    }

    .product-description {
        color: var(--rc-text-secondary);
        line-height: 1.8;
        font-size: 15px;
        margin-bottom: 35px;
    }

    /* INFO GRID (GLASSMORPHISM) */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 35px;
    }

    .info-card {
        padding: 20px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: border-color 0.3s;
    }

    .info-card:hover {
        border-color: rgba(var(--rc-accent-rgb), 0.3);
    }

    .info-card small {
        display: block;
        color: var(--rc-text-secondary);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .info-card strong {
        color: var(--rc-text-primary);
        font-size: 16px;
    }

    /* BUTTONS */
    .action-group {
        display: flex;
        gap: 16px;
    }

    .btn-primary {
        flex: 1;
        height: 58px;
        border: none;
        border-radius: 18px;
        background: var(--rc-accent);
        color: #000000;
        font-weight: 800;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s var(--transition-smooth);
        box-shadow: 0 8px 20px rgba(var(--rc-accent-rgb), 0.15);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        background: var(--rc-text-primary);
        box-shadow: 0 12px 24px rgba(var(--rc-accent-rgb), 0.3);
    }

    /* ========================
        CART MODAL CONTAINER
    ======================== */
    .cart-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .cart-modal.active {
        display: flex;
        opacity: 1;
    }

    .cart-modal-content {
        width: 100%;
        max-width: 420px;
        background: rgba(20, 20, 20, 0.85);
        border: 1px solid var(--rc-card-border);
        border-radius: 28px;
        padding: 32px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
        transform: scale(0.95);
        transition: transform 0.3s var(--transition-smooth);
    }

    .cart-modal.active .cart-modal-content {
        transform: scale(1);
    }

    .cart-modal-content h3 {
        font-size: 22px;
        color: var(--rc-text-primary);
        margin-bottom: 8px;
        font-weight: 800;
    }

    .cart-modal-content p {
        color: var(--rc-text-secondary);
        font-size: 14px;
        margin-bottom: 24px;
    }

    /* QUANTITY INPUT BOX */
    .qty-box {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin: 24px 0;
    }

    .qty-box button {
        width: 46px;
        height: 46px;
        border: 1px solid var(--rc-card-border);
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--rc-text-primary);
        font-size: 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .qty-box button:hover {
        background: var(--rc-accent);
        color: #000000;
        border-color: var(--rc-accent);
    }

    .qty-box input {
        width: 90px;
        height: 46px;
        text-align: center;
        background: rgba(0, 0, 0, 0.3);
        color: #ffffff;
        font-size: 16px;
        font-weight: 700;
        border: 1px solid var(--rc-card-border);
        border-radius: 14px;
        outline: none;
    }

    .qty-box input::-webkit-outer-spin-button,
    .qty-box input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .btn-modal-close {
        background: transparent;
        border: none;
        color: var(--rc-text-secondary);
        font-weight: 600;
        margin-top: 14px;
        width: 100%;
        cursor: pointer;
        font-size: 13px;
        transition: color 0.2s;
    }

    .btn-modal-close:hover {
        color: var(--rc-text-primary);
    }

    /* ========================
        RESPONSIVE BREAKPOINTS
    ======================== */
    @media(max-width: 992px){
        .product-wrapper {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        .product-gallery {
            position: relative;
            top: 0;
        }
        .product-title {
            font-size: 36px;
        }
    }

    @media(max-width: 576px){
        .info-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .cart-modal-content {
            margin: 0 16px;
            padding: 24px;
        }
    }
</style>
@endpush

@section('content')

<!-- VIDEO BACKGROUND COMPONENT -->
<div class="video-bg-container">
    <video autoplay loop muted playsinline>
        <source src="{{ asset('storage/video/background.mp4') }}" type="video/mp4">
    </video>
</div>
<div class="video-bg-overlay"></div>

<!-- NAVIGATION BACK -->
<div class="back-navigation">
    <a href="{{ route('products') }}" class="back-btn">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Katalog</span>
    </a>
</div>

<!-- CONTAINER LAYOUT -->
<div class="product-wrapper">

    <!-- LEFT: GALLERY -->
    <div class="product-gallery">
        <div class="product-image">
            <img src="{{ asset('storage/' . $produk->foto_produk) }}" alt="{{ $produk->nama_produk }}">
        </div>
    </div>

    <!-- RIGHT: INFORMATION -->
    <div class="product-info">
        <!-- FIX: Menyatukan Kategori & Status Tersedia secara horizontal -->
        <div class="badge-row">
            <div class="product-badge">
                {{ $produk->kategori->nama_kategori ?? 'Kategori' }}
            </div>
            @if($produk->stok > 0)
                <div class="stock-badge">
                    <i class="fa-solid fa-circle-check"></i> Tersedia
                </div>
            @endif
        </div>

        <h1 class="product-title">
            {{ $produk->nama_produk }}
        </h1>

        <div class="product-meta">
            <div class="meta-item">
                Brand: <strong>{{ $produk->brand->nama_brand ?? '-' }}</strong>
            </div>
        </div>

        <div class="price">
            Rp {{ number_format($produk->harga, 0, ',', '.') }}
        </div>

        <div class="product-description">
            {{ $produk->deskripsi_produk }}
        </div>

        <!-- PARAMETER GRID -->
        <div class="info-grid">
            <div class="info-card">
                <small>Ukuran</small>
                <strong>{{ $produk->size_produk }}</strong>
            </div>
            <div class="info-card">
                <small>Berat</small>
                <strong>{{ $produk->berat }} g</strong>
            </div>
            <div class="info-card">
                <small>Stok</small>
                <strong>{{ $produk->stok }} pcs</strong>
            </div>
        </div>

        <!-- ACTION GROUP -->
        <div class="action-group">
            @if($produk->stok > 0)
                <button type="button" class="btn-primary" onclick="openCartModal()">
                    <i class="fa-solid fa-bag-shopping"></i> &nbsp; Tambah Ke Keranjang
                </button>
            @else
                <button type="button" class="btn-primary" style="background:#222; color:#555; cursor:not-allowed; box-shadow:none;" disabled>
                    Stok Habis
                </button>
            @endif
        </div>
    </div>
</div>

<!-- INTERACTIVE MODAL BOX -->
<div class="cart-modal" id="cartModal" onclick="closeCartModalOutside(event)">
    <div class="cart-modal-content">
        <h3>Tambah ke Keranjang</h3>
        <p>Tentukan kuantitas item yang ingin Anda pesan.</p>

        <form action="{{ route('keranjang.add', $produk->id_produk) }}" method="POST">
            @csrf
            <div class="qty-box">
                <button type="button" onclick="decreaseQty()">-</button>
                <input type="number" id="qty" name="qty" value="1" min="1" max="{{ $produk->stok }}">
                <button type="button" onclick="increaseQty()">+</button>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px;">
                Konfirmasi & Masukkan
            </button>
            
            <button type="button" class="btn-modal-close" onclick="closeCartModal()">
                Batal
            </button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('cartModal');
    const qtyInput = document.getElementById('qty');
    const maxStock = parseInt("{{ $produk->stok }}") || 1;

    function openCartModal() {
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('active');
        }, 10);
    }

    function closeCartModal() {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    function closeCartModalOutside(event) {
        if (event.target === modal) {
            closeCartModal();
        }
    }

    function increaseQty() {
        let currentVal = parseInt(qtyInput.value) || 1;
        if (currentVal < maxStock) {
            qtyInput.value = currentVal + 1;
        }
    }

    function decreaseQty() {
        let currentVal = parseInt(qtyInput.value) || 1;
        if (currentVal > 1) {
            qtyInput.value = currentVal - 1;
        }
    }
</script>
@endsection