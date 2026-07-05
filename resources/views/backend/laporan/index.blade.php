@extends('backend.layouts.app')

@section('content_backend')
@php $labaBersih = $pendapatanProduk - ($pengeluaranBarang ?? 0); @endphp
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<div class="container-fluid p-0">
    {{-- HEADER KONTROL --}}
    <div class="d-flex justify-content-end mb-4 no-print">
        <button onclick="cetakLaporan()" class="btn text-white px-4 py-2" 
                style="background: rgba(255,255,255,0.05); border: 1px solid var(--rc-glass-border); border-radius: 12px; font-weight: 600; font-size: 0.85rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
            Cetak PDF
        </button>
    </div>

    {{-- FILTER TANGGAL --}}
    <form action="{{ route('admin.laporan.index') }}" method="GET" class="rc-glass-card p-3 mb-4 d-flex flex-wrap align-items-center gap-3 no-print">
        <span style="font-size: 0.8rem; color: var(--rc-text-muted); font-weight: 600;">Periode:</span>
        <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}" class="form-control form-control-sm w-auto" style="background: #0e0e11; border: 1px solid var(--rc-glass-border); color: white; border-radius: 8px; cursor: pointer;" onclick="this.showPicker()">
        <span style="color: var(--rc-text-muted);">s/d</span>
        <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="form-control form-control-sm w-auto" style="background: #0e0e11; border: 1px solid var(--rc-glass-border); color: white; border-radius: 8px; cursor: pointer;" onclick="this.showPicker()">
        <button type="submit" class="btn btn-sm text-white" style="background: var(--rc-terracotta); border: none; border-radius: 8px; padding: 0.4rem 1rem;">Filter Data</button>
    </form>

    {{-- STYLING CSS UTAMA & CETAK --}}
    <style>
        /* TABS FILTER */
        .rc-filter-tabs { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 1.5rem; }
        .rc-filter-tab {
            padding: 6px 18px; border-radius: 100px; font-size: 0.78rem; font-weight: 600;
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);
            color: var(--rc-text-muted); cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .rc-filter-tab:hover { background: rgba(255,255,255,0.08); color: var(--rc-text); }
        .rc-filter-tab.active { background: var(--rc-terracotta); border-color: var(--rc-terracotta); color: #fff; }
        
        /* SECTION VISIBILITY */
        .rc-section { display: none; }
        .rc-section.visible { display: block; }

        /* TOGGLE DETAIL TABEL */
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

        /* TABEL DETAIL STANDAR */
        .rc-detail-table { width: 100%; font-size: 0.78rem; border-collapse: collapse; }
        .rc-detail-table th { color: var(--rc-text-muted); font-weight: 600; text-transform: uppercase; padding: 8px 10px; border-bottom: 1px solid rgba(255,255,255,0.06); text-align: left; font-size: 0.7rem; }
        .rc-detail-table td { padding: 7px 10px; border-bottom: 1px solid rgba(255,255,255,0.03); color: var(--rc-text); }
        .rc-detail-table tr:hover td { background: rgba(255,255,255,0.02); }

        /* MENGATUR STRUKTUR PDF UNTUK TAMPILAN WEB (DISEMBUNYIKAN/DINETRALISIR) */
        .print-header, .print-footer, .print-header-space, .print-footer-space { display: none; }
        .print-table-wrapper { display: block; width: 100%; border: none; border-collapse: collapse; }
        .print-thead, .print-tbody, .print-tfoot, 
        .print-table-wrapper > thead > tr, .print-table-wrapper > thead > tr > td,
        .print-table-wrapper > tbody > tr, .print-table-wrapper > tbody > tr > td,
        .print-table-wrapper > tfoot > tr, .print-table-wrapper > tfoot > tr > td { 
            display: block; 
            padding: 0; 
        }

        /* =========================================================
           PERBAIKAN CSS CETAK / PDF (HEADER TIAP HALAMAN & SMART BREAK)
           ========================================================= */
        @media print {
            /* Margin 0 menghapus URL & Title bawaan browser secara paksa */
            @page { margin: 0; size: A4 portrait; }
            
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }

            /* Sembunyikan UI Web */
            .no-print, .rc-sidebar, .rc-topnav, .rc-dashboard-glow, .rc-brand-wrapper,
            .rc-logout-btn, .rc-topnav-badge, .rc-filter-tabs, .rc-filter-tab, 
            .rc-detail-toggle, .btn, form[action], .rc-info-btn, svg { 
                display: none !important; 
            }

            /* Hapus jarak bawaan body agar kita yang atur sendiri */
            body { 
                background: #fff !important; margin: 0 !important; padding: 0 !important;
                font-family: Arial, sans-serif !important; color: #000 !important; 
            }
            .rc-main-panel, .rc-content-body, .container-fluid { 
                width: 100% !important; margin: 0 !important; padding: 0 !important; max-width: 100% !important; 
            }
            
            /* --- TRIK HEADER & FOOTER BERULANG TIAP HALAMAN --- */
            .print-table-wrapper { 
                display: table !important; 
                width: 100% !important; 
                box-sizing: border-box !important;
                border-spacing: 0 !important; /* Mencegah jarak bawaan tabel */
            }

            .table-responsive {
                overflow: visible !important;
                display: block !important;
                width: 100% !important;
            }
            
            .rc-detail-table, .table { 
                display: table !important; 
                width: 100% !important; 
                border-collapse: collapse !important;
                table-layout: auto !important;
            }
            
            .rc-detail-table thead, .table thead { display: table-header-group !important; }
            .rc-detail-table tbody, .table tbody { display: table-row-group !important; }
            
            .rc-detail-table tr, .table tr { 
                display: table-row !important; 
                page-break-inside: avoid !important; 
                break-inside: avoid !important; 
            }
            
            .rc-detail-table th, .rc-detail-table td, 
            .table th, .table td { 
                display: table-cell !important; 
                white-space: nowrap !important; /* Mengunci teks ID & Angka agar selalu satu baris */
                vertical-align: middle !important;
            }

            /* Berikan dispensasi khusus kolom Nama Pelanggan / Nama Produk agar boleh turun baris jika terlalu panjang */
            .rc-detail-table td:nth-child(2), 
            .table td:nth-child(2) {
                white-space: normal !important;
            }

            .print-thead { display: table-header-group !important; }
            .print-tbody { display: table-row-group !important; }
            .print-tfoot { display: table-footer-group !important; }
            
            /* PERBAIKAN UTAMA: Izinkan wrapper tabel utama dipotong antar halaman */
            .print-table-wrapper > thead > tr, .print-table-wrapper > tbody > tr, .print-table-wrapper > tfoot > tr { 
                display: table-row !important; 
                page-break-inside: auto !important; 
                break-inside: auto !important; 
            }

            .print-table-wrapper > thead > tr > td, .print-table-wrapper > tbody > tr > td, .print-table-wrapper > tfoot > tr > td { 
                display: table-cell !important; 
                padding: 0 1.5cm !important; 
                page-break-inside: auto !important; 
                break-inside: auto !important; 
            }

            /* Blok ini menciptakan ruang kosong di setiap halaman agar konten tidak menabrak Header/Footer */
            .print-header-space { height: 3.2cm !important; display: block !important; }
            .print-footer-space { height: 2cm !important; display: block !important; }

            /* Ini wujud asli Header-nya (Terkunci di atas) */
            .print-header { 
                display: block !important; 
                position: fixed; top: 0; left: 0; right: 0; 
                padding: 1cm 1.5cm 0 1.5cm !important; /* Margin Atas Kertas */
                background: #fff !important; z-index: 999;
            }
            .print-header-border {
                border-bottom: 2px solid #222 !important; padding-bottom: 8pt !important;
            }
            .print-header h1 { font-size: 20pt; font-weight: 700; color: #111; margin: 0; }
            .print-header p { font-size: 9pt; color: #555; margin: 2pt 0 0 0; }
            
            /* Ini wujud asli Footer-nya (Terkunci di bawah) */
            .print-footer { 
                display: block !important; 
                position: fixed; bottom: 0; left: 0; right: 0; 
                padding: 0 1.5cm 1cm 1.5cm !important; /* Margin Bawah Kertas */
                background: #fff !important; z-index: 999;
            }
            .print-footer-border {
                border-top: 1px solid #ccc !important; padding-top: 8pt !important; text-align: center;
            }
            .print-footer div { font-size: 8pt !important; color: #666 !important; }


            /* --- MENGATUR KONTEN, KOTAK & SMART PAGE BREAK --- */
            .rc-section { display: none !important; }
            .rc-section.visible { 
                display: block !important; 
                margin-bottom: 25pt !important; 
                page-break-inside: auto !important; 
            }
            .rc-section.visible:first-of-type {
                margin-top: 0 !important; /* Mencegah margin atas yang memicu halaman 1 kosong */
            }

            h5 { 
                font-size: 11pt !important; color: #111 !important; margin: 0 0 10pt 0 !important; 
                font-weight: 700 !important; text-transform: uppercase !important; 
                border-bottom: 2px solid #111 !important; padding-bottom: 4px !important; display: inline-block !important; 
                page-break-after: avoid !important; break-after: avoid !important; 
            }
            
            /* Susunan Kolom Klasik - MENGHINDARI BUG FLEXBOX PADA PDF */
            .row { 
                display: block !important; /* Ubah flex menjadi block */
                margin: 0 -8px !important; 
            }
            .row::after {
                content: ""; display: table; clear: both; /* Clearfix untuk float */
            }
            .col-md-4 { 
                float: left !important; /* Gunakan float sebagai pengganti flex */
                width: 33.333333% !important; 
                padding: 0 8px !important; box-sizing: border-box !important; 
            }
            .col-md-12 { 
                float: left !important;
                width: 100% !important; 
                padding: 0 8px !important; box-sizing: border-box !important; 
            }

            /* --- KHUSUS SEKSI DIAGRAM & VISUALISASI --- */
            .rc-section[data-section="chart"] .col-md-4 {
                float: none !important;
                width: 100% !important; 
                margin-bottom: 15pt !important;
                /* Ini akan memaksa Diagram pindah ke halaman baru SECARA UTUH jika sisa halaman sblmnya tidak muat */
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .rc-section[data-section="chart"] .rc-glass-card {
                height: auto !important; min-height: 250px !important;
            }

            /* Tampilan Kotak (Card) dan Smart Break-nya */
            .rc-glass-card {
                background: #fff !important; 
                border: 1px solid #ddd !important; 
                border-radius: 6pt !important; 
                padding: 12pt !important;
                margin-bottom: 12pt !important; 
                box-shadow: none !important;
                
                /* KUNCI UTAMA: Bila kotak gak muat di suatu halaman, jatuhkan utuh ke halaman baru! */
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            /* KECUALI: Kotak yang berisi tabel panjang (Detail Perhitungan). 
               Tabel harus diizinkan terpotong halamannya agar baris datanya tidak menghilang/error */
            .rc-detail-content .rc-glass-card,
            .table-responsive {
                page-break-inside: auto !important;
                break-inside: auto !important;
            }
            
            .rc-glass-card[style*="border-left: 3px solid #10b981"] { border-left-color: #10b981 !important; border-left-width: 4px !important; }
            .rc-glass-card[style*="border-left: 3px solid #f59e0b"] { border-left-color: #f59e0b !important; border-left-width: 4px !important; }
            .rc-glass-card[style*="border-left: 3px solid #ef4444"] { border-left-color: #ef4444 !important; border-left-width: 4px !important; }
            .rc-glass-card[style*="border-left: 3px solid #f97316"] { border-left-color: #f97316 !important; border-left-width: 4px !important; }
            .rc-glass-card[style*="border-left: 3px solid {{ $labaBersih >= 0 ? '#10b981' : '#ef4444' }}"] {
                border-left: 4px solid {{ $labaBersih >= 0 ? '#10b981' : '#ef4444' }} !important;
            }

            .d-flex { display: flex !important; }
            .align-items-center { align-items: center !important; }
            .justify-content-between { justify-content: space-between !important; }
            
            .rc-glass-card h4 { font-size: 13pt !important; margin: 4pt 0 !important; color: #000 !important; font-weight: bold !important; }
            .rc-glass-card p { font-size: 8.5pt !important; color: #444 !important; margin-bottom: 2pt !important; text-transform: uppercase !important; }
            .rc-glass-card small { font-size: 7.5pt !important; color: #777 !important; }

            /* Pengaturan Tabel & Warna Font */
            .rc-detail-content { display: block !important; margin-top: 10pt !important; }
            table { page-break-inside: auto !important; }
            
            /* Aturan ini hanya berlaku untuk tabel data biasa agar barisnya tidak terpotong jelek */
            .rc-detail-table tr, .table tr { page-break-inside: avoid !important; break-inside: avoid !important; }
            
            /* Agar thead pada tabel panjang ngulang juga, tapi yang ini tabel biasa bukan tabel trik layout */
            .rc-detail-table thead, .table thead { display: table-header-group !important; }

            .rc-detail-table, .table { width: 100% !important; font-size: 9pt !important; border-collapse: collapse !important; margin: 0 !important; }
            .rc-detail-table th, .table th { 
                color: #000 !important; border-bottom: 2px solid #222 !important; 
                padding: 6pt 8pt !important; font-size: 8pt !important; font-weight: 700 !important; background-color: #f4f4f5 !important;
            }
            .rc-detail-table td, .table td { color: #222 !important; border-bottom: 1px solid #e2e8f0 !important; padding: 6pt 8pt !important; }
            .table-dark { --bs-table-bg: transparent !important; }
            .table-borderless tbody tr { border-bottom: 1px solid #e2e8f0 !important; }

            .text-white, .text-white-50, .text-muted, .font-monospace,
            [style*="color: #fff"], [style*="color: #fff;"],
            [style*="var(--rc-text-muted)"], [style*="var(--rc-text)"] { color: #222 !important; }
        }
    </style>

    {{-- PRINT HEADER (Fixed Berulang) --}}
    <div class="print-header">
        <div class="print-header-border" style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                    <img src="{{ asset('image/favicon.png') }}" alt="Republik Casual" style="width: 28px; height: 28px; border-radius: 6px;">
                    <h1 style="font-size: 20px; font-weight: 700; margin: 0; letter-spacing: -0.5px;">Republik Casual</h1>
                </div>
                <p style="margin: 2px 0 0; font-size: 11px; color: #666;">
                    Laporan Bisnis &mdash; Periode {{ \Carbon\Carbon::parse($tanggalAwal)->isoFormat('D MMMM YYYY') }} &mdash; {{ \Carbon\Carbon::parse($tanggalAkhir)->isoFormat('D MMMM YYYY') }}
                </p>
                <p style="margin: 3px 0 0; font-size: 8px; color: #999;">
                    Jl. Margonda No.8, Pondok Cina, Kecamatan Beji, Kota Depok, Jawa Barat 16424
                </p>
            </div>
            <div style="text-align: right; font-size: 8pt; color: #999;">
                <div>Dicetak: <span id="printTime">{{ now()->isoFormat('D MMMM YYYY H:mm') }}</span> - <span id="printUser">{{ auth()->user()->nama ?? 'Admin' }}</span></div>
                <div id="printFilterInfo">Semua Section</div>
            </div>
        </div>
    </div>

    {{-- PRINT FOOTER (Fixed Berulang) --}}
    <div class="print-footer">
        <div class="print-footer-border" style="font-size: 7.5pt; color: #999;">
            Republik Casual &mdash; Jl. Margonda No.8, Pondok Cina, Kecamatan Beji, Kota Depok, Jawa Barat 16424
        </div>
    </div>

    {{-- BUNGKUSAN UTAMA LAYOUT CETAK (Trik Table) --}}
    <table class="print-table-wrapper">
        <thead class="print-thead">
            <tr><td><div class="print-header-space"></div></td></tr>
        </thead>
        <tbody class="print-tbody">
            <tr><td>

                {{-- NAVIGASI TAB MENU (Hanya Tampil di Web) --}}
                <div class="rc-filter-tabs no-print">
                    <span class="rc-filter-tab active" data-filter="all" onclick="filterSection('all')">Semua</span>
                    <span class="rc-filter-tab" data-filter="pemasukan" onclick="filterSection('pemasukan')">Pemasukan</span>
                    <span class="rc-filter-tab" data-filter="pengeluaran" onclick="filterSection('pengeluaran')">Pengeluaran</span>
                    <span class="rc-filter-tab" data-filter="operasional" onclick="filterSection('operasional')">Operasional</span>
                    <span class="rc-filter-tab" data-filter="labarugi" onclick="filterSection('labarugi')">Laba/Rugi</span>
                    <span class="rc-filter-tab" data-filter="chart" onclick="filterSection('chart')">Diagram</span>
                </div>

                {{-- SEKSI: PEMASUKAN --}}
                <div class="rc-section visible" data-section="pemasukan">
                    <h5 class="mb-3">Pemasukan</h5>
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
                    <h5 class="mb-3">Pengeluaran</h5>
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
                    <h5 class="mb-3">Operasional</h5>
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
                    <h5 class="mb-3">Laba / Rugi</h5>
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
                    <h5 class="mb-3">Visualisasi Data</h5>
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
                    <div class="rc-glass-card mb-3">
                        <h5 style="font-weight: 700; margin-bottom: 0.75rem; color: #fff; border-bottom: none !important;">Produk Terlaris (Top 5 Periode Ini)</h5>
                        <div class="table-responsive">
                            <table class="table table-dark table-borderless align-middle mb-0" style="--bs-table-bg: transparent;">
                                <thead>
                                    <tr style="border-bottom: 1px solid var(--rc-glass-border); font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase;">
                                        <th class="pb-1" style="width: 12%;">Peringkat</th>
                                        <th class="pb-1">Nama Produk</th>
                                        <th class="pb-1 text-center">Jumlah Terjual</th>
                                        <th class="pb-1 text-end">Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 0.8rem;">
                                    @forelse($produkTerlaris as $index => $item)
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                                        <td class="py-1 font-monospace text-white-50">#0{{ $index + 1 }}</td>
                                        <td class="py-1" style="color: #fff; font-weight: 500;">{{ $item->produk->nama_produk ?? 'Produk Tidak Diketahui / Dihapus' }}</td>
                                        <td class="py-1 text-center text-white-50">{{ number_format($item->total_terjual, 0, ',', '.') }} Pcs</td>
                                        <td class="py-1 text-end font-weight-600" style="color: #fff;">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-white-50">Tidak ada data penjualan pada rentang periode ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>

            </td></tr>
        </tbody>
        <tfoot class="print-tfoot">
            <tr><td><div class="print-footer-space"></div></td></tr>
        </tfoot>
    </table>
</div>

<script>
// Logika Section Visibility & Filter
function filterSection(section) {
    var allSections = document.querySelectorAll('.rc-section');
    if (section === 'all') {
        var allVisible = allSections.length === document.querySelectorAll('.rc-section.visible').length;
        allSections.forEach(function(el) { el.classList.toggle('visible', !allVisible); });
        document.querySelectorAll('.rc-filter-tab').forEach(function(el) { el.classList.toggle('active', !allVisible); });
    } else {
        document.querySelector('.rc-section[data-section="' + section + '"]').classList.toggle('visible');
        document.querySelector('.rc-filter-tab[data-filter="' + section + '"]').classList.toggle('active');
        var visibleCount = document.querySelectorAll('.rc-section.visible').length;
        document.querySelector('.rc-filter-tab[data-filter="all"]').classList.toggle('active', visibleCount === allSections.length);
    }
}

function toggleDetail(el) {
    el.classList.toggle('open');
    var content = el.nextElementSibling;
    content.classList.toggle('open');
}

// Logika Cetak PDF
function cetakLaporan() {
    var visibleSections = document.querySelectorAll('.rc-section.visible');
    if (visibleSections.length === 0) {
        alert('Pilih minimal satu section untuk dicetak.');
        return;
    }
    
    var names = [];
    visibleSections.forEach(function(el) {
        var tab = document.querySelector('.rc-filter-tab[data-filter="' + el.dataset.section + '"]');
        if (tab) names.push(tab.textContent.trim());
    });
    document.getElementById('printFilterInfo').textContent = names.length ? names.join(' | ') : 'Semua Section';
    document.getElementById('printTime').textContent = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    
    // --- HACK 1: SWAP WARNA CHART KE HITAM (LIGHT MODE) SEBELUM PRINT ---
    if (window.myCharts) {
        window.myCharts.forEach(function(chart) {
            if (chart.options.scales) {
                if (chart.options.scales.x && chart.options.scales.x.ticks) chart.options.scales.x.ticks.color = '#111111';
                if (chart.options.scales.y && chart.options.scales.y.ticks) chart.options.scales.y.ticks.color = '#111111';
                if (chart.options.scales.y && chart.options.scales.y.grid) chart.options.scales.y.grid.color = '#e4e4e7';
            }
            if (chart.options.plugins && chart.options.plugins.legend && chart.options.plugins.legend.labels) {
                chart.options.plugins.legend.labels.color = '#111111';
                chart.options.plugins.legend.labels.font = { size: 9 }; // Perkecil ukuran font legend agar tidak bertumpuk
            }
            chart.update();
        });
    }

    var originalTitle = document.title;
    document.title = " "; 
    
    setTimeout(function() {
        window.print();
        document.title = originalTitle;
        
        // --- HACK 2: KEMBALIKAN WARNA CHART KE SEMULA (DARK MODE) SETELAH DI-PRINT ---
        if (window.myCharts) {
            window.myCharts.forEach(function(chart) {
                if (chart.options.scales) {
                    if (chart.options.scales.x && chart.options.scales.x.ticks) chart.options.scales.x.ticks.color = '#d4d4d8';
                    if (chart.options.scales.y && chart.options.scales.y.ticks) chart.options.scales.y.ticks.color = '#d4d4d8';
                    if (chart.options.scales.y && chart.options.scales.y.grid) chart.options.scales.y.grid.color = 'rgba(255,255,255,0.06)';
                }
                if (chart.options.plugins && chart.options.plugins.legend && chart.options.plugins.legend.labels) {
                    chart.options.plugins.legend.labels.color = '#d4d4d8';
                    chart.options.plugins.legend.labels.font = { size: 10 };
                }
                chart.update();
            });
        }
    }, 400);
}

document.addEventListener('DOMContentLoaded', function () {
    filterSection('all');
    
    var textColor = '#d4d4d8';
    var gridColor = 'rgba(255,255,255,0.06)';

    // Pengaturan Dasar Chart.js (Animasi dimatikan agar render instan saat diprint)
    Chart.defaults.animation = false;
    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    function fmtRp(v) { return 'Rp' + (v / 1000).toFixed(0) + 'k'; }

    // ==========================================
    // 1. BUAT WADAH ARRAY GLOBAL DI SINI, BRO!
    // ==========================================
    window.myCharts = [];

    // CHART 1: Finansial (Sekarang dibungkus window.myCharts.push)
    window.myCharts.push(new Chart(document.getElementById('chartFinancial'), {
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
    }));

    function truncate(str, n) { return str.length > n ? str.slice(0, n) + '...' : str; }

    // CHART 2: Produk Doughnut
    var produkLabels = [];
    var produkData = [];
    var produkColors = ['#10b981','#f59e0b','#38bdf8','#a855f7','#ef4444'];
    
    @foreach($produkTerlaris as $i => $p)
        produkLabels.push(truncate('{{ $p->produk->nama_produk ?? "Produk #$i" }}', 16) + ' ({{ $p->total_terjual }})');
        produkData.push({{ $p->total_terjual }});
    @endforeach

    if (produkData.length > 0) {
        // Dibungkus window.myCharts.push
        window.myCharts.push(new Chart(document.getElementById('chartProducts'), {
            type: 'doughnut',
            data: { labels: produkLabels, datasets: [{ data: produkData, backgroundColor: produkColors.slice(0, produkData.length), borderWidth: 0 }] },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '60%', color: textColor,
                plugins: { legend: { position: 'bottom', labels: { color: textColor, boxWidth: 10, padding: 8, font: { size: 10 } } } },
            },
        }));
    } else {
        document.getElementById('chartProducts').parentElement.innerHTML = 
            '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:' + textColor + ';font-size:0.85rem;">Belum ada data produk terjual.</div>';
    }

    // CHART 3: Tren Line Chart
    var bulanLabels = [];
    var trenProduk = [];
    var trenOngkir = [];
    
    @foreach($monthlyTrend as $m)
        bulanLabels.push('{{ $m->bulan }}');
        trenProduk.push({{ $m->total_produk }});
        trenOngkir.push({{ $m->total_ongkir }});
    @endforeach

    if (bulanLabels.length > 0) {
        // Dibungkus window.myCharts.push
        window.myCharts.push(new Chart(document.getElementById('chartTrend'), {
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
        }));
    } else {
        document.getElementById('chartTrend').parentElement.innerHTML = 
            '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:' + textColor + ';font-size:0.85rem;">Belum ada data tren bulanan.</div>';
    }
});
</script>
@endsection