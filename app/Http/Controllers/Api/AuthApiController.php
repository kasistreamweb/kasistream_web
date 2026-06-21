<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Follower;
use Illuminate\Support\Str;

class AuthApiController extends Controller
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
            $namaFoto = time() . '_' . Str::random(12) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $namaFoto);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'onopay_phone' => $request->onopay_phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_streamer' => 0,
            'foto' => $namaFoto,
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        // Gunakan foto_url (tidak menimpa field foto)
        $user->foto_url = $user->foto
            ? asset('uploads/profile/' . $user->foto)
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Salah'
            ], 401);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        // Gunakan foto_url (tidak menimpa field foto)
        $user->foto_url = $user->foto
            ? asset('uploads/profile/' . $user->foto)
            : null;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        // Gunakan foto_url (tidak menimpa field foto)
        $user->foto_url = $user->foto
            ? asset('uploads/profile/' . $user->foto)
            : null;

        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function dashboardSummary(Request $request)
    {
        $user = $request->user();

        $streamerCount = User::where('is_streamer', 1)->count();

        $followingCount = 0;

        if (class_exists(\App\Models\Follower::class)) {
            $followingCount = Follower::where('user_id', $user->id)->count();
        }

        return response()->json([
            'success' => true,
            'streamer_count' => $streamerCount,
            'following_count' => $followingCount,
            'total_donasi' => (int) $user->total_donasi,
            'balance' => (int) $user->balance,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'nullable|max:100',
            'bio' => 'nullable|max:500',
            'game' => 'nullable|max:100',
            'instagram' => 'nullable|max:100',
            'youtube' => 'nullable|max:100',
            'tiktok' => 'nullable|max:100',
            'discord' => 'nullable|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096'
        ]);

        // Update fields
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }

        if ($request->has('game')) {
            $user->game = $request->game;
        }

        if ($request->has('instagram')) {
            $user->instagram = $request->instagram;
        }

        if ($request->has('youtube')) {
            $user->youtube = $request->youtube;
        }

        if ($request->has('tiktok')) {
            $user->tiktok = $request->tiktok;
        }

        if ($request->has('discord')) {
            $user->discord = $request->discord;
        }

        // Upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path('uploads/profile/' . $user->foto))) {
                unlink(public_path('uploads/profile/' . $user->foto));
            }

            $file = $request->file('foto');
            $namaFoto = time() . '_' . Str::random(12) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $namaFoto);
            $user->foto = $namaFoto;
        }

        $user->save();

        // Gunakan foto_url (tidak menimpa field foto)
        $user->foto_url = $user->foto
            ? asset('uploads/profile/' . $user->foto)
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diperbarui',
            'user' => $user
        ]);
    }

    public function becomeStreamer(Request $request)
{
    $request->validate([
        'bio'  => 'required',
        'game' => 'required',
    ]);

    $user = $request->user();

    $user->bio = $request->bio;
    $user->game = $request->game;
    $user->instagram = $request->instagram;
    $user->youtube = $request->youtube;
    $user->tiktok = $request->tiktok;
    $user->discord = $request->discord;

    $user->is_streamer = true;

    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Berhasil menjadi streamer',
        'user' => $user,
    ]);
}
}