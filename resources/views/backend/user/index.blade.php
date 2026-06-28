@extends('backend.layouts.app')

@section('content_backend')
<style>
    /* VARIABEL TEMA - Menyelaraskan dengan tema gelap premium */
    :root {
        --rc-bg-main: #0e0e10;
        --rc-card-bg: rgba(255, 255, 255, 0.015);
        --rc-border-color: rgba(255, 255, 255, 0.06);
        --rc-text-primary: #f3f4f6;
        --rc-text-secondary: #8e9196;
        --rc-accent-terracotta: #e65c2e;
        --rc-accent-soft: rgba(230, 92, 46, 0.08);
        --rc-success: #10b981;
        --rc-success-soft: rgba(16, 185, 129, 0.08);
        --rc-danger: #ef4444;
        --rc-danger-soft: rgba(239, 68, 68, 0.08);
    }

    .rc-dashboard-wrapper {
        color: var(--rc-text-primary);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* KARTU FLAT */
    .rc-flat-card {
        background: var(--rc-card-bg);
        border: 1px solid var(--rc-border-color);
        border-radius: 12px;
        padding: 1.5rem;
    }

    /* SISTEM TOMBOL */
    .rc-btn-primary {
        background: var(--rc-accent-terracotta);
        color: #fff;
        border: 1px solid var(--rc-accent-terracotta);
        padding: 0.55rem 1.2rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .rc-btn-primary:hover {
        background: #d14d23;
        border-color: #d14d23;
        color: #fff;
    }

    .rc-btn-outline {
        background: transparent;
        color: var(--rc-text-primary);
        border: 1px solid var(--rc-border-color);
        padding: 0.4rem 0.9rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .rc-btn-outline:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: var(--rc-text-secondary);
        color: var(--rc-text-primary);
    }

    .rc-btn-outline-danger {
        background: transparent;
        color: var(--rc-danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: 0.4rem 0.9rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .rc-btn-outline-danger:hover {
        background: var(--rc-danger-soft);
        border-color: var(--rc-danger);
        color: var(--rc-danger);
    }

    /* TYPOGRAPHY LABEL */
    .rc-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--rc-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    /* TABEL BERSIH */
    .rc-clean-table th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--rc-text-secondary);
        letter-spacing: 0.5px;
        padding-bottom: 1rem !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .rc-clean-table td {
        font-size: 0.9rem;
        color: var(--rc-text-primary);
        padding: 1.1rem 0.5rem !important;
        border-bottom: 1px solid var(--rc-border-color) !important;
    }
    .rc-clean-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* LENCANA ROLE & STATUS (BADGE) */
    .rc-badge-role, .rc-badge-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        display: inline-block;
        border: 1px solid transparent;
    }
    .rc-badge-role.bg-manager {
        background: var(--rc-accent-soft);
        color: #ff7849;
        border-color: rgba(230, 92, 46, 0.2);
    }
    .rc-badge-role.bg-admin {
        background: rgba(255, 255, 255, 0.04);
        color: #cbd5e1;
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    /* STYLE BARU UNTUK BADGE STATUS ACCORDING TO THEME */
    .rc-badge-status.status-aktif {
        background: var(--rc-success-soft);
        color: #34d399;
        border-color: rgba(16, 185, 129, 0.2);
    }
    .rc-badge-status.status-nonaktif {
        background: var(--rc-danger-soft);
        color: var(--rc-danger);
        border-color: rgba(239, 68, 68, 0.2);
    }

    /* ALERT MINIMALIS */
    .rc-alert.success {
        background: var(--rc-success-soft);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.2);
        padding: 0.8rem 1.2rem;
        border-radius: 8px;
        font-size: 0.85rem;
    }
    .rc-alert.danger {
        background: var(--rc-danger-soft);
        color: var(--rc-danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: 0.8rem 1.2rem;
        border-radius: 8px;
        font-size: 0.85rem;
    }

    /* FORM MODAL */
    .rc-modal-content {
        background: #121214 !important;
        border: 1px solid var(--rc-border-color) !important;
        border-radius: 14px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5) !important;
    }
    .rc-input, .rc-select {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid var(--rc-border-color) !important;
        color: var(--rc-text-primary) !important;
        font-size: 0.85rem !important;
        padding: 0.6rem 0.8rem !important;
        border-radius: 8px !important;
    }
    .rc-input:focus, .rc-select:focus {
        border-color: var(--rc-accent-terracotta) !important;
        box-shadow: none !important;
        background: rgba(255, 255, 255, 0.04) !important;
    }
    .rc-select option {
        background: #121214;
        color: var(--rc-text-primary);
    }
</style>

<div class="container-fluid p-4 rc-dashboard-wrapper">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 pb-2 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="width: 6px; height: 6px; background-color: var(--rc-accent-terracotta); border-radius: 50%;"></span>
                <span class="rc-label">Otoritas Pengguna</span>
            </div>
            <h2 class="fw-bold m-0" style="font-size: 1.5rem; letter-spacing: -0.5px;">Kelola User Internal</h2>
            <p class="m-0 mt-1" style="color: var(--rc-text-secondary); font-size: 0.85rem;">
                Manajemen hak akses akun Admin dan Manajer Toko Republik Casual.
            </p>
        </div>
        
        @if(auth()->check() && auth()->user()->role && str_contains(strtolower(auth()->user()->role->nama_role), 'admin'))
            <button class="rc-btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Akun Staff
            </button>
        @endif
    </div>

    {{-- NOTIFIKASI SYSTEM --}}
    @if(session('success'))
        <div class="rc-alert success mb-4">{{ session('success') }}</div>
    @endif
    
    @if($errors->any())
        <div class="rc-alert danger mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="table-responsive rc-flat-card">
        <table class="table align-middle mb-0 rc-clean-table" style="--bs-table-bg: transparent;">
            <thead>
                <tr>
                    <th class="ps-2" style="width: 80px;">No</th>
                    <th>Nama Lengkap</th>
                    <th>Alamat Email</th>
                    <th>Hak Akses / Role</th>
                    <th>Status</th> {{-- Tambah Kolom Header Status --}}
                    @if(auth()->check() && auth()->user()->role && str_contains(strtolower(auth()->user()->role->nama_role), 'admin'))
                        <th class="text-end pe-2">Aksi Kendali</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="font-monospace ps-2" style="color: var(--rc-text-secondary); font-size: 0.8rem;">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="fw-semibold">{{ $user->nama }}</td>
                        <td style="color: var(--rc-text-secondary);">{{ $user->email }}</td>
                        <td>
                            @if($user->role && str_contains(strtolower($user->role->nama_role), 'manajer'))
                                <span class="rc-badge-role bg-manager">{{ $user->role->nama_role }}</span>
                            @else
                                <span class="rc-badge-role bg-admin">{{ $user->role ? $user->role->nama_role : 'Tidak Ada Role' }}</span>
                            @endif
                        </td>
                        <td> {{-- Tambah Kolom Data Status Akun --}}
                            @if(strtolower($user->status) == 'aktif')
                                <span class="rc-badge-status status-aktif">Aktif</span>
                            @else
                                <span class="rc-badge-status status-nonaktif">Nonaktif</span>
                            @endif
                        </td>
                        
                        @if(auth()->check() && auth()->user()->role && str_contains(strtolower(auth()->user()->role->nama_role), 'admin'))
                            <td class="text-end pe-2">
                                <div class="d-inline-flex gap-2">
                                    <button class="rc-btn-outline btn-edit-user" 
                                            data-id="{{ $user->id_user }}"
                                            data-nama="{{ $user->nama }}"
                                            data-email="{{ $user->email }}"
                                            data-no_telp="{{ $user->no_telp }}"
                                            data-status="{{ $user->status }}"
                                            data-role="{{ $user->id_role }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditUser">
                                        Edit
                                    </button>
                                    
                                    <form action="{{ route('admin.user.destroy', $user->id_user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus staff ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rc-btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted"> {{-- Ubah colspan dari 5 ke 6 karena bertambah 1 kolom --}}
                            Tidak ada data staff internal ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('modals')
{{-- MODAL TAMBAH USER --}}
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rc-modal-content">
            <div class="modal-header border-0 pb-0 pt-4 px-4 justify-content-between align-items-center">
                <h5 class="modal-title fw-bold" id="modalTambahUserLabel" style="font-size: 1.1rem; color: var(--rc-text-primary); letter-spacing: -0.3px;">Registrasi Akun Staff</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.8rem; box-shadow: none;"></button>
            </div>
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">Nama Lengkap</label>
                        <input type="text" class="form-control rc-input" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">Alamat Email</label>
                        <input type="email" class="form-control rc-input" name="email" value="{{ old('email') }}" required placeholder="budi@republikcasual.com">
                    </div>
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">No. Telepon</label>
                        <input type="text" class="form-control rc-input" name="no_telp" value="{{ old('no_telp') }}" required placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">Hak Akses / Role</label>
                        <select class="form-select rc-select" name="id_role" required>
                            <option value="">-- Pilih Hak Akses --</option>
                            @foreach(\App\Models\Role::all() as $r)
                                <option value="{{ $r->id_role }}">{{ $r->nama_role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">Kata Sandi</label>
                        <input type="password" class="form-control rc-input" name="password" required placeholder="••••••••">
                    </div>
                    <div class="mb-2">
                        <label class="rc-label d-block mb-1.5">Konfirmasi Kata Sandi</label>
                        <input type="password" class="form-control rc-input" name="password_confirmation" required placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn-close d-none" data-bs-dismiss="modal"></button>
                    <button type="button" class="rc-btn-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="rc-btn-primary">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT USER --}}
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rc-modal-content">
            <div class="modal-header border-0 pb-0 pt-4 px-4 justify-content-between align-items-center">
                <h5 class="modal-title fw-bold" id="modalEditUserLabel" style="font-size: 1.1rem; color: var(--rc-text-primary); letter-spacing: -0.3px;">Perbarui Akun Staff</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.8rem; box-shadow: none;"></button>
            </div>
            <form id="formEditUser" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">Nama Lengkap</label>
                        <input type="text" id="edit_nama" class="form-control rc-input" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">Alamat Email</label>
                        <input type="email" id="edit_email" class="form-control rc-input" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">No. Telepon</label>
                        <input type="text" id="edit_no_telp" class="form-control rc-input" name="no_telp" required>
                    </div>
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">Status Akun</label>
                        <select id="edit_status" class="form-select rc-select" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="rc-label d-block mb-1.5">Hak Akses / Role</label>
                        <select id="edit_role" class="form-select rc-select" name="id_role" required>
                            @foreach(\App\Models\Role::all() as $r)
                                <option value="{{ $r->id_role }}">{{ $r->nama_role }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="rc-btn-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="rc-btn-primary">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Balikkan penanganan tombol edit menggunakan JS murni standar browser
        document.addEventListener('click', function(event) {
            const button = event.target.closest('.btn-edit-user');
            if (button) {
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const email = button.getAttribute('data-email');
                const no_telp = button.getAttribute('data-no_telp');
                const status = button.getAttribute('data-status');
                const role = button.getAttribute('data-role');
                
                document.getElementById('formEditUser').action = `/admin/user/${id}`;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_no_telp').value = no_telp;
                document.getElementById('edit_status').value = status.toLowerCase(); // Dipastikan lowercase agar match dengan value option select
                document.getElementById('edit_role').value = role;
            }
        });
    });
</script>
@endsection