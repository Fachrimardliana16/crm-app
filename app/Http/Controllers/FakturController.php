<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class FakturController extends Controller
{
    public function pembayaran(Pendaftaran $pendaftaran)
    {
        // Load relationships yang dibutuhkan
        $pendaftaran->load([
            'cabang',
            'kelurahan.kecamatan',
            'tipeLayanan',
            'jenisDaftar',
            'tipePendaftaran',
            'pajak'
        ]);

        return view('faktur.pembayaran', compact('pendaftaran'));
    }

    public function multiplePrint(Request $request)
    {
        $ids = $request->input('ids', []);
        $pendaftarans = Pendaftaran::whereIn('id_pendaftaran', $ids)
            ->with([
                'cabang',
                'kelurahan.kecamatan',
                'tipeLayanan',
                'jenisDaftar',
                'tipePendaftaran',
                'pajak'
            ])
            ->get();

        return view('faktur.multiple', compact('pendaftarans'));
    }
}
