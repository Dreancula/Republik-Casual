@extends('backend.layouts.app')

@section('content_backend')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<div class="container-fluid p-0">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3 no-print">
        <div>
            <p class="text-uppercase" style="color: var(--rc-terracotta); font-size: 0.75rem; letter-spacing: 2px; font-weight: 700; margin-bottom: 0.4rem;">
                Business Intelligence
            </p>
            <h2 style="font-weight: 700; letter-spacing: -0.5px; margin: 0;">
                Laporan & Analitik
            </h2>
        </div>
        <button onclick="cetakLaporan()" class="btn text-white px-4 py-2" 
                style="background: rgba(255,255,255,0.05); border: 1px solid var(--rc-glass-border); border-radius: 12px; font-weight: 600; font-size: 0.85rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
            Cetak PDF
        </button>
    </div>

    {{-- FILTER TANGGAL --}}
    <form action="{{ route('admin.laporan.index') }}" method="GET" class="rc-glass-card p-3 mb-4 d-flex flex-wrap align-items-center gap-3 no-print">
        <span style="font-size: 0.8rem; color: var(--rc-text-muted); font-weight: 600;">Periode:</span>
        <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}" class="form-control form-control-sm w-auto" style="background: #0e0e11; border: 1px solid var(--rc-glass-border); color: white; border-radius: 8px;">
        <span style="color: var(--rc-text-muted);">s/d</span>
        <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="form-control form-control-sm w-auto" style="background: #0e0e11; border: 1px solid var(--rc-glass-border); color: white; border-radius: 8px;">
        <button type="submit" class="btn btn-sm text-white" style="background: var(--rc-terracotta); border: none; border-radius: 8px; padding: 0.4rem 1rem;">Filter Data</button>
    </form>

    {{-- FILTER SEKSI --}}
    <style>
    .rc-filter-tabs {
        display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 1.5rem;
    }
    .rc-filter-tab {
        padding: 6px 18px; border-radius: 100px; font-size: 0.78rem; font-weight: 600;
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);
        color: var(--rc-text-muted); cursor: pointer; transition: all 0.2s;
        text-decoration: none;
    }
    .rc-filter-tab:hover { background: rgba(255,255,255,0.08); color: var(--rc-text); }
    .rc-filter-tab.active { background: var(--rc-terracotta); border-color: var(--rc-terracotta); color: #fff; }
    .rc-section { display: none; }
    .rc-section.visible { display: block; }

    .rc-detail-toggle {
        font-size: 0.75rem; color: var(--rc-text-muted); cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px; margin-top: 0.5rem;
        transition: color 0.2s; user-select: none;
    }
    .rc-detail-toggle:hover { color: var(--rc-accent); }
    .rc-detail-toggle .arrow { transition: transform 0.2s; display: inline-block; }
    .rc-detail-toggle.open .arrow { transform: rotate(90deg); }
    .rc-detail-content { display: none; margin-top: 1rem; }
    .rc-detail-content.open { display: block; }

    .rc-detail-table { width: 100%; font-size: 0.78rem; border-collapse: collapse; }
    .rc-detail-table th { color: var(--rc-text-muted); font-weight: 600; text-transform: uppercase; padding: 8px 10px; border-bottom: 1px solid rgba(255,255,255,0.06); text-align: left; font-size: 0.7rem; }
    .rc-detail-table td { padding: 7px 10px; border-bottom: 1px solid rgba(255,255,255,0.03); color: var(--rc-text); }
    .rc-detail-table tr:hover td { background: rgba(255,255,255,0.02); }

    .print-header { display: none; }

    @media print {
        @page { margin: 1.2cm 1.5cm; size: A4 portrait; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

        body { background: #fff !important; padding: 0 !important; }

        .print-header { display: block !important; margin-bottom: 18pt !important; }
        .print-header h1 { font-size: 20pt; font-weight: 700; color: #111; }
        .print-header p { font-size: 9pt; color: #555; }

        .no-print, .rc-filter-tabs, .rc-filter-tab, .rc-detail-toggle .arrow,
        .btn, form[action], .rc-info-btn, .rc-glass-card small,
        .d-flex.justify-content-between button, svg { display: none !important; }

        .rc-section { display: block !important; margin-bottom: 24pt !important; }
        .rc-detail-content { display: block !important; }
        .rc-detail-toggle { display: block !important; font-size: 10pt !important; font-weight: 700 !important; color: #222 !important; margin: 12pt 0 6pt !important; cursor: default !important; }

        .container-fluid { max-width: 100% !important; padding: 0 !important; }
        .row { display: block !important; }
        .row > div { width: 100% !important; max-width: 100% !important; margin-bottom: 8pt !important; padding: 0 !important; }
        .col-md-4, .col-md-12 { float: none !important; width: 100% !important; }

        .rc-glass-card {
            background: #f8f9fa !important; border: 1px solid #dee2e6 !important;
            border-radius: 6pt !important; padding: 10pt 14pt !important;
            box-shadow: none !important; margin-bottom: 8pt !important;
            break-inside: avoid; page-break-inside: avoid;
            border-left-width: 4px !important; border-left-style: solid !important;
        }
        .rc-glass-card h4 { font-size: 14pt !important; margin-top: 4pt !important; }
        .rc-glass-card p { font-size: 8pt !important; color: #666 !important; }

        h5 { font-size: 10pt !important; color: #333 !important; letter-spacing: 0.5pt !important; margin-top: 16pt !important; }
        h2 { font-size: 18pt !important; color: #111 !important; }

        .rc-detail-table { width: 100% !important; font-size: 8pt !important; border-collapse: collapse !important; }
        .rc-detail-table th { color: #555 !important; border-bottom: 2px solid #ccc !important; padding: 5pt 6pt !important; font-size: 7pt !important; }
        .rc-detail-table td { color: #222 !important; border-bottom: 1px solid #e0e0e0 !important; padding: 4pt 6pt !important; }
        .rc-detail-table tr:hover td { background: transparent !important; }

        .table-dark { --bs-table-bg: transparent !important; }
        .table-dark thead th { color: #555 !important; border-bottom: 2px solid #ccc !important; }
        .table-dark tbody td { color: #222 !important; border-bottom: 1px solid #e0e0e0 !important; }

        .text-white-50 { color: #666 !important; }
        .text-white, [style*="color: #fff"], [style*="color: #fff;"] { color: #222 !important; }
        [style*="color: #10b981"] { color: #059669 !important; }
        [style*="color: #ef4444"] { color: #dc2626 !important; }
        [style*="color: #f59e0b"] { color: #d97706 !important; }
        [style*="color: #f97316"] { color: #ea580c !important; }

        .rc-glass-card[style*="border-left: 3px solid #10b981"] { border-left-color: #059669 !important; }
        .rc-glass-card[style*="border-left: 3px solid #ef4444"] { border-left-color: #dc2626 !important; }
        .rc-glass-card[style*="border-left: 3px solid #f59e0b"] { border-left-color: #d97706 !important; }
        .rc-glass-card[style*="border-left: 3px solid #f97316"] { border-left-color: #ea580c !important; }
        .rc-glass-card[style*="border-left: 3px solid var(--rc-text)"] { border-left-color: #333 !important; }

        .mb-4, .mb-3, .g-4 { margin-bottom: 0 !important; }
        .d-flex.align-items-center.justify-content-between .rc-glass-card small { display: none !important; }

        .table-responsive { overflow: visible !important; }
        .font-monospace { color: #555 !important; }

        [style*="height: 300px"], [style*="height: 340px"] { height: auto !important; min-height: 200px !important; }

        .text-muted, [style*="var(--rc-text-muted)"] { color: #666 !important; }

        #chartFinancial, #chartProducts, #chartTrend { max-height: 180px !important; }
    }
    </style>

    {{-- PRINT HEADER --}}
    <div class="print-header" style="display:none;">
        <div style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
            <h1 style="font-size: 20px; font-weight: 700; margin: 0; letter-spacing: -0.5px;">Republic Casual</h1>
            <p style="margin: 4px 0 0; font-size: 11px; color: #666;">
                Laporan Bisnis &mdash; Periode {{ \Carbon\Carbon::parse($tanggalAwal)->isoFormat('D MMMM YYYY') }} &mdash; {{ \Carbon\Carbon::parse($tanggalAkhir)->isoFormat('D MMMM YYYY') }}
            </p>
        </div>
    </div>

    <div class="rc-filter-tabs">
        <span class="rc-filter-tab active" data-filter="all" onclick="filterSection('all')">Semua</span>
        <span class="rc-filter-tab" data-filter="pemasukan" onclick="filterSection('pemasukan')">Pemasukan</span>
        <span class="rc-filter-tab" data-filter="pengeluaran" onclick="filterSection('pengeluaran')">Pengeluaran</span>
        <span class="rc-filter-tab" data-filter="operasional" onclick="filterSection('operasional')">Operasional</span>
        <span class="rc-filter-tab" data-filter="labarugi" onclick="filterSection('labarugi')">Laba/Rugi</span>
        <span class="rc-filter-tab" data-filter="chart" onclick="filterSection('chart')">Diagram</span>
    </div>

    {{-- SEKSI: PEMASUKAN --}}
    <div class="rc-section visible" data-section="pemasukan">
        <h5 class="mb-3" style="font-size: 0.7rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Pemasukan</h5>
        <div class="row g-4 mb-3">
            <div class="col-md-4">
                <div class="rc-glass-card" style="border-left: 3px solid #10b981;">
                    <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Pendapatan Produk</p>
                    <h4 style="font-weight: 700; margin-top: 0.5rem; color: #10b981;">Rp {{ number_format($pendapatanProduk, 0, ',', '.') }}</h4>
                    <small style="color: var(--rc-text-muted);">Dari penjualan produk</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rc-glass-card" style="border-left: 3px solid #f59e0b;">
                    <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Pendapatan Ongkir</p>
                    <h4 style="font-weight: 700; margin-top: 0.5rem; color: #f59e0b;">Rp {{ number_format($pendapatanOngkir, 0, ',', '.') }}</h4>
                    <small style="color: var(--rc-text-muted);">Dari biaya kirim customer</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rc-glass-card" style="border-left: 3px solid var(--rc-text);">
                    <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Total Pemasukan</p>
                    <h4 style="font-weight: 700; margin-top: 0.5rem; color: #fff;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                    <small style="color: var(--rc-text-muted);">Produk + Ongkir</small>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 1.5rem;">
            <span class="rc-detail-toggle" onclick="toggleDetail(this)">
                <span class="arrow">&#9654;</span> Lihat Detail Pemasukan
            </span>
            <div class="rc-detail-content">
                <div class="rc-glass-card" style="padding: 1rem;">
                    @if($detailPemasukan->count())
                    <table class="rc-detail-table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th style="text-align:right;">Produk</th>
                                <th style="text-align:right;">Ongkir</th>
                                <th style="text-align:right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailPemasukan as $dp)
                            <tr>
                                <td style="font-family: monospace; color: var(--rc-text-muted);">#RC-{{ $dp->id_pesanan }}</td>
                                <td>{{ $dp->user->nama ?? '-' }}</td>
                                <td style="color: var(--rc-text-muted);">{{ \Carbon\Carbon::parse($dp->tgl_pesanan)->format('d/m/Y') }}</td>
                                <td style="text-align:right; color: #10b981;">Rp {{ number_format($dp->total_harga_produk, 0, ',', '.') }}</td>
                                <td style="text-align:right; color: #f59e0b;">Rp {{ number_format($dp->total_ongkir, 0, ',', '.') }}</td>
                                <td style="text-align:right; font-weight: 600;">Rp {{ number_format($dp->total_bayar, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p style="color: var(--rc-text-muted); text-align: center; padding: 1rem 0; margin: 0;">Tidak ada data pemasukan pada periode ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SEKSI: PENGELUARAN --}}
    <div class="rc-section" data-section="pengeluaran">
        <h5 class="mb-3" style="font-size: 0.7rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Pengeluaran</h5>
        <div class="row g-4 mb-3">
            <div class="col-md-4">
                <div class="rc-glass-card" style="border-left: 3px solid #ef4444;">
                    <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Pengeluaran Barang</p>
                    <h4 style="font-weight: 700; margin-top: 0.5rem; color: #ef4444;">Rp {{ number_format($pengeluaranBarang ?? 0, 0, ',', '.') }}</h4>
                    <small style="color: var(--rc-text-muted);">Modal restock produk</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rc-glass-card" style="border-left: 3px solid #f97316;">
                    <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Pengeluaran Ongkir</p>
                    <h4 style="font-weight: 700; margin-top: 0.5rem; color: #f97316;">Rp {{ number_format($pendapatanOngkir, 0, ',', '.') }}</h4>
                    <small style="color: var(--rc-text-muted);">Dibayarkan ke jasa kurir</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rc-glass-card" style="border-left: 3px solid var(--rc-text);">
                    <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Total Pengeluaran</p>
                    <h4 style="font-weight: 700; margin-top: 0.5rem; color: #fff;">Rp {{ number_format($pendapatanOngkir + ($pengeluaranBarang ?? 0), 0, ',', '.') }}</h4>
                    <small style="color: var(--rc-text-muted);">Barang + Ongkir</small>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 1.5rem;">
            <span class="rc-detail-toggle" onclick="toggleDetail(this)">
                <span class="arrow">&#9654;</span> Lihat Detail Pengeluaran
            </span>
            <div class="rc-detail-content">
                <div class="rc-glass-card" style="padding: 1rem;">
                    @if($detailPengeluaran->count())
                    <table class="rc-detail-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th style="text-align:center;">Jumlah</th>
                                <th style="text-align:right;">Harga Beli</th>
                                <th style="text-align:right;">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailPengeluaran as $dp)
                            <tr>
                                <td style="color: var(--rc-text-muted);">{{ \Carbon\Carbon::parse($dp->tgl_pemasukan)->format('d/m/Y') }}</td>
                                <td>{{ $dp->nama_produk }}</td>
                                <td style="text-align:center;">{{ number_format($dp->jumlah_masuk) }} pcs</td>
                                <td style="text-align:right; color: #ef4444;">Rp {{ number_format($dp->harga_beli, 0, ',', '.') }}</td>
                                <td style="text-align:right; font-weight: 600;">Rp {{ number_format($dp->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p style="color: var(--rc-text-muted); text-align: center; padding: 1rem 0; margin: 0;">Tidak ada data pengeluaran pada periode ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SEKSI: OPERASIONAL --}}
    <div class="rc-section" data-section="operasional">
        <h5 class="mb-3" style="font-size: 0.7rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Operasional</h5>
        <div class="row g-4 mb-3">
            <div class="col-md-4">
                <div class="rc-glass-card">
                    <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Pesanan Berhasil</p>
                    <h4 style="font-weight: 700; margin-top: 0.5rem; color: #fff;">{{ number_format($pesananBerhasilCount, 0, ',', '.') }} Transaksi</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rc-glass-card">
                    <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Produk Terjual</p>
                    <h4 style="font-weight: 700; margin-top: 0.5rem; color: #fff;">{{ number_format($totalProdukTerjual, 0, ',', '.') }} Pcs</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rc-glass-card">
                    <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Rata-rata Nilai Produk/Order</p>
                    <h4 style="font-weight: 700; margin-top: 0.5rem; color: #fff;">Rp {{ number_format($rataRataOrder, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- SEKSI: LABA / RUGI --}}
    <div class="rc-section" data-section="labarugi">
        @php $labaBersih = $pendapatanProduk - ($pengeluaranBarang ?? 0); @endphp
        <h5 class="mb-3" style="font-size: 0.7rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Laba / Rugi</h5>
        <div class="row g-4 mb-3">
            <div class="col-md-12">
                <div class="rc-glass-card" style="border-left: 3px solid {{ $labaBersih >= 0 ? '#10b981' : '#ef4444' }}; background: rgba(255,255,255,0.02);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; margin-bottom: 0.2rem;">Laba / Rugi Bersih</p>
                            <h4 style="font-weight: 700; margin-top: 0.5rem; color: {{ $labaBersih >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ $labaBersih < 0 ? '-' : '' }}Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
                            </h4>
                        </div>
                        <div style="text-align: right;">
                            <small style="color: var(--rc-text-muted); display: block;">Pendapatan Produk - Pengeluaran Barang</small>
                            <small style="color: var(--rc-text-muted); display: block; margin-top: 2px;">
                                Ongkir: Rp {{ number_format($pendapatanOngkir, 0, ',', '.') }} (passthrough)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 1.5rem;">
            <span class="rc-detail-toggle" onclick="toggleDetail(this)">
                <span class="arrow">&#9654;</span> Lihat Detail Perhitungan
            </span>
            <div class="rc-detail-content">
                <div class="rc-glass-card" style="padding: 1rem;">
                    <table class="rc-detail-table">
                        <tbody>
                            <tr>
                                <td style="color: var(--rc-text-muted);">Pendapatan Produk</td>
                                <td style="text-align:right; color: #10b981; font-weight: 600;">Rp {{ number_format($pendapatanProduk, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="color: var(--rc-text-muted);">Pengeluaran Barang (Modal)</td>
                                <td style="text-align:right; color: #ef4444; font-weight: 600;">- Rp {{ number_format($pengeluaranBarang ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="color: var(--rc-text-muted);">Pendapatan Ongkir (passthrough)</td>
                                <td style="text-align:right; color: #f59e0b; font-weight: 600;">Rp {{ number_format($pendapatanOngkir, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td style="color: var(--rc-text-muted);">Pengeluaran Ongkir (ke kurir)</td>
                                <td style="text-align:right; color: #f97316; font-weight: 600;">- Rp {{ number_format($pendapatanOngkir, 0, ',', '.') }}</td>
                            </tr>
                            <tr style="border-top: 2px solid rgba(255,255,255,0.1);">
                                <td style="font-weight: 700; color: #fff;">Laba / Rugi Bersih</td>
                                <td style="text-align:right; font-weight: 700; color: {{ $labaBersih >= 0 ? '#34d399' : '#f87171' }}; font-size: 1rem;">
                                    {{ $labaBersih < 0 ? '-' : '' }}Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- SEKSI: DIAGRAM --}}
    <div class="rc-section" data-section="chart">
        <h5 class="mb-3" style="font-size: 0.7rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Visualisasi Data</h5>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="rc-glass-card" style="height: 300px; display: flex; flex-direction: column;">
                    <p style="font-size: 0.7rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem;">Pemasukan vs Pengeluaran</p>
                    <div style="flex: 1; position: relative;"><canvas id="chartFinancial"></canvas></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rc-glass-card" style="height: 340px; display: flex; flex-direction: column;">
                    <p style="font-size: 0.7rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Produk Terlaris</p>
                    <div style="flex: 1; position: relative; min-height: 0;"><canvas id="chartProducts"></canvas></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rc-glass-card" style="height: 300px; display: flex; flex-direction: column;">
                    <p style="font-size: 0.7rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem;">Tren Pendapatan (12 Bulan)</p>
                    <div style="flex: 1; position: relative;"><canvas id="chartTrend"></canvas></div>
                </div>
            </div>
        </div>
        <div class="rc-glass-card mb-4">
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
                            <td class="py-3" style="color: #fff; font-weight: 500;">{{ $item->produk->nama_produk ?? 'Produk Tidak Diketahui / Dihapus' }}</td>
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

</div>

<script>
function filterSection(section) {
    document.querySelectorAll('.rc-filter-tab').forEach(function(el) {
        if (el.dataset.filter === section) {
            el.classList.add('active');
        } else {
            el.classList.remove('active');
        }
    });
    document.querySelectorAll('.rc-section').forEach(function(el) {
        if (section === 'all' || el.dataset.section === section) {
            el.classList.add('visible');
        } else {
            el.classList.remove('visible');
        }
    });
}

function toggleDetail(el) {
    el.classList.toggle('open');
    var content = el.nextElementSibling;
    content.classList.toggle('open');
}

function cetakLaporan() {
    window.print();
}

document.addEventListener('DOMContentLoaded', function () {
    var textColor = '#d4d4d8';
    var gridColor = 'rgba(255,255,255,0.06)';

    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    function fmtRp(v) { return 'Rp' + (v / 1000).toFixed(0) + 'k'; }

    new Chart(document.getElementById('chartFinancial'), {
        type: 'bar',
        data: {
            labels: ['Produk', 'Ongkir'],
            datasets: [
                {
                    label: 'Pemasukan',
                    data: [{{ $pendapatanProduk }}, {{ $pendapatanOngkir }}],
                    backgroundColor: ['rgba(16,185,129,0.7)', 'rgba(245,158,11,0.7)'],
                    borderColor: ['#10b981', '#f59e0b'],
                    borderWidth: 1, borderRadius: 4,
                },
                {
                    label: 'Pengeluaran',
                    data: [{{ $pengeluaranBarang ?? 0 }}, {{ $pendapatanOngkir }}],
                    backgroundColor: ['rgba(239,68,68,0.6)', 'rgba(249,115,22,0.6)'],
                    borderColor: ['#ef4444', '#f97316'],
                    borderWidth: 1, borderRadius: 4,
                },
            ],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            color: textColor,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, color: textColor } } },
            scales: {
                x: { grid: { display: false }, ticks: { color: textColor } },
                y: { ticks: { color: textColor, callback: fmtRp } },
            },
        },
    });

    function truncate(str, n) { return str.length > n ? str.slice(0, n) + '...' : str; }

    var produkLabels = [];
    var produkData = [];
    var produkColors = ['#10b981','#f59e0b','#38bdf8','#a855f7','#ef4444'];
    @foreach($produkTerlaris as $i => $p)
    produkLabels.push(truncate('{{ $p->produk->nama_produk ?? "Produk #$i" }}', 16) + ' ({{ $p->total_terjual }})');
    produkData.push({{ $p->total_terjual }});
    @endforeach

    if (produkData.length > 0) {
        new Chart(document.getElementById('chartProducts'), {
            type: 'doughnut',
            data: { labels: produkLabels, datasets: [{ data: produkData, backgroundColor: produkColors.slice(0, produkData.length), borderWidth: 0 }] },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '60%', color: textColor,
                plugins: { legend: { position: 'bottom', labels: { color: textColor, boxWidth: 10, padding: 8, font: { size: 10 } } } },
            },
        });
    } else {
        document.getElementById('chartProducts').parentElement.innerHTML =
            '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:' + textColor + ';font-size:0.85rem;">Belum ada data produk terjual.</div>';
    }

    var bulanLabels = [];
    var trenProduk = [];
    var trenOngkir = [];
    @foreach($monthlyTrend as $m)
    bulanLabels.push('{{ $m->bulan }}');
    trenProduk.push({{ $m->total_produk }});
    trenOngkir.push({{ $m->total_ongkir }});
    @endforeach

    if (bulanLabels.length > 0) {
        new Chart(document.getElementById('chartTrend'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [
                    { label: 'Pendapatan Produk', data: trenProduk, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.3, pointRadius: 3, pointHoverRadius: 5 },
                    { label: 'Pendapatan Ongkir', data: trenOngkir, borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.1)', fill: true, tension: 0.3, pointRadius: 3, pointHoverRadius: 5 },
                ],
            },
            options: {
                responsive: true, maintainAspectRatio: false, color: textColor,
                plugins: { legend: { position: 'bottom', labels: { color: textColor, boxWidth: 12, padding: 12, font: { size: 10 } } } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor, font: { size: 9 } } },
                    y: { ticks: { color: textColor, font: { size: 9 }, callback: fmtRp } },
                },
                interaction: { intersect: false, mode: 'index' },
            },
        });
    } else {
        document.getElementById('chartTrend').parentElement.innerHTML =
            '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:' + textColor + ';font-size:0.85rem;">Belum ada data tren bulanan.</div>';
    }
});
</script>
@endsection