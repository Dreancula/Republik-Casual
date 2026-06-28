<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;

class UserController extends Controller
{
    /**
     * Helper privat untuk mengecek apakah user yang login adalah Admin.
     */
    private function checkAdminOtoritas()
    {
        // Pastikan user sudah login dan memiliki relasi role
        if (!auth()->check() || !auth()->user()->role) {
            abort(403, 'Otoritas ditolak. Anda tidak memiliki role yang valid.');
        }

        $roleUser = strtolower(auth()->user()->role->nama_role);

        // Mengecek apakah string nama_role mengandung kata 'admin' atau 'administrator'
        if (!str_contains($roleUser, 'admin')) {
            abort(403, 'Otoritas ditolak. Hanya Admin yang memiliki akses modifikasi data staff.');
        }
    }

    /**
     * Menampilkan Halaman Utama Staff
     */
    public function index()
    {
        // Mengambil data user beserta relasi rolenya
        $user = User::with('role')->orderBy('updated_at', 'desc')->get();

        return view('backend.user.index', [
            'judul' => 'Data User',
            'users' => $user
        ]);
    }

    public function create()
    {
        $this->checkAdminOtoritas();

        $roles = Role::orderBy('nama_role', 'asc')->get();
        return view('admin.user.create', [
            'judul' => 'User',
            'sub' => 'Tambah User',
            'roles' => $roles
        ]);
    }

    /**
     * Memproses Simpan Akun Lewat Modal
     */
    public function store(Request $request)
    {
        $this->checkAdminOtoritas();

        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users,email',
            'id_role' => 'required|exists:role,id_role',
            'no_telp' => 'required|min:10|max:13',
            'password' => 'required|min:4',
        ]);

        // Status awal default untuk staff baru
        $validatedData['status'] = 'nonaktif';

        // Validasi kompleksitas password opsional (Bisa dimatikan jika menyulitkan saat testing modal)
        $password = $request->input('password');
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';

        if (!preg_match($pattern, $password)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['password' => 'Password harus terdiri dari kombinasi huruf besar, huruf kecil, angka, dan simbol karakter.']);
        }

        $validatedData['password'] = Hash::make($request->password);

        User::create($validatedData);
        return redirect()->route('admin.user.index')->with('success', 'Data staff baru berhasil tersimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $this->checkAdminOtoritas();

        $user = User::findOrFail($id);
        $roles = Role::orderBy('nama_role', 'asc')->get();
        return view('admin.user.edit', [
            'judul' => 'Ubah User',
            'edit' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Memproses Update Akun Lewat Modal (Sesuai dengan Form Edit di View)
     */
    public function update(Request $request, string $id)
    {
        $this->checkAdminOtoritas();

        $user = User::findOrFail($id);

        $rules = [
            'nama' => 'required|max:255',
            'id_role' => 'required|exists:role,id_role',
            'status' => 'required|in:aktif,nonaktif',
            'no_telp' => 'required|min:10|max:13',
        ];

        // Jika email diubah, jalankan validasi unique
        if ($request->email != $user->email) {
            $rules['email'] = 'required|max:255|email|unique:users,email';
        }

        $validatedData = $request->validate($rules);

        $user->update($validatedData);
        return redirect()->route('admin.user.index')->with('success', 'Data staff berhasil diperbaharui');
    }

    /**
     * Menghapus Akun Staff
     */
    public function destroy(string $id)
    {
        $this->checkAdminOtoritas();

        $user = User::findOrFail($id);

        // Keamanan: Cegah admin menghapus dirinya sendiri yang sedang login
        if ($user->id_user === auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'Anda tidak diperbolehkan menghapus akun Anda sendiri yang sedang aktif.']);
        }

        // Hapus avatar jika ada sebelum record di-delete
        if ($user->avatar) {
            $oldImagePath = public_path('storage/img-user/') . $user->avatar;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'Data berhasil dihapus');
    }

    public function formUser()
    {
        return view('admin.user.form', [
            'judul' => 'Laporan Data User',
        ]);
    }

    public function cetakUser(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ], [
            'tanggal_awal.required' => 'Tanggal Awal harus diisi.',
            'tanggal_akhir.required' => 'Tanggal Akhir harus diisi.',
            'tanggal_akhir.after_or_equal' => 'Tanggal Akhir harus lebih besar atau sama dengan Tanggal Awal.',
        ]);

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $user = User::whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('id_user', 'desc')
            ->get();

        return view('admin.user.cetak', [
            'judul' => 'Laporan User',
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'cetak' => $user
        ]);
    }
}