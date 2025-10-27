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
            // Make timestamp fields nullable
            $table->timestamp('dibuat_pada')->nullable()->change();
            $table->timestamp('diperbarui_pada')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab', function (Blueprint $table) {
            // Revert timestamp fields to not nullable
            $table->timestamp('dibuat_pada')->nullable(false)->change();
            $table->timestamp('diperbarui_pada')->nullable(false)->change();
        });
    }
};
