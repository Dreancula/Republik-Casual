@extends('frontend.layouts.app')

@section('title', 'Profil Saya')

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

    .profile-wrapper {
        max-width: 1100px;
        margin: auto;
        position: relative;
        z-index: 1;
    }

    /* VIDEO BG POSITIONING LOGIC */
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
        PROFILE COVER SECT
    ======================= */
    .profile-cover {
        position: relative;
        min-height: 280px;
        border-radius: 32px;
        overflow: hidden;
        background: linear-gradient(
            135deg,
            rgba(234, 230, 223, 0.05),
            rgba(255, 255, 255, 0.02)
        );
        border: 1px solid var(--rc-card-border);
        display: flex;
        align-items: flex-end;
        padding: 40px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .profile-cover::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(
            circle at top right,
            rgba(234, 230, 223, 0.12),
            transparent 50%
        );
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 24px;
        position: relative;
        z-index: 2;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.12);
        background: #111;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
    }

    .profile-info h2 {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 6px;
        color: var(--rc-text-primary);
        letter-spacing: -0.8px;
    }

    .profile-info p {
        color: var(--rc-text-secondary);
        font-size: 15px;
        margin-bottom: 0;
    }

    .profile-body {
        margin-top: 40px;
    }

    /* ========================
        STATS METRICS GRID
    ======================== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        padding: 24px;
        border-radius: 20px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .stat-label {
        color: var(--rc-text-secondary);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        margin-top: 8px;
        color: var(--rc-text-primary);
    }

    /* ========================
        FORM & INTERACTIVE STYLING
    ======================== */
    .profile-card {
        padding: 35px;
        border-radius: 28px;
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 25px;
        color: var(--rc-accent);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group.full {
        grid-column: 1/-1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--rc-text-secondary);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        width: 100%;
        min-height: 54px;
        background: rgba(0, 0, 0, 0.4);
        color: var(--rc-text-primary);
        border: 1px solid var(--rc-card-border);
        border-radius: 14px;
        padding: 0 18px;
        transition: all 0.3s var(--transition-smooth);
    }

    .form-control:focus {
        background: rgba(0, 0, 0, 0.6);
        border-color: rgba(255, 255, 255, 0.2);
        outline: none;
        box-shadow: none;
    }

    .form-control[readonly] {
        background: rgba(255, 255, 255, 0.02);
        color: var(--rc-text-secondary);
        cursor: not-allowed;
    }

    textarea.form-control {
        height: 130px;
        padding: 18px;
        resize: none;
    }

    /* DROPDOWN LIVE SEARCH CONTAINER */
    #hasilAlamat {
        margin-top: 10px;
        border-radius: 14px;
        overflow: hidden;
        background: #0f0f0f;
        border: 1px solid var(--rc-card-border);
    }

    .alamat-item {
        padding: 14px 18px;
        cursor: pointer;
        color: var(--rc-text-secondary);
        border-bottom: 1px solid rgba(255, 255, 255, .05);
        font-size: 14px;
        transition: background 0.2s var(--transition-smooth), color 0.2s var(--transition-smooth);
    }

    .alamat-item:hover {
        background: rgba(255, 255, 255, 0.04);
        color: var(--rc-text-primary);
    }

    /* ========================
        BUTTONS & ALERT BOXES
    ======================== */
    .btn-save {
        width: 100%;
        height: 54px;
        border: none;
        border-radius: 14px;
        background: var(--rc-accent);
        color: #0a0a0a;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-save:hover {
        background: var(--rc-text-primary);
        transform: translateY(-1px);
    }

    .warning-box {
        padding: 18px;
        border-radius: 16px;
        background: rgba(245, 158, 11, 0.06);
        border: 1px solid rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        margin-bottom: 24px;
        font-size: 14px;
    }

    .success-box {
        padding: 18px;
        border-radius: 16px;
        background: rgba(34, 197, 94, 0.06);
        border: 1px solid rgba(34, 197, 94, 0.15);
        color: #4ade80;
        margin-bottom: 24px;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .form-grid, .stats-grid {
            grid-template-columns: 1fr;
        }
        .profile-cover {
            padding: 24px;
        }
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        .profile-info h2 {
            font-size: 28px;
        }
    }
