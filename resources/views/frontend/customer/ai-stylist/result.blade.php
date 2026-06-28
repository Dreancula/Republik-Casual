@extends('frontend.layouts.app')

@section('title', 'AI Stylist Recommendation - Republik Casual')

@push('styles')
<style>
    /* ========================
        VARIABLES & BASE STYLE
       ======================== */
    :root {
        --rc-bg-dark: #0a0a0a;
        --rc-card-dark: rgba(18, 18, 18, 0.45);
        --rc-card-border: rgba(255, 255, 255, 0.06);
        --rc-accent: #EAE6DF;
        --rc-accent-rgb: 234, 230, 223;
        --rc-text-primary: #ffffff;
        --rc-text-secondary: #8e8e93;
        --transition-smooth: cubic-bezier(0.16, 1, 0.3, 1);
    }

    body {
        background-color: var(--rc-bg-dark);
        position: relative;
        overflow-x: hidden;
        letter-spacing: -0.01em;
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
        filter: blur(1px) brightness(0.9); 
    }

    .video-bg-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -1;
        background: radial-gradient(circle, rgba(0,0,0,0) 0%, rgba(10,10,10,0.95) 90%);
        pointer-events: none;
    }

    /* ========================
        SPLIT LAYOUT STYLE
       ======================== */
    .stylist-container {
        max-width: 1140px;
        margin: auto;
        position: relative;
        z-index: 1;
    }

    .stylist-header {
        margin-bottom: 35px;
    }

    .sub-badge {
        display: inline-block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 2px;
        padding: 6px 16px;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: var(--rc-accent);
        font-weight: 600;
        margin-bottom: 15px;
    }

    .stylist-header h1 {
        font-size: 36px;
        font-weight: 800;
        color: var(--rc-text-primary);
        letter-spacing: -0.8px;
    }

    /* LEFT COLUMN: FIXED OVERVIEW SIDEBAR */
    .summary-sidebar {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 20px;
        padding: 30px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        position: sticky;
        top: 30px;
    }

    .summary-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--rc-text-secondary);
        font-weight: 700;
        margin-bottom: 4px;
    }

    .summary-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--rc-text-primary);
        margin-bottom: 20px;
    }

    .summary-value.highlight {
        color: var(--rc-accent);
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 20px;
    }

    /* NEW: GENDER INDICATOR CHIP */
    .gender-indicator-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        color: var(--rc-text-primary);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .gender-indicator-box.laki-laki svg {
        color: #3b82f6; /* Soft Blue accent inside for Male code logic */
    }

    .gender-indicator-box.perempuan svg {
        color: #ec4899; /* Soft Pink accent inside for Female code logic */
    }

    .metrics-mini-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .metric-mini-box {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.04);
        padding: 12px;
        border-radius: 10px;
    }

    .metric-mini-box span {
        display: block;
        font-size: 10px;
        color: var(--rc-text-secondary);
        text-transform: uppercase;
    }

    .metric-mini-box strong {
        font-size: 15px;
        color: var(--rc-text-primary);
    }

    .custom-divider {
        border: 0;
        border-top: 1px solid var(--rc-card-border);
        margin: 20px 0;
    }

    .analysis-desc {
        color: var(--rc-text-secondary);
        font-size: 13px;
        line-height: 1.6;
        margin: 0;
    }

    /* ACTION BUTTONS */
    .btn-recalculate {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 46px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        background: transparent;
        color: var(--rc-text-primary);
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        margin-top: 20px;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-recalculate:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: var(--rc-accent);
        color: var(--rc-accent);
    }

    /* RIGHT COLUMN: LUXURY CATALOG CARDS */
    .product-row-layout {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .product-card-premium {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 16px;
        display: flex;
        overflow: hidden;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        transition: border-color 0.3s var(--transition-smooth);
    }

    .product-card-premium:hover {
        border-color: rgba(255, 255, 255, 0.15);
    }

    .prod-img-box {
        width: 180px;
        min-width: 180px;
        height: 180px;
        background: #121212;
    }

    .prod-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .prod-details-box {
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-grow: 1;
        gap: 20px;
    }

    .prod-info-left {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .prod-tag {
        display: inline-block;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1px;
        color: var(--rc-accent);
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .prod-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--rc-text-primary);
        margin-bottom: 8px;
    }

    .prod-price {
        font-size: 16px;
        font-weight: 700;
        color: var(--rc-text-primary);
    }

    /* KATALOG BIG REDIRECT BUTTON */
    .btn-global-catalog {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 54px;
        background: var(--rc-accent);
        color: #0a0a0a;
        font-weight: 700;
        font-size: 14px;
        border-radius: 12px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 10px;
        transition: all 0.3s var(--transition-smooth);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .btn-global-catalog:hover {
        background: var(--rc-text-primary);
        color: #0a0a0a;
        transform: translateY(-2px);
    }

    /* RESPONSIFITAS */
    @media (max-width: 991px) {
        .summary-sidebar {
            position: relative;
            top: 0;
            margin-bottom: 30px;
        }
        .product-card-premium {
            flex-direction: column;
        }
        .prod-img-box {
            width: 100%;
            height: 280px;
        }
        .prod-details-box {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
    }
</style>
@endpush

@section('content')

<div class="video-bg-container">
    <video autoplay loop muted playsinline>
        <source src="{{ asset('storage/video/background.mp4') }}" type="video/mp4">
    </video>
</div>
<div class="video-bg-overlay"></div>

<div class="container py-5">
    <div class="stylist-container">

        <div class="stylist-header text-center text-lg-start">
            <div class="sub-badge">Curated Result</div>
            <h1>AI Personal Stylist</h1>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="summary-sidebar">
                    <div class="summary-label">Style Model</div>
                    <div class="summary-value">{{ ucfirst($style) }}</div>

                    <!-- GENDER INDICATOR CHIP (BARU) -->
                    <div class="summary-label">Anatomy Orientation</div>
                    <div>
                        @if(($gender ?? 'laki-laki') == 'laki-laki')
                            <div class="gender-indicator-box laki-laki">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="5"></circle><path d="M14 10l7-7M14 3h7v7"></path></svg>
                                Laki-Laki
                            </div>
                        @else
                            <div class="gender-indicator-box perempuan">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="5"></circle><path d="M12 14v7M9 18h6"></path></svg>
                                Perempuan
                            </div>
                        @endif
                    </div>

                    <div class="metrics-mini-grid">
                        <div class="metric-mini-box">
                            <span>Tinggi</span>
                            <strong>{{ $tinggi }} cm</strong>
                        </div>
                        <div class="metric-mini-box">
                            <span>Berat</span>
                            <strong>{{ $berat }} kg</strong>
                        </div>
                    </div>

                    <div class="summary-label">Rekomendasi Ukuran</div>
                    <div class="summary-value highlight">{{ $size }}</div>

                    <div class="summary-label">Match Score</div>
                    <div class="summary-value" style="color: var(--rc-accent);">{{ $score }}/100</div>

                    <hr class="custom-divider">

                    <div class="summary-label">Total Kombinasi</div>
                    <div class="summary-value mb-3" style="font-weight: 800;">Rp {{ number_format($total,0,',','.') }}</div>

                    <div class="p-3" style="background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.04);">
                        <div class="summary-label" style="color: var(--rc-accent);">Analisis & Tips</div>
                        <p class="analysis-desc">{{ $tips }}</p>
                    </div>

                    <!-- TOMBOL HITUNG STYLE LAIN -->
                    <a href="{{ url()->previous() }}" class="btn-recalculate">
                        Coba Hitung Style Lain
                    </a>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="product-row-layout">
                    
                    @if($kaos)
                    <div class="product-card-premium">
                        <div class="prod-img-box">
                            <img src="{{ asset('storage/'.$kaos->foto_produk) }}" alt="{{ $kaos->nama_produk }}">
                        </div>
                        <div class="prod-details-box">
                            <div class="prod-info-left">
                                <span class="prod-tag">Premium Top</span>
                                <h4 class="prod-title">{{ $kaos->nama_produk }}</h4>
                                <div class="prod-price">Rp {{ number_format($kaos->harga,0,',','.') }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($celana)
                    <div class="product-card-premium">
                        <div class="prod-img-box">
                            <img src="{{ asset('storage/'.$celana->foto_produk) }}" alt="{{ $celana->nama_produk }}">
                        </div>
                        <div class="prod-details-box">
                            <div class="prod-info-left">
                                <span class="prod-tag">Essential Bottom</span>
                                <h4 class="prod-title">{{ $celana->nama_produk }}</h4>
                                <div class="prod-price">Rp {{ number_format($celana->harga,0,',','.') }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($jaket)
                    <div class="product-card-premium">
                        <div class="prod-img-box">
                            <img src="{{ asset('storage/'.$jaket->foto_produk) }}" alt="{{ $jaket->nama_produk }}">
                        </div>
                        <div class="prod-details-box">
                            <div class="prod-info-left">
                                <span class="prod-tag">Signature Outerwear</span>
                                <h4 class="prod-title">{{ $jaket->nama_produk }}</h4>
                                <div class="prod-price">Rp {{ number_format($jaket->harga,0,',','.') }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- TOMBOL UTAMA REDIRECT KE KATALOG UTAMA -->
                    <a href="{{ route('products') }}" class="btn-global-catalog">
                        CEK KATALOG PRODUK
                    </a>

                </div>
            </div>

        </div> 
    </div>
</div>

@endsection