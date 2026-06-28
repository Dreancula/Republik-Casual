@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- HEADER MODUL --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-5 gap-3">
        <div>
            <p class="text-uppercase" style="color: var(--rc-terracotta); font-size: 0.75rem; letter-spacing: 2px; font-weight: 700; margin-bottom: 0.4rem;">
                Alur Transaksi Konsumen
            </p>
            <h2 style="font-weight: 700; letter-spacing: -0.5px; margin: 0;">
                Daftar Pesanan Masuk
            </h2>
            <p style="color: var(--rc-text-muted); font-size: 0.9rem; margin: 0; margin-top: 0.2rem;">
                Pantau pesanan baru dari website, validasi pembayaran, dan perbarui nomor resi pengiriman.
            </p>
        </div>

        {{-- RINGKASAN TOTAL AKTIVITAS --}}
        <div class="rc-glass-card py-2 px-3 d-flex align-items-center gap-3" style="border-radius: 12px; padding: 0.75rem 1.25rem !important;">
            <div class="text-end">
                <span style="font-size: 0.75rem; color: var(--rc-text-muted); display: block;">Perlu Diproses</span>
                <span style="font-size: 1rem; font-weight: 700; color: #f59e0b;">{{ $totalPerluDiproses }} Pesanan</span>
            </div>
            <div style="width: 1px; height: 30px; background: var(--rc-glass-border);"></div>
            <div>
                <span style="font-size: 0.75rem; color: var(--rc-text-muted); display: block;">Selesai (Bulan Ini)</span>
                <span style="font-size: 1rem; font-weight: 700; color: #10b981;">{{ $totalSelesaiBulanIni }} Order</span>
            </div>
        </div>
    </div>

    {{-- ALERT NOTIFIKASI SUKSES/GAGAL --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- KARTU DATA TABEL PESANAN --}}
    <div class="rc-glass-card">
        <div class="table-responsive">
            <table class="table table-dark table-borderless align-middle mb-0" style="--bs-table-bg: transparent;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--rc-glass-border); font-size: 0.8rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                        <th class="pb-3 pl-2">Nota / ID</th>
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Pelanggan</th>
                        <th class="pb-3">Total Pembayaran</th>
                        <th class="pb-3 text-center">Status Bayar</th>
                        <th class="pb-3 text-center">Status Logistik</th>
                        <th class="pb-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.85rem;">
                    
                    @forelse($pesanans as $pesanan)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.02); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.01)'" onmouseout="this.style.background='transparent'">
                            <td class="py-3 font-monospace text-white-50">#RC-{{ $pesanan->id_pesanan }}</td>
                            <td class="py-3 text-white-50">{{ \Carbon\Carbon::parse($pesanan->tgl_pesanan)->format('d M Y') }}</td>
                            <td class="py-3">
                                <div style="font-weight: 600; color: #fff;">{{ $pesanan->user->nama }}</div>
                                <div style="font-size: 0.75rem; color: var(--rc-text-muted);">{{ $pesanan->user->alamat ?? 'Alamat Belum Diatur' }}</div>
                            </td>
                            <td class="py-3 font-weight-600" style="color: #fff;">Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                            
                            {{-- STATUS PEMBAYARAN (FIXED RETUR) --}}
                            <td class="py-3 text-center">
                                @if(str_contains($pesanan->id_pesanan, 'RETUR'))
                                    <span class="badge" style="background: rgba(23, 162, 184, 0.15); color: #17a2b8; border: 1px solid rgba(23, 162, 184, 0.3); padding: 0.4rem 0.6rem; border-radius: 6px;">
                                        BARANG RETUR
                                    </span>
                                @elseif($pesanan->pembayaran && $pesanan->pembayaran->status_pembayaran == 'paid')
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); padding: 0.4rem 0.6rem; border-radius: 6px;">
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); padding: 0.4rem 0.6rem; border-radius: 6px;">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            
                            {{-- STATUS LOGISTIK (FIXED RETUR) --}}
                            <td class="py-3 text-center">
                                @if($pesanan->status_pesanan == 'selesai')
                                    Selesai / Diterima
                                @elseif($pesanan->status_pesanan == 'dikirim')
                                    Dalam Pengiriman
                                @elseif($pesanan->status_pesanan == 'diproses')
                                    Sedang Dipacking
                                @elseif($pesanan->status_pesanan == 'dibatalkan')
                                    Dibatalkan
                                @elseif($pesanan->status_pesanan == 'dibayar' || str_contains($pesanan->id_pesanan, 'RETUR'))
                                    {{-- Khusus Retur, jika status defaultnya masih 'belum_bayar', paksa text logistik tampil 'Menunggu Diproses' --}}
                                    @if($pesanan->status_pesanan == 'diproses')
                                        Sedang Dipacking
                                    @elseif($pesanan->status_pesanan == 'dikirim')
                                        Dalam Pengiriman
                                    @elseif($pesanan->status_pesanan == 'selesai')
                                        Selesai / Diterima
                                    @else
                                        Menunggu Diproses
                                    @endif
                                @else
                                    Menunggu Pembayaran
                                @endif
                            </td>
                            
                            {{-- BUTTON AKSI (FIXED RETUR BYPASS PAYMENT) --}}
                            <td class="py-3 text-end">
                                <div class="d-flex gap-1 justify-content-end flex-wrap">

                                @php 
                                    // Bypass true jika pesanan adalah BARANG RETUR, agar tombol aksi langsung aktif terbuka
                                    $isRetur = str_contains($pesanan->id_pesanan, 'RETUR');
                                    $paid = $isRetur || ($pesanan->pembayaran && $pesanan->pembayaran->status_pembayaran == 'paid'); 
                                @endphp

                                @if($pesanan->status_pesanan == 'selesai' || $pesanan->status_pesanan == 'dibatalkan')

                                    <button class="btn btn-sm text-white-50" disabled
                                        style="background:rgba(255,255,255,0.03);border:1px solid var(--rc-glass-border);border-radius:8px;padding:0.3rem 0.6rem;font-size:0.75rem;">
                                        {{ $pesanan->status_pesanan == 'selesai' ? 'Selesai' : 'Dibatalkan' }}
                                    </button>

                                @elseif($paid && ($pesanan->status_pesanan == 'dibayar' || ($isRetur && $pesanan->status_pesanan != 'diproses' && $pesanan->status_pesanan != 'dikirim')))

                                    <form action="{{ route('admin.pesanan.quick', $pesanan->id_pesanan) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="diproses">
                                        <button type="submit" class="btn btn-sm text-white"
                                            style="background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);border-radius:8px;padding:0.3rem 0.6rem;font-size:0.75rem;color:#f59e0b !important;">
                                            Proses
                                        </button>
                                    </form>

                                @elseif($paid && $pesanan->status_pesanan == 'diproses')

                                    <form action="{{ route('admin.pesanan.quick', $pesanan->id_pesanan) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="dikirim">
                                        <button type="submit" class="btn btn-sm text-white"
                                            style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);border-radius:8px;padding:0.3rem 0.6rem;font-size:0.75rem;color:#3b82f6 !important;">
                                            Kirim
                                        </button>
                                    </form>

                                @elseif($paid && ($pesanan->status_pesanan == 'dikirim'))

                                    <form action="{{ route('admin.pesanan.quick', $pesanan->id_pesanan) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="btn btn-sm text-white"
                                            style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);border-radius:8px;padding:0.3rem 0.6rem;font-size:0.75rem;color:#10b981 !important;">
                                            Selesai
                                        </button>
                                    </form>

                                @else

                                    <button class="btn btn-sm text-white-50" disabled
                                        style="background:rgba(255,255,255,.03);border:1px solid var(--rc-glass-border);border-radius:8px;padding:0.3rem 0.6rem;font-size:0.75rem;">
                                        Menunggu Bayar
                                    </button>

                                @endif

                                @if($paid && $pesanan->status_pesanan != 'selesai' && $pesanan->status_pesanan != 'dibatalkan')
                                    <button class="btn btn-sm text-white-50"
                                        style="background:rgba(255,255,255,0.03);border:1px solid var(--rc-glass-border);border-radius:8px;padding:0.3rem 0.6rem;font-size:0.75rem;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalProsesPesanan{{ $pesanan->id_pesanan }}">
                                        Detail
                                    </button>
                                @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-white-50">Tidak ada data pesanan masuk.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- BLOCK MODAL --}}
