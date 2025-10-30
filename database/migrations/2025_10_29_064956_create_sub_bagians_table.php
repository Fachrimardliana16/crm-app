<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_bagian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bagian_id')->constrained('bagian')->onDelete('cascade');
            $table->string('nama_sub_bagian', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_bagian');
    }
};
