<?php
namespace App\Filament\Resources\AreaResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Area;

/**
 * @property Area $resource
 */
class AreaTransformer extends JsonResource
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
