<?php

namespace App\Filament\Resources\CabangResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCabangRequest extends FormRequest
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
			'id_cabang' => 'required',
			'kode_cabang' => 'required',
			'nama_cabang' => 'required',
			'wilayah_pelayanan' => 'required',
			'alamat' => 'required',
			'telepon' => 'required',
			'email' => 'required',
			'kepala_cabang' => 'required',
			'status_aktif' => 'required',
			'keterangan' => 'required|string'
		];
    }
}
