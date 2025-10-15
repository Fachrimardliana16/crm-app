<?php

namespace App\Http\Controllers\Api;

use App\Models\Rayon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controller untuk manajemen Rayon
 * 
 * Mengelola pembagian wilayah pelayanan PDAM berdasarkan rayon
 * untuk memudahkan manajemen pelanggan dan operasional lapangan.
 */
class RayonController extends BaseApiController
{
    /**
     * Menampilkan daftar rayon
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Rayon::query();

        // Filter by status
        if ($request->has('status_aktif')) {
            $query->where('status_aktif', $request->get('status_aktif'));
        }

        // Search by name, code, or area
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_rayon', 'ILIKE', "%{$search}%")
                  ->orWhere('kode_rayon', 'ILIKE', "%{$search}%")
                  ->orWhere('wilayah', 'ILIKE', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'kode_rayon');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Include sub rayon count
        if ($request->boolean('include_sub_rayon_count')) {
            $query->withCount('subRayon');
        }

        // Include sub rayons
        if ($request->boolean('include_sub_rayon')) {
            $query->with('subRayon');
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $rayon = $query->paginate($perPage);

        return $this->paginatedResponse($rayon);
    }

    /**
     * Menampilkan detail rayon
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $rayon = Rayon::with(['subRayon' => function ($query) {
            $query->where('status_aktif', 'aktif')->orderBy('kode_sub_rayon');
        }])->find($id);

        if (!$rayon) {
            return $this->notFoundResponse('Rayon tidak ditemukan');
        }

        return $this->successResponse($rayon);
    }

    /**
     * Membuat rayon baru
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kode_rayon' => 'required|string|size:2|unique:rayon|regex:/^[0-9]{2}$/',
            'nama_rayon' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'wilayah' => 'nullable|string|max:255',
            'koordinat_pusat_lat' => 'nullable|numeric|between:-90,90',
            'koordinat_pusat_lng' => 'nullable|numeric|between:-180,180',
            'radius_coverage' => 'nullable|integer|min:0',
            'kapasitas_maksimal' => 'nullable|integer|min:0',
            'status_aktif' => 'in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
            'dibuat_oleh' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $data = $validator->validated();
        $data['dibuat_pada'] = now();
        
        $rayon = Rayon::create($data);

        return $this->successResponse($rayon, 'Rayon berhasil dibuat', 201);
    }

    /**
     * Mengupdate rayon
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $rayon = Rayon::find($id);

        if (!$rayon) {
            return $this->notFoundResponse('Rayon tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'kode_rayon' => 'required|string|size:2|regex:/^[0-9]{2}$/|unique:rayon,kode_rayon,' . $id . ',id_rayon',
            'nama_rayon' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'wilayah' => 'nullable|string|max:255',
            'koordinat_pusat_lat' => 'nullable|numeric|between:-90,90',
            'koordinat_pusat_lng' => 'nullable|numeric|between:-180,180',
            'radius_coverage' => 'nullable|integer|min:0',
            'kapasitas_maksimal' => 'nullable|integer|min:0',
            'status_aktif' => 'in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
            'diperbarui_oleh' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $data = $validator->validated();
        $data['diperbarui_pada'] = now();
        
        $rayon->update($data);

        return $this->successResponse($rayon, 'Rayon berhasil diupdate');
    }

    /**
     * Menghapus rayon
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $rayon = Rayon::find($id);

        if (!$rayon) {
            return $this->notFoundResponse('Rayon tidak ditemukan');
        }

        // Check if has sub rayon
        if ($rayon->subRayon()->exists()) {
            return $this->errorResponse(
                'Tidak dapat menghapus rayon yang masih memiliki sub rayon', 
                422
            );
        }

        // Check if has pelanggan
        if ($rayon->pelanggan()->exists()) {
            return $this->errorResponse(
                'Tidak dapat menghapus rayon yang masih memiliki pelanggan', 
                422
            );
        }

        $rayon->delete();

        return $this->successResponse(null, 'Rayon berhasil dihapus');
    }

    /**
     * Mendapatkan statistik rayon
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function statistics(string $id): JsonResponse
    {
        $rayon = Rayon::find($id);

        if (!$rayon) {
            return $this->notFoundResponse('Rayon tidak ditemukan');
        }

        $stats = [
            'total_sub_rayon' => $rayon->subRayon()->count(),
            'total_sub_rayon_aktif' => $rayon->subRayon()->where('status_aktif', 'aktif')->count(),
            'total_pelanggan' => $rayon->pelanggan()->count(),
            'total_pelanggan_aktif' => $rayon->pelanggan()->where('status_historis', 'aktif')->count(),
            'kapasitas_terpakai' => $rayon->jumlah_pelanggan,
            'kapasitas_maksimal' => $rayon->kapasitas_maksimal,
            'persentase_kapasitas' => $rayon->kapasitas_maksimal > 0 
                ? round(($rayon->jumlah_pelanggan / $rayon->kapasitas_maksimal) * 100, 2) 
                : 0
        ];

        return $this->successResponse([
            'rayon' => $rayon,
            'statistics' => $stats
        ]);
    }
}