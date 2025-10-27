<?php

namespace App\Filament\Resources\SurveiResource\Pages;

use App\Filament\Resources\SurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSurvei extends CreateRecord
{
    protected static string $resource = SurveiResource::class;

    // Override to hide relation managers on create page
    public function getRelationManagers(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default values
        $data['dibuat_oleh'] = auth()->id();
        $data['dibuat_pada'] = now();

        // Set Trial section fields (these are auto-managed by system)
        $data['tanggal_survei'] = now()->format('Y-m-d');
        $data['nip_surveyor'] = auth()->user()->email ?? auth()->id();
        $data['status_survei'] = 'draft';

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Generate UUID for id_survei
        $data['id_survei'] = \Illuminate\Support\Str::uuid()->toString();

        $survei = static::getModel()::create($data);
        
        // Update scoring after creation
        $this->updateSurveiScoring($survei);

        return $survei;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    private function updateSurveiScoring($survei): void
    {
        try {
            // Gunakan method dari model untuk menghitung scoring
            $survei->updateHasilSurvei();
            
            // Notification untuk debug
            \Filament\Notifications\Notification::make()
                ->title('Scoring Updated')
                ->body("Skor total: {$survei->skor_total}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Error Update Scoring')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function afterCreate(): void
    {
        // Send workflow notifications
        $notificationService = app(\App\Services\WorkflowNotificationService::class);
        $notificationService->surveiCreated($this->record);
    }
}
