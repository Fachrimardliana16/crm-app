<?php
namespace App\Filament\Resources\DanameterResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\DanameterResource;
use App\Filament\Resources\DanameterResource\Api\Requests\UpdateDanameterRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = DanameterResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Danameter
     *
     * @param UpdateDanameterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateDanameterRequest $request)
    {
        $id = $request->route('id');

        $modelClass = static::getModel();
        $model = $modelClass::where($modelClass::make()->getKeyName(), $id)->first();

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}