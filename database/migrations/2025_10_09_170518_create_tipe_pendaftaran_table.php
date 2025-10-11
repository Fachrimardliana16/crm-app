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
        Schema::create('tipe_pendaftaran', function (Blueprint $table) {
            $table->uuid('id_tipe_pendaftaran')->primary();
            $table->string('nama_tipe_pendaftaran');
            $table->text('deskripsi')->nullable();
            $table->decimal('biaya', 15, 2)->default(0);
            $table->decimal('data_pengembalian', 15, 2)->default(0); // nilai rupiah pengembalian
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->index(['nama_tipe_pendaftaran', 'status_aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipe_pendaftaran');
    }
};
