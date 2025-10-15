<?php
namespace App\Filament\Resources\SubGolonganPelangganResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\SubGolonganPelanggan;

/**
 * @property SubGolonganPelanggan $resource
 */
class SubGolonganPelangganTransformer extends JsonResource
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
