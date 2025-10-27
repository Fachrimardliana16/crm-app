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
            // Make audit fields nullable since they're filled when record is created/updated
            $table->string('dibuat_oleh')->nullable()->change();
            $table->string('diperbarui_oleh')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab', function (Blueprint $table) {
            // Revert audit fields to not nullable
            $table->string('dibuat_oleh')->nullable(false)->change();
            $table->string('diperbarui_oleh')->nullable(false)->change();
        });
    }
};
