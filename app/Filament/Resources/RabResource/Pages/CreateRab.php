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
        // Generate angsuran jika tipe pembayaran cicilan
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
        
        // Send workflow notifications
        $notificationService = app(\App\Services\WorkflowNotificationService::class);
        $notificationService->rabCreated($this->record);
    }
}
