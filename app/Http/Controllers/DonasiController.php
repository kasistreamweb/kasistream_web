<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DonasiController extends Controller
{
    public function create($id)
    {
        $streamer = User::findOrFail($id);

        return view(
            'donasi',
            compact('streamer')
        );
    }

public function store(Request $request)
{
   
   $request->validate([
    'streamer_id' => 'required|exists:users,id',
    'nominal' => 'required|numeric|min:1000',
    'pesan' => 'nullable|max:150',
    'guest_name' => 'nullable|max:100',
    'guest_phone' => 'nullable|max:20'
]);

    $fiturTotal = array_sum(
        $request->fitur ?? []
    );

    $adminFee = (int) (
        $request->admin_fee ?? 1500
    );

    $grandTotal = (int) (
        $request->grand_total ??
        (
            $request->nominal +
            $fiturTotal +
            $adminFee
        )
    );

    /*
    |--------------------------------------------------------------------------
    | CEK SALDO WALLET
    |--------------------------------------------------------------------------
    */

    if (
        Auth::check() &&
        strtolower($request->metode) != 'qris'
    ) {

        $user = Auth::user();

        if ($grandTotal > $user->balance) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Saldo wallet tidak mencukupi.'
                );
        }
    }

    try {

        DB::beginTransaction();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DONASI
        |--------------------------------------------------------------------------
        */

        $donasi = Donasi::create([

            'user_id' => Auth::check()
                ? Auth::id()
                : null,

            'guest_name' => $request->guest_name,
            'guest_phone' => $request->guest_phone,

            'streamer_id' => $request->streamer_id,

            'nominal' => $request->nominal,

            'fitur_total' => $fiturTotal,

            'admin_fee' => $adminFee,

            'grand_total' => $grandTotal,

            'payment_method' => strtolower(
                $request->metode
            ),

            'pesan' => $request->pesan,

            'status' => strtolower($request->metode) == 'qris'
                ? 'pending'
                : 'success',

            'qris_status' => 'pending'
        ]);

        /*
        |--------------------------------------------------------------------------
        | WALLET
        |--------------------------------------------------------------------------
        */

        if (
            Auth::check() &&
            strtolower($request->metode) != 'qris'
        ) {

            $user = User::findOrFail(
                Auth::id()
            );

            $user->balance -= $grandTotal;

            $user->save();
        }

        /*
        |--------------------------------------------------------------------------
        | TAMBAH SALDO STREAMER
        |--------------------------------------------------------------------------
        */



        if (
            strtolower($request->metode) != 'qris'
        ) {

            $streamer = User::findOrFail(
                $request->streamer_id
            );

            $streamer->balance +=
                $request->nominal;

            $streamer->total_donasi +=
                $request->nominal;

            $streamer->save();
        }

        DB::commit();

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with(
                'error',
                $e->getMessage()
            );
    }

/*
|--------------------------------------------------------------------------
| ONOPAY QR
|--------------------------------------------------------------------------
*/

if (strtolower($request->metode) == 'qris') {

    $streamer = User::findOrFail(
        $request->streamer_id
    );

    if (!$streamer->onopay_phone) {

        return back()->with(
            'error',
            'Nomor OnoPay streamer belum diatur.'
        );
    }

    $response = Http::post(
        'https://www.onopay.web.id/api/v1/payment/qr/generate',
        [
            'phone_number' => $streamer->onopay_phone,
            'amount' => $grandTotal,
            'description' => 'Donasi KAistream #' . $donasi->id,
            'customer_name' => $request->guest_name ?? 'Guest',
            'customer_phone' => $request->guest_phone,
            'qr_mode' => 'single_use'
        ]
    );

    \Log::info('ONOPAY GENERATE', [
        'status' => $response->status(),
        'body' => $response->body(),
    ]);

    if (!$response->successful()) {

        return back()->with(
            'error',
            'Gagal membuat QR OnoPay.'
        );
    }

    $result = $response->json();

    if (
        !isset($result['success']) ||
        !$result['success']
    ) {

        return back()->with(
            'error',
            $result['message']
                ?? 'Generate QR gagal.'
        );
    }

    $donasi->update([

        'qr_code' =>
            $result['data']['qr_code'] ?? null,

        'qr_image' =>
            $result['data']['qr_image'] ?? null,

        'onopay_receiver' =>
            $streamer->onopay_phone,

        'status' =>
            'pending'
    ]);

    return redirect()->route(
        'payment.qr',
        $donasi->id
    );
}

