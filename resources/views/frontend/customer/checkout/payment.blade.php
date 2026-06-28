@extends('frontend.layouts.app')

@section('title', 'Pembayaran')

@section('content')

<div class="video-bg-container">
    <video autoplay loop muted playsinline>
        <source src="{{ asset('storage/video/background.mp4') }}" type="video/mp4">
    </video>
</div>
<div class="video-bg-overlay"></div>

<style>
    /* ========================
        VARIABLES & BASE COHESION
    ======================== */
    :root {
        --rc-bg-dark: #0a0a0a;
        --rc-card-dark: rgba(18, 18, 18, 0.45);
        --rc-card-border: rgba(255, 255, 255, 0.06);
        --rc-accent: #EAE6DF;
        --rc-text-primary: #ffffff;
        --rc-text-secondary: #8e8e93;
        --transition-smooth: cubic-bezier(0.16, 1, 0.3, 1);
    }

    .payment-wrapper {
        max-width: 680px;
        margin: auto;
        padding: 60px 0;
        position: relative;
        z-index: 1;
    }

    .payment-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 28px;
        padding: 45px;
        text-align: center;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    /* ========================
        COUNTDOWN ELEMENTS
    ======================== */
    .icon-timer-wrapper {
        width: 74px;
        height: 74px;
        margin: auto;
        border-radius: 50%;
        background: rgba(245, 158, 11, 0.06);
        border: 1px solid rgba(245, 158, 11, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 24px;
        color: #f59e0b;
    }

    .payment-title {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--rc-text-primary);
        letter-spacing: -0.5px;
    }

    .payment-subtitle {
        color: var(--rc-text-secondary);
        font-size: 15px;
        margin-bottom: 35px;
        font-family: monospace;
        letter-spacing: 0.5px;
    }

    .countdown-box {
        background: rgba(245, 158, 11, 0.03);
        border: 1px solid rgba(245, 158, 11, 0.1);
        border-radius: 20px;
        padding: 20px 24px;
        margin-bottom: 35px;
    }

    .countdown-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #f59e0b;
        font-weight: 700;
        margin-bottom: 6px;
    }

    #countdown {
        font-size: 48px;
        font-weight: 900;
        letter-spacing: 3px;
        color: #f59e0b;
        font-feature-settings: "tnum"; /* Biar angka monospaced tidak geser-geser saat jalan */
    }

    /* ========================
        METRICS & ACTIONS
    ======================== */
    .invoice-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 15px;
        color: var(--rc-text-secondary);
    }

    .invoice-row strong {
        font-size: 20px;
        color: var(--rc-accent);
        font-weight: 800;
    }

    .btn-pay-snap {
        width: 100%;
        height: 56px;
        border: none;
        border-radius: 14px;
        background: var(--rc-accent);
        color: #0a0a0a;
        font-weight: 700;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        margin-top: 24px;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-pay-snap:hover:not(:disabled) {
        background: var(--rc-text-primary);
        transform: translateY(-1px);
    }

    .btn-pay-snap:disabled {
        background: rgba(255, 255, 255, 0.05);
        color: var(--rc-text-secondary);
        cursor: not-allowed;
        border: 1px solid var(--rc-card-border);
    }

    /* VIDEO BACKGROUND RULES */
    .video-bg-container {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -2; overflow: hidden; pointer-events: none;
    }

    .video-bg-container video {
        width: 100%; height: 100%; object-fit: cover;
        filter: blur(1px) brightness(0.9); 
    }

    .video-bg-overlay {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1;
        background: radial-gradient(circle, rgba(0,0,0,0) 0%, rgba(10,10,10,0.95) 90%);
        pointer-events: none;
    }

    @media (max-width: 576px) {
        .payment-wrapper {
            padding: 30px 15px;
        }
        .payment-card {
            padding: 30px 20px;
        }
        .payment-title {
            font-size: 26px;
        }
        #countdown {
            font-size: 38px;
        }
    }
</style>

<div class="container">
    <div class="payment-wrapper text-white">
        <div class="payment-card">
            
            <div class="icon-timer-wrapper">
                <i class="fa-regular fa-clock"></i>
            </div>

            <h1 class="payment-title">Selesaikan Pembayaran</h1>
            <p class="payment-subtitle">ORDER #RC-{{ $pesanan->id_pesanan }}</p>

            <div class="countdown-box">
                <div class="countdown-label">Batas Waktu Transaksi</div>
                <div id="countdown">15:00</div>
            </div>

            <div class="invoice-row">
                <span>Total Pembayaran</span>
                <strong>Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</strong>
            </div>

            <hr style="border-color: var(--rc-card-border); margin: 24px 0;">

            <button id="pay-button" class="btn-pay-snap">Bayar Sekarang</button>
            <button id="retry-button" class="btn-pay-snap" style="display: none; margin-top: 12px; background: transparent; border: 1px solid var(--rc-card-border); color: var(--rc-text-primary); height: 46px; font-size: 13px; text-transform: none; letter-spacing: 0.5px;">
                <i class="fa-solid fa-rotate me-2"></i> Ganti Metode Pembayaran
            </button>

        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
const expiredAt = new Date("{{ $pesanan->created_at->copy()->addMinutes(15)->format('Y-m-d H:i:s') }}");
const payButton = document.getElementById('pay-button');
const retryButton = document.getElementById('retry-button');

function updateCountdown() {
    const now = new Date();
    const distance = expiredAt - now;

    if(distance <= 0) {
        document.getElementById('countdown').innerHTML = 'EXPIRED';
        document.getElementById('countdown').style.color = '#ef4444';
        payButton.disabled = true;
        payButton.innerHTML = 'Waktu Pembayaran Habis';
        retryButton.style.display = 'block';
        return;
    }

    const minutes = Math.floor(distance / 1000 / 60);
    const seconds = Math.floor((distance / 1000) % 60);

    document.getElementById('countdown').innerHTML = 
        String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
}

setInterval(updateCountdown, 1000);
updateCountdown();

payButton.onclick = function() {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            window.location.href = "{{ route('payment.finish', $pesanan->id_pesanan) }}";
        },
        onPending: function(result) {
            window.location.href = "{{ route('payment.finish', $pesanan->id_pesanan) }}";
        },
        onError: function(result) {
            retryButton.style.display = 'block';
        }
    });
};

retryButton.onclick = function() {
    retryButton.disabled = true;
    retryButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses...';

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
                onSuccess: function() { window.location.href = "{{ route('payment.finish', $pesanan->id_pesanan) }}"; },
                onPending: function() { window.location.href = "{{ route('payment.finish', $pesanan->id_pesanan) }}"; },
                onError: function() { alert('Pembayaran gagal. Silakan coba lagi.'); retryButton.disabled = false; retryButton.innerHTML = '<i class="fa-solid fa-rotate me-2"></i> Ganti Metode Pembayaran'; }
            });
        } else {
            alert(data.error || 'Gagal mendapatkan token pembayaran.');
            retryButton.disabled = false;
            retryButton.innerHTML = '<i class="fa-solid fa-rotate me-2"></i> Ganti Metode Pembayaran';
        }
    })
    .catch(function() {
        alert('Gagal terhubung ke server. Silakan coba lagi.');
        retryButton.disabled = false;
        retryButton.innerHTML = '<i class="fa-solid fa-rotate me-2"></i> Ganti Metode Pembayaran';
    });
};
</script>

@endsection