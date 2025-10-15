<?php
namespace App\Filament\Resources\SubGolonganPelangganResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\SubGolonganPelangganResource;
use App\Filament\Resources\SubGolonganPelangganResource\Api\Requests\CreateSubGolonganPelangganRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = SubGolonganPelangganResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create SubGolonganPelanggan
     *
     * @param CreateSubGolonganPelangganRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateSubGolonganPelangganRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}