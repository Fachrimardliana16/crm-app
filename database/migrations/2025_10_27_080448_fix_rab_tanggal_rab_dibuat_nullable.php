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
            // Make tanggal_rab_dibuat nullable since it's assigned when RAB is completed/approved
            $table->date('tanggal_rab_dibuat')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab', function (Blueprint $table) {
            // Revert tanggal_rab_dibuat to not nullable
            $table->date('tanggal_rab_dibuat')->nullable(false)->change();
        });
    }
};
