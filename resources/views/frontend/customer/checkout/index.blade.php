@extends('frontend.layouts.app')

@section('title', 'Checkout')

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

    .checkout-wrapper {
        max-width: 1400px;
        margin: auto;
        position: relative;
        z-index: 1;
    }

    .checkout-title {
        font-size: 38px;
        font-weight: 800;
        margin-bottom: 30px;
        color: var(--rc-text-primary);
        letter-spacing: -0.8px;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    /* ========================
        CONTAINERS / CARDS
    ======================== */
    .checkout-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 24px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .checkout-card-sticky {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 30px;
        position: sticky;
        top: 100px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .card-subtitle {
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: 700;
        color: var(--rc-accent);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ========================
        PRODUCT ITEMS
    ======================== */
    .checkout-prod-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 16px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    }

    .checkout-prod-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .checkout-prod-img {
        width: 90px;
        height: 90px;
        border-radius: 14px;
        object-fit: cover;
        background: #111;
        border: 1px solid var(--rc-card-border);
    }

    .prod-meta-title {
        font-weight: 600;
        font-size: 16px;
        color: var(--rc-text-primary);
        margin-bottom: 4px;
    }

    .prod-meta-qty {
        font-size: 13px;
        color: var(--rc-text-secondary);
        margin-bottom: 4px;
    }

    .prod-meta-price {
        color: var(--rc-accent);
        font-weight: 700;
        font-size: 15px;
    }

    /* ========================
        SUMMARY & INPUTS
    ======================== */
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 14px;
        font-size: 14px;
        color: var(--rc-text-secondary);
    }

    .summary-row strong {
        color: var(--rc-text-primary);
    }

    .summary-total-row {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid var(--rc-card-border);
        font-size: 22px;
        font-weight: 800;
        color: var(--rc-text-primary);
    }

    .summary-total-row strong {
        color: var(--rc-accent);
    }

    .form-label-custom {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        color: var(--rc-text-secondary);
        letter-spacing: 0.5px;
    }

    .select-custom {
        width: 100%;
        height: 54px;
        background: rgba(0, 0, 0, 0.4);
        color: var(--rc-text-primary);
        border: 1px solid var(--rc-card-border);
        border-radius: 14px;
        padding: 0 16px;
        cursor: pointer;
        transition: all 0.3s var(--transition-smooth);
    }

    .select-custom:focus {
        border-color: rgba(255, 255, 255, 0.2);
        outline: none;
    }

    /* ========================
        DYNAMIC SHIPPING OPTIONS
    ======================== */
    .ongkir-option-card {
        display: block;
        padding: 16px;
        margin-top: 12px;
        border: 1px solid var(--rc-card-border);
        border-radius: 14px;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.01);
        transition: all 0.2s var(--transition-smooth);
    }

    .ongkir-option-card:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.15);
    }

    /* ========================
        BUTTONS & VIDEO BG FIXED
    ======================== */
    .btn-checkout-submit {
        width: 100%;
        height: 54px;
        border: none;
        border-radius: 14px;
        background: var(--rc-accent);
        color: #0a0a0a;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 24px;
        cursor: pointer;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-checkout-submit:hover {
        background: var(--rc-text-primary);
        transform: translateY(-1px);
    }

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

    @media (max-width: 991px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
        .checkout-card-sticky {
            position: relative;
            top: 0;
        }
    }
</style>

<div class="container py-5">
    <div class="checkout-wrapper text-white">

        <h1 class="checkout-title">Checkout</h1>

        <div class="checkout-grid">

            <div>
                <div class="checkout-card">
                    <h3 class="card-subtitle">Alamat Pengiriman</h3>
                    <div style="font-size: 16px; margin-bottom: 4px;">
                        <strong>{{ Auth::user()->nama }}</strong>
                    </div>
                    <div style="color: var(--rc-text-secondary); font-size: 14px; margin-bottom: 16px;">
                        {{ Auth::user()->no_telp }}
                    </div>
                    <div style="font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.6;">
                        {{ $alamatLengkap }}<br>
                        <span style="color: var(--rc-text-secondary);">{{ $kecamatan }}, {{ $kota }}, {{ $provinsi }}</span>
                    </div>
                </div>

                <div class="checkout-card">
                    <h3 class="card-subtitle">Produk Checkout</h3>
                    @foreach($cart as $item)
                        <div class="checkout-prod-item">
                            <img src="{{ asset('storage/'.$item['foto_produk']) }}" class="checkout-prod-img" alt="Produk">
                            <div>
                                <div class="prod-meta-title">{{ $item['nama_produk'] }}</div>
                                <div class="prod-meta-qty">Quantity: {{ $item['qty'] }}</div>
                                <div class="prod-meta-price">Rp {{ number_format($item['harga'],0,',','.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="checkout-card-sticky">
                    <h3 class="card-subtitle">Ringkasan Belanja</h3>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong>Rp {{ number_format($subtotal,0,',','.') }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Total Berat</span>
                        <strong>{{ number_format($totalBerat) }} gram</strong>
                    </div>

                    <div class="summary-row" style="margin-top: 16px;">
                        <span>Ongkos Kirim</span>
                        <strong id="ongkir-value">Rp 0</strong>
                    </div>

                    <div class="summary-total-row">
                        <span>Total</span>
                        <strong id="total-value">Rp {{ number_format($subtotal,0,',','.') }}</strong>
                    </div>

                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                        @csrf
                        <input type="hidden" name="courier" id="courier_input">
                        <input type="hidden" name="service" id="service_input">
                        <input type="hidden" name="ongkir" id="ongkir_input">

                        <button type="submit" class="btn-checkout-submit">Buat Pesanan</button>
                    </form>

                    <script>
                    document.getElementById('checkout-form').addEventListener('submit', function(e) {
                        var ongkir = document.getElementById('ongkir_input').value;
                        if (!ongkir || ongkir == '') {
                            e.preventDefault();
                            alert('Silakan pilih kurir dan layanan pengiriman terlebih dahulu.');
                        }
                    });
                    </script>

                    <hr style="border-color: var(--rc-card-border); margin: 24px 0;">

                    <div>
                        <label class="form-label-custom" for="courier">Pilih Kurir Ekspedisi</label>
                        <select id="courier" class="select-custom">
                            <option value="">-- Pilih Kurir --</option>
                            <option value="jne">JNE Express</option>
                            <option value="jnt">J&T Express</option>
                            <option value="sicepat">SiCepat Beruntung</option>
                            <option value="pos">POS Indonesia</option>
                        </select>
                    </div>

                    <div id="ongkir-container" style="margin-top: 12px;"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
const courierSelect = document.getElementById('courier');
const ongkirContainer = document.getElementById('ongkir-container');
const ongkirValue = document.getElementById('ongkir-value');
const totalValue = document.getElementById('total-value');
const subtotal = {{ $subtotal }};

function pilihOngkir(courier, service, cost) {
    document.getElementById('courier_input').value = courier;
    document.getElementById('service_input').value = service;
    document.getElementById('ongkir_input').value = cost;
}

courierSelect.addEventListener('change', async function() {
    if(!this.value) {
        ongkirContainer.innerHTML = '';
        return;
    }

    ongkirContainer.innerHTML = '<div style="color: var(--rc-text-secondary); font-size: 13px; padding: 10px 0;">Menghitung ongkos kirim...</div>';

    const response = await fetch("{{ route('checkout.ongkir') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ courier: this.value })
    });

    const result = await response.json();
    let html = '';

    if(result.data) {
        result.data.forEach(item => {
            html += `
            <label class="ongkir-option-card">
                <input type="radio" name="ongkir_option" value="${item.cost}" data-cost="${item.cost}" onclick="pilihOngkir('${item.code}', '${item.service}', ${item.cost})" style="margin-right: 10px; accent-color: var(--rc-accent);">
                <strong style="color: #fff;">${item.service}</strong>
                <br>
                <small style="color: var(--rc-text-secondary); display:inline-block; margin-top:4px;">${item.description}</small>
                <div style="margin-top: 8px; font-weight: 700; color: var(--rc-accent); font-size: 14px;">
                    Rp ${Number(item.cost).toLocaleString('id-ID')} <span style="color: var(--rc-text-secondary); font-weight: 400; font-size:12px;">• Est. ${item.etd} Hari</span>
                </div>
            </label>
            `;
        });
    }

    ongkirContainer.innerHTML = html;

    document.querySelectorAll('input[name="ongkir_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            let ongkir = parseInt(this.dataset.cost);
            ongkirValue.innerHTML = 'Rp ' + ongkir.toLocaleString('id-ID');
            totalValue.innerHTML = 'Rp ' + (subtotal + ongkir).toLocaleString('id-ID');
        });
    });
});
</script>

@endsection