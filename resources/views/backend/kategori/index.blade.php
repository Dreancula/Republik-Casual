@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- HEADER --}}
    <div class="mb-5">
        <p class="rc-eyebrow">Atribut Produk</p>
        <h2 class="rc-section-title" style="font-size: 1.5rem; color: var(--rc-text, #ffffff);">Manajemen Kategori Pakaian</h2>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="rc-alert success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rc-alert error mb-4">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        {{-- FORM TAMBAH --}}
        <div class="col-12 col-md-4">
            <div class="rc-glass-card">
                <h5 style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.95rem; margin-bottom: 1.25rem; color: var(--rc-text, #ffffff);">Tambah Kategori</h5>
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="rc-label" style="color: var(--rc-text, #ffffff); font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 0.5rem;">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control rc-input" placeholder="Contoh: Outerwear" style="color: var(--rc-text, #ffffff); background: transparent;" required>
                        @error('nama_kategori')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="rc-btn-primary w-100" style="border: none;">Simpan Kategori</button>
                </form>
            </div>
        </div>

        {{-- TABLE DATA --}}
        <div class="col-12 col-md-8">
            <div class="rc-glass-card">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 rc-table" style="--bs-table-bg: transparent; color: var(--rc-text, #ffffff);">
                        <thead>
                            <tr style="color: var(--rc-text, #ffffff); opacity: 0.9;">
                                <th style="width: 80px; color: inherit;">No</th>
                                <th style="color: inherit;">Nama Kategori</th>
                                <th class="text-end" style="color: inherit;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategori as $index => $kat)
                            <tr>
                                <td class="font-monospace" style="color: var(--rc-text-muted);-letter-spacing: 0.3px;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td style="font-weight: 600; color: var(--rc-text, #ffffff);">{{ $kat->nama_kategori }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.kategori.destroy', $kat->id_kategori) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rc-btn-outline" style="padding: 0.3rem 0.7rem; font-size: 0.8rem; color: var(--rc-danger, #ef4444); border-color: rgba(239,68,68,0.2);">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4" style="color: var(--rc-text-muted, #9ca3af);">Belum ada data kategori.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection