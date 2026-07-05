@extends('frontend.layouts.app')

@section('title', 'Ajukan Komplain - Republik Casual')

@push('styles')
<style>
    .video-bg-container {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -2; overflow: hidden; pointer-events: none;
    }
    .video-bg-container video {
        width: 100%; height: 100%; object-fit: cover;
        filter: blur(1px) brightness(0.9); 
    }
    .video-bg-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        z-index: -1;
        background: radial-gradient(circle, rgba(0,0,0,0) 0%, rgba(10,10,10,0.95) 90%);
        pointer-events: none;
    }

    .form-container {
        max-width: 720px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .form-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: var(--radius-lg);
        padding: 36px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .form-header {
        padding-bottom: 24px;
        border-bottom: 1px solid var(--rc-card-border);
        margin-bottom: 28px;
    }
    .form-header h4 {
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: var(--rc-text-primary);
    }
    .form-header p {
        color: var(--rc-text-secondary);
        font-size: 14px;
        margin-top: 6px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        color: var(--rc-text-secondary);
        letter-spacing: 0.5px;
    }

    .input-field {
        width: 100%;
        background: rgba(0, 0, 0, 0.4);
        color: var(--rc-text-primary);
        border: 1px solid var(--rc-card-border);
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s var(--transition-smooth);
        font-size: 14px;
    }
    .input-field:focus {
        border-color: rgba(255, 255, 255, 0.2);
        outline: none;
        background: rgba(0, 0, 0, 0.6);
    }
    select.input-field option {
        background-color: #121212;
        color: var(--rc-text-primary);
    }
    textarea.input-field {
        font-family: inherit;
        resize: vertical;
    }

    .btn-primary {
        height: 48px;
        border: none;
        border-radius: 12px;
        background: var(--rc-accent);
        color: #0a0a0a;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 0 28px;
        cursor: pointer;
        transition: all 0.3s var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary:hover {
        background: #fff;
        transform: translateY(-1px);
    }

    .btn-secondary {
        height: 48px;
        display: inline-flex;
        align-items: center;
        background: transparent;
        border: 1px solid var(--rc-card-border);
        border-radius: 12px;
        color: var(--rc-text-primary);
        font-weight: 600;
        font-size: 13px;
        padding: 0 22px;
        text-decoration: none;
        transition: all 0.3s var(--transition-smooth);
    }
    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.04);
        color: var(--rc-text-primary);
        border-color: rgba(255, 255, 255, 0.15);
    }

    /* PRODUCT CARDS */
    .product-card {
        border: 1px solid var(--rc-card-border);
        border-radius: 14px;
        margin-bottom: 12px;
        overflow: hidden;
        transition: all 0.3s var(--transition-smooth);
    }
    .product-card.checked {
        border-color: var(--rc-accent);
        background: rgba(234, 230, 223, 0.04);
    }

    .product-card .product-header {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        cursor: pointer;
        gap: 14px;
    }
    .product-card .product-header .thumb {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 10px;
        flex-shrink: 0;
    }
    .product-card .product-header .info {
        flex: 1;
        min-width: 0;
    }
    .product-card .product-header .info .name {
        color: var(--rc-text-primary);
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .product-card .product-header .info .meta {
        color: var(--rc-text-secondary);
        font-size: 12px;
        display: block;
        margin-top: 2px;
    }
    .product-card .product-header .qty-input {
        width: 56px;
        text-align: center;
        padding: 6px 8px;
        border-radius: 8px;
        background: rgba(0,0,0,0.3);
        border: 1px solid var(--rc-card-border);
        color: var(--rc-text-primary);
        font-size: 13px;
    }
    .product-card .product-header .qty-input:focus {
        outline: none;
        border-color: var(--rc-accent);
    }

    .product-card .product-body {
        padding: 14px 18px;
        background: rgba(0, 0, 0, 0.2);
        border-top: 1px solid var(--rc-card-border);
    }

    .foto-list {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .foto-preview {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        border: 1px solid var(--rc-card-border);
        flex-shrink: 0;
    }
    .foto-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .foto-preview .remove-foto {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #FF453A;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.5);
        font-weight: 700;
        line-height: 1;
    }

    .btn-add-foto {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: rgba(0,0,0,0.3);
        border: 1px dashed var(--rc-card-border);
        border-radius: 10px;
        color: var(--rc-text-secondary);
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s var(--transition-smooth);
    }
    .btn-add-foto:hover {
        border-color: rgba(var(--rc-accent-rgb), 0.3);
        color: var(--rc-text-primary);
    }

    /* EXISTING COMPLAINT CARD */
    .existing-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: var(--radius-lg);
        padding: 40px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        text-align: center;
    }
    .existing-card .status-icon {
        font-size: 52px;
        margin-bottom: 16px;
    }
    .existing-card h3 {
        font-weight: 700;
        color: var(--rc-text-primary);
        margin-bottom: 6px;
    }
    .existing-card .sub {
        color: var(--rc-text-secondary);
        font-size: 14px;
    }

    .info-table {
        margin: 28px auto;
        max-width: 480px;
        text-align: left;
    }
    .info-table .row-wrap {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--rc-card-border);
        gap: 20px;
    }
    .info-table .row-wrap:last-child {
        border-bottom: none;
    }
    .info-table .label {
        color: var(--rc-text-secondary);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
        flex-shrink: 0;
    }
    .info-table .value {
        color: var(--rc-text-primary);
        font-weight: 600;
        font-size: 13px;
        text-align: right;
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

<div class="form-container">

    @if(session('success'))
        <div class="d-flex align-items-center gap-2 py-2 px-3 mb-4" style="background: rgba(48, 209, 88, 0.08); color: #30D158; border: 1px solid rgba(48, 209, 88, 0.15); border-radius: 10px; font-size: 13px;">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: rgba(255, 69, 58, 0.08); border: 1px solid rgba(255, 69, 58, 0.15); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
            <div class="d-flex align-items-center gap-2 mb-2" style="color: #FF453A; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.3px;">
                <i class="fa-solid fa-circle-exclamation"></i> Perbaiki kesalahan berikut:
            </div>
            <ul style="margin: 0; padding-left: 20px; color: #FF453A; font-size: 13px; line-height: 1.8;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php $komplainAktif = $pesanan->komplain->first(); @endphp

    @if($komplainAktif)
        {{-- EXISTING COMPLAINT --}}
        @php $ks = $komplainAktif->status_komplain; @endphp
        <div class="existing-card">
            <div class="status-icon">
                @if($ks == 'pending')
                    <i class="fa-solid fa-hourglass-half" style="color: #FFD60A;"></i>
                @elseif($ks == 'approved')
                    <i class="fa-solid fa-circle-check" style="color: #30D158;"></i>
                @elseif($ks == 'selesai')
                    <i class="fa-solid fa-check-double" style="color: #4A8BD9;"></i>
                @else
                    <i class="fa-solid fa-circle-xmark" style="color: #FF453A;"></i>
                @endif
            </div>

            <h3>Komplain Diproses</h3>
            <p class="sub">Pesanan #RC-{{ $pesanan->id_pesanan }}</p>

            <hr style="border-color: var(--rc-card-border); margin: 24px 0;">

            <div class="info-table">
                <div class="row-wrap">
                    <span class="label">Produk Dikomplain</span>
                    <span class="value">
                        @foreach($komplainAktif->detailKomplain as $dk)
                            {{ $dk->produk->nama_produk ?? 'Produk' }} x{{ $dk->qty }}@if(!$loop->last), @endif
                        @endforeach
                    </span>
                </div>
                <div class="row-wrap">
                    <span class="label">Jenis Komplain</span>
                    <span class="value">{{ $komplainAktif->jenis_komplain }}</span>
                </div>
                <div class="row-wrap">
                    <span class="label">Status</span>
                    <span class="value">
                        @if($ks == 'pending')
                            <span style="color: #FFD60A;">Menunggu Verifikasi</span>
                        @elseif($ks == 'approved')
                            <span style="color: #30D158;">Disetujui</span>
                        @elseif($ks == 'selesai')
                            <span style="color: #4A8BD9;">Selesai</span>
                        @else
                            <span style="color: #FF453A;">Ditolak</span>
                        @endif
                    </span>
                </div>
                @if($ks == 'approved' && $komplainAktif->no_resi_return)
                <div class="row-wrap">
                    <span class="label">Resi Retur</span>
                    <span class="value" style="font-family: monospace; letter-spacing: 0.5px;">{{ $komplainAktif->no_resi_return }}</span>
                </div>
                @endif
                @if($ks == 'approved' && $komplainAktif->foto_return)
                <div class="row-wrap">
                    <span class="label">Bukti Retur</span>
                    <span class="value">
                        <a href="{{ asset('storage/' . $komplainAktif->foto_return) }}" target="_blank" style="color: var(--rc-text-secondary); text-decoration: underline; font-size: 12px;">Lihat</a>
                    </span>
                </div>
                @endif
            </div>

            <a href="{{ route('pesanan.saya') }}" class="btn-secondary" style="margin-top: 8px;">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Pesanan
            </a>
        </div>
    @else
        {{-- NEW COMPLAINT FORM --}}
        <div class="form-card">
            <div class="form-header">
                <h4><i class="fa-solid fa-circle-exclamation" style="color: #FF453A; margin-right: 8px;"></i> Ajukan Komplain</h4>
                <p>Pesanan #RC-{{ $pesanan->id_pesanan }} &middot; Laporkan produk yang bermasalah</p>
            </div>

            <form action="{{ route('komplain.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">

                {{-- PRODUCT SELECTION --}}
                <div class="mb-4">
                    <label class="form-label">Pilih Produk</label>
                    @foreach($pesanan->detailPesanan as $detail)
                    @php $fotoPath = $detail->produk->foto_produk ?? 'produk/default.jpg'; @endphp
                    <div class="product-card" data-id="{{ $detail->id_produk }}">
                        <label class="product-header">
                            <input type="checkbox" name="id_produk[]" value="{{ $detail->id_produk }}" class="product-checkbox" style="width: 18px; height: 18px; accent-color: var(--rc-accent); flex-shrink: 0;">
                            <img src="{{ asset('storage/' . $fotoPath) }}" class="thumb" alt="">
                            <div class="info">
                                <div class="name">{{ $detail->produk->nama_produk ?? 'Produk' }}</div>
                                <span class="meta">{{ $detail->jumlah }}x dibeli</span>
                            </div>
                            <div style="text-align: center; flex-shrink: 0;">
                                <small style="color: var(--rc-text-secondary); font-size: 10px; display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.3px;">Rusak</small>
                                <input type="number" name="qty_{{ $detail->id_produk }}" value="1" min="1" max="{{ $detail->jumlah }}" class="qty-input">
                            </div>
                        </label>
                        <div class="product-body">
                            <div class="foto-list" id="fotoList-{{ $detail->id_produk }}"></div>
                            <button type="button" class="btn-add-foto" data-produk="{{ $detail->id_produk }}">
                                <i class="fa-solid fa-plus"></i> Tambah Foto
                            </button>
                            <small style="color: var(--rc-text-secondary); display: block; margin-top: 6px; font-size: 11px;">Klik untuk upload bukti (minimal 1 foto per produk)</small>
                        </div>
                    </div>
                    @endforeach
                    <small style="color: var(--rc-text-secondary); display: block; margin-top: 10px; font-size: 12px;">Centang produk yang bermasalah, tentukan jumlah rusak, upload minimal 1 foto sebagai bukti</small>
                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="input-field" rows="4" placeholder="Jelaskan secara detail masalah yang Anda alami..." required minlength="10">{{ old('deskripsi') }}</textarea>
                    <small style="color: var(--rc-text-secondary); display: block; margin-top: 6px; font-size: 11px;">Minimal 10 karakter — ceritakan apa yang terjadi dengan produk yang diterima.</small>
                </div>

                {{-- COMPLAINT TYPE --}}
                <div class="mb-4">
                    <label class="form-label">Jenis Komplain</label>
                    <select name="jenis_komplain" class="input-field" required>
                        <option value="">-- Pilih Alasan --</option>
                        <option value="Barang Rusak / Cacat">Barang Rusak / Cacat</option>
                        <option value="Barang Salah / Tidak Sesuai">Barang Salah / Tidak Sesuai</option>
                        <option value="Komponen / Item Kurang">Komponen / Item Kurang</option>
                    </select>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex justify-content-between align-items-center mt-5" style="gap: 12px;">
                    <a href="{{ url()->previous() }}" class="btn-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn-primary">Kirim Komplain</button>
                </div>
            </form>
        </div>
    @endif

</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.product-checkbox').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var card = this.closest('.product-card');
        card.classList.toggle('checked', this.checked);
    });
});

