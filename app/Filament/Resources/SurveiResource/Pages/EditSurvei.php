<?php

namespace App\Filament\Resources\SurveiResource\Pages;

use App\Filament\Resources\SurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurvei extends EditRecord
{
    protected static string $resource = SurveiResource::class;

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
                    $this->record->update([
                        'status_survei' => 'disetujui',
                        'diperbarui_oleh' => auth()->id(),
                        'diperbarui_pada' => now(),
                    ]);

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

        // Calculate scoring
        $data = $this->calculateSurveyScore($data);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    private function calculateSurveyScore(array $data): array
    {
        $score = 0;

        // Scoring untuk setiap parameter (masing-masing maksimal 10 poin)
        $scoreMap = [
            'luas_tanah' => [
                '0-60' => 2,
                '60-120' => 4,
                '120-200' => 6,
                '200-300' => 8,
                '>300' => 10
            ],
            'luas_bangunan' => [
                '0-36' => 2,
                '36-70' => 4,
                '70-120' => 6,
                '120-200' => 8,
                '>200' => 10
            ],
            'lokasi_bangunan' => [
                'gang-sempit' => 2,
                'gang-sedang' => 4,
                'tepi-jalan-kecil' => 6,
                'tepi-jalan-besar' => 8,
                'jalan-utama' => 10
            ],
            'dinding_bangunan' => [
                'bambu-kayu' => 2,
                'semi-permanen' => 4,
                'tembok-setengah' => 6,
                'tembok-penuh' => 8,
                'bata-expose' => 10
            ],
            'lantai_bangunan' => [
                'tanah' => 2,
                'semen' => 4,
                'keramik-biasa' => 6,
                'keramik-bagus' => 8,
                'granit-marmer' => 10
            ],
            'atap_bangunan' => [
                'rumbia-jerami' => 2,
                'seng-asbes' => 4,
                'genteng-tanah' => 6,
                'genteng-beton' => 8,
                'dak-beton' => 10
            ],
            'pagar_bangunan' => [
                'tidak-ada' => 2,
                'bambu-kayu' => 4,
                'kawat-seng' => 6,
                'tembok-setengah' => 8,
                'tembok-penuh' => 10
            ],
            'lokasi_jalan' => [
                'tanah-berbatu' => 2,
                'makadam' => 4,
                'paving-conblock' => 6,
                'aspal-sedang' => 8,
                'aspal-mulus' => 10
            ],
            'daya_listrik' => [
                'non-pln' => 2,
                '450-900' => 4,
                '1300' => 6,
                '2200' => 8,
                '>2200' => 10
            ],
            'fungsi_rumah' => [
                'kontrak-kost' => 2,
                'rumah-sendiri' => 4,
                'rumah-keluarga' => 6,
                'rumah-dinas' => 8,
                'rumah-mewah' => 10
            ],
            'kepemilikan_kendaraan' => [
                'tidak-ada' => 2,
                'sepeda-becak' => 4,
                'sepeda-motor' => 6,
                'mobil-motor' => 8,
                'mobil-mewah' => 10
            ],
        ];

        foreach ($scoreMap as $field => $values) {
            if (isset($data[$field]) && isset($values[$data[$field]])) {
                $score += $values[$data[$field]];
            }
        }

        // Set nilai survei jika ada parameter yang diisi
        if ($score > 0) {
            $data['nilai_survei'] = $score;

            // Auto set golongan berdasarkan score (total maksimal 110 poin)
            if ($score >= 88) { // 80% dari 110
                $data['golongan_survei'] = 'A';
            } elseif ($score >= 66) { // 60% dari 110
                $data['golongan_survei'] = 'B';
            } elseif ($score >= 44) { // 40% dari 110
                $data['golongan_survei'] = 'C';
            } else {
                $data['golongan_survei'] = 'D';
            }
        }

        return $data;
    }
}
