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
        // Add geometry columns to cabang table
        Schema::table('cabang', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('alamat_cabang');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
        
        // Add PostGIS geometry column using raw SQL
        DB::statement('ALTER TABLE cabang ADD COLUMN polygon_area GEOMETRY(POLYGON, 4326)');
        DB::statement('CREATE INDEX idx_cabang_coordinates ON cabang (latitude, longitude)');
        DB::statement('CREATE INDEX idx_cabang_polygon ON cabang USING GIST (polygon_area)');

        // Add geometry columns to kecamatan table
        Schema::table('kecamatan', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('nama_kecamatan');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
        
        DB::statement('ALTER TABLE kecamatan ADD COLUMN polygon_area GEOMETRY(POLYGON, 4326)');
        DB::statement('CREATE INDEX idx_kecamatan_coordinates ON kecamatan (latitude, longitude)');
        DB::statement('CREATE INDEX idx_kecamatan_polygon ON kecamatan USING GIST (polygon_area)');

        // Add geometry columns to kelurahan table
        Schema::table('kelurahan', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('nama_kelurahan');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
        
        DB::statement('ALTER TABLE kelurahan ADD COLUMN polygon_area GEOMETRY(POLYGON, 4326)');
        DB::statement('CREATE INDEX idx_kelurahan_coordinates ON kelurahan (latitude, longitude)');
        DB::statement('CREATE INDEX idx_kelurahan_polygon ON kelurahan USING GIST (polygon_area)');

        // Add geometry columns to spam table
        Schema::table('spam', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('alamat_spam');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
        
        DB::statement('ALTER TABLE spam ADD COLUMN polygon_area GEOMETRY(POLYGON, 4326)');
        DB::statement('CREATE INDEX idx_spam_coordinates ON spam (latitude, longitude)');
        DB::statement('CREATE INDEX idx_spam_polygon ON spam USING GIST (polygon_area)');

        // Add geometry columns to rayon table
        Schema::table('rayon', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('deskripsi');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
        
        DB::statement('ALTER TABLE rayon ADD COLUMN polygon_area GEOMETRY(POLYGON, 4326)');
        DB::statement('CREATE INDEX idx_rayon_coordinates ON rayon (latitude, longitude)');
        DB::statement('CREATE INDEX idx_rayon_polygon ON rayon USING GIST (polygon_area)');

        // Add geometry columns to sub_rayon table
        Schema::table('sub_rayon', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('deskripsi');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
        
        DB::statement('ALTER TABLE sub_rayon ADD COLUMN polygon_area GEOMETRY(POLYGON, 4326)');
        DB::statement('CREATE INDEX idx_sub_rayon_coordinates ON sub_rayon (latitude, longitude)');
        DB::statement('CREATE INDEX idx_sub_rayon_polygon ON sub_rayon USING GIST (polygon_area)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove geometry columns from cabang table
        DB::statement('DROP INDEX IF EXISTS idx_cabang_polygon');
        DB::statement('DROP INDEX IF EXISTS idx_cabang_coordinates');
        Schema::table('cabang', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'polygon_area']);
        });

        // Remove geometry columns from kecamatan table
        DB::statement('DROP INDEX IF EXISTS idx_kecamatan_polygon');
        DB::statement('DROP INDEX IF EXISTS idx_kecamatan_coordinates');
        Schema::table('kecamatan', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'polygon_area']);
        });

        // Remove geometry columns from kelurahan table
        DB::statement('DROP INDEX IF EXISTS idx_kelurahan_polygon');
        DB::statement('DROP INDEX IF EXISTS idx_kelurahan_coordinates');
        Schema::table('kelurahan', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'polygon_area']);
        });

        // Remove geometry columns from spam table
        DB::statement('DROP INDEX IF EXISTS idx_spam_polygon');
        DB::statement('DROP INDEX IF EXISTS idx_spam_coordinates');
        Schema::table('spam', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'polygon_area']);
        });

        // Remove geometry columns from rayon table
        DB::statement('DROP INDEX IF EXISTS idx_rayon_polygon');
        DB::statement('DROP INDEX IF EXISTS idx_rayon_coordinates');
        Schema::table('rayon', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'polygon_area']);
        });

        // Remove geometry columns from sub_rayon table
        DB::statement('DROP INDEX IF EXISTS idx_sub_rayon_polygon');
        DB::statement('DROP INDEX IF EXISTS idx_sub_rayon_coordinates');
        Schema::table('sub_rayon', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'polygon_area']);
        });
    }
};
