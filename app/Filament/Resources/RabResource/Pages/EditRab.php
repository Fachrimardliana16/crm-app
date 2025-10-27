<?php

namespace App\Filament\Resources\RabResource\Pages;

use App\Filament\Resources\RabResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRab extends EditRecord
{
    protected static string $resource = RabResource::class;
    protected $oldStatus = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Track old status for notifications
        $this->oldStatus = $this->record->status_rab ?? null;
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Send notification if status changed
        if ($this->oldStatus && $this->oldStatus !== $this->record->status_rab) {
            $notificationService = app(\App\Services\WorkflowNotificationService::class);
            $notificationService->rabStatusChanged(
                $this->record, 
                $this->oldStatus, 
                $this->record->status_rab
            );
        }
    }
}
