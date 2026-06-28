@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- HEADER & AKSI CETAK --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-5 gap-3">
        <div>
            <p class="text-uppercase" style="color: var(--rc-terracotta); font-size: 0.75rem; letter-spacing: 2px; font-weight: 700; margin-bottom: 0.4rem;">
                Business Intelligence
            </p>
            <h2 style="font-weight: 700; letter-spacing: -0.5px; margin: 0;">
                Laporan & Analitik
            </h2>
        </div>
        
        <button onclick="window.print()" class="btn text-white px-4 py-2" 
                style="background: rgba(255,255,255,0.05); border: 1px solid var(--rc-glass-border); border-radius: 12px; font-weight: 600; font-size: 0.85rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
            Cetak Laporan PDF
        </button>
    </div>

    {{-- FILTER TANGGAL DINAMIS --}}
    <form action="{{ route('admin.laporan.index') }}" method="GET" class="rc-glass-card p-3 mb-4 d-flex align-items-center gap-3">
        <span style="font-size: 0.8rem; color: var(--rc-text-muted); font-weight: 600;">Periode:</span>
        <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}" class="form-control form-control-sm w-auto" style="background: #0e0e11; border: 1px solid var(--rc-glass-border); color: white; border-radius: 8px;">
        <span style="color: var(--rc-text-muted);">s/d</span>
        <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="form-control form-control-sm w-auto" style="background: #0e0e11; border: 1px solid var(--rc-glass-border); color: white; border-radius: 8px;">
        <button type="submit" class="btn btn-sm text-white" style="background: var(--rc-terracotta); border: none; border-radius: 8px; padding: 0.4rem 1rem;">Filter Data</button>
    </form>

    {{-- KARTU STATISTIK (HIGHLIGHTS) DINAMIS --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="rc-glass-card">
                <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Total Pendapatan</p>
                <h4 style="font-weight: 700; margin-top: 0.5rem; color: #fff;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rc-glass-card">
                <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Pesanan Berhasil</p>
                <h4 style="font-weight: 700; margin-top: 0.5rem; color: #fff;">{{ number_format($pesananBerhasilCount, 0, ',', '.') }} Transaksi</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rc-glass-card">
                <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Produk Terjual</p>
                <h4 style="font-weight: 700; margin-top: 0.5rem; color: #fff;">{{ number_format($totalProdukTerjual, 0, ',', '.') }} Pcs</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rc-glass-card">
                <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Rata-rata Order</p>
                <h4 style="font-weight: 700; margin-top: 0.5rem; color: #fff;">Rp {{ number_format($rataRataOrder, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    {{-- TABEL DETIL PRODUK TERLARIS --}}
    <div class="rc-glass-card">
        <h5 style="font-weight: 700; margin-bottom: 1.5rem; color: #fff;">Produk Terlaris (Top 5 Periode Ini)</h5>
        <div class="table-responsive">
            <table class="table table-dark table-borderless align-middle mb-0" style="--bs-table-bg: transparent;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--rc-glass-border); font-size: 0.8rem; color: var(--rc-text-muted); text-transform: uppercase;">
                        <th class="pb-3" style="width: 15%;">Peringkat</th>
                        <th class="pb-3">Nama Produk</th>
                        <th class="pb-3 text-center">Jumlah Terjual</th>
                        <th class="pb-3 text-end">Total Nilai</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.9rem;">
                    @forelse($produkTerlaris as $index => $item)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <td class="py-3 font-monospace text-white-50">#0{{ $index + 1 }}</td>
                            <td class="py-3" style="color: #fff; font-weight: 500;">
                                {{ $item->produk->nama_produk ?? 'Produk Tidak Diketahui / Dihapus' }}
                            </td>
                            <td class="py-3 text-center text-white-50">{{ number_format($item->total_terjual, 0, ',', '.') }} Pcs</td>
                            <td class="py-3 text-end font-weight-600" style="color: #fff;">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-white-50">Tidak ada data penjualan pada rentang periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection