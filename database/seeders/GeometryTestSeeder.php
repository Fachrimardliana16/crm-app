<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cabang;
use App\Models\Rayon;
use App\Models\SubRayon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GeometryTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🗺️ Creating sample geometry data...');

        // Disable activity logging for seeding
        activity()->disableLogging();

        // Create sample Cabang with polygon area
        $cabang = Cabang::create([
            'id_cabang' => Str::uuid(),
            'kode_cabang' => 'TEST',
            'nama_cabang' => 'Cabang Test Geometry',
            'alamat' => 'Jl. Test Geometry No. 123, Surabaya',
            'latitude' => -7.2575,
            'longitude' => 112.7521,
            'status_aktif' => true,
        ]);

        // Set polygon for cabang using raw SQL
        DB::statement("
            UPDATE cabang 
            SET polygon_area = ST_GeomFromText('POLYGON((
                112.7500 -7.2500,
                112.7600 -7.2500,
                112.7600 -7.2600,
                112.7500 -7.2600,
                112.7500 -7.2500
            ))', 4326)
            WHERE id_cabang = ?
        ", [$cabang->id_cabang]);

        $this->command->info('✅ Created Cabang: ' . $cabang->nama_cabang);

        // Create sample Rayon with polygon
        $rayon = Rayon::create([
            'id_rayon' => Str::uuid(),
            'kode_rayon' => 'T1',
            'nama_rayon' => 'Rayon Test Geometry',
            'deskripsi' => 'Area test untuk geometry',
            'latitude' => -7.2400,
            'longitude' => 112.7500,
            'koordinat_pusat_lat' => -7.2400,
            'koordinat_pusat_lng' => 112.7500,
            'radius_coverage' => 5000,
            'kapasitas_maksimal' => 1000,
            'status_aktif' => 'aktif',
            'dibuat_oleh' => 'system',
            'dibuat_pada' => now(),
        ]);

        // Set polygon for rayon
        DB::statement("
            UPDATE rayon 
            SET polygon_area = ST_GeomFromText('POLYGON((
                112.7300 -7.2200,
                112.7700 -7.2200,
                112.7700 -7.2600,
                112.7300 -7.2600,
                112.7300 -7.2200
            ))', 4326)
            WHERE id_rayon = ?
        ", [$rayon->id_rayon]);

        $this->command->info('✅ Created Rayon: ' . $rayon->nama_rayon);

        // Create sample SubRayon with polygon
        $subRayon = SubRayon::create([
            'id_sub_rayon' => Str::uuid(),
            'id_rayon' => $rayon->id_rayon,
            'kode_sub_rayon' => 'T1',
            'nama_sub_rayon' => 'Sub Rayon Test',
            'deskripsi' => 'Area test geometry',
            'latitude' => -7.2450,
            'longitude' => 112.7450,
            'koordinat_pusat_lat' => -7.2450,
            'koordinat_pusat_lng' => 112.7450,
            'radius_coverage' => 2000,
            'kapasitas_maksimal' => 300,
            'nomor_pelanggan_terakhir' => 0,
            'status_aktif' => 'aktif',
            'dibuat_oleh' => 'system',
            'dibuat_pada' => now(),
        ]);

        // Set polygon for sub_rayon
        DB::statement("
            UPDATE sub_rayon 
            SET polygon_area = ST_GeomFromText('POLYGON((
                112.7400 -7.2400,
                112.7500 -7.2400,
                112.7500 -7.2500,
                112.7400 -7.2500,
                112.7400 -7.2400
            ))', 4326)
            WHERE id_sub_rayon = ?
        ", [$subRayon->id_sub_rayon]);

        $this->command->info('✅ Created SubRayon: ' . $subRayon->nama_sub_rayon);

        $this->command->info('🎉 Geometry test data created successfully!');
        $this->command->line('');
        $this->command->info('You can now test geometry functions:');
        $this->command->line('- Point in polygon checks');
        $this->command->line('- Distance calculations');
        $this->command->line('- Area intersections');
        $this->command->line('- Spatial queries');

        // Re-enable activity logging
        activity()->enableLogging();
    }
}