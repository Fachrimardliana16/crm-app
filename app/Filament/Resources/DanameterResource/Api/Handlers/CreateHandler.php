<?php
namespace App\Filament\Resources\DanameterResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\DanameterResource;
use App\Filament\Resources\DanameterResource\Api\Requests\CreateDanameterRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = DanameterResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Danameter
     *
     * @param CreateDanameterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateDanameterRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}