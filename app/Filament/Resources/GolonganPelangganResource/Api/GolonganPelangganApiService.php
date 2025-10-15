<?php
namespace App\Filament\Resources\GolonganPelangganResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\GolonganPelangganResource;
use Illuminate\Routing\Router;


class GolonganPelangganApiService extends ApiService
{
    protected static string | null $resource = GolonganPelangganResource::class;

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
