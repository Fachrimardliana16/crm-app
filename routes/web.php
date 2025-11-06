<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pendaftaran;
use App\Http\Controllers\FakturController;
use App\Http\Controllers\PendaftaranReportController;
use App\Http\Controllers\SurveiPrintController;

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

// Route untuk print faktur survei
Route::get('/survei/print-faktur/{id}', [SurveiPrintController::class, 'printFaktur'])
    ->name('survei.print-faktur');

Route::get('/survei/download-pdf/{id}', [SurveiPrintController::class, 'downloadPdf'])
    ->name('survei.download-pdf');

Route::post('/survei/print-multiple', [SurveiPrintController::class, 'printMultiple'])
    ->name('survei.print-multiple');

Route::post('/survei/download-multiple-pdf', [SurveiPrintController::class, 'downloadMultiplePdf'])
    ->name('survei.download-multiple-pdf');


// Route untuk download MOU (Surat Pernyataan) pendaftaran
Route::get('/surat-pernyataan', function () {
    return view('surat-pernyataan.pernyataan-pendaftaran');
})->name('surat-pernyataan');
