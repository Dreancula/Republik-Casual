@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- HEADER MODUL --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-5 gap-3">
        <div>
            <p class="text-uppercase" style="color: var(--rc-terracotta); font-size: 0.75rem; letter-spacing: 2px; font-weight: 700; margin-bottom: 0.4rem;">
                Manajemen Logistik
            </p>
            <h2 style="font-weight: 700; letter-spacing: -0.5px; margin: 0;">
                Logistik Pemasukan Barang
            </h2>
            <p style="color: var(--rc-text-muted); font-size: 0.9rem; margin: 0; margin-top: 0.2rem;">
                Kelola riwayat faktur masuk dan pasokan stok produk gudang utama.
            </p>
        </div>

        {{-- Tombol Tambah Faktur --}}
        <a href="{{ route('admin.pemasukan-barang.create') }}" class="btn text-white px-4 py-2.5 d-flex align-items-center gap-2" 
           style="background: var(--rc-terracotta); border-radius: 12px; font-weight: 600; font-size: 0.9rem; border: none; box-shadow: 0 4px 15px rgba(200, 90, 50, 0.25); text-decoration: none;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Faktur Masuk
        </a>
    </div>

    {{-- KARTU RINGKASAN DATA (STATS CARD) --}}
    <div class="row g-3 mb-5">
        <div class="col-12 col-md-4">
            <div class="rc-glass-card p-4 d-flex align-items-center gap-3" style="background: rgba(255,255,255,0.01); border: 1px solid var(--rc-glass-border); border-radius: 16px;">
                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--rc-glass-border); color: var(--rc-terracotta);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Total Faktur</div>
                    <div class="fs-4 font-weight-700 text-white mt-1">{{ $pemasukan->count() }} Nota</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="rc-glass-card p-4 d-flex align-items-center gap-3" style="background: rgba(255,255,255,0.01); border: 1px solid var(--rc-glass-border); border-radius: 16px;">
                <div class="p-3 rounded-3" style="background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.15); color: #60a5fa;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Volume Item Masuk</div>
                    <div class="fs-4 font-weight-700 text-white mt-1">
                        {{ $pemasukan->sum(function($p) { return $p->detailPemasukanBarang->sum('jumlah_masuk'); }) }} Pcs
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="rc-glass-card p-4 d-flex align-items-center gap-3" style="background: rgba(255,255,255,0.01); border: 1px solid var(--rc-glass-border); border-radius: 16px;">
                <div class="p-3 rounded-3" style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.15); color: #34d399;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="12" x2="12" y2="12.01"/><path d="M20.42 4.58a10 10 0 1 0-14.14 14.14 10 10 0 0 0 14.14-14.14z"/></svg>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Total Pengeluaran Beli</div>
                    <div class="fs-4 font-weight-700 text-white mt-1">
                        Rp {{ number_format($pemasukan->sum(function($p) { 
                            return $p->detailPemasukanBarang->sum(function($d) { return $d->jumlah_masuk * $d->harga_beli; }); 
                        }), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL DATA MASTER --}}
    <div class="rc-glass-card">
        <div class="table-responsive">
            <table class="table table-dark table-borderless align-middle mb-0" style="--bs-table-bg: transparent;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--rc-glass-border); font-size: 0.8rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                        <th class="pb-3 pl-2" style="width: 60px;">No</th>
                        <th class="pb-3">Nomor Faktur</th>
                        <th class="pb-3">Tanggal Masuk</th>
                        <th class="pb-3">Petugas Gudang</th>
                        <th class="pb-3 text-center">Variasi Produk</th>
                        <th class="pb-3 text-end">Total Item</th>
                        <th class="pb-3 text-end">Total Biaya</th>
                        <th class="pb-3 text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.9rem;">
                    @forelse ($pemasukan as $index => $item)
                        @php
                            $jumlahVariasi = $item->detailPemasukanBarang->count();
                            $totalKuantitas = $item->detailPemasukanBarang->sum('jumlah_masuk');
                            $totalBiayaFaktur = $item->detailPemasukanBarang->sum(function($detail) {
                                return $detail->jumlah_masuk * $detail->harga_beli;
                            });
                        @endphp
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.02); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.01)'" onmouseout="this.style.background='transparent'">
                            <td class="py-3 text-white-50 font-monospace">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 font-weight-600 text-white">{{ $item->no_faktur }}</td>
                            <td class="py-3 text-white-50">
                                {{ \Carbon\Carbon::parse($item->tgl_pemasukan)->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="small rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                         style="width: 26px; height: 26px; font-size: 0.7rem; background: rgba(255,255,255,0.05); border: 1px solid var(--rc-glass-border);">
                                        {{ strtoupper(substr($item->user->nama ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="text-white-50">{{ $item->user->nama ?? 'Staff Tidak Diketahui' }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-dark text-light border px-2 py-1" style="border-color: var(--rc-glass-border) !important; font-size: 0.75rem; background: rgba(0,0,0,0.2) !important;">
                                    {{ $jumlahVariasi }} SKU
                                </span>
                            </td>
                            <td class="py-3 text-end font-weight-600 text-white-50">{{ number_format($totalKuantitas, 0, ',', '.') }} pcs</td>
                            <td class="py-3 text-end font-weight-600" style="color: #10b981;">
                                Rp {{ number_format($totalBiayaFaktur, 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-center">
                                <a href="{{ route('admin.pemasukan-barang.show', $item->id_pemasukan) }}" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.03); border: 1px solid var(--rc-glass-border); border-radius: 8px; padding: 0.4rem 0.6rem; font-size: 0.85rem;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5" style="color: var(--rc-text-muted);">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3" style="opacity: 0.5;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                                <div class="font-weight-600 text-white-50">Belum Ada Riwayat Pemasukan Barang</div>
                                <small class="d-block mt-1" style="color: var(--rc-text-muted);">Silakan tambahkan data faktur logistik baru menggunakan tombol di atas.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection