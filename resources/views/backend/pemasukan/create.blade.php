@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- KEPALA HALAMAN --}}
    <div class="d-flex flex-column mb-5">
        <a href="{{ route('admin.pemasukan-barang.index') }}" class="text-decoration-none small d-flex align-items-center gap-2 mb-3" style="color: var(--rc-text-muted); transition: color 0.2s; font-size: 0.85rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Riwayat
        </a>
        <p class="text-uppercase" style="color: var(--rc-terracotta); font-size: 0.75rem; letter-spacing: 2px; font-weight: 700; margin-bottom: 0.4rem;">
            Formulir Logistik
        </p>
        <h2 style="font-weight: 700; letter-spacing: -0.5px; margin: 0; color: #fff;">
            Registrasi Faktur Masuk
        </h2>
        <p style="color: var(--rc-text-muted); font-size: 0.9rem; margin: 0; margin-top: 0.2rem;">
            Input pasokan stok barang yang baru tiba di gudang penyimpanan utama.
        </p>
    </div>

    {{-- NOTIFIKASI ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 mb-4" style="background: rgba(239, 68, 68, 0.1); color: #f87171; border-radius: 12px; font-size: 0.9rem;">
            <div class="fw-bold mb-1">Ada kendala penginputan:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM UTAMA --}}
    <form action="{{ route('admin.pemasukan-barang.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            
            {{-- PANEL KIRI: DATA INFORMASI FAKTUR --}}
            <div class="col-12 col-xl-4">
                <div class="rc-glass-card p-4" style="background: rgba(255, 255, 255, 0.01); border: 1px solid var(--rc-glass-border); border-radius: 16px;">
                    <h5 class="fw-bold text-white mb-4" style="font-size: 1rem; letter-spacing: 0.5px;">Data Informasi Faktur</h5>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nomor Faktur / Invoice</label>
                        <input type="text" name="no_faktur" value="{{ old('no_faktur', $noFakturOtomatis ?? '') }}" class="form-control rc-custom-input fw-bold" style="color: var(--rc-terracotta) !important;" placeholder="Contoh: INV-XXXXX" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold" style="font-size: 0.75rem; color: var(--rc-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Pemasukan Barang</label>
                        <input type="datetime-local" name="tgl_pemasukan" value="{{ old('tgl_pemasukan', now()->format('Y-m-d\TH:i')) }}" class="form-control rc-custom-input text-white" required>
                    </div>

                    <div class="p-3 mb-4" style="background: rgba(255,255,255,0.02); border: 1px solid var(--rc-glass-border); border-radius: 12px;">
                        <div class="small mb-1" style="color: var(--rc-text-muted); font-size: 0.75rem;">Petugas Logistik Sesi Ini:</div>
                        <div class="fw-bold text-white small d-flex align-items-center gap-2">
                            <span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: #10b981;"></span>
                            {{ Auth::user()->name ?? Auth::user()->nama ?? 'Manajer Toko' }}
                        </div>
                    </div>

                    <button type="submit" class="btn text-white w-100 py-2.5" style="background: var(--rc-terracotta); border-radius: 12px; font-weight: 600; font-size: 0.9rem; border: none; box-shadow: 0 4px 15px rgba(200, 90, 50, 0.25);">
                        Simpan & Pembukuan Stok
                    </button>
                </div>
            </div>

            {{-- PANEL KANAN: DAFTAR ITEM PRODUK MASUK --}}
            <div class="col-12 col-xl-8">
                <div class="rc-glass-card p-4" style="background: rgba(255, 255, 255, 0.01); border: 1px solid var(--rc-glass-border); border-radius: 16px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-white mb-0" style="font-size: 1rem; letter-spacing: 0.5px;">Daftar Item Produk Masuk</h5>
                        <button type="button" id="btn-tambah-baris" class="btn btn-sm text-white px-3 py-1.5 d-flex align-items-center gap-1" style="border-radius: 8px; font-size: 0.8rem; background: rgba(255,255,255,0.04); border: 1px solid var(--rc-glass-border); font-weight: 600; position: relative; z-index: 10;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Baris
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark table-borderless align-middle mb-0" style="--bs-table-bg: transparent;">
                            <thead>
                                <tr style="color: var(--rc-text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--rc-glass-border);">
                                    <th class="pb-3" style="min-width: 280px;">Pilih Produk SKU</th>
                                    <th class="pb-3 text-center" style="width: 130px;">Jumlah (Pcs)</th>
                                    <th class="pb-3" style="min-width: 200px;">Harga Beli / Satuan</th>
                                    <th class="pb-3 text-end" style="width: 50px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="container-baris-produk">
                                
                                {{-- TEMPLATE BARIS DINAMIS --}}
                                <tr class="baris-produk-item">
                                    <td class="py-3 pe-2">
                                        <select name="id_produk[]" class="form-select rc-custom-input text-white" required>
                                            <option value="" disabled selected style="background: #111; color: #777;">-- Pilih SKU Produk --</option>
                                            @if(isset($produk))
                                                @foreach ($produk as $p)
                                                    <option value="{{ $p->id_produk }}" style="background: #121214; color: #fff;">
                                                        {{ $p->nama_produk }} (Stok Saat Ini: {{ $p->stok }})
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled style="background: #121214;">Simulasi Produk Contoh A</option>
                                                <option value="" disabled style="background: #121214;">Simulasi Produk Contoh B</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td class="py-3 pe-2">
                                        <input type="number" name="jumlah_masuk[]" min="1" placeholder="0" class="form-control rc-custom-input text-center text-white font-monospace" required>
                                    </td>
                                    <td class="py-3 pe-2">
                                        <div class="input-group">
                                            <span class="input-group-text" style="background: rgba(255,255,255,0.02); border: 1px solid var(--rc-glass-border); border-right: none; color: var(--rc-text-muted); border-radius: 10px 0 0 10px; font-size: 0.85rem;">Rp</span>
                                            <input type="number" name="harga_beli[]" min="0" placeholder="0" class="form-control rc-custom-input text-end text-white font-monospace" style="border-radius: 0 10px 10px 0; border-left: none;" required>
                                        </div>
                                    </td>
                                    <td class="py-3 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-baris d-none" style="border-radius: 8px; padding: 0.45rem 0.55rem; border-color: rgba(239, 68, 68, 0.2);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

{{-- INJEKSI STYLE CUSTOM AGAR FORM SEPADAN DENGAN BERKAS UTAMA --}}
@section('styles')
<style>
    .rc-custom-input {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid var(--rc-glass-border) !important;
        color: #fff !important;
        border-radius: 10px !important;
        padding: 0.6rem 1rem;
        font-size: 0.88rem;
    }
    .rc-custom-input:focus {
        border-color: var(--rc-terracotta) !important;
        box-shadow: 0 0 0 3px rgba(200, 90, 50, 0.15) !important;
        background: rgba(255, 255, 255, 0.04) !important;
    }
    /* Mengatasi warna drop-down select pada browser berbasis chromium */
    .form-select option {
        background: #121214 !important;
        color: #fff !important;
    }
</style>
@endsection

{{-- JAVASCRIPT DINAMIS UNTUK BARIS ITEM --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('container-baris-produk');
        const btnTambah = document.getElementById('btn-tambah-baris');

        // Fungsi mengecek sisa baris untuk menyembunyikan/menampilkan tombol hapus
        function validasiTombolHapus() {
            const semuaBaris = container.querySelectorAll('.baris-produk-item');
            semuaBaris.forEach(baris => {
                const btnHapus = baris.querySelector('.btn-hapus-baris');
                if (semuaBaris.length === 1) {
                    btnHapus.classList.add('d-none');
                } else {
                    btnHapus.classList.remove('d-none');
                }
            });
        }

        // Event Tambah Baris
        btnTambah.addEventListener('click', function () {
            const barisPertama = container.querySelector('.baris-produk-item');
            if (!barisPertama) return;

            // Proses kloning baris pertama
            const cloneBaris = barisPertama.cloneNode(true);
            
            // Reset semua field input & select di baris baru
            cloneBaris.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
            
            const selectField = cloneBaris.querySelector('select');
            if(selectField) {
                selectField.selectedIndex = 0;
            }

            // Pastikan tombol hapus di baris baru tidak tersembunyi secara default
            const btnHapusBaru = cloneBaris.querySelector('.btn-hapus-baris');
            if(btnHapusBaru) {
                btnHapusBaru.classList.remove('d-none');
            }

            // Masukkan ke dalam tabel container
            container.appendChild(cloneBaris);
            validasiTombolHapus();
        });

        // Event Delegation untuk Aksi Hapus
        container.addEventListener('click', function (e) {
            const tombolHapus = e.target.closest('.btn-hapus-baris');
            
            if (tombolHapus) {
                const barisTarget = tombolHapus.closest('.baris-produk-item');
                const semuaBaris = container.querySelectorAll('.baris-produk-item');
                
                // Cegah hapus jika tersisa hanya 1 baris
                if (semuaBaris.length > 1 && barisTarget) {
                    barisTarget.remove();
                    validasiTombolHapus();
                }
            }
        });

        // Jalankan verifikasi awal saat halaman pertama kali dimuat
        validasiTombolHapus();
    });
</script>
@endpush