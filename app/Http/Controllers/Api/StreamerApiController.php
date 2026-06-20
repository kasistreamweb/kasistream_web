<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;

class StreamerApiController extends Controller
{
    public function index()
    {
        $streamers = User::where('is_streamer', 1)
            ->get()
            ->map(function ($streamer) {

                // Hitung jumlah followers untuk setiap streamer
                $streamer->followers = Follower::where(
                    'streamer_id',
                    $streamer->id
                )->count();

                // Tambahkan foto_url
                $streamer->foto_url = $streamer->foto
                    ? asset('uploads/profile/' . $streamer->foto)
                    : null;

                return $streamer;
            });

        return response()->json($streamers);
    }

    public function show($id)
    {
        $streamer = User::where('is_streamer', 1)
            ->where('id', $id)
            ->first();

        if (!$streamer) {
            return response()->json([
                'message' => 'Streamer tidak ditemukan'
            ], 404);
        }

        // Hitung jumlah followers
        $streamer->followers = Follower::where(
            'streamer_id',
            $streamer->id
        )->count();

        // Tambahkan foto_url
        $streamer->foto_url = $streamer->foto
            ? asset('uploads/profile/' . $streamer->foto)
            : null;

        return response()->json($streamer);
    }

    public function follow(Request $request, $id)
    {
        $user = $request->user();
        $streamer = User::where('is_streamer', 1)
            ->where('id', $id)
            ->first();

        if (!$streamer) {
            return response()->json([
                'message' => 'Streamer tidak ditemukan'
            ], 404);
        }

        if ($user->id == $streamer->id) {
            return response()->json([
                'message' => 'Tidak bisa follow diri sendiri'
            ], 400);
        }

        // Cek apakah sudah follow
        $existingFollow = Follower::where('user_id', $user->id)
            ->where('streamer_id', $streamer->id)
            ->first();

        if ($existingFollow) {
            return response()->json([
                'message' => 'Sudah mengikuti streamer ini'
            ], 400);
        }

        // Create follow
        Follower::create([
            'user_id' => $user->id,
            'streamer_id' => $streamer->id,
        ]);

        // Hitung followers terbaru
        $followersCount = Follower::where('streamer_id', $streamer->id)->count();

        return response()->json([
            'message' => 'Berhasil mengikuti streamer',
            'followers' => $followersCount
        ]);
    }

    public function unfollow(Request $request, $id)
    {
        $user = $request->user();
        $streamer = User::where('is_streamer', 1)
            ->where('id', $id)
            ->first();

        if (!$streamer) {
            return response()->json([
                'message' => 'Streamer tidak ditemukan'
            ], 404);
        }

        // Cek apakah sudah follow
        $existingFollow = Follower::where('user_id', $user->id)
            ->where('streamer_id', $streamer->id)
            ->first();

        if (!$existingFollow) {
            return response()->json([
                'message' => 'Belum mengikuti streamer ini'
            ], 400);
        }

        // Delete follow
        $existingFollow->delete();

        // Hitung followers terbaru
        $followersCount = Follower::where('streamer_id', $streamer->id)->count();

        return response()->json([
            'message' => 'Berhasil unfollow streamer',
            'followers' => $followersCount
        ]);
    }

    public function checkFollow(Request $request, $id)
    {
        $user = $request->user();
        $streamer = User::where('is_streamer', 1)
            ->where('id', $id)
            ->first();

        if (!$streamer) {
            return response()->json([
                'message' => 'Streamer tidak ditemukan'
            ], 404);
        }

        $isFollowing = Follower::where('user_id', $user->id)
            ->where('streamer_id', $streamer->id)
            ->exists();

        return response()->json([
            'is_following' => $isFollowing
        ]);
    }

    public function getFollowers($id)
    {
        $streamer = User::where('is_streamer', 1)
            ->where('id', $id)
            ->first();

        if (!$streamer) {
            return response()->json([
                'message' => 'Streamer tidak ditemukan'
            ], 404);
        }

        $followers = Follower::where('streamer_id', $streamer->id)
            ->with('user')
            ->get()
            ->map(function ($follower) {
                return $follower->user;
            });

        return response()->json($followers);
    }
}