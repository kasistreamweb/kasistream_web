<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Donasi;
use App\Models\User;

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

    // ── PAYMENT DETAIL ──
    public function paymentDetail($id)
    {
        $donasi = Donasi::with([
            'streamer',
            'user'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $donasi->id,
                'streamer_name' => $donasi->streamer->name,
                'nominal' => $donasi->nominal,
                'fitur_total' => $donasi->fitur_total,
                'admin_fee' => $donasi->admin_fee,
                'grand_total' => $donasi->grand_total,
                'status' => $donasi->status,
                'payment_method' => $donasi->payment_method,
                'created_at' => $donasi->created_at,
                'qr_image' => $donasi->qr_image,
                'qr_code' => $donasi->qr_code,
            ]
        ]);
    }

    // ── CHECK PAYMENT ──
    public function checkPayment($id)
    {
        $donasi = Donasi::findOrFail($id);

        return response()->json([
            'success' => true,
            'status' => $donasi->status,
            'qris_status' => $donasi->qris_status,
        ]);
    }

    // ── PAY ONOPAY ──
    public function payOnopay($id)
    {
        $donasi = Donasi::findOrFail($id);

        // Cek apakah sudah dibayar
        if ($donasi->status == 'success') {
            return response()->json([
                'success' => true,
                'message' => 'Sudah dibayar'
            ]);
        }

        // Cek nomor OnoPay user
        $payerPhone = auth()->user()->onopay_phone;

        if (!$payerPhone) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor OnoPay tidak ditemukan'
            ], 400);
        }

        // Panggil API OnoPay untuk pembayaran
        $response = Http::post(
            'https://www.onopay.web.id/api/v1/payment/qr/pay',
            [
                'qr_code'     => $donasi->qr_code,
                'payer_phone' => $payerPhone,
            ]
        );

        $result = $response->json();

        // Cek response OnoPay
        if (
            !$response->successful() ||
            !isset($result['success']) ||
            !$result['success']
        ) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Pembayaran gagal'
            ], 400);
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Update status donasi
            $donasi->update([
                'status'      => 'success',
                'qris_status' => 'paid'
            ]);

            // Tambah saldo streamer
            $streamer = User::findOrFail(
                $donasi->streamer_id
            );

            $streamer->balance += $donasi->nominal;
            $streamer->total_donasi += $donasi->nominal;
            $streamer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $donasi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ── GUEST DONATE QRIS ──
    public function guestDonateQris(Request $request)
    {
        $request->validate([
            'streamer_id' => 'required|exists:users,id',
            'guest_name' => 'required|string|max:100',
            'guest_phone' => 'required|string|max:20',
            'nominal' => 'required|numeric|min:1000',
            'pesan' => 'nullable|max:150',
        ]);

        $streamer = User::findOrFail(
            $request->streamer_id
        );

        $adminFee = 1500;

        $grandTotal =
            $request->nominal +
            $adminFee;

        $donasi = Donasi::create([
            'user_id' => null,
            'guest_name' => $request->guest_name,
            'guest_phone' => $request->guest_phone,

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
                    $request->guest_name,

                'customer_phone' =>
                    $request->guest_phone,

                'qr_mode' =>
                    'single_use'
            ]
        );

        if (!$response->successful()) {
            return response()->json([
                'success' => false
            ], 500);
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

    // ── GUEST PAYMENT DETAIL ──
    public function guestPaymentDetail($id)
    {
        return $this->paymentDetail($id);
    }

    // ── GUEST CHECK PAYMENT ──
    public function guestCheckPayment($id)
    {
        return $this->checkPayment($id);
    }

    // ── GUEST PAY ONOPAY ──
    public function guestPayOnopay($id)
    {
        $donasi = Donasi::findOrFail($id);

        if (!$donasi->guest_phone) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor OnoPay guest tidak ditemukan'
            ], 400);
        }

        $response = Http::post(
            'https://www.onopay.web.id/api/v1/payment/qr/pay',
            [
                'qr_code' =>
                    $donasi->qr_code,

                'payer_phone' =>
                    $donasi->guest_phone,
            ]
        );

        $result = $response->json();

        if (
            !$response->successful() ||
            !isset($result['success']) ||
            !$result['success']
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    $result['message']
                    ?? 'Pembayaran gagal'
            ], 400);
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
                'data' => $donasi
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}