</style>

<div class="profile-wrapper text-white">

    <div class="profile-cover">
        <div class="profile-header">
            <img src="{{ Auth::user()->avatar ? (str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/img-customer/'.Auth::user()->avatar)) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&background=111111&color=ffffff' }}" class="profile-avatar" alt="Avatar">
            <div class="profile-info">
                <h2>{{ Auth::user()->nama }}</h2>
                <p>{{ Auth::user()->email }}</p>
                
                <div style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 999px; background: rgba(255,255,255,.04); border: 1px solid var(--rc-card-border); margin-top: 14px; font-size: 12px; font-weight: 600; color: #fff;">
                    <i class="fa-brands fa-google"></i> Login via Google
                </div>
            </div>
        </div>
    </div>

    <div class="profile-body">

        @if(empty(Auth::user()->alamat) || empty(Auth::user()->no_telp))
            <div class="warning-box">
                Lengkapi nomor HP dan wilayah pengiriman Anda terlebih dahulu sebelum melakukan proses checkout produk.
            </div>
        @else
            <div class="success-box">
                Profil Anda sudah lengkap dan siap digunakan untuk melakukan order maupun checkout.
            </div>
        @endif

        {{-- STATS METRICS GRID (REALTIME SYNCED) --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-value">{{ Auth::user()->pesanan()->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Menunggu Pembayaran</div>
                <div class="stat-value">
                    {{ Auth::user()->pesanan()->where('status_pesanan', 'belum_bayar')->count() }}
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pesanan Selesai</div>
                <div class="stat-value">
                    {{ Auth::user()->pesanan()->where('status_pesanan', 'selesai')->count() }}
                </div>
            </div>
        </div>

        <div class="profile-card">
            <div class="card-title">Informasi Akun</div>

            <form action="{{ route('profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', Auth::user()->nama) }}">
                    </div>

                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', Auth::user()->no_telp) }}">
                    </div>

                    <div class="form-group full">
                        <label>Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                    </div>

                    <div class="form-group full">
                        <label>Cari Wilayah Pengiriman</label>
                        <input type="text" id="searchAlamat" class="form-control" value="{{ trim($kecamatan.', '.$kota.', '.$provinsi, ', ') }}" placeholder="Ketik nama kecamatan minimal 3 huruf...">
                        <div id="hasilAlamat"></div>

                        <input type="hidden" name="provinsi" id="provinsi">
                        <input type="hidden" name="kota" id="kota">
                        <input type="hidden" name="kecamatan" id="kecamatan">
                        <input type="hidden" name="destination_id" id="destination_id">
                    </div>

                    <div class="form-group full">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" class="form-control" placeholder="Contoh: Jl. Margonda Raya No.100 RT01 RW02">{{ old('alamat_lengkap', $alamatLengkap) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </form>
        </div>

    </div>
</div>

<script>
const searchInput = document.getElementById('searchAlamat');
const hasil = document.getElementById('hasilAlamat');

searchInput.addEventListener('keyup', async function() {
    let keyword = this.value;

    if(keyword.length < 3) {
        hasil.innerHTML = '';
        return;
    }

    const response = await fetch('/api/search-destination?keyword=' + encodeURIComponent(keyword));
    const json = await response.json();
    let html = '';

    if(json.data) {
        json.data.forEach(item => {
            html += `
            <div class="alamat-item" onclick="pilihAlamat('${item.id}', '${item.province_name}', '${item.city_name}', '${item.subdistrict_name}')">
                ${item.subdistrict_name}, ${item.city_name}, ${item.province_name}
            </div>
            `;
        });
    }
    hasil.innerHTML = html;
});

function pilihAlamat(id, provinsi, kota, kecamatan) {
    document.getElementById('destination_id').value = id;
    document.getElementById('provinsi').value = provinsi;
    document.getElementById('kota').value = kota;
    document.getElementById('kecamatan').value = kecamatan;
    document.getElementById('searchAlamat').value = `${kecamatan}, ${kota}, ${provinsi}`;
    hasil.innerHTML = '';
}
</script>
@endsection