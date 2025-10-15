<?php
namespace App\Filament\Resources\RayonResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\RayonResource;
use App\Filament\Resources\RayonResource\Api\Requests\CreateRayonRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = RayonResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Rayon
     *
     * @param CreateRayonRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateRayonRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}