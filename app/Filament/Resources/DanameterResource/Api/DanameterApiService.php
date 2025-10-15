<?php
namespace App\Filament\Resources\DanameterResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\DanameterResource;
use Illuminate\Routing\Router;


class DanameterApiService extends ApiService
{
    protected static string | null $resource = DanameterResource::class;

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
