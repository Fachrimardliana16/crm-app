<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RabPrintController extends Controller
{
    /**
     * Print faktur RAB (HTML view for direct print)
     */
    public function printFaktur($id)
    {
        try {
            $rab = Rab::with([
                'pendaftaran.cabang',
                'pelanggan'
            ])->findOrFail($id);

            return view('print.faktur-rab', [
                'rab' => $rab,
                'title' => 'Faktur RAB',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'RAB tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Download faktur RAB sebagai PDF
     */
    public function downloadPdf($id)
    {
        try {
            $rab = Rab::with([
                'pendaftaran.cabang',
                'pelanggan'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('print.faktur-rab', [
                'rab' => $rab,
                'title' => 'Faktur RAB',
            ]);

            $pdf->setPaper('a5', 'landscape');
            
            $filename = 'Faktur_RAB_' . $rab->pendaftaran->nomor_registrasi . '_' . now()->format('Ymd_His') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print multiple faktur RAB
     */
    public function printMultiple(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return back()->with('error', 'Tidak ada RAB yang dipilih');
            }

            $rabs = Rab::with([
                'pendaftaran.cabang',
                'pelanggan'
            ])->whereIn('id_rab', $ids)->get();

            return view('print.faktur-rab-multiple', [
                'rabs' => $rabs,
                'title' => 'Faktur RAB - Multiple',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Download multiple faktur RAB sebagai PDF
     */
    public function downloadMultiplePdf(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return back()->with('error', 'Tidak ada RAB yang dipilih');
            }

            $rabs = Rab::with([
                'pendaftaran.cabang',
                'pelanggan'
            ])->whereIn('id_rab', $ids)->get();

            $pdf = Pdf::loadView('print.faktur-rab-multiple', [
                'rabs' => $rabs,
                'title' => 'Faktur RAB - Multiple',
            ]);

            $pdf->setPaper('a5', 'landscape');
            
            $filename = 'Faktur_RAB_Multiple_' . now()->format('Ymd_His') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print faktur RAB untuk Dot Matrix (HTML view for direct print)
     */
    public function printFakturDotMatrix($id)
    {
        try {
            $rab = Rab::with([
                'pendaftaran.cabang',
                'pelanggan'
            ])->findOrFail($id);

            return view('print.faktur-rab-dotmatrix', [
                'rab' => $rab,
                'title' => 'Faktur RAB - Dot Matrix',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'RAB tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Download faktur RAB untuk Dot Matrix sebagai PDF
     */
    public function downloadPdfDotMatrix($id)
    {
        try {
            $rab = Rab::with([
                'pendaftaran.cabang',
                'pelanggan'
            ])->findOrFail($id);

            $pdf = Pdf::loadView('print.faktur-rab-dotmatrix', [
                'rab' => $rab,
                'title' => 'Faktur RAB - Dot Matrix',
            ]);

            $pdf->setPaper('a5', 'landscape');
            
            $filename = 'Faktur_RAB_DotMatrix_' . $rab->pendaftaran->nomor_registrasi . '_' . now()->format('Ymd_His') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print multiple faktur RAB untuk Dot Matrix
     */
    public function printMultipleDotMatrix(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return back()->with('error', 'Tidak ada RAB yang dipilih');
            }

            $rabs = Rab::with([
                'pendaftaran.cabang',
                'pelanggan'
            ])->whereIn('id_rab', $ids)->get();

            return view('print.faktur-rab-dotmatrix-multiple', [
                'rabs' => $rabs,
                'title' => 'Faktur RAB - Multiple (Dot Matrix)',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Download multiple faktur RAB untuk Dot Matrix sebagai PDF
     */
    public function downloadMultiplePdfDotMatrix(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return back()->with('error', 'Tidak ada RAB yang dipilih');
            }

            $rabs = Rab::with([
                'pendaftaran.cabang',
                'pelanggan'
            ])->whereIn('id_rab', $ids)->get();

            $pdf = Pdf::loadView('print.faktur-rab-dotmatrix-multiple', [
                'rabs' => $rabs,
                'title' => 'Faktur RAB - Multiple (Dot Matrix)',
            ]);

            $pdf->setPaper('a5', 'landscape');
            
            $filename = 'Faktur_RAB_DotMatrix_Multiple_' . now()->format('Ymd_His') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }
}
