<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:10000',
            'bank' => 'required',
            'rekening' => 'required',
            'nama_rekening' => 'required'
        ]);

        $user = auth()->user();

        if(
            $request->nominal >
            $user->balance
        ){
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi'
            ],400);
        }

        $withdraw = Withdraw::create([

            'user_id' => $user->id,

            'nominal' => $request->nominal,

            'bank' => $request->bank,

            'rekening' => $request->rekening,

            'nama_rekening' => $request->nama_rekening,

            'status' => 'pending'

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Withdraw berhasil diajukan',

            'data' => $withdraw

        ]);
    }

    public function history()
    {
        $data = Withdraw::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function summary(Request $request)
{
    $user = $request->user();

    $totalDonasi =
        \App\Models\Donasi::where(
            'streamer_id',
            $user->id
        )
        ->where('status', 'paid')
        ->sum('nominal');

    $totalWithdraw =
        \App\Models\Withdraw::where(
            'user_id',
            $user->id
        )
        ->where('status', 'approved')
        ->sum('nominal');

    $pendingWithdraw =
        \App\Models\Withdraw::where(
            'user_id',
            $user->id
        )
        ->where('status', 'pending')
        ->sum('nominal');

    return response()->json([
        'balance' => $user->balance,
        'total_donasi' => $totalDonasi,
        'total_withdraw' => $totalWithdraw,
        'pending_withdraw' => $pendingWithdraw,
    ]);
}
}