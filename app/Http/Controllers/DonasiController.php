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

    // ── QR PAYMENT ──
    public function qrPayment($id)
    {
        $donasi = Donasi::with([
            'streamer',
            'user'
        ])->findOrFail($id);

        // ── CEK APAKAH GUEST ──
        $isGuest = $donasi->user_id === null;

        $baselineBalance = 0;

        // ── USER LOGIN: AMBIL BASELINE BALANCE ──
        if (!$isGuest && auth()->check() && auth()->user()->onopay_phone) {

            try {

                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post(
                    'https://onopay.web.id/api/v1/merchant/check-balance',
                    [
                        'phone_number' =>
                            auth()->user()->onopay_phone
                    ]
                );

                $data = $response->json();

                if (
                    isset($data['success']) &&
                    $data['success'] == true &&
                    isset($data['data']['balance'])
                ) {

                    $baselineBalance =
                        (int) $data['data']['balance'];
                }

            } catch (\Exception $e) {

                \Log::error(
                    'QR PAYMENT BASELINE ERROR : '
                    . $e->getMessage()
                );
            }
        }

        return view(
            'payment-qr',
            compact(
                'donasi',
                'baselineBalance',
                'isGuest'
            )
        );
    }

    // ── CHECK PAYMENT (USER LOGIN) ──
    public function checkPayment($id)
    {
        $donasi = Donasi::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $donasi->id,
                'status' => $donasi->status,
                'qris_status' => $donasi->qris_status,
                'nominal' => $donasi->nominal,
                'grand_total' => $donasi->grand_total,
            ]
        ]);
    }

    // ── GUEST CHECK PAYMENT ──
    public function guestCheckPayment($id)
    {
        $donasi = Donasi::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $donasi->id,
                'status' => $donasi->status,
                'qris_status' => $donasi->qris_status,
            ]
        ]);
    }

    // ── ONOPAY BALANCE (API) ──
    public function onopayBalance()
    {
        $user = auth()->user();

        if (!$user || !$user->onopay_phone) {

            return response()->json([
                'success' => false,
                'message' => 'Nomor OnoPay tidak ditemukan'
            ]);
        }

        try {

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(
                'https://onopay.web.id/api/v1/merchant/check-balance',
                [
                    'phone_number' =>
                        $user->onopay_phone
                ]
            );

            return response()->json(
                $response->json()
            );

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // ── CONFIRM PAYMENT (USER LOGIN) ──
    public function confirmPayment($id)
    {
        $donasi = Donasi::findOrFail($id);

        if (
            $donasi->status == 'success' ||
            $donasi->status == 'paid'
        ) {
            return response()->json([
                'success' => true,
                'message' => 'Sudah dibayar'
            ]);
        }

        DB::beginTransaction();

        try {

            $donasi->update([
                'status' => 'success',
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

            return response()->json([
                'success' => true,
                'message' =>
                    'Pembayaran berhasil dikonfirmasi'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ── GUEST PAY ONOPAY (MANUAL CHECK) ──
    public function guestPayOnopay($id)
    {
        $donasi = Donasi::findOrFail($id);

        // ── CEK APAKAH SUDAH SUCCESS ──
        if ($donasi->status == 'success') {
            return response()->json([
                'success' => true,
                'message' => 'Sudah dibayar'
            ]);
        }

        // ── AMBIL NOMOR HP GUEST ──
        $payerPhone = $donasi->guest_phone;

        if (!$payerPhone) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor OnoPay guest tidak ditemukan.'
            ], 400);
        }

        if (!$donasi->qr_code) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak ditemukan.'
            ], 400);
        }

        try {
            // ── PANGGIL API ONOPAY ──
            $response = Http::post(
                'https://www.onopay.web.id/api/v1/payment/qr/pay',
                [
                    'qr_code' => $donasi->qr_code,
                    'payer_phone' => $payerPhone,
                ]
            );

            \Log::info('GUEST ONOPAY PAY', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $result = $response->json();

            // ── CEK RESPONSE ONOPAY ──
            if (!$response->successful() || !isset($result['success']) || !$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Pembayaran gagal. Silakan coba lagi.'
                ], 400);
            }

            // ── KONFIRMASI PEMBAYARAN ──
            DB::beginTransaction();

            $donasi->update([
                'status' => 'success',
                'qris_status' => 'paid'
            ]);

            $streamer = User::findOrFail($donasi->streamer_id);
            $streamer->balance += $donasi->nominal;
            $streamer->total_donasi += $donasi->nominal;
            $streamer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('GUEST ONOPAY ERROR', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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