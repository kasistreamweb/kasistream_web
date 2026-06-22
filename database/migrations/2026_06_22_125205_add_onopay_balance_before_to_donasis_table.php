<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasis', function (Blueprint $table) {
            $table->bigInteger('onopay_balance_before')
                  ->default(0)
                  ->after('onopay_receiver');
        });
    }

    public function down(): void
    {
        Schema::table('donasis', function (Blueprint $table) {
            $table->dropColumn('onopay_balance_before');
        });
    }
};