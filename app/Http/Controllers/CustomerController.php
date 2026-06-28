<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;

class CustomerController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();

            $registeredUser = User::where('email', $socialUser->email)->first();

            if (!$registeredUser) {
                $roleCustomer = Role::where('nama_role', 'Customer')->first();

                $user = User::create([
                    'nama' => $socialUser->name,
                    'email' => $socialUser->email,
                    'id_role' => $roleCustomer ? $roleCustomer->id_role : 2,
                    'status' => 'aktif',
                    'password' => Hash::make('default_password'),
                    'google_id' => $socialUser->id,
                ]);

                Auth::login($user);
            } else {
                Auth::login($registeredUser);
            }

            return redirect()->intended('beranda');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    public function index()
    {
        $roleCustomer = Role::where('nama_role', 'Customer')->first();
        $customer = User::with('role')
            ->where('id_role', $roleCustomer ? $roleCustomer->id_role : 2)
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('admin.user.index', [
            'judul' => 'Customer',
            'sub' => 'Halaman Customer',
            'index' => $customer
        ]);
    }

    public function akun($id)
    {
        $loggedInUserId = Auth::user()->id_user;
        if ($id != $loggedInUserId) {
            return redirect()->route('customer.akun', ['id' => $loggedInUserId])
                ->with('msgError', 'Anda tidak berhak mengakses akun ini.');
        }
        $user = User::findOrFail($id);
        return view('customer.profil.edit', [
            'judul' => 'Customer',
            'subJudul' => 'Akun Customer',
            'edit' => $user
        ]);
    }

    public function updateAkun(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rules = [
            'nama' => 'required|max:255',
            'no_telp' => 'required|min:10|max:13',
            'avatar' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];
        $messages = [
            'avatar.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'avatar.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ];

        if ($request->email != $user->email) {
            $rules['email'] = 'required|max:255|email|unique:users';
        }
        if ($request->alamat != $user->alamat) {
            $rules['alamat'] = 'required';
        }

        $validatedData = $request->validate($rules, $messages);

        if ($request->file('avatar')) {
            if ($user->avatar) {
                $oldImagePath = public_path('storage/img-customer/') . $user->avatar;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-customer/';
            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);
            $validatedData['avatar'] = $originalFileName;
        }

        $user->update($validatedData);

        return redirect()->route('customer.akun', $id)->with('success', 'Data berhasil diperbarui');
    }
}
