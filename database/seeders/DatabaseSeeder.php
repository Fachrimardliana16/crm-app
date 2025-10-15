<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class, // Added
            CabangSeeder::class,
            PekerjaanSeeder::class,
            TipeLayananSeeder::class,
            JenisDaftarSeeder::class,
            TipePendaftaranSeeder::class,
            PajakSeeder::class,
            KecamatanSeeder::class,
            KelurahanSeeder::class,
            MasterParameterSurveiSeeder::class, // Added
            GolonganPelangganSeeder::class, // Added
            StatusPelangganSeeder::class, // Added - Status Pelanggan data
            RayonSeeder::class, // Added - Rayon data master
            SubRayonSeeder::class,
            DanameterSeeder::class, // Added - Danameter data master
        ]);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
