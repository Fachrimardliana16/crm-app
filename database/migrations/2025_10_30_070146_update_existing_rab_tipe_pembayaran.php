<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update semua record RAB yang memiliki tipe_pembayaran NULL ke 'lunas'
        DB::table('rab')
            ->whereNull('tipe_pembayaran')
            ->update(['tipe_pembayaran' => 'lunas']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini adalah data fix
    }
};
