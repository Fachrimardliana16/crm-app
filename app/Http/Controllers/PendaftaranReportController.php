<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PendaftaranReportController extends Controller
{
    public function downloadPdf(Request $request)
    {
        try {
            // Validate required parameters
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            // Get filters from request
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            
            // Build query
            $query = Pendaftaran::with([
                'cabang',
                'kelurahan.kecamatan',
                'tipeLayanan',
                'jenisDaftar',
                'tipePendaftaran',
                'pelanggan'
            ])
            ->whereBetween('tanggal_daftar', [$startDate, $endDate]);

            // Apply filters
            if ($request->filled('cabang_unit') && is_array($request->cabang_unit)) {
                $query->whereIn('id_cabang', $request->cabang_unit);
            }

            if ($request->filled('kecamatan') && is_array($request->kecamatan)) {
                $query->whereHas('kelurahan', function ($q) use ($request) {
                    $q->whereIn('id_kecamatan', $request->kecamatan);
                });
            }

            if ($request->filled('kelurahan') && is_array($request->kelurahan)) {
                $query->whereIn('id_kelurahan', $request->kelurahan);
            }

            if ($request->filled('tipe_pelayanan') && is_array($request->tipe_pelayanan)) {
                $query->whereIn('id_tipe_layanan', $request->tipe_pelayanan);
            }

            if ($request->filled('jenis_daftar') && is_array($request->jenis_daftar)) {
                $query->whereIn('id_jenis_daftar', $request->jenis_daftar);
            }

            if ($request->filled('tipe_pendaftaran') && is_array($request->tipe_pendaftaran)) {
                $query->whereIn('id_tipe_pendaftaran', $request->tipe_pendaftaran);
            }

            // Get results
            $pendaftarans = $query->orderBy('tanggal_daftar', 'desc')->get();

            // Prepare filters array for view
            $filters = [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'cabang_unit' => $request->cabang_unit ?? [],
                'kecamatan' => $request->kecamatan ?? [],
                'kelurahan' => $request->kelurahan ?? [],
                'tipe_pelayanan' => $request->tipe_pelayanan ?? [],
                'jenis_daftar' => $request->jenis_daftar ?? [],
                'tipe_pendaftaran' => $request->tipe_pendaftaran ?? [],
            ];

            // Generate PDF
            $pdf = Pdf::loadView('reports.pendaftaran', [
                'pendaftarans' => $pendaftarans,
                'filters' => $filters,
                'generated_at' => now(),
                'total_records' => $pendaftarans->count()
            ]);

            // Set PDF options
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
            ]);

            // Generate filename
            $filename = 'laporan-pendaftaran-' . 
                       $startDate->format('d-m-Y') . '-sampai-' . 
                       $endDate->format('d-m-Y') . '.pdf';

            // Return PDF download
            return $pdf->download($filename);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Parameter tidak valid',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal membuat laporan: ' . $e->getMessage()
            ], 500);
        }
    }
}