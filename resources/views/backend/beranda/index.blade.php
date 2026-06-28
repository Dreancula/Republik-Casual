@extends('backend.layouts.app')

@section('content_backend')
{{-- INITIALIZE FALLBACK VARIABLES (Mengamankan variabel jika tidak dikirim dari Controller) --}}
@php
    $grossRevenue = $grossRevenue ?? 0;
    $totalPengeluaran = $totalPengeluaran ?? 0;
    $netProfit = $netProfit ?? ($grossRevenue - $totalPengeluaran);
    $totalTerjual = $totalTerjual ?? 0;
@endphp

<div class="container-fluid p-0">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
        <div>
            <p class="rc-eyebrow">Sistem Pusat Kendali</p>
            <h2 class="rc-section-title" style="font-size: 1.6rem; color: var(--rc-text, #ffffff);">
                Halo, {{ Auth::user()->name ?? 'Staff Core' }}
            </h2>
            <p class="rc-section-desc">
                Berikut rangkuman laporan laba rugi dan metrik operasional Republik Casual.
            </p>
        </div>
        <div class="rc-period-pill">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Review Finansial & Operasional 2026
        </div>
    </div>

    {{-- ROW 1: METRIK KEUANGAN UTAMA (Pemasukan, Pengeluaran, Untung Bersih) --}}
    <h5 class="rc-label mb-3" style="font-size: 0.7rem; color: var(--rc-text-muted);">Neraca Keuangan Inti</h5>
    <div class="row g-4 mb-4 rc-stagger">
        
        {{-- CARD 1: PENDAPATAN KOTOR --}}
        <div class="col-xl-4 col-md-6">
            <div class="rc-glass-card" style="border-left: 3px solid var(--rc-success);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="rc-stat-label">Total Pendapatan (Beli)</span>
                    <span class="rc-stat-icon" style="color: var(--rc-success); background: var(--rc-success-soft);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </span>
                </div>
                <div class="rc-stat-value" style="color: var(--rc-text, #ffffff); font-size: 1.4rem;">
                    Rp {{ number_format($grossRevenue, 0, ',', '.') }}
                </div>
                <span style="font-size: 0.75rem; color: var(--rc-text-muted); font-weight: 500;">Dari pesanan berstatus lunas</span>
            </div>
        </div>

       {{-- CARD 2: TOTAL PENGELUARAN --}}
        <div class="col-xl-4 col-md-6">
            <div class="rc-glass-card" style="border-left: 3px solid #ef4444;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="rc-stat-label">Total Pengeluaran</span>
                    <span class="rc-stat-icon" style="color: #ef4444; background: rgba(239, 68, 68, 0.08);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </span>
                </div>
                <div class="rc-stat-value" style="color: var(--rc-text, #ffffff); font-size: 1.4rem;">
                    @if(isset($pemasukan))
                        Rp {{ number_format($pemasukan->sum(function($p) { 
                            return $p->detailPemasukanBarang->sum(function($d) { 
                                return $d->jumlah_masuk * $d->harga_beli; 
                            }); 
                        }), 0, ',', '.') }}
                    @else
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    @endif
                </div>
                <span style="font-size: 0.75rem; color: var(--rc-text-muted); font-weight: 500;">Biaya restock & pengadaan barang</span>
            </div>
        </div>

        {{-- CARD 3: KEUNTUNGAN BERSIH --}}
        <div class="col-xl-4 col-md-12">
            <div class="rc-glass-card" style="border-left: 3px solid {{ $netProfit >= 0 ? '#38bdf8' : '#ef4444' }}; background: rgba(255, 255, 255, 0.02);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="rc-stat-label">Keuntungan Bersih (Untung)</span>
                    <span class="rc-stat-icon" style="color: {{ $netProfit >= 0 ? '#38bdf8' : '#ef4444' }}; background: {{ $netProfit >= 0 ? 'rgba(56, 189, 248, 0.08)' : 'rgba(239, 68, 68, 0.08)' }};">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 6l-9.5 9.5-5-5L1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </span>
                </div>
                <div class="rc-stat-value" style="color: {{ $netProfit >= 0 ? '#34d399' : '#f87171' }}; font-size: 1.4rem; font-weight: 700;">
                    {{ $netProfit < 0 ? '-' : '' }} Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                </div>
                <span style="font-size: 0.75rem; color: var(--rc-text-muted); font-weight: 500;">
                    {{ $netProfit >= 0 ? 'Margin profit bernilai positif' : 'Warning: Pengeluaran lebih besar dari omset' }}
                </span>
            </div>
        </div>

    </div>

    {{-- ROW 2: METRIK OPERASIONAL --}}
    <h5 class="rc-label mb-3" style="font-size: 0.7rem; color: var(--rc-text-muted);">Aktivitas & Log Gudang</h5>
    <div class="row g-4 mb-5 rc-stagger">
        
        <div class="col-xl-3 col-md-6">
            <div class="rc-glass-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="rc-stat-label">Pesanan Baru</span>
                    <span class="rc-stat-icon" style="color: var(--rc-accent); background: var(--rc-accent-soft);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </span>
                </div>
                <div class="rc-stat-value" style="color: var(--rc-text, #ffffff);">{{ $pesananBaruCount ?? 0 }} Order</div>
                <span style="font-size: 0.75rem; color: var(--rc-text-muted); font-weight: 500;">Menunggu verifikasi kirim</span>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="rc-glass-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="rc-stat-label">Katalog Produk</span>
                    <span class="rc-stat-icon" style="color: var(--rc-terracotta); background: var(--rc-terracotta-soft);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                    </span>
                </div>
                <div class="rc-stat-value" style="color: var(--rc-text, #ffffff);">{{ number_format($totalProduk ?? 0, 0, ',', '.') }} Pcs</div>
                <span style="font-size: 0.75rem; font-weight: 500; color: {{ (Auth::check() && str_contains(strtolower(Auth::user()->role), 'manajer')) ? 'var(--rc-success)' : 'var(--rc-accent)' }};">
                    {{ (Auth::check() && str_contains(strtolower(Auth::user()->role), 'manajer')) ? 'Akses Penuh Terbuka' : 'Mode Baca Aktif' }}
                </span>
            </div>
        </div>

        {{-- BARU: CARD SEBERAPA BANYAK STOK PRODUK TERJUAL (PESANAN SELESAI) --}}
        <div class="col-xl-3 col-md-6">
            <div class="rc-glass-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="rc-stat-label">Produk Terjual</span>
                    <span class="rc-stat-icon" style="color: #10b981; background: rgba(16, 185, 129, 0.08);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </span>
                </div>
                <div class="rc-stat-value" style="color: #34d399;">{{ number_format($totalTerjual, 0, ',', '.') }} Item</div>
                <span style="font-size: 0.75rem; color: var(--rc-text-muted); font-weight: 500;">Dari pesanan selesai lunas</span>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="rc-glass-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="rc-stat-label">Barang Rusak (Deadstok)</span>
                    <span class="rc-stat-icon" style="color: #ef4444; background: rgba(239, 68, 68, 0.08);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </span>
                </div>
                <div class="rc-stat-value" style="color: #f87171;">Rp {{ number_format($totalDeadstokLoss ?? 0, 0, ',', '.') }}</div>
                <span style="font-size: 0.75rem; color: var(--rc-text-muted); font-weight: 500;">Total kerugian dari retur komplain</span>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="rc-glass-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="rc-stat-label">Akun Otentikasi</span>
                    <span class="rc-stat-icon" style="color: #a855f7; background: rgba(168,85,247,0.10);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </span>
                </div>
                <div class="rc-stat-value" style="color: var(--rc-text, #ffffff);">{{ $totalStaff ?? 0 }} Akun</div>
                <span style="font-size: 0.75rem; color: var(--rc-text-muted); font-weight: 500;">Manajer Toko & Admin Aktif</span>
            </div>
        </div>

    </div>

    {{-- ROLE BANNER --}}
    <div class="rc-glass-card rc-role-banner mb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h4 style="font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 600; margin-bottom: 0.3rem; color: var(--rc-text, #ffffff);">
                    Pemberitahuan Otoritas Sistem Kontrol
                </h4>
                @if(Auth::check() && str_contains(strtolower(Auth::user()->role), 'manajer'))
                    <p style="color: var(--rc-text-muted); font-size: 0.85rem; margin: 0;">
                        Sebagai <strong style="color: var(--rc-text, #ffffff);">Manajer Toko</strong>, Anda memiliki hak penuh untuk memodifikasi produk (CRUD) dan memperbarui kuantitas stok melalui modul <strong style="color: var(--rc-terracotta);">Pemasukan Barang</strong>.
                    </p>
                @else
                    <p style="color: var(--rc-text-muted); font-size: 0.85rem; margin: 0;">
                        Sebagai <strong style="color: var(--rc-text, #ffffff);">Admin Utama</strong>, hak akses produk Anda dikunci dalam mode <strong style="color: var(--rc-warning);">Read-Only (Lihat Saja)</strong> demi menjaga validasi stok fisik dari divisi Manajer Toko.
                    </p>
                @endif
            </div>
            <div>
                @if(Auth::check() && str_contains(strtolower(Auth::user()->role), 'manajer'))
                    <a href="{{ route('admin.pemasukan-barang.create') }}" class="rc-btn-primary text-decoration-none d-inline-block">
                        + Log Pemasukan Stok
                    </a>
                @else
                    <a href="{{ route('admin.produk.index') }}" class="rc-btn-outline text-decoration-none d-inline-block">
                        Lihat Katalog Produk
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- TRANSACTION TABLE --}}
    <div class="rc-glass-card">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.05rem; font-weight: 700; margin: 0; letter-spacing: -0.3px; color: var(--rc-text, #ffffff);">
                    Aktivitas Transaksi Masuk
                </h4>
                <p style="color: var(--rc-text-muted); font-size: 0.8rem; margin: 0.2rem 0 0 0;">
                    Data pesanan langsung dari sistem frontend konsumen.
                </p>
            </div>
            <a href="{{ route('admin.pesanan.index') }}" class="rc-btn-outline text-decoration-none d-inline-block">
                Lihat Semua Pesanan
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 rc-table" style="--bs-table-bg: transparent; color: var(--rc-text, #ffffff);">
                <thead>
                    <tr style="color: var(--rc-text, #ffffff); opacity: 0.9;">
                        <th class="pb-3 ps-2">ID Pesanan</th>
                        <th class="pb-3">Pelanggan</th>
                        <th class="pb-3">Produk Dibeli</th>
                        <th class="pb-3">Total Tagihan</th>
                        <th class="pb-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru ?? [] as $transaksi)
                        <tr>
                            <td class="font-monospace" style="color: var(--rc-text-muted); letter-spacing: 0.3px;">
                                #RC-{{ $transaksi->id_pesanan }}
                            </td>
                            <td style="font-weight: 600; color: var(--rc-text, #ffffff);">
                                {{ $transaksi->user->nama ?? 'Guest Customer' }}
                            </td>
                            <td style="color: var(--rc-text-muted); max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                @foreach($transaksi->detailPesanan as $detail)
                                    {{ $detail->quantity }}x {{ $detail->produk->nama_produk ?? 'Item Terhapus' }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td style="font-weight: 600; color: var(--rc-text, #ffffff);">
                                Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="text-center">

    @if($transaksi->status_pesanan == 'pending')

        <span class="rc-badge-status pending">
            Menunggu Pembayaran
        </span>

    @elseif($transaksi->status_pesanan == 'dibayar')

        <span
            class="rc-badge-status"
            style="
                background:rgba(16,185,129,.1);
                color:#10b981;
            "
        >
            Sudah Dibayar
        </span>

    @elseif($transaksi->status_pesanan == 'diproses')

        <span
            class="rc-badge-status"
            style="
                background:rgba(139,92,246,.1);
                color:#8b5cf6;
            "
        >
            Diproses
        </span>

    @elseif($transaksi->status_pesanan == 'dikirim')

        <span
            class="rc-badge-status"
            style="
                background:rgba(59,130,246,.1);
                color:#3b82f6;
            "
        >
            Dikirim
        </span>

    @elseif($transaksi->status_pesanan == 'selesai')

        <span class="rc-badge-status paid">
            Selesai
        </span>

    @elseif($transaksi->status_pesanan == 'dibatalkan')

        <span
            class="rc-badge-status"
            style="
                background:rgba(239,68,68,.1);
                color:#ef4444;
            "
        >
            Dibatalkan
        </span>

    @else

        <span
            class="rc-badge-status"
            style="
                background:rgba(107,114,128,.1);
                color:#9ca3af;
            "
        >
            {{ $transaksi->status_pesanan }}
        </span>

    @endif

</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-white-50">Belum ada transaksi terekam pada database.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection