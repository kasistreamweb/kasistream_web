<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'streamer_id',

        'nominal',
        'pesan',
        'status',

        'guest_name',
        'guest_phone',

        'fitur_total',
        'admin_fee',
        'grand_total',

        'payment_method',

        // QRIS / OnoPay
        'invoice_id',
        'qris_content',
        'qris_status',

        'qr_code',
        'qr_image',
        'onopay_receiver',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function streamer()
    {
        return $this->belongsTo(User::class, 'streamer_id');
    }
}