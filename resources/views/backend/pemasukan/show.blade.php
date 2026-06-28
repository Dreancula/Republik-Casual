@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <p class="text-uppercase"
               style="color: var(--rc-terracotta); font-size: .75rem; letter-spacing:2px; font-weight:700;">
                Detail Faktur
            </p>

            <h2 class="mb-1 fw-bold">
                {{ $pemasukan->no_faktur }}
            </h2>

            <p style="color: var(--rc-text-muted);">
                Detail transaksi pemasukan barang gudang.
            </p>
        </div>

        <a href="{{ route('admin.pemasukan-barang.index') }}"
           class="btn text-white"
           style="background: rgba(255,255,255,.05);
                  border:1px solid var(--rc-glass-border);
                  border-radius:12px;">
            Kembali
        </a>
    </div>

    {{-- INFO FAKTUR --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="rc-glass-card p-4">
                <small style="color:var(--rc-text-muted)">
                    Nomor Faktur
                </small>

                <h5 class="text-white mt-2 mb-0">
                    {{ $pemasukan->no_faktur }}
                </h5>
            </div>
        </div>

        <div class="col-md-4">
            <div class="rc-glass-card p-4">
                <small style="color:var(--rc-text-muted)">
                    Tanggal Pemasukan
                </small>

                <h5 class="text-white mt-2 mb-0">
                    {{ \Carbon\Carbon::parse($pemasukan->tgl_pemasukan)->translatedFormat('d F Y H:i') }}
                </h5>
            </div>
        </div>

        <div class="col-md-4">
            <div class="rc-glass-card p-4">
                <small style="color:var(--rc-text-muted)">
                    Petugas Gudang
                </small>

                <h5 class="text-white mt-2 mb-0">
                    {{ $pemasukan->user->nama ?? '-' }}
                </h5>
            </div>
        </div>

    </div>

    {{-- DETAIL ITEM --}}
    <div class="rc-glass-card">

        <div class="p-4 border-bottom"
             style="border-color:var(--rc-glass-border)!important;">

            <h5 class="text-white mb-0">
                Detail Produk Masuk
            </h5>

        </div>

        <div class="table-responsive">

            <table class="table table-dark table-borderless align-middle mb-0"
                   style="--bs-table-bg:transparent;">

                <thead>
                    <tr style="border-bottom:1px solid var(--rc-glass-border);">
                        <th>Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $grandTotal = 0;
                    @endphp

                    @foreach($pemasukan->detailPemasukanBarang as $detail)

                        @php
                            $subtotal = $detail->jumlah_masuk * $detail->harga_beli;
                            $grandTotal += $subtotal;
                        @endphp

                        <tr>

                            <td>
                                <div class="fw-semibold text-white">
                                    {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}
                                </div>
                            </td>

                            <td class="text-center">
                                {{ number_format($detail->jumlah_masuk,0,',','.') }}
                                pcs
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($detail->harga_beli,0,',','.') }}
                            </td>

                            <td class="text-end text-success fw-bold">
                                Rp {{ number_format($subtotal,0,',','.') }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

                <tfoot>

                    <tr style="border-top:1px solid var(--rc-glass-border);">
                        <th colspan="3" class="text-end text-white">
                            Total Faktur
                        </th>

                        <th class="text-end"
                            style="color:#10b981;">
                            Rp {{ number_format($grandTotal,0,',','.') }}
                        </th>
                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>
@endsection