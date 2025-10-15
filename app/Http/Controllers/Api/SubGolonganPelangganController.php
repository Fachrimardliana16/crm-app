<?php

namespace App\Http\Controllers\Api;

use App\Models\SubGolonganPelanggan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controller untuk manajemen Sub Golongan Pelanggan
 * 
 * Mengelola sub kategori pelanggan dan tarif air berdasarkan sistem
 * blok per 10 m³ sesuai dengan tarif PDAM Purbalingga.
 */
class SubGolonganPelangganController extends BaseApiController
{
    /**
     * Menampilkan daftar sub golongan pelanggan
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = SubGolonganPelanggan::query();

        // Filter by golongan pelanggan
        if ($request->has('id_golongan_pelanggan')) {
            $query->where('id_golongan_pelanggan', $request->get('id_golongan_pelanggan'));
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Search by name or code
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_sub_golongan', 'ILIKE', "%{$search}%")
                  ->orWhere('kode_sub_golongan', 'ILIKE', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'urutan');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $subGolongan = $query->with('golonganPelanggan')->paginate($perPage);

        return $this->paginatedResponse($subGolongan);
    }

    /**
     * Menampilkan detail sub golongan pelanggan
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $subGolongan = SubGolonganPelanggan::with('golonganPelanggan')->find($id);

        if (!$subGolongan) {
            return $this->notFoundResponse('Sub golongan pelanggan tidak ditemukan');
        }

        return $this->successResponse($subGolongan);
    }

    /**
     * Membuat sub golongan pelanggan baru
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_golongan_pelanggan' => 'required|uuid|exists:golongan_pelanggan,id_golongan_pelanggan',
            'kode_sub_golongan' => 'required|string|max:20|unique:sub_golongan_pelanggan',
            'nama_sub_golongan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'biaya_tetap_subgolongan' => 'required|numeric|min:0',
            'tarif_blok_1' => 'required|numeric|min:0',
            'tarif_blok_2' => 'required|numeric|min:0',
            'tarif_blok_3' => 'required|numeric|min:0',
            'tarif_blok_4' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'urutan' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $subGolongan = SubGolonganPelanggan::create($validator->validated());

        return $this->successResponse($subGolongan, 'Sub golongan pelanggan berhasil dibuat', 201);
    }

    /**
     * Mengupdate sub golongan pelanggan
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $subGolongan = SubGolonganPelanggan::find($id);

        if (!$subGolongan) {
            return $this->notFoundResponse('Sub golongan pelanggan tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'id_golongan_pelanggan' => 'required|uuid|exists:golongan_pelanggan,id_golongan_pelanggan',
            'kode_sub_golongan' => 'required|string|max:20|unique:sub_golongan_pelanggan,kode_sub_golongan,' . $id . ',id_sub_golongan_pelanggan',
            'nama_sub_golongan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'biaya_tetap_subgolongan' => 'required|numeric|min:0',
            'tarif_blok_1' => 'required|numeric|min:0',
            'tarif_blok_2' => 'required|numeric|min:0',
            'tarif_blok_3' => 'required|numeric|min:0',
            'tarif_blok_4' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'urutan' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $subGolongan->update($validator->validated());

        return $this->successResponse($subGolongan, 'Sub golongan pelanggan berhasil diupdate');
    }

    /**
     * Menghapus sub golongan pelanggan
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $subGolongan = SubGolonganPelanggan::find($id);

        if (!$subGolongan) {
            return $this->notFoundResponse('Sub golongan pelanggan tidak ditemukan');
        }

        $subGolongan->delete();

        return $this->successResponse(null, 'Sub golongan pelanggan berhasil dihapus');
    }

    /**
     * Menghitung tarif berdasarkan volume pemakaian
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function calculateTarif(Request $request, string $id): JsonResponse
    {
        $subGolongan = SubGolonganPelanggan::find($id);

        if (!$subGolongan) {
            return $this->notFoundResponse('Sub golongan pelanggan tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'volume' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $volume = $request->get('volume');
        $calculation = $subGolongan->hitungTotalTarif($volume);

        return $this->successResponse([
            'sub_golongan' => $subGolongan,
            'volume_m3' => $volume,
            'calculation' => $calculation
        ], 'Tarif berhasil dihitung');
    }
}