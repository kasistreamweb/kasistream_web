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

                        <!-- ── COUNTDOWN EXPIRED ── -->
                        <div class="alert alert-warning mt-3">

                            QRIS akan kedaluwarsa dalam
                            <span id="countdown">15:00</span>

                        </div>

                        <!-- ── TOMBOL CEK STATUS MANUAL ── -->
                        <div class="mt-4 text-center">

                            <button
                                onclick="checkPayment()"
                                id="btnCheckPayment"
                                class="btn btn-primary btn-lg"
                            >
                                <i class="fa-solid fa-rotate"></i>
                                Cek Status Pembayaran
                            </button>

                        </div>

                        <div class="payment-apps">

                            <div>
                                <i class="fa-solid fa-wallet"></i>
                                DANA
                            </div>

                            <div>
                                <i class="fa-solid fa-wallet"></i>
                                OVO
                            </div>

                            <div>
                                <i class="fa-solid fa-wallet"></i>
                                GoPay
                            </div>

                            <div>
                                <i class="fa-solid fa-building-columns"></i>
                                M-Banking
                            </div>

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

                        <div class="mt-4">

                            <!-- ── TOMBOL CEK STATUS PEMBAYARAN (AJAX) ── -->
                            <button
                                onclick="checkPayment()"
                                id="btnCheckPaymentBottom"
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
// ── ONOPAY BALANCE CHECK ──
// ── PERUBAHAN: Baseline langsung dari server ──
let baselineBalance = {{ $baselineBalance ?? 0 }};
let isExpired = false;

const total = {{ $donasi->grand_total }};

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

// ── HAPUS: initBaseline() ──
// ── HAPUS: initBaseline() dipanggil ──

async function checkPaymentAuto() {
    if (baselineBalance === null || isExpired) return;

    const result = await getBalance();

    if (!result || !result.success || !result.data) return;

    const current = parseInt(result.data.balance);
    const diff = baselineBalance - current;

    // ── TAMBAHKAN DEBUG ──
    console.log('TOTAL:', total);
    console.log('BASELINE:', baselineBalance);
    console.log('CURRENT:', current);
    console.log('DIFF:', diff);

    if (diff >= total) {
        try {
            const confirm = await fetch('/confirm-payment/{{ $donasi->id }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const confirmResult = await confirm.json();

            if (confirmResult.success) {
                window.location.href = '/payment-success/{{ $donasi->id }}';
            }
        } catch (e) {
            console.error('Error confirming payment:', e);
        }
    }
}

// ── CEK APAKAH STATUS SUDAH SUCCESS SAAT LOAD ──
async function checkInitialStatus() {
    try {
        const response = await fetch('/check-payment/{{ $donasi->id }}');
        const result = await response.json();
        
        if (result.data && (result.data.status === 'success' || result.data.status === 'paid')) {
            window.location.href = '/payment-success/{{ $donasi->id }}';
        }
    } catch (e) {
        console.error('Error checking initial status:', e);
    }
}

// ── INISIALISASI ──
// ── HAPUS: initBaseline() ──
checkInitialStatus();

// ── AUTO CHECK SETIAP 5 DETIK ──
setInterval(checkPaymentAuto, 5000);

// ── CEK STATUS PEMBAYARAN ──
let checking = false;

async function checkPayment() {
    if (checking || isExpired) return;
    checking = true;

    try {
        const response = await fetch(
            '/check-payment/{{ $donasi->id }}'
        );

        const result = await response.json();

        console.log('Check Payment Result:', result);

        // ── UPDATE STATUS BADGE ──
        const statusEl = document.getElementById('paymentStatus');
        const statusText = document.getElementById('statusText');

        if (
            result.data &&
            (result.data.status === 'success' || result.data.status === 'paid')
        ) {
            // ── STATUS BERHASIL ──
            if (statusEl) {
                statusEl.className = 'badge bg-success';
                statusEl.innerText = 'PEMBAYARAN BERHASIL';
            }

            if (statusText) {
                statusText.innerText = 'PEMBAYARAN BERHASIL';
            }

            // ── REDIRECT KE HALAMAN SUKSES ──
            window.location.href =
                '/payment-success/{{ $donasi->id }}';

            return;
        } else {
            // ── STATUS PENDING ──
            if (statusEl) {
                statusEl.className = 'badge bg-warning text-dark';
                statusEl.innerText = 'MENUNGGU PEMBAYARAN';
            }

            if (statusText) {
                statusText.innerText = 'MENUNGGU PEMBAYARAN';
            }
        }

    } catch (e) {
        console.error('Error checking payment:', e);
    }

    checking = false;
}

// ── AUTO CHECK STATUS SETIAP 5 DETIK ──
setInterval(checkPayment, 5000);

// ── PANGGIL PERTAMA KALI ──
checkPayment();

// ── COUNTDOWN EXPIRED ──
let time = 900; // 15 menit

setInterval(() => {
    let minutes = Math.floor(time / 60);
    let seconds = time % 60;

    const countdownEl = document.getElementById('countdown');

    if (countdownEl) {
        countdownEl.innerText =
            `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (time > 0) {
            time--;
        }

        // ── PERINGATAN SAAT WAKTU HAMPIR HABIS ──
        if (time <= 60 && time > 0) {
            countdownEl.style.color = '#dc3545';
            countdownEl.style.fontWeight = 'bold';
        }

        // ── SAAT WAKTU HABIS ──
        if (time <= 0) {
            isExpired = true;
            countdownEl.innerText = 'Kedaluwarsa!';
            countdownEl.style.color = '#dc3545';
            countdownEl.style.fontWeight = 'bold';
            
            // ── NONAKTIFKAN TOMBOL ──
            document.querySelectorAll('#btnCheckPayment, #btnCheckPaymentBottom').forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-hourglass-end me-2"></i>Kedaluwarsa';
                btn.className = 'btn btn-secondary w-100 mb-2';
            });
            
            // ── TAMPILKAN ALERT ──
            const alertHtml = `
                <div class="alert alert-danger mt-3">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    QRIS telah kedaluwarsa. Silakan lakukan donasi ulang.
                </div>
            `;
            const qrCard = document.querySelector('.qr-card');
            if (!qrCard.querySelector('.alert-danger')) {
                qrCard.insertAdjacentHTML('beforeend', alertHtml);
            }
        }
    }
}, 1000);
</script>

</body>
</html>