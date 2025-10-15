<?php

namespace App\Filament\Resources\RayonResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRayonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_rayon' => 'required',
			'kode_rayon' => 'required',
			'nama_rayon' => 'required',
			'deskripsi' => 'required|string',
			'wilayah' => 'required',
			'koordinat_pusat_lat' => 'required',
			'koordinat_pusat_lng' => 'required',
			'radius_coverage' => 'required',
			'jumlah_pelanggan' => 'required',
			'kapasitas_maksimal' => 'required',
			'status_aktif' => 'required',
			'keterangan' => 'required|string',
			'dibuat_oleh' => 'required',
			'dibuat_pada' => 'required',
			'diperbarui_oleh' => 'required',
			'diperbarui_pada' => 'required'
		];
    }
}
