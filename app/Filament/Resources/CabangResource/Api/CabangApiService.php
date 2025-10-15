<?php
namespace App\Filament\Resources\CabangResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\CabangResource;
use Illuminate\Routing\Router;


class CabangApiService extends ApiService
{
    protected static string | null $resource = CabangResource::class;

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
