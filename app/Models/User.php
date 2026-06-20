<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'onopay_phone',
        'role',
        'foto',
        'is_streamer',
        'bio',
        'game',
        'jadwal_live',
        'instagram',
        'youtube',
        'tiktok',
        'discord',
        'followers',
        'total_donasi',
        'balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'foto_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_streamer' => 'boolean',
        ];
    }

   public function getFotoUrlAttribute()
{
    if (!$this->foto) {
        return 'https://via.placeholder.com/100';
    }

    return asset('uploads/profile/' . $this->foto);
}

public function donationsReceived()
{
    return $this->hasMany(
        \App\Models\Donasi::class,
        'streamer_id'
    );
}

public function donationsSent()
{
    return $this->hasMany(
        \App\Models\Donasi::class,
        'user_id'
    );
}
}