<?php
namespace App\Filament\Resources\CabangResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Cabang;

/**
 * @property Cabang $resource
 */
class CabangTransformer extends JsonResource
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
