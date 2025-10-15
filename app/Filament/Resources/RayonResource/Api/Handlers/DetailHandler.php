<?php

namespace App\Filament\Resources\RayonResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\RayonResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Filament\Resources\RayonResource\Api\Transformers\RayonTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = RayonResource::class;


    /**
     * Show Rayon
     *
     * @param Request $request
     * @return RayonTransformer|\Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();
        
        // Get model instance to get correct primary key name
        $model = $query->getModel();
        $primaryKey = $model->getKeyName(); // This will return 'id_rayon'

        $query = QueryBuilder::for(
            $query->where($primaryKey, $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new RayonTransformer($query);
    }
}
