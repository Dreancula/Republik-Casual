<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        return view('frontend.customer.profil.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'no_telp' => 'required|max:20',
            'alamat' => 'required'
        ]);

        $user = Auth::user();

        $user->update([
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }
}