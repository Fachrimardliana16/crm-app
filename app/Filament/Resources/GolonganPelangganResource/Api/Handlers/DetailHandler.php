<?php

namespace App\Filament\Resources\GolonganPelangganResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\GolonganPelangganResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\GolonganPelangganResource\Api\Transformers\GolonganPelangganTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = GolonganPelangganResource::class;


        /**
     * Show Golongan Pelanggan
     *
     * @param Request $request
     * @return GolonganPelangganTransformer|\Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();
        
        // Get model instance to get correct primary key name
        $model = $query->getModel();
        $primaryKey = $model->getKeyName(); // This will return 'id_golongan_pelanggan'

        $query = QueryBuilder::for(
            $query->where($primaryKey, $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new GolonganPelangganTransformer($query);
    }
}
