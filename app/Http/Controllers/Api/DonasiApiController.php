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

    // ── DONATE QRIS (USER LOGIN) ──
    public function donateQris(Request $request)
    {
        $request->validate([
            'streamer_id' => 'required|exists:users,id',
            'nominal' => 'required|numeric|min:1000',
            'pesan' => 'nullable|string|max:150',
        ]);

        $user = auth()->user();

        // ── CEK ONOPAY PHONE USER ──
        if (!$user->onopay_phone) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor OnoPay user tidak ditemukan. Silakan daftarkan nomor OnoPay terlebih dahulu.'
            ], 400);
        }

        $streamer = User::findOrFail(
            $request->streamer_id
        );

        // ── CEK ONOPAY PHONE STREAMER ──
        if (!$streamer->onopay_phone) {
            return response()->json([
                'success' => false,
                'message' => 'Streamer belum memiliki nomor OnoPay yang terdaftar.'
            ], 400);
        }

        $adminFee = 1500;
        $grandTotal = $request->nominal + $adminFee;

        $donasi = Donasi::create([
            'user_id' => $user->id,
            'streamer_id' => $streamer->id,
            'nominal' => $request->nominal,
            'pesan' => $request->pesan,
            'admin_fee' => $adminFee,
            'grand_total' => $grandTotal,
            'payment_method' => 'qris',
            'status' => 'pending',
        ]);

        // ── PANGGIL API ONOPAY ──
        $response = Http::post(
            'https://www.onopay.web.id/api/v1/payment/qr/generate',
            [
                'phone_number' => $streamer->onopay_phone,
                'amount' => $grandTotal,
                'description' => 'Donasi KAistream #' . $donasi->id,
                'qr_mode' => 'single_use'
            ]
        );

        // ── CEK RESPONSE ONOPAY ──
        if (!$response->successful()) {
            $onopayResponse = $response->json();

            // Hapus donasi yang gagal
            $donasi->delete();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QRIS dari OnoPay',
                'status_code' => $response->status(),
                'onopay_response' => $onopayResponse,
                'streamer_phone' => $streamer->onopay_phone,
                'amount' => $grandTotal,
            ], $response->status());
        }

        $result = $response->json();

        // ── CEK APAKAH DATA QRIS ADA ──
        if (!isset($result['data']) || empty($result['data']['qr_code'])) {
            $donasi->delete();

            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak ditemukan dalam response OnoPay',
                'onopay_response' => $result,
            ], 500);
        }

        // ── UPDATE DONASI DENGAN QR CODE ──
        $donasi->update([
            'qr_code' => $result['data']['qr_code'] ?? null,
            'qr_image' => $result['data']['qr_image'] ?? null,
            'onopay_receiver' => $streamer->onopay_phone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QRIS berhasil dibuat',
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
        // ── PERUBAHAN: Hapus guest_phone dari validasi ──
        $request->validate([
            'streamer_id' => 'required|exists:users,id',
            'guest_name' => 'required|string|max:100',
            'nominal' => 'required|numeric|min:1000',
            'pesan' => 'nullable|max:150',
        ]);

        $streamer = User::findOrFail(
            $request->streamer_id
        );

        // ── CEK ONOPAY PHONE STREAMER ──
        if (!$streamer->onopay_phone) {
            return response()->json([
                'success' => false,
                'message' => 'Streamer belum memiliki nomor OnoPay yang terdaftar.'
            ], 400);
        }

        $adminFee = 1500;
        $grandTotal = $request->nominal + $adminFee;

        // ── PERUBAHAN: Hapus guest_phone dari create ──
        $donasi = Donasi::create([
            'user_id' => null,
            'guest_name' => $request->guest_name,
            'streamer_id' => $streamer->id,
            'nominal' => $request->nominal,
            'pesan' => $request->pesan,
            'admin_fee' => $adminFee,
            'grand_total' => $grandTotal,
            'payment_method' => 'qris',
            'status' => 'pending',
        ]);

        // ── PANGGIL API ONOPAY ──
        $response = Http::post(
            'https://www.onopay.web.id/api/v1/payment/qr/generate',
            [
                'phone_number' => $streamer->onopay_phone,
                'amount' => $grandTotal,
                'description' => 'Donasi KAistream #' . $donasi->id,
                'qr_mode' => 'single_use'
            ]
        );

        // ── CEK RESPONSE ONOPAY ──
        if (!$response->successful()) {
            $onopayResponse = $response->json();

            // Hapus donasi yang gagal
            $donasi->delete();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QRIS dari OnoPay',
                'status_code' => $response->status(),
                'onopay_response' => $onopayResponse,
                'streamer_phone' => $streamer->onopay_phone,
                'amount' => $grandTotal,
            ], $response->status());
        }

        $result = $response->json();

        // ── CEK APAKAH DATA QRIS ADA ──
        if (!isset($result['data']) || empty($result['data']['qr_code'])) {
            $donasi->delete();

            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak ditemukan dalam response OnoPay',
                'onopay_response' => $result,
            ], 500);
        }

        // ── UPDATE DONASI DENGAN QR CODE ──
        $donasi->update([
            'qr_code' => $result['data']['qr_code'] ?? null,
            'qr_image' => $result['data']['qr_image'] ?? null,
            'onopay_receiver' => $streamer->onopay_phone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QRIS berhasil dibuat',
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

    // ── STREAMER DASHBOARD ──
    public function streamerDashboard()
    {
        $streamer = auth()->user();

        // ── BASE QUERY ──
        $baseQuery = Donasi::query()
            ->where('streamer_id', $streamer->id)
            ->where('status', 'success');

        $totalDonasi = (int) (clone $baseQuery)->sum('nominal');

        $totalTransaksi = (int) (clone $baseQuery)->count();

        $totalDonatur = (int) (clone $baseQuery)
            ->select('guest_phone', 'user_id')
            ->distinct()
            ->count();

        $donasiTerbesar = (int) (clone $baseQuery)->max('nominal');

        $rataRata = $totalTransaksi > 0
            ? intval($totalDonasi / $totalTransaksi)
            : 0;

        $topDonaturRow = Donasi::query()
            ->where('streamer_id', $streamer->id)
            ->where('status', 'success')
            ->selectRaw(
                '
                COALESCE(
                    guest_name,
                    (
                        SELECT name
                        FROM users
                        WHERE users.id = donasis.user_id
                    )
                ) as donor_name,
                SUM(nominal) as total
                '
            )
            ->groupBy('donor_name')
            ->orderByDesc('total')
            ->first();

        $recentDonations = Donasi::with('user')
            ->where('streamer_id', $streamer->id)
            ->where('status', 'success')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($d) {
                return [
                    'id' => $d->id,
                    'donor' => $d->guest_name ?? $d->user?->name ?? 'Guest',
                    'pesan' => $d->pesan,
                    'nominal' => $d->nominal,
                    'created_at' => $d->created_at->format('d M Y H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_donasi' => $totalDonasi,
                'total_transaksi' => $totalTransaksi,
                'total_donatur' => $totalDonatur,
                'donasi_terbesar' => $donasiTerbesar,
                'rata_rata' => $rataRata,
                'top_donatur' => $topDonaturRow ? $topDonaturRow->donor_name : '-',
                'recent_donations' => $recentDonations,
            ]
        ]);
    }

    // ── CONFIRM PAYMENT ──
    public function confirmPayment($id)
    {
        $donasi = Donasi::findOrFail($id);

        // Cek apakah sudah dibayar
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
            // Update status donasi
            $donasi->status = 'success';
            $donasi->qris_status = 'paid';
            $donasi->save();

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
                'message' => 'Pembayaran berhasil dikonfirmasi'
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