@extends('v_layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Riwayat Pesanan</h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Order</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->created_at->format('d M Y H:i') }}</td>
                            <td>Rp. {{ number_format($row->total_harga + $row->biaya_ongkir, 0, ',', '.') }}</td>
                            <td>{{ $row->status }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>Belum ada pesanan.</p>
    @endif
</div>
@endsection
