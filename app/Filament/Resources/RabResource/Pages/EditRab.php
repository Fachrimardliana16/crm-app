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
        // Generate ulang angsuran jika tipe pembayaran berubah ke cicilan
        // atau jika ada perubahan dalam jumlah/nominal cicilan
        if ($this->record && $this->record->tipe_pembayaran === 'cicilan') {
            // Validate custom angsuran jika mode custom
            if ($this->record->mode_cicilan === 'custom') {
                $validation = $this->record->validateCustomAngsuran();
                if (!$validation['valid']) {
                    \Filament\Notifications\Notification::make()
                        ->title('Peringatan: Total Custom Angsuran Tidak Sesuai')
                        ->body("Total custom: Rp " . number_format($validation['total_custom'], 0, ',', '.') . 
                               " | Target: Rp " . number_format($validation['total_biaya'], 0, ',', '.'))
                        ->warning()
                        ->send();
                }
            }
            
            $this->record->generateAngsuran();
        }
        
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
