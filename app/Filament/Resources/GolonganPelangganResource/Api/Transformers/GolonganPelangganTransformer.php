<?php
namespace App\Filament\Resources\GolonganPelangganResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\GolonganPelanggan;

/**
 * @property GolonganPelanggan $resource
 */
class GolonganPelangganTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
