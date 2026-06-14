<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'onopay_phone' => 'required|max:20|unique:users,onopay_phone',
            'password' => 'required|min:8',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096'
        ]);

        $namaFoto = null;

        if ($request->hasFile('foto')) {

            $file = $request->file('foto');

            $namaFoto = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('uploads/profile'),
                $namaFoto
            );
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'onopay_phone' => $request->onopay_phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_streamer' => 0,
            'foto' => $namaFoto
        ]);

        return redirect('/login')
            ->with(
                'success',
                'Registrasi berhasil, silakan login'
            );
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            if (Auth::user()->role == 'admin') {
                return redirect('/admin-dashboard');
            }

            return redirect('/user-dashboard');
        }

        return back()
        ->withInput($request->only('email'))
        ->with(
            'error',
            'Email atau Password Salah'
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function profile()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'onopay_phone' => 'nullable|max:20|unique:users,onopay_phone,' . Auth::id(),
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096'
        ]);

        $user = Auth::user();

        $user->name = $request->name;
        $user->onopay_phone = $request->onopay_phone;

        $user->{'jadwal_Live'} = $request->jadwal_Live;

        if ($request->hasFile('foto')) {

            $file = $request->file('foto');

            $namaFoto = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('uploads/profile'),
                $namaFoto
            );

            $user->foto = $namaFoto;
        }

        $user->save();

        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }
}