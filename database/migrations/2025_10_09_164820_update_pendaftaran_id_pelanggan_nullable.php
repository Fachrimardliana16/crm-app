<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['id_pelanggan']);

            // Make id_pelanggan nullable
            $table->uuid('id_pelanggan')->nullable()->change();

            // Re-add foreign key constraint with nullable
            $table->foreign('id_pelanggan')
                  ->references('id_pelanggan')
                  ->on('pelanggan')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['id_pelanggan']);

            // Make id_pelanggan not nullable
            $table->uuid('id_pelanggan')->nullable(false)->change();

            // Re-add foreign key constraint without nullable
            $table->foreign('id_pelanggan')
                  ->references('id_pelanggan')
                  ->on('pelanggan')
                  ->onDelete('cascade');
        });
    }
};
