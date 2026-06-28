@extends('frontend.layouts.app')

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan . ' - Republik Casual')

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
        MAIN TWO-COLUMN LAYOUT
    ======================== */
    .detail-container {
        max-width: 1200px;
        margin: auto;
        position: relative;
        z-index: 1;
        padding: 0 15px;
    }

    .page-header {
        margin-bottom: 35px;
    }

    .page-header h1 {
        font-size: 40px;
        font-weight: 900;
        color: var(--rc-text-primary);
        letter-spacing: -0.5px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 30px;
        align-items: start;
    }

    /* ========================
        CARDS & SECTIONS
    ======================== */
    .rc-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 28px;
        margin-bottom: 24px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .rc-card h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--rc-text-primary);
        margin-bottom: 20px;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--rc-card-border);
        padding-bottom: 14px;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
    }

    .info-item span:first-child {
        color: var(--rc-text-secondary);
    }

    .info-item span:last-child {
        color: var(--rc-text-primary);
        font-weight: 600;
    }

    .highlight-resi {
        color: var(--rc-accent) !important;
        font-family: monospace;
        letter-spacing: 0.5px;
    }

    /* ========================
        COUNTDOWN & PAY BLOCK
    ======================== */
    .payment-alert {
        background: rgba(245, 158, 11, 0.06);
        border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 24px;
        padding: 28px;
        margin-bottom: 24px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        text-align: center;
    }

    .payment-alert h3 {
        font-size: 20px;
        font-weight: 800;
        color: #f59e0b;
        margin-bottom: 6px;
    }

    .payment-alert p {
        color: var(--rc-text-secondary);
        font-size: 14px;
        margin-bottom: 16px;
    }

    .countdown-timer {
        font-size: 46px;
        font-weight: 900;
        color: var(--rc-text-primary);
        margin-bottom: 24px;
        letter-spacing: 1px;
        font-family: monospace;
    }

    .btn-pay {
        width: 100%;
        max-width: 240px;
        padding: 16px 32px;
        border: none;
        border-radius: 16px;
        background: var(--rc-accent);
        color: #000000;
        font-weight: 800;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s var(--transition-smooth);
        box-shadow: 0 8px 24px rgba(var(--rc-accent-rgb), 0.2);
    }

    .btn-pay:hover:not(:disabled) {
        transform: translateY(-2px);
        background: var(--rc-text-primary);
        box-shadow: 0 12px 30px rgba(var(--rc-accent-rgb), 0.35);
    }

    .btn-pay:disabled {
        background: #222;
        color: #555;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* ========================
        COMPLAINT BUTTON STYLE
    ======================== */
    .complaint-container {
        margin-top: 24px;
        border-top: 1px solid var(--rc-card-border);
        padding-top: 20px;
    }

    .btn-complaint {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        background: transparent;
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 16px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-complaint:hover {
        background: rgba(239, 68, 68, 0.06);
        border-color: #ef4444;
        transform: translateY(-1px);
    }

    /* ========================
        PRODUCT ITEMS & TOTALS
    ======================== */
    .product-item {
        display: flex;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .product-item:last-of-type {
        border-bottom: none;
        padding-bottom: 0;
    }

    .product-item img {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        object-fit: cover;
        border: 1px solid var(--rc-card-border);
    }

    .product-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .product-name {
        font-weight: 700;
        font-size: 15px;
        color: var(--rc-text-primary);
        margin-bottom: 4px;
    }

    .product-qty {
        color: var(--rc-text-secondary);
        font-size: 13px;
        margin-bottom: 4px;
    }

    .product-price {
        color: var(--rc-accent);
        font-weight: 700;
        font-size: 14px;
    }

    .pricing-divider {
        border: 0;
        border-top: 1px solid var(--rc-card-border);
        margin: 20px 0;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
        color: var(--rc-text-secondary);
    }

    .price-row.grand-total {
        font-size: 22px;
        font-weight: 900;
        color: var(--rc-text-primary);
        margin-top: 15px;
        margin-bottom: 0;
    }

    .price-row strong {
        color: var(--rc-text-primary);
    }

    .price-row.grand-total strong {
        color: var(--rc-accent);
    }

    /* ========================
        STATUS BADGES
    ======================== */
    .status-badge {
        padding: 4px 12px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 12px;
    }
    .status-PENDING { color: #f59e0b; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); }
    .status-DIBAYAR, .status-SELESAI { color: #10b981; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-DIPROSES { color: #8b5cf6; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.2); }
    .status-DIKIRIM { color: #3b82f6; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); }

    /* RESPONSIVE */
    @media(max-width: 992px) {
        .detail-grid {
            grid-template-columns: 1fr;
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

<div class="detail-container">

    <div class="page-header">
        <h1>Detail Pesanan</h1>
    </div>

    <div class="detail-grid">
        
        <!-- LEFT COLUMN: DATA & ALERT -->
        <div>
            <!-- COUNTDOWN PAY BLOCK -->
            @if($pesanan->status_pesanan == 'pending' && $pesanan->pembayaran && $pesanan->pembayaran->status_pembayaran == 'pending')
                <div class="payment-alert">
                    <h3>Menunggu Pembayaran</h3>
                    <p>Selesaikan transaksi Anda sebelum batas waktu berakhir:</p>
                    <div id="countdown" class="countdown-timer">--:--</div>
                    <button id="pay-button" class="btn-pay">Bayar Sekarang</button>
                    <button id="retry-button" class="btn-pay" style="display: none; margin-top: 10px; background: transparent; border: 1px solid var(--rc-card-border); color: var(--rc-text-secondary); height: 42px; font-size: 13px; text-transform: none; letter-spacing: 0.5px;">
                        <i class="fa-solid fa-rotate me-2"></i> Ganti Metode Pembayaran
                    </button>
                </div>
            @endif

            <!-- ORDER SUMMARY CARD -->
            <div class="rc-card">
                <h3><i class="fa-solid fa-receipt"></i> Informasi Transaksi</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span>No. Pesanan</span>
                        <span>RC-{{ $pesanan->id_pesanan }}</span>
                    </div>
                    <div class="info-item">
                        <span>Tanggal Pembelian</span>
                        <span>{{ \Carbon\Carbon::parse($pesanan->tgl_pesanan)->translatedFormat('d F Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span>Status Pesanan</span>
                        <span>
                            <span class="status-badge status-{{ strtoupper($pesanan->status_pesanan) }}">
                                {{ strtoupper($pesanan->status_pesanan) }}
                            </span>
                        </span>
                    </div>
                </div>

                <!-- CONDITION FOR COMPLAINT BUTTON -->
                @php
                    $komplainAktif = $pesanan->komplain->first();
                    $waktuSelesai = $pesanan->updated_at;
                    $batasMenit = 30;
                    $masihBisaKomplain = now()->diffInMinutes($waktuSelesai) < $batasMenit;
                @endphp

                @if($pesanan->status_pesanan == 'selesai' && $masihBisaKomplain)
    <div class="complaint-container">

        @if($komplainAktif)
            <div class="alert alert-info py-2 px-3 d-inline-block text-sm">
                <i class="fa-solid fa-circle-info"></i> Status Komplain: 
                <strong>{{ strtoupper($komplainAktif->status_komplain) }}</strong>
                @if($komplainAktif->status_komplain == 'approved')
                    — Produk: {{ $komplainAktif->produk->nama_produk ?? 'Produk' }} ({{ $komplainAktif->qty }} pcs)
                @endif
            </div>

            @if($komplainAktif->status_komplain == 'approved' && !$komplainAktif->no_resi_return)
                <form action="{{ route('komplain.konfirmasi-retur', $komplainAktif->id_komplain) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label class="text-muted small d-block mb-1">Upload Bukti Barang Sudah Diserahkan ke Kurir</label>
                        <input type="file" name="foto_return" class="form-control form-control-sm" accept="image/*" required style="max-width: 300px; background: rgba(0,0,0,0.4); color: #fff; border: 1px solid rgba(255,255,255,0.1);">
                    </div>
                    <button type="submit" class="btn btn-sm" style="background: #EAE6DF; color: #000; font-weight: 700; border-radius: 30px; border: none;">
                        <i class="fa-solid fa-truck-ramp-box"></i> Konfirmasi Kirim Retur
                    </button>
                    <small class="text-muted d-block mt-1">No. Resi retur akan di-generate otomatis</small>
                </form>
            @elseif($komplainAktif->status_komplain == 'approved' && $komplainAktif->no_resi_return)
                <div class="mt-2">
                    <small class="text-success d-block">
                        <i class="fa-solid fa-check-circle"></i> Retur terkonfirmasi — No. Resi: <strong>{{ $komplainAktif->no_resi_return }}</strong>
                    </small>
                    @if($komplainAktif->foto_return)
                        <small class="text-muted d-block mt-1">
                            <i class="fa-solid fa-image"></i> 
                            <a href="{{ asset('storage/' . $komplainAktif->foto_return) }}" target="_blank" class="text-decoration-underline text-muted">Lihat bukti serah terima</a>
                        </small>
                    @endif
                </div>
            @elseif($komplainAktif->status_komplain == 'selesai')
                <div class="mt-2">
                    <small class="text-success d-block">
                        <i class="fa-solid fa-circle-check"></i> Komplain selesai — Barang pengganti sedang diproses
                    </small>
                </div>
            @elseif($komplainAktif->status_komplain == 'rejected')
                <div class="mt-2">
                    <small class="text-danger d-block">
                        <i class="fa-solid fa-circle-xmark"></i> Komplain ditolak oleh admin
                    </small>
                </div>
            @endif
        @else
            <a href="{{ route('komplain.create', $pesanan->id_pesanan) }}" class="btn-complaint">
                <i class="fa-solid fa-circle-exclamation"></i> Ajukan Komplain
            </a>
        @endif

    </div>
@endif
            </div>

            <!-- SHIPPING CARD -->
            <div class="rc-card">
                <h3><i class="fa-solid fa-truck"></i> Informasi Pengiriman</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span>Kurir Ekspedisi</span>
                        <span>{{ $pesanan->pengirimanUtama->nama_kurir ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span>Jenis Layanan</span>
                        <span>{{ $pesanan->pengirimanUtama->layanan ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span>Nomor Resi</span>
                        <span class="highlight-resi">{{ $pesanan->pengirimanUtama->no_resi ?? 'Belum tersedia' }}</span>
                    </div>

                    @php $pengirimanPengganti = $pesanan->pengiriman->where('jenis_pengiriman', 'pengganti')->first(); @endphp
                    @if($pengirimanPengganti)
                        <hr style="border-color: var(--rc-card-border); margin: 12px 0;">
                        <div class="info-item">
                            <span class="text-success"><i class="fa-solid fa-rotate"></i> Pengiriman Pengganti</span>
                            <span class="text-success">(Setelah Komplain)</span>
                        </div>
                        <div class="info-item">
                            <span>Kurir</span>
                            <span>{{ $pengirimanPengganti->nama_kurir ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span>No. Resi Pengganti</span>
                            <span class="highlight-resi">{{ $pengirimanPengganti->no_resi ?? 'Belum tersedia' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: PRODUCTS & BILLING -->
        <div>
            <div class="rc-card">
                <h3><i class="fa-solid fa-basket-shopping"></i> Produk Belanja</h3>
                
                <!-- ITEMS ITERATION -->
                @foreach($pesanan->detailPesanan as $item)
                    <div class="product-item">
                        <img src="{{ asset('storage/'.$item->produk->foto_produk) }}" alt="{{ $item->produk->nama_produk }}">
                        <div class="product-details">
                            <div class="product-name">{{ $item->produk->nama_produk }}</div>
                            <div class="product-qty">Kuantitas: {{ $item->quantity }}</div>
                            <div class="product-price">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach

                <hr class="pricing-divider">

                <!-- PRICING MATRIX -->
                <div class="price-row">
                    <span>Subtotal Produk</span>
                    <strong>Rp {{ number_format($pesanan->total_harga_produk, 0, ',', '.') }}</strong>
                </div>
                
                <div class="price-row">
                    <span>Biaya Pengiriman (Ongkir)</span>
                    <strong>Rp {{ number_format($pesanan->total_ongkir, 0, ',', '.') }}</strong>
                </div>

                <hr class="pricing-divider">

                <div class="price-row grand-total">
                    <span>Total Bayar</span>
                    <strong>Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MIDTRANS SNAP INTEGRATION -->
@if($pesanan->status_pesanan == 'pending' && $pesanan->pembayaran)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    const expiredAt = new Date("{{ $pesanan->created_at->copy()->addMinutes(15)->format('Y-m-d H:i:s') }}");

    function updateCountdown() {
        const now = new Date();
        const distance = expiredAt - now;

        if(distance <= 0){
            document.getElementById('countdown').innerHTML = 'EXPIRED';
            document.getElementById('pay-button').disabled = true;
            document.getElementById('retry-button').style.display = 'block';
            return;
        }

        const minutes = Math.floor(distance / 1000 / 60);
        const seconds = Math.floor((distance / 1000) % 60);

        document.getElementById('countdown').innerHTML = 
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();

    document.getElementById('pay-button').addEventListener('click', function(){
        snap.pay('{{ $pesanan->pembayaran->snap_token }}', {
            onSuccess: function() { location.reload(); },
            onPending: function() { location.reload(); },
            onError: function() { document.getElementById('retry-button').style.display = 'block'; }
        });
    });

    document.getElementById('retry-button').addEventListener('click', function(){
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses...';

        fetch('{{ route('payment.retry', $pesanan->id_pesanan) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function() { location.reload(); },
                    onPending: function() { location.reload(); },
                    onError: function() { alert('Pembayaran gagal. Silakan coba lagi.'); btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-rotate me-2"></i> Ganti Metode Pembayaran'; }
                });
            } else {
                alert(data.error || 'Gagal mendapatkan token pembayaran.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-rotate me-2"></i> Ganti Metode Pembayaran';
            }
        })
        .catch(function() {
            alert('Gagal terhubung ke server. Silakan coba lagi.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-rotate me-2"></i> Ganti Metode Pembayaran';
        });
    });
</script>
@endif

@endsection