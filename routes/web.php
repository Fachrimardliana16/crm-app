<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pendaftaran;
use App\Http\Controllers\FakturController;
use App\Http\Controllers\PendaftaranReportController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk print faktur pembayaran
Route::get('/faktur/pembayaran/{pendaftaran}', [FakturController::class, 'pembayaran'])
    ->name('faktur.pembayaran');

Route::post('/faktur/multiple-print', [FakturController::class, 'multiplePrint'])
    ->name('faktur.multiple-print');

// Route untuk download PDF report pendaftaran
Route::get('/reports/pendaftaran/pdf', [PendaftaranReportController::class, 'downloadPdf'])
    ->name('reports.pendaftaran.pdf');
