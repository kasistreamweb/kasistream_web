<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pembayaran QRIS - KAsistream</title>

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/kasistream.css') }}">

<link rel="stylesheet" href="{{ asset('css/payment-qr.css') }}">

</head>

<body>

<div class="container-fluid">

    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 p-4 content-area">

            <div class="welcome-section mb-4">

                <img
                    src="{{ asset('images/logo.png') }}"
                    class="header-logo"
                    alt="Logo"
                >

                <h1>
                    💳 Pembayaran QRIS
                </h1>

                <p>
                    Scan QR Code berikut untuk menyelesaikan pembayaran donasi.
                </p>

            </div>

            <div class="row">

                <!-- QR SECTION -->
                <div class="col-lg-6 mb-4">

                    <div class="qr-card">

                        <div class="status-badge">
                            <i class="fa-solid fa-clock me-2"></i>
                            <span id="statusText">MENUNGGU PEMBAYARAN</span>
                        </div>

                        <div class="qr-wrapper">

                            @if($donasi->qr_image)

                            <img
                                src="{{ $donasi->qr_image }}"
                                class="img-fluid rounded shadow"
                                alt="QR OnoPay"
                            >

                            @else

                            <div class="text-center p-5">
                                <i class="fa-solid fa-qrcode fa-5x text-muted"></i>
                                <p class="mt-3 text-muted">QR Code belum tersedia</p>
                            </div>

                            @endif

                        </div>

                    </div>

                </div>

                <!-- DETAIL -->
                <div class="col-lg-6">

                    <div class="detail-card">

                        <h3 class="section-title">

                            Detail Transaksi

                        </h3>

                        <div class="detail-row">

                            <span>ID Transaksi</span>

                            <strong>

                                #TRX{{ $donasi->id }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Streamer</span>

                            <strong>

                                {{ $donasi->streamer->name ?? '-' }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Nominal Donasi</span>

                            <strong>

                                Rp {{ number_format($donasi->nominal) }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Fitur Tambahan</span>

                            <strong>

                                Rp {{ number_format($donasi->fitur_total ?? 0) }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Biaya Admin</span>

                            <strong>

                                Rp {{ number_format($donasi->admin_fee ?? 0) }}

                            </strong>

                        </div>

                        <hr>

                        <div class="detail-row total-row">

                            <span>Total Pembayaran</span>

                            <strong>

                                Rp {{ number_format($donasi->grand_total ?? $donasi->nominal) }}

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Status</span>

                            <!-- ── STATUS BADGE DINAMIS ── -->
                            <span
                                id="paymentStatus"
                                class="badge bg-warning text-dark"
                            >
                                MENUNGGU PEMBAYARAN
                            </span>

                        </div>

                        <div class="detail-row">

                            <span>Metode</span>

                            <strong>

                                QRIS

                            </strong>

                        </div>

                        <div class="detail-row">

                            <span>Dibuat</span>

                            <strong>

                                {{ $donasi->created_at->format('d M Y H:i') }}

                            </strong>

                        </div>

                        @if(isset($isGuest) && $isGuest)
                        <div class="mt-3 alert alert-info">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <strong>Info Guest:</strong> Setelah melakukan pembayaran, klik tombol 
                            "Cek Status Pembayaran" untuk memperbarui status donasi Anda.
                        </div>
                        @endif

                        <div class="mt-4">

                            <!-- ── TOMBOL CEK STATUS PEMBAYARAN ── -->
                            <button
                                onclick="checkPayment()"
                                id="btnCheckPayment"
                                class="btn btn-primary w-100 mb-2"
                            >
                                <i class="fa-solid fa-rotate me-2"></i>
                                Cek Status Pembayaran
                            </button>

                            <a
                                href="/riwayat-donasi"
                                class="btn btn-outline-light w-100"
                            >

                                Kembali

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ── SCRIPTS ── -->
<script>
// ── VARIABEL ──
const isGuest = {{ isset($isGuest) && $isGuest ? 'true' : 'false' }};
const donasiId = {{ $donasi->id }};
const baselineBalance = {{ $baselineBalance ?? 0 }};
const total = {{ $donasi->grand_total }};

console.log('=== PAYMENT QRIS ===');
console.log('isGuest:', isGuest);
console.log('donasiId:', donasiId);
console.log('baselineBalance:', baselineBalance);
console.log('total:', total);

// ── ONOPAY BALANCE CHECK (HANYA UNTUK USER LOGIN) ──
async function getBalance() {
    try {
        const response = await fetch('/onopay-balance', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            console.error('Response not OK:', response.status);
            return null;
        }
        
        return await response.json();
    } catch (e) {
        console.error('Error fetching balance:', e);
        return null;
    }
}

// ── AUTO CHECK PAYMENT (HANYA UNTUK USER LOGIN) ──
async function checkPaymentAuto() {
    // ── GUEST: SKIP AUTO CHECK ──
    if (isGuest) return;
    
    if (baselineBalance === 0) return;

    const result = await getBalance();

    if (!result || !result.success || !result.data) return;

    const current = parseInt(result.data.balance);
    const diff = baselineBalance - current;

    console.log('AUTO CHECK - TOTAL:', total);
    console.log('AUTO CHECK - BASELINE:', baselineBalance);
    console.log('AUTO CHECK - CURRENT:', current);
    console.log('AUTO CHECK - DIFF:', diff);

    if (diff >= total) {
        try {
            const confirm = await fetch('/confirm-payment/' + donasiId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const confirmResult = await confirm.json();

            if (confirmResult.success) {
                window.location.href = '/payment-success/' + donasiId;
            }
        } catch (e) {
            console.error('Error confirming payment:', e);
        }
    }
}

// ── CEK STATUS PEMBAYARAN ──
let checking = false;

async function checkPayment() {
    if (checking) return;
    checking = true;

    const btn = document.getElementById('btnCheckPayment');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Memeriksa...';
    btn.disabled = true;

    try {
        let response;
        let result;

        if (isGuest) {
            // ── GUEST: PAKAI guest-pay-onopay ──
            console.log('GUEST: Calling guest-pay-onopay...');
            
            response = await fetch('/guest/pay-onopay/' + donasiId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            result = await response.json();

            console.log('GUEST Pay Onopay Result:', result);

            if (result.success) {
                // ── STATUS BERHASIL ──
                document.getElementById('paymentStatus').className = 'badge bg-success';
                document.getElementById('paymentStatus').innerText = 'PEMBAYARAN BERHASIL';
                document.getElementById('statusText').innerText = 'PEMBAYARAN BERHASIL';
                
                // Redirect ke halaman sukses
                window.location.href = '/payment-success/' + donasiId;
                return;
            } else {
                // ── GAGAL / PENDING ──
                alert(result.message || 'Pembayaran masih pending. Silakan coba lagi.');
            }

        } else {
            // ── USER LOGIN: PAKAI check-payment ──
            console.log('USER: Calling check-payment...');
            
            response = await fetch('/check-payment/' + donasiId);
            result = await response.json();

            console.log('USER Check Payment Result:', result);

            const statusEl = document.getElementById('paymentStatus');
            const statusText = document.getElementById('statusText');

            if (result.data && (result.data.status === 'success' || result.data.status === 'paid')) {
                statusEl.className = 'badge bg-success';
                statusEl.innerText = 'PEMBAYARAN BERHASIL';
                statusText.innerText = 'PEMBAYARAN BERHASIL';
                window.location.href = '/payment-success/' + donasiId;
                return;
            } else {
                alert('Pembayaran masih pending. Silakan coba lagi.');
            }
        }

    } catch (e) {
        console.error('Error:', e);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    } finally {
        checking = false;
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// ── CEK APAKAH STATUS SUDAH SUCCESS SAAT LOAD ──
async function checkInitialStatus() {
    try {
        let response;
        let result;

        if (isGuest) {
            // ── GUEST: PAKAI guest-check-payment ──
            response = await fetch('/guest/check-payment/' + donasiId);
        } else {
            // ── USER: PAKAI check-payment ──
            response = await fetch('/check-payment/' + donasiId);
        }
        
        result = await response.json();
        
        if (result.data && (result.data.status === 'success' || result.data.status === 'paid')) {
            window.location.href = '/payment-success/' + donasiId;
        }
    } catch (e) {
        console.error('Error checking initial status:', e);
    }
}

// ── INISIALISASI ──
checkInitialStatus();

// ── AUTO CHECK SETIAP 5 DETIK (HANYA UNTUK USER LOGIN) ──
if (!isGuest) {
    console.log('USER LOGIN: Auto check enabled (every 5 seconds)');
    setInterval(checkPaymentAuto, 5000);
} else {
    console.log('GUEST: Auto check disabled (manual only)');
}

// ── AUTO CHECK STATUS SETIAP 5 DETIK (HANYA UNTUK USER LOGIN) ──
if (!isGuest) {
    setInterval(async function() {
        try {
            const response = await fetch('/check-payment/' + donasiId);
            const result = await response.json();

            if (result.data && (result.data.status === 'success' || result.data.status === 'paid')) {
                window.location.href = '/payment-success/' + donasiId;
            }
        } catch (e) {
            console.error('Auto check error:', e);
        }
    }, 5000);
}
</script>

</body>
</html>