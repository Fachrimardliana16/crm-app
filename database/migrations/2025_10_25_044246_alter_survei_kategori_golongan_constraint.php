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
        // Drop the existing enum constraint
        DB::statement("ALTER TABLE survei DROP CONSTRAINT IF EXISTS survei_kategori_golongan_check");
        
        // Change the column type to TEXT to allow golongan names like "Rumah Tangga"
        Schema::table('survei', function (Blueprint $table) {
            $table->text('kategori_golongan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the original enum constraint
        Schema::table('survei', function (Blueprint $table) {
            $table->enum('kategori_golongan', ['A', 'B', 'C', 'D'])->nullable()->change();
        });
    }
};
