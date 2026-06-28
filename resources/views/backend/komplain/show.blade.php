@extends('backend.layouts.app')

@section('content_backend')
<div class="container-fluid p-0">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
        <div>
            <a href="{{ route('admin.komplain.index') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 mb-2" style="color: var(--rc-text-muted); font-size: 0.85rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Daftar Komplain
            </a>
            <p class="rc-eyebrow">Quality Control</p>
            <h2 class="rc-section-title" style="font-size: 1.6rem; color: var(--rc-text, #ffffff);">
                Detail Pengajuan Komplain
            </h2>
            <p class="rc-section-desc">
                Pesanan <span style="color: var(--rc-accent); font-weight: 600;">#RC-{{ $komplain->id_pesanan }}</span>
                &middot; Diajukan {{ \Carbon\Carbon::parse($komplain->created_at)->translatedFormat('d F Y H:i') }}
            </p>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="rc-alert mb-4" style="background: var(--rc-success-soft); color: var(--rc-success); border: 1px solid rgba(48,209,88,0.2); border-radius: 12px; padding: 0.8rem 1.2rem; font-size: 0.85rem;">
            <i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rc-alert mb-4" style="background: var(--rc-danger-soft); color: var(--rc-danger); border: 1px solid rgba(255,69,58,0.2); border-radius: 12px; padding: 0.8rem 1.2rem; font-size: 0.85rem;">
            <i class="fa-solid fa-exclamation-circle me-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">

        {{-- LEFT COLUMN — Detail Keluhan --}}
        <div class="col-lg-7">

            {{-- STATUS TIMELINE --}}
            <div class="rc-glass-card mb-4">
                <h5 class="mb-4" style="font-weight: 600; color: var(--rc-text); font-size: 0.95rem;">Status Pengajuan</h5>
                @php
                    $steps = [
                        ['label' => 'Diajukan', 'icon' => 'fa-solid fa-file-pen', 'done' => true],
                        ['label' => 'Disetujui', 'icon' => 'fa-solid fa-check', 'done' => in_array($komplain->status_komplain, ['approved', 'selesai'])],
                        ['label' => 'Barang Diterima', 'icon' => 'fa-solid fa-box', 'done' => $komplain->foto_return && $komplain->status_komplain == 'approved'],
                        ['label' => 'Selesai', 'icon' => 'fa-solid fa-check-double', 'done' => $komplain->status_komplain == 'selesai'],
                    ];
                    if ($komplain->status_komplain == 'rejected') {
                        $steps = [
                            ['label' => 'Diajukan', 'icon' => 'fa-solid fa-file-pen', 'done' => true],
                            ['label' => 'Ditolak', 'icon' => 'fa-solid fa-ban', 'done' => true],
                        ];
                    }
                @endphp
                <div class="d-flex justify-content-between align-items-start" style="gap: 0;">
                    @foreach($steps as $i => $step)
                        <div class="d-flex flex-column align-items-center" style="flex: 1; position: relative;">
                            @if(!$loop->first)
                                <div style="position: absolute; top: 18px; right: 50%; width: 100%; height: 2px; background: {{ $step['done'] ? 'var(--rc-success)' : 'rgba(255,255,255,0.08)' }}; z-index: 0;"></div>
                            @endif
                            <div style="width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: {{ $step['done'] ? 'var(--rc-success-soft)' : 'rgba(255,255,255,0.03)' }}; border: 2px solid {{ $step['done'] ? 'var(--rc-success)' : 'rgba(255,255,255,0.1)' }}; color: {{ $step['done'] ? 'var(--rc-success)' : 'var(--rc-text-muted)' }}; position: relative; z-index: 1;">
                                <i class="{{ $step['icon'] }}" style="font-size: 14px;"></i>
                            </div>
                            <span style="font-size: 0.7rem; color: {{ $step['done'] ? 'var(--rc-text)' : 'var(--rc-text-muted)' }}; font-weight: {{ $step['done'] ? '600' : '400' }}; margin-top: 8px; text-align: center; white-space: nowrap;">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- INFORMASI KELUHAN --}}
            <div class="rc-glass-card mb-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 style="font-weight: 600; color: var(--rc-text); font-size: 0.95rem; margin: 0;">Informasi Keluhan</h5>
                    <span class="rc-badge-status" style="background: {{ 
                        $komplain->status_komplain == 'pending' ? 'var(--rc-warning-soft)' : 
                        ($komplain->status_komplain == 'approved' ? 'var(--rc-success-soft)' : 
                        ($komplain->status_komplain == 'selesai' ? 'rgba(74,139,217,0.1)' : 'var(--rc-danger-soft)')) 
                    }}; color: {{ 
                        $komplain->status_komplain == 'pending' ? 'var(--rc-warning)' : 
                        ($komplain->status_komplain == 'approved' ? 'var(--rc-success)' : 
                        ($komplain->status_komplain == 'selesai' ? 'var(--rc-accent)' : 'var(--rc-danger)')) 
                    }}; border: none;">
                        @if($komplain->status_komplain == 'pending')
                            <i class="fa-solid fa-clock me-1"></i> Pending
                        @elseif($komplain->status_komplain == 'approved')
                            <i class="fa-solid fa-check me-1"></i> Disetujui
                        @elseif($komplain->status_komplain == 'selesai')
                            <i class="fa-solid fa-check-double me-1"></i> Selesai
                        @else
                            <i class="fa-solid fa-ban me-1"></i> Ditolak
                        @endif
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <small style="color: var(--rc-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Pelanggan</small>
                        <span style="color: var(--rc-text); font-weight: 500;">{{ $komplain->user->name ?? 'Customer' }}</span>
                        <small style="color: var(--rc-text-muted); display: block;">{{ $komplain->user->email ?? '-' }}</small>
                    </div>
                    <div>
                        <small style="color: var(--rc-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Jenis Komplain</small>
                        <span style="color: var(--rc-danger); font-weight: 600;">{{ $komplain->jenis_komplain }}</span>
                    </div>
                </div>

                @if($komplain->deskripsi)
                <div class="mb-3">
                    <small style="color: var(--rc-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Deskripsi Komplain</small>
                    <p style="color: var(--rc-text); font-size: 0.9rem; line-height: 1.6; margin: 0; padding: 12px 16px; background: rgba(255,255,255,0.02); border-radius: 10px; border: 1px solid rgba(255,255,255,0.04);">
                        {{ $komplain->deskripsi }}
                    </p>
                </div>
                @endif

                <div class="mb-3">
                    <small style="color: var(--rc-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 10px;">Produk Dikomplain ({{ $komplain->detailKomplain->count() }} produk)</small>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @forelse($komplain->detailKomplain as $dk)
                            <div class="d-flex align-items-center p-2" style="border: 1px solid rgba(255,255,255,0.05); border-radius: 10px;">
                                <img src="{{ asset('storage/' . ($dk->produk->foto_produk ?? $dk->produk->foto ?? 'default.jpg')) }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px; margin-right: 12px;">
                                <div class="flex-grow-1">
                                    <span style="color: var(--rc-text); font-weight: 500;">{{ $dk->produk->nama_produk ?? 'Produk tidak tersedia' }}</span>
                                    <small style="color: var(--rc-text-muted); display: block;">x{{ $dk->qty }}</small>
                                </div>
                                @if($dk->foto_array && count($dk->foto_array) > 0)
                                    <small style="color: var(--rc-text-muted);"><i class="fa-solid fa-image me-1"></i>{{ count($dk->foto_array )}} foto</small>
                                @endif
                            </div>
                        @empty
                            <p style="color: var(--rc-text-muted); margin: 0;">Tidak ada produk.</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <small style="color: var(--rc-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 10px;">Bukti Foto</small>
                    @php $allPhotos = $komplain->detailKomplain->flatMap(fn($dk) => $dk->foto_array); @endphp
                    @if(count($allPhotos) > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($allPhotos as $foto)
                                <a href="{{ asset('storage/' . $foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $foto) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08); transition: all 0.2s;"
                                         onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                </a>
                            @endforeach
                        </div>
                        <small style="color: var(--rc-text-muted); display: block; margin-top: 8px; font-size: 0.75rem;"><i class="fa-solid fa-up-right-and-down-left-from-center me-1"></i> Klik gambar untuk memperbesar</small>
                    @else
                        <p style="color: var(--rc-text-muted); margin: 0;">User tidak melampirkan foto bukti.</p>
                    @endif
                </div>

                @if($komplain->no_resi_return || $komplain->foto_return)
                <hr style="border-color: rgba(255,255,255,0.06); margin: 1.5rem 0;">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    @if($komplain->no_resi_return)
                    <div>
                        <small style="color: var(--rc-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">No. Resi Retur</small>
                        <span style="color: var(--rc-text); font-weight: 600; font-family: monospace;">{{ $komplain->no_resi_return }}</span>
                    </div>
                    @endif
                    @if($komplain->foto_return)
                    <div>
                        <small style="color: var(--rc-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Foto Bukti Retur</small>
                        <a href="{{ asset('storage/' . $komplain->foto_return) }}" target="_blank">
                            <img src="{{ asset('storage/' . $komplain->foto_return) }}" style="max-width: 100%; max-height: 160px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08); object-fit: contain;">
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                {{-- ACTION BUTTONS --}}
                @if($komplain->status_komplain == 'pending' || $komplain->status_komplain == 'approved')
                <hr style="border-color: rgba(255,255,255,0.06); margin: 1.5rem 0;">
                <div class="d-flex gap-2 flex-wrap">
                    @if($komplain->status_komplain == 'pending')
                        @if($komplain->detailKomplain->count() > 0)
                            <button type="button" class="rc-btn-primary text-decoration-none d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                Setujui & Atur Retur
                            </button>
                        @else
                            <form action="{{ route('admin.komplain.approve', $komplain->id_komplain) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="rc-btn-primary text-decoration-none d-inline-flex align-items-center gap-2" onclick="return confirm('Setujui komplain ini?')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Setujui
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.komplain.reject', $komplain->id_komplain) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="rc-btn-outline text-decoration-none d-inline-flex align-items-center gap-2" style="color: var(--rc-danger); border-color: rgba(255,69,58,0.2);" onclick="return confirm('Tolak komplain ini?')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Tolak
                            </button>
                        </form>
                    @endif

                    @if($komplain->status_komplain == 'approved')
                        @if($komplain->no_resi_return && $komplain->foto_return)
                            <form action="{{ route('admin.komplain.finalize', $komplain->id_komplain) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="rc-btn-primary text-decoration-none d-inline-flex align-items-center gap-2">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/><polyline points="20 12 9 21 4 16"/></svg>
                                    Finalize (Selesai)
                                </button>
                            </form>
                        @elseif($komplain->no_resi_return && !$komplain->foto_return)
                            <span class="d-inline-flex align-items-center gap-2" style="background: var(--rc-warning-soft); color: var(--rc-warning); padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8rem;">
                                <i class="fa-solid fa-clock"></i> Menunggu customer upload bukti serah terima retur
                            </span>
                        @else
                            <span class="d-inline-flex align-items-center gap-2" style="background: var(--rc-accent-soft); color: var(--rc-accent); padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8rem;">
                                <i class="fa-solid fa-info-circle"></i> Menunggu customer konfirmasi kirim retur
                            </span>
                        @endif
                    @endif
                </div>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN — Order Items Summary --}}
        <div class="col-lg-5">

            <div class="rc-glass-card">
                <h5 class="mb-3" style="font-weight: 600; color: var(--rc-text); font-size: 0.95rem;">Item dalam Pesanan</h5>

                @if($komplain->pesanan && $komplain->pesanan->detailPesanan)
                    @foreach($komplain->pesanan->detailPesanan as $detail)
                        <div class="d-flex align-items-center mb-3 p-2" style="border: 1px solid rgba(255,255,255,0.04); border-radius: 10px;">
                            <img src="{{ asset('storage/' . ($detail->produk->foto_produk ?? $detail->produk->foto ?? 'default.jpg')) }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; margin-right: 12px;">
                            <div class="flex-grow-1">
                                <span style="color: var(--rc-text); font-weight: 500; font-size: 0.85rem;">{{ $detail->produk->nama_produk ?? 'Produk Terhapus' }}</span>
                                <small style="color: var(--rc-text-muted); display: block;">Jumlah: {{ $detail->jumlah }}x</small>
                            </div>
                            <span style="color: var(--rc-accent); font-weight: 600; font-size: 0.85rem;">Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                @endif

                <hr style="border-color: rgba(255,255,255,0.06);">

                <div class="d-flex justify-content-between align-items-center">
                    <span style="color: var(--rc-text-muted); font-size: 0.85rem;">Total Transaksi</span>
                    <span style="color: var(--rc-text); font-weight: 700; font-size: 1.1rem;">Rp{{ number_format($komplain->pesanan->total_harga ?? 0, 0, ',', '.') }}</span>
                </div>

                <hr style="border-color: rgba(255,255,255,0.06);">

                <div>
                    <small style="color: var(--rc-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Produk Dikomplain</small>
                    @foreach($komplain->detailKomplain as $dk)
                        <div class="d-flex align-items-center mb-1" style="color: var(--rc-danger); font-size: 0.85rem;">
                            <i class="fa-solid fa-circle-exclamation me-2" style="font-size: 10px;"></i>
                            {{ $dk->produk->nama_produk ?? '-' }} x{{ $dk->qty }}
                        </div>
                    @endforeach
                </div>

                {{-- Info retur order jika sudah difinalisasi --}}
                @php $returOrder = $komplain->returPesanan; @endphp
                @if($returOrder)
                    <hr style="border-color: rgba(255,255,255,0.06);">
                    <div>
                        <small style="color: var(--rc-text-muted); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">Retur Order Pengganti</small>
                        <span style="color: var(--rc-text); font-weight: 600; font-family: monospace;">#{{ $returOrder->id_pesanan }}</span>
                        @if($returOrder->pengiriman)
                            @foreach($returOrder->pengiriman as $p)
                                <div style="font-size: 0.8rem; color: var(--rc-text-muted); margin-top: 4px;">
                                    <i class="fa-solid fa-truck me-1"></i> {{ $p->nama_kurir }} &middot; {{ $p->no_resi }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>

@push('modals')
@if($komplain->status_komplain == 'pending' && $komplain->detailKomplain->count() > 0)
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--rc-glass-bg); backdrop-filter: blur(40px); -webkit-backdrop-filter: blur(40px); border: 1px solid var(--rc-glass-border); border-radius: 20px;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <h5 class="modal-title" style="color: var(--rc-text); font-weight: 700;">
                    <i class="fa-solid fa-pen-to-square me-2" style="color: var(--rc-terracotta);"></i>
                    Atur Produk Retur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.komplain.approve', $komplain->id_komplain) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding: 1.5rem;">
                    <p style="color: var(--rc-text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                        Centang produk yang ingin diretur, sesuaikan qty jika perlu
                    </p>
                    @foreach($komplain->detailKomplain as $dk)
                        <div class="d-flex align-items-center p-3 mb-2" style="border: 1px solid rgba(255,255,255,0.06); border-radius: 12px;">
                            <input type="checkbox" name="id_produk[]" value="{{ $dk->id_produk }}" checked class="me-3" id="prod_{{ $dk->id_produk }}" style="width: 18px; height: 18px; accent-color: var(--rc-terracotta);">
                            <img src="{{ asset('storage/' . ($dk->produk->foto_produk ?? $dk->produk->foto ?? 'default.jpg')) }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; margin-right: 12px;">
                            <div class="flex-grow-1">
                                <strong style="color: var(--rc-text);">{{ $dk->produk->nama_produk ?? 'Produk' }}</strong>
                                <small style="color: var(--rc-text-muted); display: block;">Diajukan: x{{ $dk->qty }}</small>
                            </div>
                            <div style="text-align: center;">
                                <small style="color: var(--rc-text-muted); font-size: 0.7rem; display: block;">Qty Retur</small>
                                <input type="number" name="qty_{{ $dk->id_produk }}" value="{{ $dk->qty }}" min="1" max="{{ $dk->qty }}" class="form-control form-control-sm" style="width: 70px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.1); color: var(--rc-text); text-align: center;">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.06); padding: 1rem 1.5rem;">
                    <button type="button" class="rc-btn-outline text-decoration-none" style="padding: 0.5rem 1.5rem;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="rc-btn-primary text-decoration-none d-inline-flex align-items-center gap-2" style="padding: 0.5rem 1.5rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan & Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endpush
@endsection
