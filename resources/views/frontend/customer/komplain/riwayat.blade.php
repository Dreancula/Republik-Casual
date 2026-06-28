@extends('frontend.layouts.app')

@section('title', 'Komplain Saya - Republik Casual')

@push('styles')
<style>
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

    .komplain-container {
        max-width: 900px;
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

    .komplain-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 28px;
        margin-bottom: 24px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s var(--transition-smooth);
    }
    .komplain-card:hover {
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

    .komplain-id {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 6px;
        color: var(--rc-text-primary);
        letter-spacing: 0.5px;
    }
    .komplain-date {
        color: var(--rc-text-secondary);
        font-size: 14px;
    }

    .komplain-divider {
        border: 0;
        border-top: 1px solid var(--rc-card-border);
        margin: 20px 0;
    }

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
    .status-pending { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.25); color: #f59e0b; }
    .status-approved { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); color: #10b981; }
    .status-dikirim { background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.25); color: #3b82f6; }
    .status-terima { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); color: #10b981; }
    .status-ditolak { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); color: #ef4444; }

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
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-detail:hover {
        transform: translateY(-2px);
        background: var(--rc-text-primary);
        box-shadow: 0 8px 20px rgba(var(--rc-accent-rgb), 0.25);
        color: #000;
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

    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 13px;
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
</style>
@endpush

@section('content')

<div class="video-bg-container">
    <video autoplay loop muted playsinline>
        <source src="{{ asset('storage/video/background.mp4') }}" type="video/mp4">
    </video>
</div>
<div class="video-bg-overlay"></div>

<div class="komplain-container">

    <div class="page-header">
        <h1>Komplain Saya</h1>
        <p>Pantau status pengajuan retur dan pengiriman pengganti Anda</p>
    </div>

    @if(session('success'))
        <div class="alert alert-dismissible fade show mb-4 d-flex align-items-center gap-2" role="alert" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 12px;">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @forelse($komplainList as $komplain)
        <div class="komplain-card">
            <div class="card-row">
                <div>
                    <div class="komplain-id">
                        #RC-{{ $komplain->id_pesanan }}
                    </div>
                    <div class="komplain-date">
                        <i class="fa-regular fa-calendar-days"></i> &nbsp;
                        {{ \Carbon\Carbon::parse($komplain->created_at)->translatedFormat('d F Y H:i') }}
                    </div>
                </div>

                <div>
                    @php
                        $statusLabel = 'Ditolak';
                        $statusClass = 'status-ditolak';
                        $statusIcon = 'fa-solid fa-circle-xmark';

                        if ($komplain->status_komplain == 'pending') {
                            $statusLabel = 'Menunggu diverifikasi admin';
                            $statusClass = 'status-pending';
                            $statusIcon = 'fa-solid fa-clock';
                        } elseif ($komplain->status_komplain == 'approved' && !$komplain->no_resi_return) {
                            $statusLabel = 'Disetujui';
                            $statusClass = 'status-approved';
                            $statusIcon = 'fa-solid fa-circle-check';
                        } elseif ($komplain->status_komplain == 'approved' && $komplain->no_resi_return) {
                            $statusLabel = 'Sedang dikirim';
                            $statusClass = 'status-dikirim';
                            $statusIcon = 'fa-solid fa-truck-fast';
                        } elseif ($komplain->status_komplain == 'selesai') {
                            $statusLabel = 'Sudah diterima';
                            $statusClass = 'status-terima';
                            $statusIcon = 'fa-solid fa-circle-check';
                        }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        <i class="{{ $statusIcon }}"></i> {{ $statusLabel }}
                    </span>
                </div>
            </div>

            <hr class="komplain-divider">

            <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 12px;">
                <div>
                    @foreach($komplain->detailKomplain as $dk)
                        <div style="color: var(--rc-text-primary); font-weight: 700; font-size: 16px; margin-bottom: 2px;">
                            {{ $dk->produk->nama_produk ?? 'Produk tidak tersedia' }}
                            <span style="color: var(--rc-text-secondary); font-weight: 400;">x{{ $dk->qty }}</span>
                        </div>
                    @endforeach
                    <div style="color: var(--rc-text-secondary); font-size: 13px; margin-bottom: 10px; margin-top: 4px;">
                        <i class="fa-regular fa-receipt"></i> Pesanan #RC-{{ $komplain->id_pesanan }}
                        &middot;
                        <i class="fa-regular fa-message"></i> {{ $komplain->jenis_komplain }}
                    </div>

                    <div class="d-flex flex-wrap" style="gap: 8px;">
                        @php $allPhotos = $komplain->detailKomplain->flatMap(fn($dk) => $dk->foto_array); @endphp
                        @if(count($allPhotos) > 0)
                            <span class="info-chip">
                                <i class="fa-regular fa-images"></i> 
                                @foreach($allPhotos as $foto)
                                    <a href="{{ asset('storage/' . $foto) }}" target="_blank" style="color: var(--rc-text-secondary);">Foto{{ $loop->iteration }}</a>@if(!$loop->last), @endif
                                @endforeach
                            </span>
                        @endif

                        @if($komplain->no_resi_return)
                            <span class="info-chip">
                                <i class="fa-solid fa-truck-ramp-box"></i> Resi Retur: <strong>{{ $komplain->no_resi_return }}</strong>
                            </span>
                        @endif

                        @if($komplain->foto_return)
                            <span class="info-chip">
                                <i class="fa-regular fa-image"></i>
                                <a href="{{ asset('storage/' . $komplain->foto_return) }}" target="_blank" style="color: var(--rc-text-secondary);">Bukti Retur</a>
                            </span>
                        @endif

                        @php
                            $returOrder = $komplain->returPesanan;
                            $pengirimanPengganti = $returOrder?->pengiriman?->where('jenis_pengiriman', 'pengganti')?->first();
                        @endphp
                        @if($pengirimanPengganti)
                            <span class="info-chip" style="border-color: rgba(16,185,129,0.3);">
                                <i class="fa-solid fa-rotate" style="color: #10b981;"></i>
                                Pengganti: <strong>{{ $pengirimanPengganti->nama_kurir }} - {{ $pengirimanPengganti->no_resi }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <a href="{{ route('pesanan.saya.show', $komplain->id_pesanan) }}" class="btn-detail">
                    Lihat Detail <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fa-solid fa-circle-exclamation"></i>
            Belum ada pengajuan komplain
            <p style="margin-top: 8px; color: var(--rc-text-secondary);">Anda dapat mengajukan komplain dari halaman detail pesanan setelah pesanan selesai.</p>
            <a href="{{ route('pesanan.saya') }}" class="btn-detail" style="margin-top: 16px; display: inline-flex;">
                <i class="fa-solid fa-arrow-left"></i> Ke Pesanan Saya
            </a>
        </div>
    @endforelse

</div>

@endsection
