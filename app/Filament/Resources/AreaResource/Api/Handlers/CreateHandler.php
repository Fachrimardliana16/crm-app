<?php
namespace App\Filament\Resources\AreaResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\AreaResource;
use App\Filament\Resources\AreaResource\Api\Requests\CreateAreaRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = AreaResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Area
     *
     * @param CreateAreaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateAreaRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}