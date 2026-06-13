<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasis', function (Blueprint $table) {

            $table->string('qr_code')
                ->nullable()
                ->after('qris_content');

            $table->longText('qr_image')
                ->nullable()
                ->after('qr_code');

            $table->string('onopay_receiver')
                ->nullable()
                ->after('qr_image');
        });
    }

    public function down(): void
    {
        Schema::table('donasis', function (Blueprint $table) {

            $table->dropColumn([
                'qr_code',
                'qr_image',
                'onopay_receiver'
            ]);
        });
    }
};