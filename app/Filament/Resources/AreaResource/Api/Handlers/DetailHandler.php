<?php

namespace App\Filament\Resources\AreaResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\AreaResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\AreaResource\Api\Transformers\AreaTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = AreaResource::class;


    /**
     * Show Area
     *
     * @param Request $request
     * @return AreaTransformer|\Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();
        
        // Get model instance to get correct primary key name
        $model = $query->getModel();
        $primaryKey = $model->getKeyName(); // This will return 'id_area'

        $query = QueryBuilder::for(
            $query->where($primaryKey, $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new AreaTransformer($query);
    }
}
