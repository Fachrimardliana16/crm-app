<?php

namespace App\Filament\Resources\SurveiResource\Pages;

use App\Filament\Resources\SurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurvei extends EditRecord
{
    protected static string $resource = SurveiResource::class;

    // Override to hide relation managers on edit page
    public function getRelationManagers(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),

            Actions\Action::make('setujui')
                ->label('Setujui Hasil')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => $this->record->status_survei === 'draft' && !empty($this->record->rekomendasi_teknis))
                ->action(function () {
                    $oldStatus = $this->record->status_survei;
                    
                    $this->record->update([
                        'status_survei' => 'disetujui',
                        'diperbarui_oleh' => auth()->id(),
                        'diperbarui_pada' => now(),
                    ]);

                    // Send notification for status change
                    $notificationService = app(\App\Services\WorkflowNotificationService::class);
                    $notificationService->surveiStatusChanged($this->record, $oldStatus, 'disetujui');

                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                })
                ->requiresConfirmation()
                ->modalHeading('Setujui Hasil Survei')
                ->modalDescription('Apakah Anda yakin ingin menyetujui hasil survei ini?'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Set updated by and timestamp
        $data['diperbarui_oleh'] = auth()->id();
        $data['diperbarui_pada'] = now();

        // Preserve original values for protected fields in Trial section
        $originalRecord = $this->record;
        $data['tanggal_survei'] = $originalRecord->tanggal_survei ?? now()->format('Y-m-d');
        $data['nip_surveyor'] = $originalRecord->nip_surveyor ?? (auth()->user()->email ?? auth()->id());
        // Status survei hanya dapat diubah melalui action buttons, bukan form edit
        $data['status_survei'] = $originalRecord->status_survei ?? 'draft';

        return $data;
    }

    protected function afterSave(): void
    {
        // Update scoring after save
        $this->updateSurveiScoring($this->record);
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
}
