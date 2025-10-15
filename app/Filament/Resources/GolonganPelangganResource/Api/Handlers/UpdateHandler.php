<?php
namespace App\Filament\Resources\GolonganPelangganResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\GolonganPelangganResource;
use App\Filament\Resources\GolonganPelangganResource\Api\Requests\UpdateGolonganPelangganRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = GolonganPelangganResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update GolonganPelanggan
     *
     * @param UpdateGolonganPelangganRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateGolonganPelangganRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}