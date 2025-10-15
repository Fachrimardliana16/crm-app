<?php
namespace App\Filament\Resources\RayonResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\RayonResource;
use Illuminate\Routing\Router;


class RayonApiService extends ApiService
{
    protected static string | null $resource = RayonResource::class;

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