@foreach($pesanans as $pesanan)
    @if($pesanan->status_pesanan != 'selesai' && $pesanan->status_pesanan != 'dibatalkan')
        <div class="modal fade" id="modalProsesPesanan{{ $pesanan->id_pesanan }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content" style="background: #0e0e11; border: 1px solid var(--rc-glass-border); border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.5);">
                    
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <div>
                            <h5 class="modal-title" style="font-weight: 700; color: #fff;">Manajemen Pengiriman #RC-{{ $pesanan->id_pesanan }}</h5>
                            <p style="font-size: 0.75rem; color: var(--rc-text-muted); margin: 0; margin-top: 0.15rem;">
                                Pemesan: {{ $pesanan->user->nama ?? 'Guest' }} — {{ $pesanan->pengirimanUtama->nama_kurir ?? 'Ekspedisi' }}
                            </p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form action="{{ route('admin.pesanan.update', $pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            
                            {{-- Detail Item --}}
                            <div class="p-3 mb-4" style="background: rgba(255,255,255,0.01); border: 1px solid var(--rc-glass-border); border-radius: 12px;">
                                <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--rc-text-muted); display: block; margin-bottom: 0.5rem; font-weight: 600;">Item yang Harus Dipacking:</span>
                                
                                @forelse($pesanan->detailPesanan as $detail)
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span style="font-size: 0.85rem; color: #fff; font-weight: 500;">
                                            {{ $detail->quantity }}x {{ $detail->produk ? $detail->produk->nama_produk : "Item Terhapus" }}
                                        </span>
                                        <span style="font-size: 0.85rem; color: var(--rc-text-muted);">
                                            Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @empty
                                    <span class="text-white-50" style="font-size: 0.85rem;">Tidak ada rincian item.</span>
                                @endforelse
                            </div>

                            {{-- Input Resi --}}
                            <div class="mb-4">
                                <label class="form-label" style="font-size: 0.75rem; color: var(--rc-text-muted); font-weight: 600; text-transform: uppercase;">Nomor Resi Kurir / AWB</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nomor_resi" id="resi-{{ $pesanan->id_pesanan }}" value="{{ optional($pesanan->pengirimanUtama)->no_resi ?? '' }}" placeholder="Contoh: JNE12093847291"
                                           style="background: rgba(255,255,255,0.02); border: 1px solid var(--rc-glass-border); color: #fff; border-radius: 10px 0 0 10px; padding: 0.7rem 1rem; font-family: monospace; letter-spacing: 0.5px;">
                                    <button type="button" class="btn text-white"
                                            data-courier="{{ optional($pesanan->pengirimanUtama)->nama_kurir ?? 'JNE' }}"
                                            onclick="generateResi(this, 'resi-{{ $pesanan->id_pesanan }}')"
                                            style="background: rgba(255,255,255,0.05); border: 1px solid var(--rc-glass-border); border-left: none; border-radius: 0 10px 10px 0; padding: 0.7rem 1rem; font-size: 0.8rem; white-space: nowrap;">
                                        Generate
                                    </button>
                                </div>
                                <div class="form-text" style="font-size: 0.7rem; color: var(--rc-text-muted); margin-top: 0.35rem;">Klik <strong class="text-white">Generate</strong> untuk isi otomatis, atau kosongkan jika belum punya resi.</div>
                            </div>

                            @php $pengirimanPengganti = $pesanan->pengiriman->where('jenis_pengiriman', 'pengganti')->first(); @endphp
                            @if($pengirimanPengganti)
                            <div class="mb-4">
                                <label class="form-label" style="font-size: 0.75rem; color: #10b981; font-weight: 600; text-transform: uppercase;"><i class="fa-solid fa-rotate"></i> Resi Pengiriman Pengganti</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nomor_resi_pengganti" id="resi-pengganti-{{ $pesanan->id_pesanan }}" value="{{ $pengirimanPengganti->no_resi ?? '' }}" placeholder="Contoh: JNE12093847291"
                                           style="background: rgba(255,255,255,0.02); border: 1px solid rgba(16,185,129,0.3); color: #fff; border-radius: 10px 0 0 10px; padding: 0.7rem 1rem; font-family: monospace; letter-spacing: 0.5px;">
                                    <button type="button" class="btn text-success"
                                            data-courier="{{ $pengirimanPengganti->nama_kurir ?? 'JNE' }}"
                                            onclick="generateResi(this, 'resi-pengganti-{{ $pesanan->id_pesanan }}')"
                                            style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); border-left: none; border-radius: 0 10px 10px 0; padding: 0.7rem 1rem; font-size: 0.8rem; white-space: nowrap;">
                                        Generate
                                    </button>
                                </div>
                            </div>
                            @endif

                            {{-- Status Logistik --}}
                            <div class="mb-2">
                                <label class="form-label" style="font-size: 0.75rem; color: var(--rc-text-muted); font-weight: 600; text-transform: uppercase;">Status Logistik Alternatif</label>
                               <select
                                    class="form-select"
                                    name="status_pesanan"
                                    style="
                                        background:#0e0e11;
                                        border:1px solid var(--rc-glass-border);
                                        color:#fff;
                                        border-radius:10px;
                                        padding:0.65rem 1rem;
                                        font-size:0.85rem;
                                    "
                                >
                                    <option value="diproses"
                                        {{ $pesanan->status_pesanan == 'diproses' ? 'selected' : '' }}>
                                        Sedang Dipacking
                                    </option>

                                    <option value="dikirim"
                                        {{ $pesanan->status_pesanan == 'dikirim' ? 'selected' : '' }}>
                                        Sudah Diserahkan ke Kurir
                                    </option>

                                    <option value="selesai"
                                        {{ $pesanan->status_pesanan == 'selesai' ? 'selected' : '' }}>
                                        Pesanan Telah Diterima Customer
                                    </option>

                                    <option value="dibatalkan"
                                        {{ $pesanan->status_pesanan == 'dibatalkan' ? 'selected' : '' }}>
                                        Batalkan Pesanan
                                    </option>
                                </select>
                            </div>

                        </div>

                        <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex gap-2">
                            <button type="button" class="btn text-white-50" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.02); border: 1px solid var(--rc-glass-border); border-radius: 10px; font-size: 0.85rem; padding: 0.6rem 1.25rem;">Kembali</button>
                            <button type="submit" class="btn text-white" style="background: var(--rc-terracotta); border-radius: 10px; font-size: 0.85rem; padding: 0.6rem 1.25rem; border: none; font-weight: 600;">Update Status Paket</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- JAVASCRIPT FIXER --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            document.body.appendChild(modal);
        });
    });

    function generateResi(btn, inputId) {
        const prefix = btn.getAttribute('data-courier').replace(/\s+/g, '');
        let digits = '';
        for (let i = 0; i < 12; i++) {
            digits += Math.floor(Math.random() * 10);
        }
        document.getElementById(inputId).value = prefix + digits;
    }
</script>

@endsection