document.querySelectorAll('.qty-input').forEach(function(qty) {
    qty.addEventListener('input', function() {
        var min = parseInt(this.min) || 1;
        var max = parseInt(this.max) || 99999;
        var val = parseInt(this.value);
        if (this.value === '') return;
        if (isNaN(val) || val < min) this.value = min;
        if (val > max) this.value = max;
    });
    qty.addEventListener('blur', function() {
        if (this.value === '') this.value = this.min || 1;
    });
});

(function() {
    var addButtons = document.querySelectorAll('.btn-add-foto');
    addButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var produkId = this.dataset.produk;
            var list = document.getElementById('fotoList-' + produkId);
            var form = this.closest('form');

            var input = document.createElement('input');
            input.type = 'file';
            input.name = 'fotos_' + produkId + '[]';
            input.accept = 'image/*';
            input.style.display = 'none';

            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    var file = this.files[0];

                    var preview = document.createElement('div');
                    preview.className = 'foto-preview';

                    var img = document.createElement('img');
                    img.alt = 'Foto';

                    var removeBtn = document.createElement('span');
                    removeBtn.className = 'remove-foto';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        preview.remove();
                        input.remove();
                    });

                    preview.appendChild(img);
                    preview.appendChild(removeBtn);
                    list.appendChild(preview);

                    var reader = new FileReader();
                    reader.onload = function(e) { img.src = e.target.result; };
                    reader.readAsDataURL(file);
                }
            });

            form.appendChild(input);
            input.click();
        });
    });
})();
</script>
@endpush
