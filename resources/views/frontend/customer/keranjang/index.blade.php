@extends('frontend.layouts.app')

@section('title', 'Keranjang Belanja - Republik Casual')

@push('styles')
<style>
    /* ========================
        VARIABLES & BASE STYLE
    ======================== */
    :root {
        --rc-bg-dark: #0a0a0a;
        --rc-card-dark: rgba(18, 18, 18, 0.5);
        --rc-card-border: rgba(255, 255, 255, 0.08);
        --rc-accent: #EAE6DF;
        --rc-accent-rgb: 234, 230, 223;
        --rc-text-primary: #ffffff;
        --rc-text-secondary: #999999;
        --rc-danger: #dc2626;
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
        CART CONTAINER LAYOUT
    ======================== */
    .cart-container {
        max-width: 1400px;
        margin: auto;
        position: relative;
        z-index: 1;
        padding: 0 15px;
    }

    .cart-header {
        margin-bottom: 40px;
    }

    .cart-header h1 {
        font-size: 42px;
        font-weight: 900;
        color: var(--rc-text-primary);
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .cart-header p {
        color: var(--rc-text-secondary);
        font-size: 16px;
    }

    /* ========================
        CART ITEM LAYOUT
    ======================== */
    .cart-grid {
        display: grid;
        gap: 20px;
    }

    .cart-item {
        display: flex;
        gap: 24px;
        align-items: center;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 24px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: border-color 0.3s var(--transition-smooth);
    }

    .cart-item:hover {
        border-color: rgba(255, 255, 255, 0.15);
    }

    .cart-item img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 18px;
        border: 1px solid var(--rc-card-border);
    }

    .item-info {
        flex: 1;
    }

    .item-info h3 {
        font-size: 22px;
        font-weight: 800;
        color: var(--rc-text-primary);
        margin-bottom: 8px;
        letter-spacing: -0.3px;
    }

    .item-price {
        color: var(--rc-accent);
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 18px;
    }

    /* ========================
        QUANTITY FORM
    ======================== */
    .qty-form {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .qty-input {
        width: 80px;
        height: 42px;
        background: #111111;
        color: var(--rc-text-primary);
        border: 1px solid var(--rc-card-border);
        border-radius: 12px;
        text-align: center;
        font-weight: 700;
        font-size: 15px;
        outline: none;
        transition: border-color 0.3s var(--transition-smooth);
    }

    .qty-input:focus {
        border-color: var(--rc-accent);
    }

    .btn-update {
        height: 42px;
        padding: 0 20px;
        border: none;
        border-radius: 12px;
        background: transparent;
        color: var(--rc-text-primary);
        border: 1px solid var(--rc-card-border);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-update:hover {
        background: var(--rc-text-primary);
        color: #000000;
        border-color: var(--rc-text-primary);
    }

    /* ========================
        ITEM ACTIONS & SUBTOTAL
    ======================== */
    .item-actions {
        text-align: right;
        min-width: 220px;
    }

    .subtotal-label {
        color: var(--rc-text-secondary);
        font-size: 14px;
        margin-bottom: 6px;
    }

    .subtotal-amount {
        color: var(--rc-text-primary);
        font-size: 24px;
        font-weight: 900;
        margin-bottom: 20px;
    }

    .btn-remove {
        width: 100%;
        height: 44px;
        border: none;
        border-radius: 12px;
        background: rgba(220, 38, 38, 0.1);
        color: #f87171;
        border: 1px solid rgba(220, 38, 38, 0.2);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-remove:hover {
        background: var(--rc-danger);
        color: var(--rc-text-primary);
        border-color: var(--rc-danger);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    /* ========================
        SUMMARY CART FOOTER
    ======================== */
    .cart-summary {
        margin-top: 30px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 30px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .summary-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .total-label {
        color: var(--rc-text-secondary);
        font-size: 15px;
        margin-bottom: 6px;
    }

    .total-amount {
        font-size: 38px;
        font-weight: 900;
        color: var(--rc-accent);
        letter-spacing: -0.5px;
    }

    .btn-checkout {
        display: inline-block;
        padding: 18px 36px;
        border-radius: 16px;
        background: var(--rc-accent);
        color: #000000;
        font-weight: 800;
        font-size: 16px;
        text-decoration: none;
        box-shadow: 0 8px 24px rgba(var(--rc-accent-rgb), 0.2);
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-checkout:hover {
        background: var(--rc-text-primary);
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(var(--rc-accent-rgb), 0.35);
    }

    /* ========================
        EMPTY STATE VIEW
    ======================== */
    .cart-empty {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 90px 30px;
        text-align: center;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .cart-empty i {
        font-size: 64px;
        color: var(--rc-accent);
        margin-bottom: 24px;
        opacity: 0.8;
    }

    .cart-empty h2 {
        font-size: 28px;
        font-weight: 800;
        color: var(--rc-text-primary);
        margin-bottom: 12px;
    }

    .cart-empty p {
        color: var(--rc-text-secondary);
        font-size: 15px;
        margin-bottom: 30px;
    }

    .btn-shop {
        display: inline-block;
        padding: 16px 32px;
        background: var(--rc-accent);
        color: #000000;
        border-radius: 16px;
        font-weight: 800;
        font-size: 15px;
        text-decoration: none;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-shop:hover {
        background: var(--rc-text-primary);
        transform: translateY(-2px);
    }

    /* RESPONSIFITAS */
    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }
        .qty-form {
            justify-content: center;
            margin-bottom: 15px;
        }
        .item-actions {
            text-align: center;
            width: 100%;
            min-width: unset;
        }
        .summary-flex {
            flex-direction: column;
            text-align: center;
        }
        .btn-checkout {
            width: 100%;
            text-align: center;
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

<div class="cart-container">

    <!-- HEADER SECT -->
    <div class="cart-header">
        <h1>Keranjang Belanja</h1>
        <p>Produk yang telah Anda pilih akan muncul di sini.</p>
    </div>

    @if(count(session('cart', [])) > 0)

        @php $total = 0; @endphp

        <!-- MAIN PRODUCTS GRID -->
        <div class="cart-grid">

            @foreach(session('cart') as $item)
                @php
                    $subtotal = $item['harga'] * $item['qty'];
                    $total += $subtotal;
                @endphp

                <div class="cart-item">
                    <!-- PRODUCT IMAGE -->
                    <img src="{{ asset('storage/'.$item['foto_produk']) }}" alt="{{ $item['nama_produk'] }}">

                    <!-- PRODUCT DATA & EDIT QTY -->
                    <div class="item-info">
                        <h3>{{ $item['nama_produk'] }}</h3>
                        <div class="item-price">Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>

                        <form action="{{ route('keranjang.update', $item['id_produk']) }}" method="POST" class="qty-form">
                            @csrf
                            <input type="number" name="qty" min="1" value="{{ $item['qty'] }}" class="qty-input">
                            <button type="submit" class="btn-update">Update</button>
                        </form>
                    </div>

                    <!-- TOTAL PER ITEM & REMOVE ACTION -->
                    <div class="item-actions">
                        <div class="subtotal-label">Subtotal</div>
                        <div class="subtotal-amount">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>

                        <form action="{{ route('keranjang.remove', $item['id_produk']) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-remove">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach 

        </div>

        <!-- RECAP FOOTER BOX -->
        <div class="cart-summary">
            <div class="summary-flex">
                <div>
                    <div class="total-label">Total Belanja</div>
                    <div class="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>
                <a href="{{ route('checkout.index') }}" class="btn-checkout">
                    Checkout Sekarang
                </a>
            </div>
        </div>

    @else

        <!-- EMPTY STATE SCREEN -->
        <div class="cart-empty">
            <i class="fa-solid fa-bag-shopping"></i>
            <h2>Keranjang Masih Kosong</h2>
            <p>Belum ada produk yang ditambahkan ke keranjang belanja Anda.</p>
            <a href="{{ route('products') }}" class="btn-shop">
                Belanja Sekarang
            </a>
        </div>

    @endif

</div>

@endsection