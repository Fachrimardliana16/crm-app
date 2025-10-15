<?php
namespace App\Filament\Resources\RayonResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Rayon;

/**
 * @property Rayon $resource
 */
class RayonTransformer extends JsonResource
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
