<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rab', function (Blueprint $table) {
            $table->enum('mode_cicilan', ['auto', 'custom'])->default('auto')->after('jumlah_cicilan');
            $table->json('custom_angsuran_data')->nullable()->after('mode_cicilan'); // Menyimpan data custom angsuran
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab', function (Blueprint $table) {
            $table->dropColumn(['mode_cicilan', 'custom_angsuran_data']);
        });
    }
};
