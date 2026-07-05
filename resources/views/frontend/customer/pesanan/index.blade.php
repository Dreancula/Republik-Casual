@extends('frontend.layouts.app')

@section('title', 'Pesanan Saya - Republik Casual')

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
        MAIN LAYOUT & HEADER
    ======================== */
    .orders-container {
        max-width: 1200px;
        margin: auto;
        position: relative;
        z-index: 1;
        padding: 0 15px;
    }

    .page-header {
        margin-bottom: 40px;
    }

    .page-header h1 {
        font-size: 42px;
        font-weight: 900;
        margin-bottom: 10px;
        color: var(--rc-text-primary);
        letter-spacing: -0.5px;
    }

    .page-header p {
        color: var(--rc-text-secondary);
        font-size: 16px;
    }

    /* ========================
        FILTER TABS
    ======================== */
    .filter-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 36px;
    }

    .filter-tab {
        padding: 10px 20px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        color: var(--rc-text-secondary);
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--rc-card-border);
        transition: all 0.3s var(--transition-smooth);
    }

    .filter-tab:hover {
        color: var(--rc-text-primary);
        border-color: rgba(var(--rc-accent-rgb), 0.3);
    }

    .filter-tab.active {
        color: #000;
        background: var(--rc-accent);
        border-color: var(--rc-accent);
    }

    .filter-tab.komplain-tab {
        color: #fb7185;
        border-color: rgba(251, 113, 133, 0.3);
    }

    .filter-tab.komplain-tab:hover {
        border-color: rgba(251, 113, 133, 0.6);
    }

    .filter-tab.komplain-tab.active {
        color: #fff;
        background: #fb7185;
        border-color: #fb7185;
    }

    /* ========================
        ORDER CARD (GLASSMORPHISM)
    ======================== */
    .order-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 28px;
        margin-bottom: 24px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s var(--transition-smooth);
    }

    .order-card:hover {
        transform: translateY(-2px);
        border-color: rgba(var(--rc-accent-rgb), 0.2);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
    }

    .card-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .order-id {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 6px;
        color: var(--rc-text-primary);
        letter-spacing: 0.5px;
    }

    .order-date {
        color: var(--rc-text-secondary);
        font-size: 14px;
    }

    .order-divider {
        border: 0;
        border-top: 1px solid var(--rc-card-border);
        margin: 24px 0;
    }

    .price-label {
        color: var(--rc-text-secondary);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .price-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--rc-accent);
    }

    /* ========================
        STATUS BADGES
    ======================== */
    .status-badge {
        padding: 8px 16px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.25);
        color: #f59e0b;
    }

    .status-dibayar, .status-selesai {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.25);
        color: #10b981;
    }

    .status-diproses {
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.25);
        color: #8b5cf6;
    }

    .status-dikirim {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.25);
        color: #3b82f6;
    }

    .status-dibatalkan {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #ef4444;
    }

    /* ========================
        KOMPLAIN STATUS BADGES
    ======================== */
    .status-komplain-pending {
        background: rgba(251, 113, 133, 0.1);
        border: 1px solid rgba(251, 113, 133, 0.25);
        color: #fb7185;
    }

    .status-komplain-approved {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.25);
        color: #10b981;
    }

    .status-komplain-selesai {
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.25);
        color: #8b5cf6;
    }

    .status-komplain-rejected {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #ef4444;
    }

    /* ========================
        KOMPLAIN CARD EXTRAS
    ======================== */
    .komplain-products {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin: 16px 0;
    }

    .komplain-product-chip {
        padding: 6px 14px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--rc-card-border);
        font-size: 12px;
        color: var(--rc-text-secondary);
    }

    .komplain-product-chip strong {
        color: var(--rc-text-primary);
    }

    /* ========================
        BUTTONS & EMPTY STATE
    ======================== */
    .btn-detail {
        padding: 14px 24px;
        background: var(--rc-accent);
        color: #000000;
        text-decoration: none;
        border-radius: 16px;
        font-weight: 800;
        font-size: 14px;
        transition: all 0.3s var(--transition-smooth);
        box-shadow: 0 4px 12px rgba(var(--rc-accent-rgb), 0.1);
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        background: var(--rc-text-primary);
        box-shadow: 0 8px 20px rgba(var(--rc-accent-rgb), 0.25);
    }

    .btn-outline {
        padding: 14px 24px;
        border: 1px solid var(--rc-card-border);
        color: var(--rc-text-primary);
        text-decoration: none;
        border-radius: 16px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-outline:hover {
        border-color: rgba(var(--rc-accent-rgb), 0.3);
        background: rgba(255, 255, 255, 0.04);
    }

    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: var(--rc-text-secondary);
        font-size: 16px;
    }

    .empty-state i {
        font-size: 48px;
        color: rgba(var(--rc-accent-rgb), 0.3);
        margin-bottom: 20px;
        display: block;
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

<!-- MAIN CONTENT CONTAINER -->
<div class="orders-container">

    <div class="page-header">
        <h1>Pesanan Saya</h1>
        <p>Riwayat seluruh transaksi Anda di Republik Casual</p>
    </div>

    @php
        $currentStatus = request('status');
        $currentFilter = request('filter');
    @endphp

    {{-- FILTER TABS --}}
    <div class="filter-tabs">
        <a href="{{ route('pesanan.saya') }}"
           class="filter-tab {{ !$currentStatus && $currentFilter !== 'komplain' ? 'active' : '' }}">
            Semua
        </a>
        <a href="{{ route('pesanan.saya', ['status' => 'pending']) }}"
           class="filter-tab {{ $currentStatus === 'pending' ? 'active' : '' }}">
            Menunggu Bayar
        </a>
        <a href="{{ route('pesanan.saya', ['status' => 'dibayar']) }}"
           class="filter-tab {{ $currentStatus === 'dibayar' ? 'active' : '' }}">
            Dibayar
        </a>
        <a href="{{ route('pesanan.saya', ['status' => 'diproses']) }}"
           class="filter-tab {{ $currentStatus === 'diproses' ? 'active' : '' }}">
            Diproses
        </a>
        <a href="{{ route('pesanan.saya', ['status' => 'dikirim']) }}"
           class="filter-tab {{ $currentStatus === 'dikirim' ? 'active' : '' }}">
            Dikirim
        </a>
        <a href="{{ route('pesanan.saya', ['status' => 'selesai']) }}"
           class="filter-tab {{ $currentStatus === 'selesai' ? 'active' : '' }}">
            Selesai
        </a>
        @if($komplainCount > 0)
            <a href="{{ route('pesanan.saya', ['filter' => 'komplain']) }}"
               class="filter-tab komplain-tab {{ $currentFilter === 'komplain' ? 'active' : '' }}">
                Komplain ({{ $komplainCount }})
            </a>
        @endif
    </div>

    {{-- KOMPLAIN VIEW --}}
    @if($currentFilter === 'komplain')
        @forelse($komplainList as $komplain)
            <div class="order-card">
                <div class="card-row">
                    <div>
                        <div class="order-id" style="font-size:16px;">
                            Komplain — RC-{{ $komplain->id_pesanan }}
                        </div>
                        <div class="order-date">
                            <i class="fa-regular fa-calendar-days"></i> &nbsp;
                            {{ \Carbon\Carbon::parse($komplain->created_at)->translatedFormat('d F Y H:i') }}
                        </div>
                    </div>
                    <div>
                        @if($komplain->status_komplain == 'pending')
                            <span class="status-badge status-komplain-pending">
                                <i class="fa-solid fa-clock"></i> Menunggu Verifikasi
                            </span>
                        @elseif($komplain->status_komplain == 'approved' && !$komplain->no_resi_return)
                            <span class="status-badge status-komplain-approved">
                                <i class="fa-solid fa-circle-check"></i> Disetujui
                            </span>
                        @elseif($komplain->status_komplain == 'approved' && $komplain->no_resi_return)
                            <span class="status-badge status-komplain-approved">
                                <i class="fa-solid fa-truck-fast"></i> Dalam Pengiriman
                            </span>
                        @elseif($komplain->status_komplain == 'selesai')
                            <span class="status-badge status-komplain-selesai">
                                <i class="fa-solid fa-circle-check"></i> Selesai
                            </span>
                        @else
                            <span class="status-badge status-komplain-rejected">
                                <i class="fa-solid fa-circle-xmark"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                @if($komplain->detailKomplain->isNotEmpty())
                    <div class="komplain-products">
                        @foreach($komplain->detailKomplain as $dk)
                            <span class="komplain-product-chip">
                                <strong>{{ $dk->produk?->nama_produk ?? 'Produk' }}</strong> × {{ $dk->qty ?? 1 }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <hr class="order-divider">

                <div class="card-row">
                    <div class="order-date">
                        @if($komplain->deskripsi)
                            <i class="fa-regular fa-note-sticky"></i>
                            {{ \Illuminate\Support\Str::limit($komplain->deskripsi, 80) }}
                        @endif
                    </div>
                    <a href="{{ route('pesanan.saya.show', $komplain->id_pesanan) }}" class="btn-outline">
                        Lihat Detail &nbsp; <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-solid fa-message"></i>
                Tidak ada riwayat komplain.
            </div>
        @endforelse

    {{-- ORDER VIEW --}}
    @else
        @forelse($pesanans as $pesanan)
            <div class="order-card">
                
                <!-- TOP CARD ROW: ID & STATUS -->
                <div class="card-row">
                    <div>
                        <div class="order-id">
                            RC-{{ $pesanan->id_pesanan }}
                        </div>
                        <div class="order-date">
                            <i class="fa-regular fa-calendar-days"></i> &nbsp;
                            {{ \Carbon\Carbon::parse($pesanan->tgl_pesanan)->translatedFormat('d F Y H:i') }}
                        </div>
                    </div>

                    <div>
                        @if($pesanan->status_pesanan == 'pending')
                            <span class="status-badge status-pending">
                                <i class="fa-solid fa-clock"></i> Menunggu Pembayaran
                            </span>
                        @elseif($pesanan->status_pesanan == 'dibayar')
                            <span class="status-badge status-dibayar">
                                <i class="fa-solid fa-circle-check"></i> Sudah Dibayar
                            </span>
                        @elseif($pesanan->status_pesanan == 'diproses')
                            <span class="status-badge status-diproses">
                                <i class="fa-solid fa-arrows-spin fa-spin"></i> Sedang Diproses
                            </span>
                        @elseif($pesanan->status_pesanan == 'dikirim')
                            <span class="status-badge status-dikirim">
                                <i class="fa-solid fa-truck-fast"></i> Dalam Pengiriman
                            </span>
                        @elseif($pesanan->status_pesanan == 'selesai')
                            <span class="status-badge status-selesai">
                                <i class="fa-solid fa-circle-check"></i> Selesai
                            </span>
                        @else
                            <span class="status-badge status-dibatalkan">
                                <i class="fa-solid fa-circle-xmark"></i> Dibatalkan
                            </span>
                        @endif
                    </div>
                </div>

                <hr class="order-divider">

                @php $adaPengganti = $pesanan->pengiriman->where('jenis_pengiriman', 'pengganti')->isNotEmpty(); @endphp
                @if($adaPengganti)
                    <div style="margin-bottom:16px;padding:10px 16px;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:12px;font-size:13px;color:#10b981;">
                        <i class="fa-solid fa-rotate"></i> Ada pengiriman pengganti dari komplain —
                        <a href="{{ route('pesanan.saya.show', $pesanan->id_pesanan) }}" style="color:#10b981;text-decoration:underline;font-weight:600;">cek resi</a>
                    </div>
                @endif

                <!-- BOTTOM CARD ROW: TOTAL PRICE & BUTTON -->
                <div class="card-row">
                    <div>
                        <div class="price-label">Total Belanja</div>
                        <div class="price-value">
                            Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}
                        </div>
                    </div>

                    <a href="{{ route('pesanan.saya.show', $pesanan->id_pesanan) }}" class="btn-detail">
                        Lihat Detail &nbsp; <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>

            </div>
        @empty
            <!-- EMPTY STATE INTERFACE -->
            <div class="empty-state">
                <i class="fa-solid fa-box-open"></i>
                Belum ada data pesanan atau riwayat transaksi saat ini.
            </div>
        @endforelse
    @endif

</div>

@endsection
