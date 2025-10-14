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
        Schema::table('pelanggan', function (Blueprint $table) {
            // Menambahkan kolom rayon dan sub_rayon setelah id_area
            $table->uuid('id_rayon')->nullable()->after('id_area')->comment('Foreign key ke tabel rayon');
            $table->uuid('id_sub_rayon')->nullable()->after('id_rayon')->comment('Foreign key ke tabel sub_rayon');
            
            // Menambahkan foreign key constraints
            $table->foreign('id_rayon')->references('id_rayon')->on('rayon')->onDelete('set null');
            $table->foreign('id_sub_rayon')->references('id_sub_rayon')->on('sub_rayon')->onDelete('set null');
            
            // Menambahkan indexes untuk performance
            $table->index(['id_rayon']);
            $table->index(['id_sub_rayon']);
            $table->index(['id_rayon', 'id_sub_rayon']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['id_rayon']);
            $table->dropForeign(['id_sub_rayon']);
            
            // Drop indexes
            $table->dropIndex(['id_rayon']);
            $table->dropIndex(['id_sub_rayon']);
            $table->dropIndex(['id_rayon', 'id_sub_rayon']);
            
            // Drop columns
            $table->dropColumn(['id_rayon', 'id_sub_rayon']);
        });
    }
};
