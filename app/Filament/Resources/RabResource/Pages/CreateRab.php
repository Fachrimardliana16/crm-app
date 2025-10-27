<?php

namespace App\Filament\Resources\RabResource\Pages;

use App\Filament\Resources\RabResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRab extends CreateRecord
{
    protected static string $resource = RabResource::class;

    protected function afterCreate(): void
    {
        // Send workflow notifications
        $notificationService = app(\App\Services\WorkflowNotificationService::class);
        $notificationService->rabCreated($this->record);
    }
}
