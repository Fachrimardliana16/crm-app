<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pendaftaran;
use App\Http\Controllers\FakturController;
use App\Http\Controllers\PendaftaranReportController;
use App\Http\Controllers\SurveiPrintController;
use App\Http\Controllers\RabPrintController;

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

// Route untuk print faktur RAB
Route::get('/rab/print-faktur/{id}', [RabPrintController::class, 'printFaktur'])
    ->name('rab.print-faktur');

Route::get('/rab/download-pdf/{id}', [RabPrintController::class, 'downloadPdf'])
    ->name('rab.download-pdf');

Route::post('/rab/print-multiple', [RabPrintController::class, 'printMultiple'])
    ->name('rab.print-multiple');

Route::post('/rab/download-multiple-pdf', [RabPrintController::class, 'downloadMultiplePdf'])
    ->name('rab.download-multiple-pdf');

// Route untuk print faktur RAB - Dot Matrix
Route::get('/rab/print-faktur-dotmatrix/{id}', [RabPrintController::class, 'printFakturDotMatrix'])
    ->name('rab.print-faktur-dotmatrix');

Route::get('/rab/download-pdf-dotmatrix/{id}', [RabPrintController::class, 'downloadPdfDotMatrix'])
    ->name('rab.download-pdf-dotmatrix');

Route::post('/rab/print-multiple-dotmatrix', [RabPrintController::class, 'printMultipleDotMatrix'])
    ->name('rab.print-multiple-dotmatrix');

Route::post('/rab/download-multiple-pdf-dotmatrix', [RabPrintController::class, 'downloadMultiplePdfDotMatrix'])
    ->name('rab.download-multiple-pdf-dotmatrix');


// Route untuk download MOU (Surat Pernyataan) pendaftaran
Route::get('/surat-pernyataan', function () {
    return view('surat-pernyataan.pernyataan-pendaftaran');
})->name('surat-pernyataan');
