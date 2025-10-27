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
            // Make id_pelanggan nullable - will be filled after installation complete
            $table->uuid('id_pelanggan')->nullable()->change();
            
            // Remove sub rayon and no_langganan from RAB - will be assigned later
            $table->dropForeign(['id_sub_rayon']);
            $table->dropColumn(['id_sub_rayon', 'no_langganan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rab', function (Blueprint $table) {
            // Restore id_pelanggan as not nullable
            $table->uuid('id_pelanggan')->nullable(false)->change();
            
            // Add back sub rayon and no_langganan
            $table->uuid('id_sub_rayon')->nullable()->after('jenis_biaya_sambungan');
            $table->string('no_langganan', 50)->nullable()->after('id_sub_rayon');
            $table->foreign('id_sub_rayon')->references('id_sub_rayon')->on('sub_rayon')->onDelete('set null');
        });
    }
};
