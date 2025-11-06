<?php

namespace App\Http\Controllers;

use App\Models\Survei;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SurveiPrintController extends Controller
{
    /**
     * Print faktur survei (HTML view for direct print)
     */
    public function printFaktur($id)
    {
        try {
            $survei = Survei::with([
                'pendaftaran.cabang',
                'pelanggan',
                'spam',
                'rekomendasiSubGolongan.golonganPelanggan',
                'masterLuasTanah',
                'masterLuasBangunan',
                'masterLokasiBangunan',
                'masterDindingBangunan',
                'masterLantaiBangunan',
                'masterAtapBangunan',
                'masterPagarBangunan',
                'masterKondisiJalan',
                'masterDayaListrik',
                'masterFungsiRumah',
                'masterKepemilikanKendaraan'
            ])->findOrFail($id);

            return view('print.faktur-survei', [
                'survei' => $survei,
                'title' => 'Faktur Hasil Survei',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Survei tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Download faktur survei sebagai PDF
     */
    public function downloadPdf($id)
    {
        try {
            $survei = Survei::with([
                'pendaftaran.cabang',
                'pelanggan',
                'spam',
                'rekomendasiSubGolongan.golonganPelanggan',
                'masterLuasTanah',
                'masterLuasBangunan',
                'masterLokasiBangunan',
                'masterDindingBangunan',
                'masterLantaiBangunan',
                'masterAtapBangunan',
                'masterPagarBangunan',
                'masterKondisiJalan',
                'masterDayaListrik',
                'masterFungsiRumah',
                'masterKepemilikanKendaraan'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('print.faktur-survei', [
                'survei' => $survei,
                'title' => 'Faktur Hasil Survei',
            ]);

            $pdf->setPaper('a4', 'portrait');
            
            $filename = 'Faktur_Survei_' . $survei->pendaftaran->nomor_registrasi . '_' . now()->format('Ymd_His') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print multiple faktur survei
     */
    public function printMultiple(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return back()->with('error', 'Tidak ada survei yang dipilih');
            }

            $surveis = Survei::with([
                'pendaftaran.cabang',
                'pelanggan',
                'spam',
                'rekomendasiSubGolongan.golonganPelanggan',
                'masterLuasTanah',
                'masterLuasBangunan',
                'masterLokasiBangunan',
                'masterDindingBangunan',
                'masterLantaiBangunan',
                'masterAtapBangunan',
                'masterPagarBangunan',
                'masterKondisiJalan',
                'masterDayaListrik',
                'masterFungsiRumah',
                'masterKepemilikanKendaraan'
            ])->whereIn('id_survei', $ids)->get();

            return view('print.faktur-survei-multiple', [
                'surveis' => $surveis,
                'title' => 'Faktur Hasil Survei - Multiple',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Download multiple faktur survei sebagai PDF
     */
    public function downloadMultiplePdf(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return back()->with('error', 'Tidak ada survei yang dipilih');
            }

            $surveis = Survei::with([
                'pendaftaran.cabang',
                'pelanggan',
                'spam',
                'rekomendasiSubGolongan.golonganPelanggan',
                'masterLuasTanah',
                'masterLuasBangunan',
                'masterLokasiBangunan',
                'masterDindingBangunan',
                'masterLantaiBangunan',
                'masterAtapBangunan',
                'masterPagarBangunan',
                'masterKondisiJalan',
                'masterDayaListrik',
                'masterFungsiRumah',
                'masterKepemilikanKendaraan'
            ])->whereIn('id_survei', $ids)->get();

            $pdf = Pdf::loadView('print.faktur-survei-multiple', [
                'surveis' => $surveis,
                'title' => 'Faktur Hasil Survei - Multiple',
            ]);

            $pdf->setPaper('a4', 'portrait');
            
            $filename = 'Faktur_Survei_Multiple_' . now()->format('Ymd_His') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }
}
