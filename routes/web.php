<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pendaftaran;
use App\Http\Controllers\FakturController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk print faktur pembayaran
Route::get('/faktur/pembayaran/{pendaftaran}', [FakturController::class, 'pembayaran'])
    ->name('faktur.pembayaran');

Route::post('/faktur/multiple-print', [FakturController::class, 'multiplePrint'])
    ->name('faktur.multiple-print');
