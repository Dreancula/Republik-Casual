@extends('frontend.layouts.app')

@section('title', 'Komplain Saya - Republik Casual')

@push('styles')
<style>
    .video-bg-container {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -2; overflow: hidden; pointer-events: none;
    }
    .video-bg-container video {
        width: 100%; height: 100%; object-fit: cover;
        filter: blur(1px) brightness(1);
    }
    .video-bg-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -1;
        background: radial-gradient(circle, rgba(0,0,0,0.1) 0%, rgba(10,10,10,0.9) 85%);
        pointer-events: none;
    }

    .page-header {
        margin-bottom: 40px;
    }
    .page-header h1 {
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 10px;
        color: var(--rc-text-primary);
        letter-spacing: -1px;
    }
    .page-header p {
        color: var(--rc-text-secondary);
        font-size: 15px;
    }

    .komplain-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: var(--radius-lg);
        padding: 28px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s var(--transition-smooth);
    }
    .komplain-card:hover {
        border-color: rgba(var(--rc-accent-rgb), 0.15);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
    }

    .card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .komplain-id {
        font-size: 18px;
        font-weight: 700;
        color: var(--rc-text-primary);
        letter-spacing: 0.3px;
    }
    .komplain-date {
        color: var(--rc-text-secondary);
        font-size: 13px;
        margin-top: 4px;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .status-pending { background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .status-approved { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; }
    .status-dikirim { background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); color: #3b82f6; }
    .status-terima { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; }
    .status-ditolak { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; }

    .card-divider {
        border: 0;
        border-top: 1px solid var(--rc-card-border);
        margin: 20px 0;
    }

    .product-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 0;
    }
    .product-item:not(:last-child) {
        border-bottom: 1px solid rgba(255,255,255,0.03);
        margin-bottom: 8px;
    }
    .product-item img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--rc-card-border);
    }
    .product-item .name {
        color: var(--rc-text-primary);
        font-weight: 600;
        font-size: 14px;
    }
    .product-item .qty {
        color: var(--rc-text-secondary);
        font-size: 13px;
    }

    .info-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }
    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--rc-card-border);
        color: var(--rc-text-secondary);
    }
    .info-chip strong {
        color: var(--rc-text-primary);
        font-family: monospace;
        letter-spacing: 0.5px;
    }
    .info-chip.green {
        border-color: rgba(16,185,129,0.25);
    }

    .photo-strip {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 12px;
    }
    .photo-strip a {
        display: block;
        width: 64px;
        height: 64px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--rc-card-border);
        transition: all 0.2s var(--transition-smooth);
        position: relative;
    }
    .photo-strip a:hover {
        transform: scale(1.08);
        border-color: var(--rc-accent);
        z-index: 2;
    }
    .photo-strip a img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .photo-strip a .count-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
    }

    .btn-detail {
        padding: 12px 20px;
        background: var(--rc-accent);
        color: #000;
        text-decoration: none;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 13px;
        transition: all 0.3s var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .btn-detail:hover {
        background: #fff;
        color: #000;
        transform: translateY(-1px);
    }

    .card-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 16px;
    }

    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: var(--radius-lg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: var(--rc-text-secondary);
    }
    .empty-state i {
        font-size: 48px;
        color: rgba(var(--rc-accent-rgb), 0.2);
        margin-bottom: 16px;
        display: block;
    }
    .empty-state h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--rc-text-primary);
        margin-bottom: 8px;
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

