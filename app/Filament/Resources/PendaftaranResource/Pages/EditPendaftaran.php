<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPendaftaran extends EditRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->icon('heroicon-o-eye')
                ->color('info'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->color('danger'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Track old status for notifications
        $this->oldStatus = $this->record->status_pendaftaran ?? null;
        
        // Update audit fields
        $data['diperbarui_oleh'] = auth()->user()->name ?? 'System';
        $data['diperbarui_pada'] = now();

        return $data;
    }

    protected function afterSave(): void
    {
        // Send notification if status changed
        if ($this->oldStatus && $this->oldStatus !== $this->record->status_pendaftaran) {
            $notificationService = app(\App\Services\WorkflowNotificationService::class);
            $notificationService->pendaftaranStatusChanged(
                $this->record, 
                $this->oldStatus, 
                $this->record->status_pendaftaran
            );
        }
    }

    protected $oldStatus = null;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pendaftaran Berhasil Diperbarui')
            ->body('Data pendaftaran telah berhasil disimpan.')
            ->duration(5000);
    }
}
