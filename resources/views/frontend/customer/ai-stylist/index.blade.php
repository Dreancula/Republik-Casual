@extends('frontend.layouts.app')

@section('title', 'Personal Stylist - Republik Casual')

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
        MAIN CONTENT LAYOUT
       ======================== */
    .stylist-container {
        max-width: 960px;
        margin: auto;
        position: relative;
        z-index: 1;
    }

    .stylist-header {
        text-align: center;
        margin-bottom: 50px;
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
        margin-bottom: 24px;
    }

    .stylist-header h1 {
        font-size: 48px;
        font-weight: 800;
        color: var(--rc-text-primary);
        margin-bottom: 16px;
        letter-spacing: -0.8px;
    }

    .stylist-header p {
        color: var(--rc-text-secondary);
        max-width: 600px;
        margin: auto;
        line-height: 1.7;
        font-size: 15px;
    }

    /* ========================
        FORM & PANEL CARD
       ======================== */
    .stylist-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 20px;
        padding: 45px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .stylist-card label {
        display: block;
        margin-bottom: 12px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--rc-accent);
    }

    /* PREMIUM GENDER SELECTOR CARDS */
    .rc-gender-group {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 30px;
    }

    .rc-gender-card {
        position: relative;
        cursor: pointer;
    }

    .rc-gender-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .rc-gender-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        height: 54px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        background: rgba(20, 20, 20, 0.6);
        color: var(--rc-text-secondary);
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s var(--transition-smooth);
    }

    .rc-gender-card:hover .rc-gender-content {
        border-color: rgba(255, 255, 255, 0.2);
        color: var(--rc-text-primary);
    }

    .rc-gender-card input[type="radio"]:checked + .rc-gender-content {
        background: rgba(var(--rc-accent-rgb), 0.1);
        border-color: var(--rc-accent);
        color: var(--rc-accent);
        box-shadow: 0 0 15px rgba(var(--rc-accent-rgb), 0.05);
    }

    /* MATRIKS RESPONSIVE GRID UNTUK INPUT INPUT */
    .stylist-grid-inputs {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 24px;
    }

    /* FIX DEFINITIF UNTUK JARAK SELECT & TOMBOL GENERATE */
    .stylist-select-wrapper {
        margin-bottom: 45px !important; 
    }

    .stylist-input {
        width: 100%;
        height: 54px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 0 18px;
        background: rgba(20, 20, 20, 0.6);
        color: var(--rc-text-primary);
        font-size: 15px;
        outline: none;
        transition: all 0.3s var(--transition-smooth);
    }

    .stylist-input:focus {
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.02);
    }

    .stylist-select {
        width: 100%;
        height: 54px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 0 18px;
        background: rgba(20, 20, 20, 0.6);
        color: var(--rc-text-primary);
        font-size: 15px;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23EAE6DF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
        background-repeat: no-repeat;
        background-position: right 18px center;
        background-size: 16px;
        transition: all 0.3s var(--transition-smooth);
    }

    .stylist-select:focus {
        border-color: rgba(255, 255, 255, 0.3);
    }

    .btn-submit-ai {
        width: 100%;
        height: 56px;
        border: none;
        border-radius: 12px;
        background: var(--rc-accent);
        color: #0a0a0a;
        font-weight: 700;
        font-size: 15px;
        letter-spacing: 0.5px;
        cursor: pointer;
        box-shadow: 0 10px 30px rgba(var(--rc-accent-rgb), 0.15);
        transition: all 0.4s var(--transition-smooth);
    }

    .btn-submit-ai:hover {
        background: var(--rc-text-primary);
        transform: translateY(-1px);
        box-shadow: 0 15px 35px rgba(var(--rc-accent-rgb), 0.25);
    }

    /* ========================
        INFO GRID & CARDS
       ======================== */
    .info-card {
        background: rgba(18, 18, 18, 0.3);
        border: 1px solid var(--rc-card-border);
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .info-card h4 {
        font-size: 16px;
        margin-bottom: 10px;
        font-weight: 700;
        color: var(--rc-text-primary);
    }

    .info-card p {
        font-size: 14px;
        color: var(--rc-text-secondary);
        margin: 0;
        line-height: 1.6;
    }

    /* ========================
        EXPLANATION BOX
       ======================== */
    .explanation-box {
        margin-top: 50px;
        border-top: 1px solid var(--rc-card-border);
        padding-top: 35px;
    }

    .explanation-box h3 {
        font-size: 18px;
        margin-bottom: 20px;
        font-weight: 700;
        color: var(--rc-text-primary);
    }

    .explanation-box ul {
        color: var(--rc-text-secondary);
        line-height: 1.8;
        margin: 0;
        font-size: 14px;
        padding-left: 18px;
    }

    .explanation-box li {
        margin-bottom: 8px;
    }

    /* RESPONSIFITAS */
    @media (max-width: 768px) {
        .stylist-header h1 {
            font-size: 34px;
        }
        .stylist-card {
            padding: 24px;
        }
        .stylist-grid-inputs {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .rc-gender-group {
            gap: 10px;
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

<div class="container py-5">
    <div class="stylist-container">

        <!-- HEADER SECT -->
        <div class="stylist-header">
            <div class="sub-badge">
                Digital Tailor Service
            </div>
            <h1>Akurasi Anatomi & Gaya</h1>
            <p>Sistem kurasi cerdas kami akan menganalisis proporsi tubuh Anda untuk menghasilkan kombinasi lini produk esensial Republik Casual yang paling presisi.</p>
        </div>

        <!-- FORM CARD -->
        <div class="stylist-card">
            <form action="{{ route('ai.recommend') }}" method="POST">
                @csrf

                <!-- SELEKTOR GENDER (BARU) -->
                <div class="mb-4">
                    <label>Orientasi Anatomi / Gender</label>
                    <div class="rc-gender-group">
                        <label class="rc-gender-card">
                            <input type="radio" name="gender" value="laki-laki" required checked>
                            <div class="rc-gender-content">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin='round'><circle cx="10" cy="14" r="5"></circle><path d="M14 10l7-7M14 3h7v7"></path></svg>
                                Laki-Laki
                            </div>
                        </label>
                        <label class="rc-gender-card">
                            <input type="radio" name="gender" value="perempuan" required>
                            <div class="rc-gender-content">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin='round'><circle cx="12" cy="9" r="5"></circle><path d="M12 14v7M9 18h6"></path></svg>
                                Perempuan
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Menggunakan CSS Grid Murni untuk Menjamin Presisi Sejajar Desktop -->
                <div class="stylist-grid-inputs">
                    <div>
                        <label>Tinggi Badan (cm)</label>
                        <input type="number" name="tinggi" class="stylist-input" required placeholder="175" min="100" max="250">
                    </div>
                    <div>
                        <label>Berat Badan (kg)</label>
                        <input type="number" name="berat" class="stylist-input" required placeholder="68" min="30" max="200">
                    </div>
                </div>

                <!-- Pembungkus Khusus yang Dipaksa Renggang dari CSS Utama -->
                <div class="stylist-select-wrapper">
                    <label>Arsitektur Pakaian / Style</label>
                    <select name="style" class="stylist-select" required>
                        <option value="">Pilih Karakter Panduan</option>
                        <option value="streetwear">Streetwear</option>
                        <option value="oversized">Oversized Silhouette</option>
                        <option value="cargo">Cargo Utilitarian</option>
                        <option value="casual">Casual Daily</option>
                    </select>
                </div>

                <!-- Tombol Generate Utama -->
                <button type="submit" class="btn-submit-ai">
                    Generate Personalized Style
                </button>
            </form>
        </div>

        <!-- INFO GRID -->
        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="info-card">
                    <h4>Streetwear Keselarasan</h4>
                    <p>Fokus pada konstruksi tumpuk, struktur luaran tak kaku, dan potongan bawah fungsional yang dinamis.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="info-card">
                    <h4>Oversized Silhouette</h4>
                    <p>Menghasilkan volume yang seimbang tanpa menghilangkan proporsi asli anatomi tubuh Anda.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="info-card">
                    <h4>Casual Daily Architecture</h4>
                    <p>Pola berpakaian esensial yang minimalis, bersih, serta adaptif untuk multi-aktivitas harian.</p>
                </div>
            </div>
        </div>

        <!-- EXPLANATION BOX -->
        <div class="explanation-box">
            <h3>Metodologi Kurasi</h3>
            <ul>
                <li>Menerjemahkan parameter matriks dimensi tubuh ke skala ukuran produk asli.</li>
                <li>Menghubungkan komposisi warna atasan, bawahan, serta lapisan ketiga (jaket/luaran).</li>
                <li>Menghitung kecocokan volume kain berdasarkan berat dan tinggi subjek.</li>
                <li>Menyediakan analisis objektif di balik penataan arsitektur pakaian yang direkomendasikan.</li>
            </ul>
        </div>

    </div>
</div>

@endsection