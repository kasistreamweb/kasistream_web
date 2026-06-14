<?php

namespace App\Exports;

use App\Models\Donasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Donasi::with(['user','streamer'])
            ->select(
                'created_at',
                'user_id',
                'streamer_id',
                'nominal',
                'admin_fee',
                'grand_total',
                'status',
                'payment_method'
            )
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->created_at->format('d-m-Y H:i'),
                    'donatur' => $item->user?->name,
                    'streamer' => $item->streamer?->name,
                    'nominal' => $item->nominal,
                    'admin_fee' => $item->admin_fee,
                    'grand_total' => $item->grand_total,
                    'status' => $item->status,
                    'payment_method' => $item->payment_method,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Donatur',
            'Streamer',
            'Nominal',
            'Biaya Admin',
            'Grand Total',
            'Status',
            'Metode Pembayaran'
        ];
    }
}