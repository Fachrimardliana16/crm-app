<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode', 20)->unique();
            $table->string('nama_kas', 100);
            $table->boolean('status')->default(true); // true = aktif, false = tidak aktif
            $table->timestamps();
        });

        // Optional: Set default UUID jika menggunakan PostgreSQL atau ingin auto-generate
        // Untuk MySQL, kita handle di model
    }

    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};
