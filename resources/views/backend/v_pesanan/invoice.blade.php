@extends('backend.v_layouts.app')
@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3> {{ $subJudul }} </h3>
            </div>
            <div class="card-body">
                <div class="invoice-title text-center mb-3">
                    <h2>Invoice #{{ $order->id }}</h2>
                    <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <h5>Pelanggan</h5>
                        <address>
                            Nama: {{ $order->customer->user->nama }}<br>
                            Email: {{ $order->customer->user->email }}<br>
                            Hp: {{ $order->customer->user->hp }}<br>
                        </address>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 text-right">
                        <h5>Ongkos Kirim</h5>
                        <address>
                            Kurir: {{ $order->kurir }}<br>
                            Layanan: {{ $order->layanan_ongkir }}<br>
                            Estimasi: {{ $order->estimasi_ongkir }} Hari<br>
                            Berat: {{ $order->total_berat }} Gram<br>
                        </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h5>Produk</h5>
                        <table class="table table-bordered table-hover display">
                            <thead>
                                <tr>
                                    <th colspan="2">Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalHarga = 0;
                                @endphp
                                @foreach ($order->orderItems as $item)
                                    @php
                                        $totalHarga += $item->harga * $item->quantity;
                                    @endphp
                                    <tr>
                                        <td align="center"><img
                                                src="{{ asset('storage/img-produk/thumb_sm_' . $item->produk->foto) }}"
                                                alt="" width="60%"></td>
                                        <td class="details">
                                            <a>{{ $item->produk->nama_produk }}
                                                #{{ $item->produk->kategori->nama_kategori }}</a>
                                        </td>
                                        <td class="price text-center">Rp.
                                            {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="qty text-center">{{ $item->quantity }}</td>
                                        <td class="total text-center">Rp.
                                            {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <th class="empty" colspan="3"></th>
                                <td>Subtotal</td>
                                <td colspan="2">Rp. {{ number_format($totalHarga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="empty" colspan="3"></th>
                                <td>Ongkos Kirim</td>
                                <td colspan="2">Rp. {{ number_format($order->biaya_ongkir, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="empty" colspan="3"></th>
                                <th>TOTAL BAYAR</th>
                                <th colspan="2" class="total">Rp.
                                    {{ number_format($totalHarga + $order->biaya_ongkir, 0, ',', '.') }}</th>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <a href="{{ url()->previous() }}">
                    <button type="button" class="btn btn-secondary">Kembali</button>
                </a>
            </div>
        </div>
    </div>
@endsection
