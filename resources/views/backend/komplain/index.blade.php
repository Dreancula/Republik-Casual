@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
        <div>
            <p class="rc-eyebrow">Quality Control</p>
            <h2 class="rc-section-title" style="font-size: 1.6rem; color: var(--rc-text, #ffffff);">
                Manajemen Komplain
            </h2>
            <p class="rc-section-desc">
                Pantau dan proses semua pengajuan retur barang dari pelanggan.
            </p>
        </div>
        <div class="rc-period-pill">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Retur & Pengembalian
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="rc-alert" style="background: var(--rc-success-soft); color: var(--rc-success); border: 1px solid rgba(48,209,88,0.2); border-radius: 12px; padding: 0.8rem 1.2rem; margin-bottom: 1.5rem; font-size: 0.85rem;">
            <i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rc-alert" style="background: var(--rc-danger-soft); color: var(--rc-danger); border: 1px solid rgba(255,69,58,0.2); border-radius: 12px; padding: 0.8rem 1.2rem; margin-bottom: 1.5rem; font-size: 0.85rem;">
            <i class="fa-solid fa-exclamation-circle me-1"></i> {{ session('error') }}
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="rc-glass-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0 rc-table" style="--bs-table-bg: transparent; color: var(--rc-text, #ffffff);">
                <thead>
                    <tr style="color: var(--rc-text, #ffffff); opacity: 0.9;">
                        <th class="pb-3 ps-2">ID Pesanan</th>
                        <th class="pb-3">Pelanggan</th>
                        <th class="pb-3">Produk</th>
                        <th class="pb-3">Jenis</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Resi Retur</th>
                        <th class="pb-3 text-end pe-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allKomplain as $item)
                    <tr>
                        <td class="font-monospace ps-2" style="color: var(--rc-accent); letter-spacing: 0.3px;">
                            #RC-{{ $item->id_pesanan }}
                        </td>
                        <td style="font-weight: 600; color: var(--rc-text, #ffffff);">
                            {{ $item->user->name ?? 'Customer' }}
                        </td>
                        <td style="color: var(--rc-text-muted); max-width: 240px;">
                            @forelse($item->detailKomplain as $dk)
                                <div style="font-weight: 500; color: var(--rc-text);">{{ $dk->produk->nama_produk ?? 'Produk tidak tersedia' }}</div>
                                <small style="color: var(--rc-text-muted);">x{{ $dk->qty }}</small>
                                @if(!$loop->last)<div class="mb-1"></div>@endif
                            @empty
                                <span style="font-weight: 500;">{{ $item->produk->nama_produk ?? 'Produk tidak tersedia' }}</span>
                                <small class="d-block" style="color: var(--rc-text-muted);">x{{ $item->qty }}</small>
                            @endforelse
                        </td>
                        <td>
                            <span class="rc-badge-status" style="background: var(--rc-danger-soft); color: var(--rc-danger); border: 1px solid rgba(255,69,58,0.15);">
                                {{ $item->jenis_komplain }}
                            </span>
                        </td>
                        <td>
                            @if($item->status_komplain == 'pending')
                                <span class="rc-badge-status pending">Pending</span>
                            @elseif($item->status_komplain == 'approved')
                                <span class="rc-badge-status" style="background: var(--rc-success-soft); color: var(--rc-success);">Approved</span>
                            @elseif($item->status_komplain == 'selesai')
                                <span class="rc-badge-status paid">Selesai</span>
                            @else
                                <span class="rc-badge-status" style="background: var(--rc-danger-soft); color: var(--rc-danger);">Ditolak</span>
                            @endif
                        </td>
                        <td style="color: var(--rc-text, #ffffff); font-size: 0.85rem;">
                            @if($item->no_resi_return)
                                <span class="font-monospace">{{ $item->no_resi_return }}</span>
                                @if($item->foto_return)
                                    <a href="{{ asset('storage/' . $item->foto_return) }}" target="_blank" class="d-block small" style="color: var(--rc-text-muted);">
                                        <i class="fa-solid fa-image"></i> Lihat bukti
                                    </a>
                                @endif
                            @else
                                <span style="color: var(--rc-text-muted);">-</span>
                            @endif
                        </td>
                        <td class="text-end pe-2">
                            <a href="{{ route('admin.komplain.show', $item->id_komplain) }}" class="rc-btn-outline text-decoration-none d-inline-flex align-items-center gap-1" style="padding: 0.4rem 1rem;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color: var(--rc-text-muted);">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2" style="opacity: 0.4;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <div class="fw-semibold" style="font-size: 0.95rem;">Belum ada pengajuan komplain masuk.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
