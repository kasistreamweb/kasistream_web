<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Follower;

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

            $namaFoto =
                time() .
                '_' .
                $file->getClientOriginalName();

            $file->move(
                public_path('uploads/profile'),
                $namaFoto
            );
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

        $token = $user
            ->createToken('mobile')
            ->plainTextToken;

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

        $user = User::where(
            'email',
            $request->email
        )->first();

        if (
            !$user ||
            !Hash::check(
                $request->password,
                $user->password
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Salah'
            ], 401);
        }

        $token = $user
            ->createToken('mobile')
            ->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json(
            $request->user()
        );
    }

    public function logout(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function dashboardSummary(Request $request)
{
    $user = $request->user();

    $streamerCount = User::where(
        'is_streamer',
        1
    )->count();

    $followingCount = 0;

    if (class_exists(\App\Models\Follower::class)) {

        $followingCount = Follower::where(
            'user_id',
            $user->id
        )->count();
    }

    return response()->json([
        'success' => true,

        'streamer_count' => $streamerCount,

        'following_count' => $followingCount,

        'total_donasi' => (int) $user->total_donasi,

        'balance' => (int) $user->balance,
    ]);
}
}