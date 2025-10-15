<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Version 1
Route::prefix('v1')->group(function () {
    // Health check endpoint
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now(),
            'version' => config('scramble.info.version'),
            'service' => 'PDAM Purbalingga CRM API'
        ]);
    });

    // Master Data Routes
    Route::prefix('master')->group(function () {
        // Golongan & Sub Golongan Pelanggan
        Route::apiResource('golongan-pelanggan', App\Http\Controllers\Api\GolonganPelangganController::class);
        Route::apiResource('sub-golongan-pelanggan', App\Http\Controllers\Api\SubGolonganPelangganController::class);
        
        // Geographic Data
        Route::apiResource('rayon', App\Http\Controllers\Api\RayonController::class);
        // Route::apiResource('sub-rayon', App\Http\Controllers\Api\SubRayonController::class);
        // Route::apiResource('area', App\Http\Controllers\Api\AreaController::class);
        
        // Danameter
        Route::apiResource('danameter', App\Http\Controllers\Api\DanameterController::class);
        
        // Administrative Data (commented for now)
        // Route::apiResource('cabang', App\Http\Controllers\Api\CabangController::class);
        // Route::apiResource('kecamatan', App\Http\Controllers\Api\KecamatanController::class);
        // Route::apiResource('kelurahan', App\Http\Controllers\Api\KelurahanController::class);
        // Route::apiResource('pekerjaan', App\Http\Controllers\Api\PekerjaanController::class);
    });

    // Core Business Routes (commented for now)
    // Route::prefix('pelanggan')->group(function () {
    //     Route::apiResource('/', App\Http\Controllers\Api\PelangganController::class);
    //     Route::get('/{pelanggan}/tarif', [App\Http\Controllers\Api\PelangganController::class, 'calculateTarif']);
    //     Route::get('/{pelanggan}/history', [App\Http\Controllers\Api\PelangganController::class, 'getHistory']);
    // });

    // Tariff & Billing Routes
    Route::prefix('tarif')->group(function () {
        Route::post('/calculate', [App\Http\Controllers\Api\TarifController::class, 'calculate']);
        Route::post('/simulate', [App\Http\Controllers\Api\TarifController::class, 'simulate']);
        Route::get('/structure', [App\Http\Controllers\Api\TarifController::class, 'getStructure']);
    });

    // Operational Routes (commented for now)
    // Route::prefix('operational')->group(function () {
    //     Route::apiResource('instalasi', App\Http\Controllers\Api\InstalasiController::class);
    //     Route::apiResource('bacaan-meter', App\Http\Controllers\Api\BacaanMeterController::class);
    //     Route::apiResource('pembayaran', App\Http\Controllers\Api\PembayaranController::class);
    // });

    // Reporting Routes (commented for now)
    // Route::prefix('reports')->group(function () {
    //     Route::get('/summary', [App\Http\Controllers\Api\ReportController::class, 'summary']);
    //     Route::get('/pelanggan-by-rayon', [App\Http\Controllers\Api\ReportController::class, 'pelangganByRayon']);
    //     Route::get('/revenue', [App\Http\Controllers\Api\ReportController::class, 'revenue']);
    // });
});