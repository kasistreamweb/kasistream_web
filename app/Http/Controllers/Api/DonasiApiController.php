<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonasiApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'streamer_id' => 'required|exists:users,id',
            'nominal' => 'required|numeric|min:1000',
            'pesan' => 'nullable|max:150'
        ]);

        $user = auth()->user();

        $fiturTotal = 0;
        $adminFee = 1500;
        $grandTotal = $request->nominal + $adminFee;

        if($grandTotal > $user->balance)
        {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi'
            ],400);
        }

        DB::beginTransaction();

        try{

            $donasi = Donasi::create([

                'user_id' => $user->id,
                'streamer_id' => $request->streamer_id,
                'nominal' => $request->nominal,
                'pesan' => $request->pesan,

                'fitur_total' => $fiturTotal,
                'admin_fee' => $adminFee,
                'grand_total' => $grandTotal,

                'payment_method' => 'wallet',

                'status' => 'success'

            ]);

            $user->balance -= $grandTotal;
            $user->save();

            $streamer =
                User::findOrFail(
                    $request->streamer_id
                );

            $streamer->balance +=
                $request->nominal;

            $streamer->total_donasi +=
                $request->nominal;

            $streamer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Donasi berhasil',
                'data' => $donasi
            ]);

        }
        catch(\Exception $e){

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);

        }
    }

    public function history()
    {
        $data = Donasi::with([
            'streamer'
        ])
        ->where(
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

    public function donateQris(Request $request)
{
    $request->validate([
        'streamer_id' => 'required',
        'nominal' => 'required|numeric|min:1000',
        'pesan' => 'nullable'
    ]);

    $streamer = User::findOrFail(
        $request->streamer_id
    );

    $adminFee = 1500;

    $grandTotal =
        $request->nominal +
        $adminFee;

    $donasi = Donasi::create([
        'user_id' => auth()->id(),
        'streamer_id' => $streamer->id,
        'nominal' => $request->nominal,
        'pesan' => $request->pesan,
        'admin_fee' => $adminFee,
        'grand_total' => $grandTotal,
        'payment_method' => 'qris',
        'status' => 'pending',
    ]);

    $response = Http::post(
        'https://www.onopay.web.id/api/v1/payment/qr/generate',
        [
            'phone_number' =>
                $streamer->onopay_phone,

            'amount' =>
                $grandTotal,

            'description' =>
                'Donasi KAistream #' .
                $donasi->id,

            'customer_name' =>
                auth()->user()->name,

            'customer_phone' =>
                auth()->user()->onopay_phone,

            'qr_mode' =>
                'single_use'
        ]
    );

    if (!$response->successful()) {

        return response()->json([
            'success' => false
        ],500);
    }

    $result = $response->json();

    $donasi->update([

        'qr_code' =>
            $result['data']['qr_code']
                ?? null,

        'qr_image' =>
            $result['data']['qr_image']
                ?? null,

        'onopay_receiver' =>
            $streamer->onopay_phone,
    ]);

    return response()->json([
        'success' => true,
        'data' => $donasi
    ]);
}
}