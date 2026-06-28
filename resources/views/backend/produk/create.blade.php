@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- HEADER --}}
    <div class="d-flex flex-column mb-5">
        <a href="{{ route('admin.produk.index') }}" class="text-decoration-none d-flex align-items-center gap-2 mb-3" style="color: var(--rc-text-muted); font-size: 0.85rem; transition: color 0.2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Katalog
        </a>
        <p class="rc-eyebrow">Katalog Master Data</p>
        <h2 class="rc-section-title" style="font-size: 1.5rem;">Tambah Artikel Produk Baru</h2>
        <p class="rc-section-desc">Daftarkan SKU atau artikel pakaian baru ke dalam database sistem.</p>
    </div>

    {{-- NOTIFIKASI ERROR --}}
    @if ($errors->any())
        <div class="rc-alert error mb-4">
            <div style="font-weight: 600; margin-bottom: 0.25rem;">Ada kendala pengisian formulir:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            {{-- PANEL KIRI: DATA IDENTITAS --}}
            <div class="col-12 col-lg-7">
                <div class="rc-glass-card">
                    <h5 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.95rem; margin-bottom: 1.25rem; color: var(--rc-text);">Detail Atribut Artikel</h5>

                    <div class="mb-3">
                        <label class="rc-label">Nama Lengkap Produk</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="form-control rc-input" placeholder="Contoh: Kaos Oversize Terracotta Premium" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="rc-label">Kategori Pakaian</label>
                            <select name="id_kategori" class="form-select rc-select" required>
                                <option value="" disabled selected style="color: var(--rc-text-muted);">-- Pilih Kategori --</option>
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="rc-label">Brand / Apparel</label>
                            <select name="id_brand" class="form-select rc-select" required>
                                <option value="" disabled selected style="color: var(--rc-text-muted);">-- Pilih Brand --</option>
                                @foreach ($brand as $b)
                                    <option value="{{ $b->id_brand }}" {{ old('id_brand') == $b->id_brand ? 'selected' : '' }}>{{ $b->nama_brand }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="rc-label">Deskripsi / Spesifikasi Produk</label>
                        <textarea name="deskripsi_produk" rows="4" class="form-control rc-input" placeholder="Tuliskan detail bahan, petunjuk pencucian, atau spesifikasi fitting di sini...">{{ old('deskripsi_produk') }}</textarea>
                    </div>

                    <div class="mb-0">
                        <label class="rc-label">Foto Artikel Produk (Opsional)</label>
                        <input type="file" name="foto_produk" class="form-control rc-input">
                        <div class="form-text" style="font-size: 0.75rem; color: var(--rc-text-muted); margin-top: 0.3rem;">Format: JPG, JPEG, PNG, WEBP (Maks. 2MB)</div>
                    </div>
                </div>
            </div>

            {{-- PANEL KANAN: DIMENSI & NILAI JUAL --}}
            <div class="col-12 col-lg-5">
                <div class="rc-glass-card d-flex flex-column justify-content-between" style="height: 100%;">
                    <div>
                        <h5 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.95rem; margin-bottom: 1.25rem; color: var(--rc-text);">Dimensi & Nilai Jual</h5>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="rc-label">Ukuran (Size)</label>
                                <select name="size_produk" class="form-select rc-select" required>
                                    <option value="S" {{ old('size_produk') == 'S' ? 'selected' : '' }}>S</option>
                                    <option value="M" {{ old('size_produk') == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="L" {{ old('size_produk') == 'L' ? 'selected' : '' }} selected>L</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="rc-label">Berat (Gram)</label>
                                <input type="number" name="berat" value="{{ old('berat', 200) }}" min="1" class="form-control rc-input" placeholder="200" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="rc-label">Harga Jual Toko (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text rc-input-group-text">Rp</span>
                                <input type="number" name="harga" value="{{ old('harga', 0) }}" min="0" class="form-control rc-input" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;" placeholder="0" required>
                            </div>
                            <div class="form-text" style="font-size: 0.75rem; color: var(--rc-text-muted); margin-top: 0.3rem;">Set ke <strong style="color: var(--rc-text);">0</strong> jika nilai ditentukan via faktur logistik.</div>
                        </div>

                        <input type="hidden" name="stok" value="0">
                        <input type="hidden" name="status_produk" value="Nonaktif">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="rc-btn-primary w-100" style="border: none; padding: 0.7rem 1rem; font-size: 0.9rem;">Daftarkan Produk Baru</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
