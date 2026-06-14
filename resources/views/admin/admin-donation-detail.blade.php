<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detail Donasi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header">

            <h3>
                Detail Donasi #{{ $donasi->id }}
            </h3>

        </div>

        <div class="card-body">

            <table class="table">

                <tr>
                    <th>Donatur</th>
                    <td>
                        {{ optional($donasi->user)->name ?? $donasi->guest_name }}
                    </td>
                </tr>

                <tr>
                    <th>Tipe Donatur</th>
                    <td>
                        {{ $donasi->user ? 'Member' : 'Guest' }}
                    </td>
                </tr>

                <tr>
                    <th>No HP Guest</th>
                    <td>
                        {{ $donasi->guest_phone ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Streamer</th>
                    <td>
                        {{ $donasi->streamer->name }}
                    </td>
                </tr>

                <tr>
                    <th>Nominal</th>
                    <td>
                        Rp {{ number_format($donasi->nominal) }}
                    </td>
                </tr>

                <tr>
                    <th>Admin Fee</th>
                    <td>
                        Rp {{ number_format($donasi->admin_fee) }}
                    </td>
                </tr>

                <tr>
                    <th>Grand Total</th>
                    <td>
                        Rp {{ number_format($donasi->grand_total) }}
                    </td>
                </tr>

                <tr>
                    <th>Metode</th>
                    <td>
                        {{ $donasi->payment_method }}
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        {{ strtoupper($donasi->status) }}
                    </td>
                </tr>

                <tr>
                    <th>Invoice ID</th>
                    <td>
                        {{ $donasi->invoice_id ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>QRIS Status</th>
                    <td>
                        {{ $donasi->qris_status ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>OnoPay Receiver</th>
                    <td>
                        {{ $donasi->onopay_receiver ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Pesan</th>
                    <td>
                        {{ $donasi->pesan ?: '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Tanggal</th>
                    <td>
                        {{ $donasi->created_at }}
                    </td>
                </tr>

            </table>

            <a
                href="/admin-donations"
                class="btn btn-secondary"
            >
                Kembali
            </a>

        </div>

    </div>

</div>

</body>
</html>