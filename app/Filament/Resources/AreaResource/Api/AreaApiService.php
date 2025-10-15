<?php
namespace App\Filament\Resources\AreaResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\AreaResource;
use Illuminate\Routing\Router;


class AreaApiService extends ApiService
{
    protected static string | null $resource = AreaResource::class;

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
