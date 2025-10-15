<?php

namespace App\Http\Controllers\Api;

use App\Models\GolonganPelanggan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controller untuk manajemen Golongan Pelanggan
 * 
 * Mengelola kategori pelanggan seperti Sosial, Komersial, Industri, dll.
 * Setiap golongan memiliki sub-golongan dengan tarif yang berbeda.
 */
class GolonganPelangganController extends BaseApiController
{
    /**
     * Menampilkan daftar golongan pelanggan
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = GolonganPelanggan::query();

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Search by name or code
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_golongan', 'ILIKE', "%{$search}%")
                  ->orWhere('kode_golongan', 'ILIKE', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'urutan');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $golongan = $query->with('subGolongan')->paginate($perPage);

        return $this->paginatedResponse($golongan);
    }

    /**
     * Menampilkan detail golongan pelanggan
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $golongan = GolonganPelanggan::with('subGolongan')->find($id);

        if (!$golongan) {
            return $this->notFoundResponse('Golongan pelanggan tidak ditemukan');
        }

        return $this->successResponse($golongan);
    }

    /**
     * Membuat golongan pelanggan baru
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kode_golongan' => 'required|string|max:10|unique:golongan_pelanggan',
            'nama_golongan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
            'urutan' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $golongan = GolonganPelanggan::create($validator->validated());

        return $this->successResponse($golongan, 'Golongan pelanggan berhasil dibuat', 201);
    }

    /**
     * Mengupdate golongan pelanggan
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $golongan = GolonganPelanggan::find($id);

        if (!$golongan) {
            return $this->notFoundResponse('Golongan pelanggan tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'kode_golongan' => 'required|string|max:10|unique:golongan_pelanggan,kode_golongan,' . $id . ',id_golongan_pelanggan',
            'nama_golongan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
            'urutan' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $golongan->update($validator->validated());

        return $this->successResponse($golongan, 'Golongan pelanggan berhasil diupdate');
    }

    /**
     * Menghapus golongan pelanggan
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $golongan = GolonganPelanggan::find($id);

        if (!$golongan) {
            return $this->notFoundResponse('Golongan pelanggan tidak ditemukan');
        }

        // Check if has sub golongan
        if ($golongan->subGolongan()->exists()) {
            return $this->errorResponse(
                'Tidak dapat menghapus golongan yang masih memiliki sub golongan', 
                422
            );
        }

        $golongan->delete();

        return $this->successResponse(null, 'Golongan pelanggan berhasil dihapus');
    }
}