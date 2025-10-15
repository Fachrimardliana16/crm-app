<?php

namespace App\Filament\Resources\DanameterResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDanameterRequest extends FormRequest
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
			'id_danameter' => 'required',
			'kode_danameter' => 'required',
			'diameter_pipa' => 'required',
			'tarif_danameter' => 'required',
			'deskripsi' => 'required|string',
			'is_active' => 'required',
			'urutan' => 'required'
		];
    }
}
