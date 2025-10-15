<?php

namespace App\Filament\Resources\GolonganPelangganResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGolonganPelangganRequest extends FormRequest
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
			'id_golongan_pelanggan' => 'required',
			'kode_golongan' => 'required',
			'nama_golongan' => 'required',
			'deskripsi' => 'required|string',
			'is_active' => 'required',
			'urutan' => 'required'
		];
    }
}
