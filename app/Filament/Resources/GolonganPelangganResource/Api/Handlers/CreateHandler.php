<?php
namespace App\Filament\Resources\GolonganPelangganResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\GolonganPelangganResource;
use App\Filament\Resources\GolonganPelangganResource\Api\Requests\CreateGolonganPelangganRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = GolonganPelangganResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create GolonganPelanggan
     *
     * @param CreateGolonganPelangganRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateGolonganPelangganRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}