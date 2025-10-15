<?php

namespace App\Http\Controllers\Api;

use App\Models\Danameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controller untuk manajemen Danameter
 * 
 * Mengelola data diameter pipa dan tarif berdasarkan ukuran pipa
 * yang digunakan untuk instalasi pelanggan PDAM.
 */
class DanameterController extends BaseApiController
{
    /**
     * Menampilkan daftar danameter
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Danameter::query();

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Search by code or diameter
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_danameter', 'ILIKE', "%{$search}%")
                  ->orWhere('diameter_pipa', 'ILIKE', "%{$search}%");
            });
        }

        // Filter by tariff range
        if ($request->has('tarif_min')) {
            $query->where('tarif_danameter', '>=', $request->get('tarif_min'));
        }
        if ($request->has('tarif_max')) {
            $query->where('tarif_danameter', '<=', $request->get('tarif_max'));
        }

        // Sort
        $sortBy = $request->get('sort_by', 'urutan');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $danameter = $query->paginate($perPage);

        return $this->paginatedResponse($danameter);
    }

    /**
     * Menampilkan detail danameter
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $danameter = Danameter::find($id);

        if (!$danameter) {
            return $this->notFoundResponse('Danameter tidak ditemukan');
        }

        return $this->successResponse($danameter);
    }

    /**
     * Membuat danameter baru
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kode_danameter' => 'required|string|max:10|unique:danameter|regex:/^[A-Z0-9]+$/',
            'diameter_pipa' => 'required|string|max:20',
            'tarif_danameter' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
            'urutan' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $danameter = Danameter::create($validator->validated());

        return $this->successResponse($danameter, 'Danameter berhasil dibuat', 201);
    }

    /**
     * Mengupdate danameter
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $danameter = Danameter::find($id);

        if (!$danameter) {
            return $this->notFoundResponse('Danameter tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'kode_danameter' => 'required|string|max:10|regex:/^[A-Z0-9]+$/|unique:danameter,kode_danameter,' . $id . ',id_danameter',
            'diameter_pipa' => 'required|string|max:20',
            'tarif_danameter' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
            'urutan' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $danameter->update($validator->validated());

        return $this->successResponse($danameter, 'Danameter berhasil diupdate');
    }

    /**
     * Menghapus danameter
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $danameter = Danameter::find($id);

        if (!$danameter) {
            return $this->notFoundResponse('Danameter tidak ditemukan');
        }

        $danameter->delete();

        return $this->successResponse(null, 'Danameter berhasil dihapus');
    }

    /**
     * Mendapatkan danameter berdasarkan kode
     * 
     * @param string $kode
     * @return JsonResponse
     */
    public function getByCode(string $kode): JsonResponse
    {
        $danameter = Danameter::where('kode_danameter', $kode)
                              ->where('is_active', true)
                              ->first();

        if (!$danameter) {
            return $this->notFoundResponse('Danameter dengan kode tersebut tidak ditemukan');
        }

        return $this->successResponse($danameter);
    }
}