<div style="max-width: 820px; margin: 0 auto;">

    <div class="page-header">
        <h1>Komplain Saya</h1>
        <p>Pantau status pengajuan retur dan pengiriman pengganti Anda</p>
    </div>

    @if(session('success'))
        <div class="alert d-flex align-items-center gap-2 py-2 px-3 mb-4" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 12px; font-size: 14px;">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @forelse($komplainList as $komplain)
        <div class="komplain-card">
            {{-- TOP ROW: ID + Status --}}
            <div class="card-top">
                <div>
                    <div class="komplain-id">#RC-{{ $komplain->id_pesanan }}</div>
                    <div class="komplain-date">
                        <i class="fa-regular fa-calendar-days me-1"></i>
                        {{ \Carbon\Carbon::parse($komplain->created_at)->translatedFormat('d F Y H:i') }}
                    </div>
                </div>
                <div>
                    @php
                        $statusLabel = 'Ditolak';
                        $statusClass = 'status-ditolak';
                        $statusIcon = 'fa-solid fa-circle-xmark';
                        if ($komplain->status_komplain == 'pending') {
                            $statusLabel = 'Menunggu Verifikasi';
                            $statusClass = 'status-pending';
                            $statusIcon = 'fa-solid fa-clock';
                        } elseif ($komplain->status_komplain == 'approved' && !$komplain->no_resi_return) {
                            $statusLabel = 'Disetujui';
                            $statusClass = 'status-approved';
                            $statusIcon = 'fa-solid fa-circle-check';
                        } elseif ($komplain->status_komplain == 'approved' && $komplain->no_resi_return) {
                            $statusLabel = 'Dalam Pengiriman';
                            $statusClass = 'status-dikirim';
                            $statusIcon = 'fa-solid fa-truck-fast';
                        } elseif ($komplain->status_komplain == 'selesai') {
                            $statusLabel = 'Selesai';
                            $statusClass = 'status-terima';
                            $statusIcon = 'fa-solid fa-circle-check';
                        }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        <i class="{{ $statusIcon }}"></i> {{ $statusLabel }}
                    </span>
                </div>
            </div>

            {{-- PRODUCTS --}}
            @foreach($komplain->detailKomplain as $dk)
                <div class="product-item">
                    <img src="{{ asset('storage/' . ($dk->produk->foto_produk ?? $dk->produk->foto ?? 'produk/default.jpg')) }}" alt="">
                    <div>
                        <div class="name">{{ $dk->produk->nama_produk ?? 'Produk tidak tersedia' }}</div>
                        <div class="qty">{{ $komplain->jenis_komplain }} &middot; x{{ $dk->qty }}</div>
                    </div>
                </div>
            @endforeach

            {{-- PHOTO THUMBNAILS --}}
            @php $allPhotos = $komplain->detailKomplain->flatMap(fn($dk) => $dk->foto_array); @endphp
            @if(count($allPhotos) > 0)
                <div class="photo-strip">
                    @php $shown = 0; $total = count($allPhotos); @endphp
                    @foreach($allPhotos as $foto)
                        @php $shown++; @endphp
                        @if($shown <= 5)
                            <a href="{{ asset('storage/' . $foto) }}" target="_blank" title="Foto bukti {{ $shown }}">
                                <img src="{{ asset('storage/' . $foto) }}" alt="Foto {{ $shown }}">
                            </a>
                        @elseif($shown == 6)
                            <a href="{{ asset('storage/' . $foto) }}" target="_blank">
                                <img src="{{ asset('storage/' . $foto) }}" alt="">
                                <span class="count-overlay">+{{ $total - 5 }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif

            <hr class="card-divider">

            {{-- INFO CHIPS --}}
            <div class="info-chips">
                @if($komplain->no_resi_return)
                    <span class="info-chip">
                        <i class="fa-solid fa-truck-ramp-box"></i> Resi Retur: <strong>{{ $komplain->no_resi_return }}</strong>
                    </span>
                @endif
                @if($komplain->foto_return)
                    <span class="info-chip">
                        <i class="fa-regular fa-image"></i>
                        <a href="{{ asset('storage/' . $komplain->foto_return) }}" target="_blank" style="color: var(--rc-text-secondary); text-decoration: underline;">Bukti Retur</a>
                    </span>
                @endif
                @php
                    $returOrder = $komplain->returPesanan;
                    $pengirimanPengganti = $returOrder?->pengiriman?->where('jenis_pengiriman', 'pengganti')?->first();
                @endphp
                @if($pengirimanPengganti)
                    <span class="info-chip green">
                        <i class="fa-solid fa-rotate" style="color: #10b981;"></i>
                        Pengganti: <strong>{{ $pengirimanPengganti->nama_kurir }} - {{ $pengirimanPengganti->no_resi }}</strong>
                    </span>
                @endif
            </div>

            {{-- BOTTOM ACTION --}}
            <div class="card-bottom">
                <div style="color: var(--rc-text-secondary); font-size: 12px;">
                    <i class="fa-regular fa-receipt me-1"></i> Pesanan #RC-{{ $komplain->id_pesanan }}
                </div>
                <a href="{{ route('pesanan.saya.show', $komplain->id_pesanan) }}" class="btn-detail">
                    Lihat Detail <i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i>
                </a>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h3>Belum Ada Komplain</h3>
            <p style="margin-top: 8px; max-width: 400px; margin-left: auto; margin-right: auto;">Anda dapat mengajukan komplain dari halaman detail pesanan setelah pesanan selesai.</p>
            <a href="{{ route('pesanan.saya') }}" class="btn-detail" style="margin-top: 20px;">
                <i class="fa-solid fa-arrow-left"></i> Ke Pesanan Saya
            </a>
        </div>
    @endforelse

</div>

@endsection
