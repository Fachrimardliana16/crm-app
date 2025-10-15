<?php

namespace App\Filament\Resources\SubGolonganPelangganResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubGolonganPelangganRequest extends FormRequest
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
			'id_sub_golongan_pelanggan' => 'required',
			'id_golongan_pelanggan' => 'required',
			'kode_sub_golongan' => 'required',
			'nama_sub_golongan' => 'required',
			'deskripsi' => 'required|string',
			'biaya_tetap_subgolongan' => 'required',
			'tarif_blok_1' => 'required',
			'tarif_blok_2' => 'required',
			'tarif_blok_3' => 'required',
			'tarif_blok_4' => 'required',
			'is_active' => 'required',
			'urutan' => 'required'
		];
    }
}
