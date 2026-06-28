<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        $user = User::where(
            'email',
            $googleUser->email
        )->first();

        if (!$user) {

            $user = User::create([
                'nama' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => bcrypt(uniqid()),
                'status' => 'aktif',
                'id_role' => 3
            ]);
        }

        Auth::login($user);

        if (
            empty($user->alamat) ||
            empty($user->no_telp)
        ) {
            return redirect()->route('profil.index');
        }

        return redirect()->route('beranda');
    }
}