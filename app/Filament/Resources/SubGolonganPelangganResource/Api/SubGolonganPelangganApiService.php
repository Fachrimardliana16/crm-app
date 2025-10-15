<?php
namespace App\Filament\Resources\SubGolonganPelangganResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\SubGolonganPelangganResource;
use Illuminate\Routing\Router;


class SubGolonganPelangganApiService extends ApiService
{
    protected static string | null $resource = SubGolonganPelangganResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
