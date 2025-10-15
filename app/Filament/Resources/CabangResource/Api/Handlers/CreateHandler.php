<?php
namespace App\Filament\Resources\CabangResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\CabangResource;
use App\Filament\Resources\CabangResource\Api\Requests\CreateCabangRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = CabangResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Cabang
     *
     * @param CreateCabangRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateCabangRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}