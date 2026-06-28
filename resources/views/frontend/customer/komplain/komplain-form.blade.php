@extends('frontend.layouts.app')

@section('title', 'Ajukan Komplain')

@push('styles')
<style>
    :root {
        --rc-bg-dark: #0a0a0a;
        --rc-card-dark: rgba(18, 18, 18, 0.45);
        --rc-card-border: rgba(255, 255, 255, 0.06);
        --rc-accent: #EAE6DF;
        --rc-text-primary: #ffffff;
        --rc-text-secondary: #8e8e93;
        --transition-smooth: cubic-bezier(0.16, 1, 0.3, 1);
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

    .complain-wrapper {
        max-width: 800px;
        margin: 120px auto 80px auto;
        position: relative;
        z-index: 1;
    }

    .complain-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 40px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .complain-card-header {
        padding-bottom: 24px;
        border-bottom: 1px solid var(--rc-card-border);
        margin-bottom: 30px;
    }

    .complain-title {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: var(--rc-text-primary);
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

    .input-custom {
        width: 100%;
        background: rgba(0, 0, 0, 0.4);
        color: var(--rc-text-primary);
        border: 1px solid var(--rc-card-border);
        border-radius: 14px;
        padding: 14px 18px;
        transition: all 0.3s var(--transition-smooth);
    }

    .input-custom:focus {
        border-color: rgba(255, 255, 255, 0.2);
        outline: none;
        background: rgba(0, 0, 0, 0.6);
    }

    select.input-custom option {
        background-color: #121212;
        color: var(--rc-text-primary);
    }

    .btn-complain-submit {
        height: 50px;
        border: none;
        border-radius: 14px;
        background: var(--rc-accent);
        color: #0a0a0a;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 30px;
        cursor: pointer;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-complain-submit:hover {
        background: var(--rc-text-primary);
        transform: translateY(-1px);
    }

    .btn-complain-secondary {
        height: 50px;
        display: inline-flex;
        align-items: center;
        background: transparent;
        border: 1px solid var(--rc-card-border);
        border-radius: 14px;
        color: var(--rc-text-primary);
        font-weight: 600;
        font-size: 14px;
        padding: 0 24px;
        text-decoration: none;
        transition: all 0.3s var(--transition-smooth);
    }

    .btn-complain-secondary:hover {
        background: rgba(255, 255, 255, 0.05);
        color: var(--rc-text-primary);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .text-muted-custom {
        color: var(--rc-text-secondary);
    }

    .table-complain td {
        padding: 12px 0;
        color: var(--rc-text-primary);
    }

    .product-card {
        border: 1px solid var(--rc-card-border);
        border-radius: 16px;
        margin-bottom: 16px;
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
        padding: 16px 20px;
        cursor: pointer;
        border-bottom: 1px solid var(--rc-card-border);
    }

    .product-card .product-body {
        padding: 16px 20px;
        background: rgba(0, 0, 0, 0.2);
    }

    .photo-upload {
        width: 100px;
        height: 100px;
        border: 1px dashed var(--rc-card-border);
        border-radius: 12px;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.3);
        transition: all 0.3s var(--transition-smooth);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .photo-upload:hover {
        border-color: rgba(var(--rc-accent-rgb), 0.3);
        background: rgba(0, 0, 0, 0.5);
    }

    .photo-upload.uploaded {
        border-color: var(--rc-success);
    }

    .existing-complain-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: 24px;
        padding: 40px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        text-align: center;
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

<div class="container">
    <div class="complain-wrapper">

        @if(session('success'))
            <div class="alert d-flex align-items-center gap-2 py-2 px-3 mb-4" style="background: rgba(48, 209, 88, 0.1); color: #30D158; border: 1px solid rgba(48, 209, 88, 0.2); border-radius: 12px; font-size: 14px;">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert d-flex align-items-center gap-2 py-2 px-3 mb-4" style="background: rgba(255, 69, 58, 0.1); color: #FF453A; border: 1px solid rgba(255, 69, 58, 0.2); border-radius: 12px; font-size: 14px;">
                <i class="fa-solid fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        @php $komplainAktif = $pesanan->komplain->first(); @endphp
        @if($komplainAktif)
            {{-- EXISTING COMPLAINT DISPLAY --}}
            @php $ks = $komplainAktif->status_komplain; @endphp
            <div class="existing-complain-card">
                
                <div class="mb-4">
                    @if($ks == 'pending')
                        <i class="fa-solid fa-hourglass-half" style="font-size: 54px; color: #FFD60A;"></i>
                    @elseif($ks == 'approved')
                        <i class="fa-solid fa-circle-check" style="font-size: 54px; color: #30D158;"></i>
                    @elseif($ks == 'selesai')
                        <i class="fa-solid fa-check-double" style="font-size: 54px; color: #4A8BD9;"></i>
                    @else
                        <i class="fa-solid fa-circle-xmark" style="font-size: 54px; color: #FF453A;"></i>
                    @endif
                </div>

                <h3 class="fw-bold mb-2" style="color: var(--rc-text-primary);">Komplain Diproses</h3>
                <p style="color: var(--rc-text-secondary); font-size: 15px;">
                    Nomor Pesanan: <strong style="color: var(--rc-text-primary);">#RC-{{ $pesanan->id_pesanan }}</strong>
                </p>
                
                <hr style="border-color: var(--rc-card-border); margin: 30px 0;">

                <div class="row text-start justify-content-center">
                    <div class="col-md-11">
                        <table class="table table-borderless table-complain" style="font-size: 15px; margin-bottom: 0;">
                            <tr>
                                <td width="40%" class="text-muted-custom">PRODUK DIKOMPLAIN</td>
                                <td>: 
                                    @foreach($komplainAktif->detailKomplain as $dk)
                                        {{ $dk->produk->nama_produk ?? 'Produk' }} x{{ $dk->qty }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted-custom">JENIS KOMPLAIN</td>
                                <td>: {{ $komplainAktif->jenis_komplain }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted-custom">STATUS PERSETUJUAN</td>
                                <td>: 
                                    @if($ks == 'pending')
                                        <span class="badge" style="background: rgba(255, 214, 10, 0.1); color: #FFD60A; padding: 6px 14px; border-radius: 6px; font-weight: 700;">MENUNGGU ACC ADMIN</span>
                                    @elseif($ks == 'approved')
                                        <span class="badge" style="background: rgba(48, 209, 88, 0.1); color: #30D158; padding: 6px 14px; border-radius: 6px; font-weight: 700;">DISETUJUI (APPROVED)</span>
                                    @elseif($ks == 'selesai')
                                        <span class="badge" style="background: rgba(74, 139, 217, 0.1); color: #4A8BD9; padding: 6px 14px; border-radius: 6px; font-weight: 700;">SELESAI</span>
                                    @else
                                        <span class="badge" style="background: rgba(255, 69, 58, 0.1); color: #FF453A; padding: 6px 14px; border-radius: 6px; font-weight: 700;">DITOLAK</span>
                                    @endif
                                </td>
                            </tr>
                            @if($ks == 'approved' && $komplainAktif->no_resi_return)
                            <tr>
                                <td class="text-muted-custom">NO. RESI RETUR</td>
                                <td>: <strong style="color: var(--rc-text-primary);">{{ $komplainAktif->no_resi_return }}</strong></td>
                            </tr>
                            @endif
                            @if($ks == 'approved' && $komplainAktif->foto_return)
                            <tr>
                                <td class="text-muted-custom">FOTO BUKTI RETUR</td>
                                <td>: <a href="{{ asset('storage/' . $komplainAktif->foto_return) }}" target="_blank" style="color: var(--rc-text-secondary); text-decoration: underline;">Lihat</a></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="{{ route('pesanan.saya') }}" class="btn-complain-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Pesanan Saya
                    </a>
                </div>
            </div>

        @else
            {{-- NEW COMPLAINT FORM --}}
            <div class="complain-card">
                <div class="complain-card-header">
                    <h5 class="complain-title mb-0">
                        <i class="fa-solid fa-circle-exclamation" style="color: #FF453A; margin-right: 10px;"></i> Ajukan Komplain
                    </h5>
                    <p class="text-muted-custom small mb-0 mt-2">
                        Anda mengajukan komplain untuk pesanan: <strong style="color: var(--rc-text-primary);">#RC-{{ $pesanan->id_pesanan }}</strong>
                    </p>
                </div>
                
                <form action="{{ route('komplain.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">

                    <div class="mb-4">
                        <label class="form-label-custom">Pilih Produk yang Dikomplain</label>
                        @foreach($pesanan->detailPesanan as $detail)
                        <div class="product-card" data-id="{{ $detail->id_produk }}">
                            <label class="product-header">
                                <input type="checkbox" name="id_produk[]" value="{{ $detail->id_produk }}" class="me-3 product-checkbox" style="width: 18px; height: 18px; accent-color: var(--rc-accent);">
                                <img src="{{ asset('storage/' . ($detail->produk->foto_produk ?? $detail->produk->foto ?? 'default.jpg')) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 10px; margin-right: 14px;">
                                <div class="flex-grow-1">
                                    <strong style="color: var(--rc-text-primary);">{{ $detail->produk->nama_produk ?? 'Produk' }}</strong>
                                    <small style="color: var(--rc-text-secondary); display: block;">{{ $detail->jumlah }}x dibeli</small>
                                </div>
                                <div style="text-align: center;">
                                    <small style="color: var(--rc-text-secondary); font-size: 11px; display: block;">Jml Rusak</small>
                                    <input type="number" name="qty_{{ $detail->id_produk }}" value="1" min="1" max="{{ $detail->jumlah }}" class="input-custom" style="width: 60px; text-align: center; padding: 6px 8px; border-radius: 8px;">
                                </div>
                            </label>
                            <div class="product-body">
                                <small style="color: var(--rc-text-secondary); display: block; margin-bottom: 10px;">Upload Foto Bukti</small>
                                <div class="foto-list d-flex gap-3 flex-wrap" id="fotoList-{{ $detail->id_produk }}"></div>
                                <button type="button" class="btn-add-foto" data-produk="{{ $detail->id_produk }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; background: rgba(0,0,0,0.3); border: 1px dashed var(--rc-card-border); border-radius: 10px; color: var(--rc-text-secondary); cursor: pointer; font-size: 13px; transition: all 0.3s var(--transition-smooth);">
                                    <i class="fa-solid fa-plus"></i> Tambah Foto
                                </button>
                                <small style="color: var(--rc-text-secondary); display: block; margin-top: 6px;">Klik "Tambah Foto" untuk upload bukti (minimal 1 foto per produk)</small>
                            </div>
                        </div>
                        @endforeach
                        <small style="color: var(--rc-text-secondary); display: block; margin-top: 10px;">Centang produk yang ingin dikomplain, tentukan jumlah rusak, upload minimal 1 foto per produk (klik "Tambah Foto" untuk menambahkan)</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Deskripsi Komplain</label>
                        <textarea name="deskripsi" class="input-custom" rows="4" placeholder="Jelaskan secara detail masalah yang Anda alami..." required minlength="10" style="resize: vertical; font-family: inherit;">{{ old('deskripsi') }}</textarea>
                        <small style="color: var(--rc-text-secondary); display: block; margin-top: 6px;">Minimal 10 karakter. Ceritakan apa yang terjadi dengan produk yang diterima.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Jenis Komplain</label>
                        <select name="jenis_komplain" class="input-custom form-select" required>
                            <option value="">-- Pilih Alasan --</option>
                            <option value="Barang Rusak / Cacat">Barang Rusak / Cacat</option>
                            <option value="Barang Salah / Tidak Sesuai">Barang Salah / Tidak Sesuai</option>
                            <option value="Komponen / Item Kurang">Komponen / Item Kurang</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <a href="{{ url()->previous() }}" class="btn-complain-secondary">
                            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn-complain-submit">Kirim Komplain</button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.product-checkbox').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var card = this.closest('.product-card');
        if (this.checked) {
            card.classList.add('checked');
        } else {
            card.classList.remove('checked');
        }
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
                    preview.style.cssText = 'width:100px;height:100px;border-radius:12px;overflow:hidden;position:relative;border:1px solid rgba(255,255,255,0.08);flex-shrink:0;';

                    var img = document.createElement('img');
                    img.style.cssText = 'width:100%;height:100%;object-fit:cover;';

                    var removeBtn = document.createElement('span');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.style.cssText = 'position:absolute;top:-6px;right:-6px;width:22px;height:22px;border-radius:50%;background:#FF453A;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.4);line-height:1;font-weight:700;';
                    removeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        preview.remove();
                        input.remove();
                    });

                    preview.appendChild(img);
                    preview.appendChild(removeBtn);
                    list.appendChild(preview);

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
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
@endsection
