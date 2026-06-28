@extends('backend.layouts.app')

@section('content_backend')
<style>
    /* VARIABEL TEMA */
    :root {
        --rc-bg-main: #0e0e10;
        --rc-card-bg: rgba(255, 255, 255, 0.015);
        --rc-border-color: rgba(255, 255, 255, 0.06);
        --rc-text-primary: #f3f4f6;
        --rc-text-secondary: #8e9196;
        --rc-accent-terracotta: #e65c2e;
        --rc-accent-soft: rgba(230, 92, 46, 0.08);
        --rc-success: #10b981;
        --rc-success-soft: rgba(16, 185, 129, 0.08);
        --rc-warning: #f59e0b;
        --rc-warning-soft: rgba(245, 158, 11, 0.08);
        --rc-danger: #ef4444;
        --rc-danger-soft: rgba(239, 68, 68, 0.08);
    }

    .rc-dashboard-wrapper {
        color: var(--rc-text-primary);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* KARTU FLAT */
    .rc-flat-card {
        background: var(--rc-card-bg);
        border: 1px solid var(--rc-border-color);
        border-radius: 12px;
        padding: 1.5rem;
    }

    /* SISTEM TOMBOL */
    .rc-btn-primary {
        background: var(--rc-accent-terracotta);
        color: #fff;
        border: 1px solid var(--rc-accent-terracotta);
        padding: 0.55rem 1.2rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .rc-btn-primary:hover {
        background: #d14d23;
        border-color: #d14d23;
        color: #fff;
    }

    .rc-btn-outline {
        background: transparent;
        color: var(--rc-text-primary);
        border: 1px solid var(--rc-border-color);
        padding: 0.4rem 0.9rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .rc-btn-outline:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: var(--rc-text-secondary);
        color: var(--rc-text-primary);
    }

    /* TYPOGRAPHY LABEL */
    .rc-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--rc-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    /* TABEL BERSIH */
    .rc-clean-table th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--rc-text-secondary);
        letter-spacing: 0.5px;
        padding-bottom: 1rem !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .rc-clean-table td {
        font-size: 0.9rem;
        color: var(--rc-text-primary);
        padding: 1.1rem 0.5rem !important;
        border-bottom: 1px solid var(--rc-border-color) !important;
    }
    .rc-clean-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* LENCANA STATUS & BADGE MINIMALIS */
    .rc-status-pill {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        display: inline-block;
        border: 1px solid transparent;
    }
    .rc-status-pill.status-active {
        background: var(--rc-success-soft);
        color: #34d399;
        border-color: rgba(16, 185, 129, 0.2);
    }
    .rc-status-pill.status-draft {
        background: var(--rc-warning-soft);
        color: #fbbf24;
        border-color: rgba(245, 158, 11, 0.2);
    }
    .rc-status-pill.status-danger {
        background: var(--rc-danger-soft);
        color: #f87171;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .rc-tag-inline {
        font-size: 0.75rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--rc-border-color);
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        color: #cbd5e1;
    }

    /* ALERT MINIMALIS */
    .rc-alert.success {
        background: var(--rc-success-soft);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.2);
        padding: 0.8rem 1.2rem;
        border-radius: 8px;
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid p-4 rc-dashboard-wrapper">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 pb-2 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="width: 6px; height: 6px; background-color: var(--rc-accent-terracotta); border-radius: 50%;"></span>
                <span class="rc-label">Katalog Gudang</span>
            </div>
            <h2 class="fw-bold m-0" style="font-size: 1.5rem; letter-spacing: -0.5px;">Master Data Produk</h2>
            <p class="m-0 mt-1" style="color: var(--rc-text-secondary); font-size: 0.85rem;">
                Kelola informasi katalog, harga jual, dan status publikasi front-end.
            </p>
        </div>
        
        {{-- PROTEKSI TOMBOL TAMBAH ARTIKEL - Hanya untuk Manajer Toko --}}
        @if(Auth::check() && str_contains(strtolower(Auth::user()->role), 'manajer'))
            <a href="{{ route('admin.produk.create') }}" class="rc-btn-primary text-decoration-none d-flex align-items-center gap-2">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Artikel Baru
            </a>
        @endif
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="rc-alert success mb-4">{{ session('success') }}</div>
    @endif

    {{-- TABLE CARD --}}
    <div class="rc-flat-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0 rc-clean-table" style="--bs-table-bg: transparent;">
                <thead>
                    <tr>
                        <th class="ps-2" style="width: 60px;">No</th>
                        <th style="width: 70px;">Foto</th>
                        <th>Nama Produk</th>
                        <th>Kategori / Brand</th>
                        <th>Stok Toko</th>
                        <th>Dead Stok</th>
                        <th>Harga Jual</th>
                        <th>Status Katalog</th>
                        <th class="text-end pe-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produk as $index => $prod)
                    <tr>
                        <td class="font-monospace ps-2" style="color: var(--rc-text-secondary); font-size: 0.8rem;">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>
                            <img src="{{ $prod->foto_produk ? asset('storage/'.$prod->foto_produk) : asset('image/icon_rc.png') }}" 
                                 width="44" height="44" class="rounded-2" style="object-fit: cover; border: 1px solid var(--rc-border-color);">
                        </td>
                        <td class="fw-semibold">{{ $prod->nama_produk }}</td>
                        <td>
                            <span class="rc-tag-inline">{{ $prod->kategori->nama_kategori ?? '-' }}</span>
                            <div style="color: var(--rc-text-secondary); font-size: 0.78rem; margin-top: 0.25rem;">{{ $prod->brand->nama_brand ?? '-' }}</div>
                        </td>
                        <td>
                            @if($prod->stok > 0)
                                <span class="rc-status-pill status-active">{{ $prod->stok }} pcs</span>
                            @else
                                <span class="rc-status-pill status-danger">Habis</span>
                            @endif
                        </td>
                        <td>
                            @if(($prod->deadstok ?? 0) > 0)
                                <span style="background: rgba(200, 90, 50, 0.12); color: #C85A32; padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">
                                    {{ $prod->deadstok }} pcs
                                </span>
                            @else
                                <span style="color: var(--rc-text-secondary); font-size: 0.85rem;">0</span>
                            @endif
                        </td>
                        <td class="fw-semibold">Rp {{ number_format($prod->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($prod->status_produk == 'aktif')
                                <span class="rc-status-pill status-active">Aktif</span>
                            @else
                                <span class="rc-status-pill status-draft">Draft</span>
                            @endif
                        </td>
                        <td class="text-end pe-2">
                            <div class="d-flex justify-content-end gap-2">
                                {{-- PROTEKSI OPERASI MODIFIKASI DATA (EDIT & HAPUS) --}}
                                @if(Auth::check() && str_contains(strtolower(Auth::user()->role), 'manajer'))
                                    {{-- Munculkan tombol jika yang login adalah Manajer Toko --}}
                                    <a href="{{ route('admin.produk.edit', $prod->id_produk) }}" class="rc-btn-outline text-decoration-none d-inline-flex align-items-center gap-1">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.produk.destroy', $prod->id_produk) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari katalog?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rc-btn-outline d-inline-flex align-items-center gap-1" style="color: var(--rc-danger); border-color: rgba(239,68,68,0.2);">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    {{-- Tampilan Read-Only khusus untuk Admin Utama --}}
                                    <span class="rc-status-pill status-draft" style="font-size: 0.8rem; border-style: dashed;">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-1"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        Dikunci
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5" style="color: var(--rc-text-secondary);">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2" style="opacity: 0.4;"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            <div class="fw-semibold" style="font-size: 0.95rem;">Belum ada data produk di database.</div>
                            @if(Auth::check() && str_contains(strtolower(Auth::user()->role), 'manajer'))
                                <small class="d-block mt-1" style="font-size: 0.8rem;">Klik tombol <strong style="color: var(--rc-text-primary);">Tambah Artikel Baru</strong> untuk mendaftarkan SKU.</small>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection