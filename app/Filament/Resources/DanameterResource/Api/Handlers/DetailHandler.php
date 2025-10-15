<?php

namespace App\Filament\Resources\DanameterResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\DanameterResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\DanameterResource\Api\Transformers\DanameterTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = DanameterResource::class;


    /**
     * Show Danameter
     *
     * @param Request $request
     * @return DanameterTransformer|\Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();
        
        // Get model instance to get correct primary key name
        $model = $query->getModel();
        $primaryKey = $model->getKeyName(); // This will return 'id_danameter'

        $query = QueryBuilder::for(
            $query->where($primaryKey, $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new DanameterTransformer($query);
    }
}
