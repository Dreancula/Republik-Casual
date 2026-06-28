@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- HEADER --}}
    <div class="mb-5">
        <p class="rc-eyebrow">Manajemen Katalog</p>
        <h2 class="rc-section-title" style="font-size: 1.5rem;">Edit Artikel Produk</h2>
    </div>

    <div class="rc-glass-card">
        <form action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- NAMA PRODUK --}}
                <div class="col-12 col-md-6">
                    <label class="rc-label">Nama Produk / Artikel</label>
                    <input type="text" name="nama_produk" class="form-control rc-input" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                </div>

                {{-- HARGA --}}
                <div class="col-12 col-md-3">
                    <label class="rc-label">Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control rc-input" value="{{ old('harga', $produk->harga) }}" required>
                </div>

                {{-- BERAT --}}
                <div class="col-12 col-md-3">
                    <label class="rc-label">Berat (Gram)</label>
                    <input type="number" name="berat" class="form-control rc-input" value="{{ old('berat', $produk->berat) }}" required>
                </div>

                {{-- KATEGORI --}}
                <div class="col-12 col-md-4">
                    <label class="rc-label">Kategori</label>
                    <select name="id_kategori" class="form-select rc-select" required>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ $produk->id_kategori == $kat->id_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- BRAND --}}
                <div class="col-12 col-md-4">
                    <label class="rc-label">Brand / Apparel</label>
                    <select name="id_brand" class="form-select rc-select" required>
                        @foreach($brand as $brnd)
                            <option value="{{ $brnd->id_brand }}" {{ $produk->id_brand == $brnd->id_brand ? 'selected' : '' }}>{{ $brnd->nama_brand }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- SIZE --}}
                <div class="col-12 col-md-4">
                    <label class="rc-label">Ukuran (Size)</label>
                    <input type="text" name="size_produk" class="form-control rc-input" placeholder="Contoh: S, M, L, XL" value="{{ old('size_produk', $produk->size_produk) }}" required>
                </div>

                {{-- STATUS --}}
                <div class="col-12">
                    <label class="rc-label">Status Publikasi</label>
                    <select name="status_produk" class="form-select rc-select" required>
                        <option value="aktif" {{ $produk->status_produk == 'aktif' ? 'selected' : '' }}>Aktif (Tayang di Katalog)</option>
                        <option value="nonaktif" {{ $produk->status_produk == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Draft)</option>
                    </select>
                </div>

                {{-- FOTO --}}
                <div class="col-12 col-md-6">
                    <label class="rc-label">Foto Produk (.jpg, .png, .webp)</label>
                    <input type="file" name="foto_produk" class="form-control rc-input">
                    @if($produk->foto_produk)
                        <small class="d-block mt-2" style="color: var(--rc-text-muted);">File aktif: <span style="color: var(--rc-accent);">{{ $produk->foto_produk }}</span></small>
                    @endif
                </div>

                {{-- DESKRIPSI --}}
                <div class="col-12">
                    <label class="rc-label">Deskripsi Produk</label>
                    <textarea name="deskripsi_produk" class="form-control rc-input" rows="4" required>{{ old('deskripsi_produk', $produk->deskripsi_produk) }}</textarea>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="d-flex justify-content-end gap-2 mt-5">
                <a href="{{ route('admin.produk.index') }}" class="rc-btn-outline text-decoration-none">Batal</a>
                <button type="submit" class="rc-btn-primary" style="border: none;">Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>
@endsection
