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
        return response()->json(

            User::where(
                'is_streamer',
                1
            )->get()

        );
    }

public function show($id)
{
    $streamer = User::findOrFail($id);

    $topDonatur = Donasi::where('streamer_id', $id)
        ->selectRaw('guest_name, user_id, SUM(nominal) as total_donasi')
        ->groupBy('guest_name', 'user_id')
        ->orderByDesc('total_donasi')
        ->take(10)
        ->get();

    $isFollowing = auth()->check()
        ? Follower::where('user_id', auth()->id())
            ->where('streamer_id', $id)
            ->exists()
        : false;

    return view(
        'streamers-detail',
        compact(
            'streamer',
            'isFollowing',
            'topDonatur'
        )
    );
}

    public function follow($id)
    {
        Follower::firstOrCreate([

            'user_id'=>auth()->id(),

            'streamer_id'=>$id

        ]);

        return response()->json([
            'success'=>true
        ]);
    }

    public function unfollow($id)
    {
        Follower::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'streamer_id',
            $id
        )
        ->delete();

        return response()->json([
            'success'=>true
        ]);
    }
}
