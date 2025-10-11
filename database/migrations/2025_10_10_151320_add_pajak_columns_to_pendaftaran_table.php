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
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->decimal('subtotal_biaya', 15, 2)->nullable()->after('total_biaya_pendaftaran');
            $table->uuid('id_pajak')->nullable()->after('subtotal_biaya');
            $table->decimal('nilai_pajak', 15, 2)->nullable()->after('id_pajak');

            // Add foreign key constraint
            $table->foreign('id_pajak')->references('id_pajak')->on('pajak')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['id_pajak']);
            $table->dropColumn(['subtotal_biaya', 'id_pajak', 'nilai_pajak']);
        });
    }
};