return redirect()->route(
    'payment.success',
    $donasi->id
);
}

public function history()
{
    $donasi = Donasi::with('streamer')
        ->where('user_id', auth()->id())
        ->latest()
        ->paginate(10);

    return view(
        'riwayat-donasi',
        compact('donasi')
    );
}

public function paymentSuccess($id)
{
    $donasi = Donasi::with([
        'streamer',
        'user'
    ])->findOrFail($id);

    return view(
        'payment-success',
        compact('donasi')
    );
}

public function qrPayment($id)
{
    $donasi = Donasi::with([
        'streamer',
        'user'
    ])->findOrFail($id);

    return view(
        'payment-qr',
        compact('donasi')
    );
}

public function checkPayment($id)
{
    $donasi = Donasi::findOrFail($id);

    if ($donasi->status == 'pending') {

        return back()->with(
            'error',
            'Pembayaran belum diterima'
        );
    }

    return redirect()->route(
        'payment.success',
        $donasi->id
    );
}

public function simulateQris($id)
{
    $donasi = Donasi::findOrFail($id);

    if ($donasi->status == 'pending') {

        $donasi->update([
            'status' => 'success',
            'qris_status' => 'paid'
        ]);

        $streamer = User::find(
            $donasi->streamer_id
        );

        if ($streamer) {

            $streamer->balance +=
                $donasi->nominal;

            $streamer->total_donasi +=
                $donasi->nominal;

            $streamer->save();
        }
    }

    return redirect()->route(
        'payment.success',
        $donasi->id
    );
}

public function payOnopay($id)
{
    $donasi = Donasi::findOrFail($id);

    if ($donasi->status == 'success') {

        return redirect()->route(
            'payment.success',
            $donasi->id
        );
    }

if (Auth::check()) {

    $payerPhone = Auth::user()->onopay_phone;

} else {

    $payerPhone = $donasi->guest_phone;
}

if (!$payerPhone) {

    return back()->with(
        'error',
        'Nomor OnoPay tidak ditemukan.'
    );
}

    if (!$donasi->qr_code) {

        return back()->with(
            'error',
            'QR Code OnoPay tidak ditemukan.'
        );
    }

    try {

        $response = Http::post(
            'https://www.onopay.web.id/api/v1/payment/qr/pay',
            [
                'qr_code'     => $donasi->qr_code,
                'payer_phone' => $payerPhone,
            ]
        );

        \Log::info('ONOPAY PAY', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        $result = $response->json();

        if (
            !$response->successful() ||
            !isset($result['success']) ||
            !$result['success']
        ) {

            return back()->with(
                'error',
                $result['message']
                    ?? 'Pembayaran OnoPay gagal.'
            );
        }

        DB::beginTransaction();

        $donasi->update([
            'status'      => 'success',
            'qris_status' => 'paid'
        ]);

        $streamer = User::findOrFail(
            $donasi->streamer_id
        );

        $streamer->balance +=
            $donasi->nominal;

        $streamer->total_donasi +=
            $donasi->nominal;

        $streamer->save();

        DB::commit();

        return redirect()->route(
            'payment.success',
            $donasi->id
        );

    } catch (\Exception $e) {

        DB::rollBack();

        \Log::error('ONOPAY ERROR', [
            'message' => $e->getMessage()
        ]);

        return back()->with(
            'error',
            $e->getMessage()
        );
    }
}
}