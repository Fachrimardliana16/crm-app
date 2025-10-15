<?php

namespace App\Filament\Resources\AreaResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAreaRequest extends FormRequest
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
			'id_area' => 'required',
			'kode_area' => 'required',
			'nama_area' => 'required',
			'deskripsi' => 'required|string',
			'koordinat_batas' => 'required|string',
			'status' => 'required',
			'dibuat_oleh' => 'required',
			'dibuat_pada' => 'required'
		];
    }
}
