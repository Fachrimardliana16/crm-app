# Contoh Integrasi Action Tentukan Sub Golongan di SurveiResource

## Cara menambahkan action ke SurveiResource

Tambahkan import di bagian atas file SurveiResource.php:

```php
use App\Filament\Actions\TentukanSubGolonganAction;
```

Tambahkan action di dalam method `table()` pada bagian actions:

```php
->actions([
    Tables\Actions\Action::make('input_hasil')
        ->label('Input Hasil')
        ->icon('heroicon-o-pencil')
        ->visible(fn ($record) => $record->status_survei === 'disetujui')
        ->url(fn ($record) => route('filament.admin.resources.surveis.edit', $record)),

    // Action baru untuk tentukan sub golongan
    TentukanSubGolonganAction::make()
        ->visible(fn ($record) => $record->status_survei === 'disetujui' && $record->skor_total > 0),

    Tables\Actions\ViewAction::make()
        ->icon('heroicon-o-eye'),

    Tables\Actions\EditAction::make()
        ->icon('heroicon-o-pencil'),
        
    // ... actions lainnya
])
```

## Atau sebagai bulk action:

```php
->bulkActions([
    Tables\Actions\BulkActionGroup::make([
        Tables\Actions\DeleteBulkAction::make(),
        
        // Bulk action untuk tentukan sub golongan banyak record sekaligus
        Tables\Actions\BulkAction::make('bulk_tentukan_sub_golongan')
            ->label('Tentukan Sub Golongan')
            ->icon('heroicon-o-calculator')
            ->color('primary')
            ->form([
                Forms\Components\Select::make('id_golongan_pelanggan')
                    ->label('Golongan Pelanggan Target')
                    ->options(GolonganPelanggan::aktif()->pluck('nama_golongan', 'id_golongan_pelanggan'))
                    ->searchable()
                    ->nullable(),
            ])
            ->action(function (Collection $records, array $data) {
                $golonganId = $data['id_golongan_pelanggan'] ?? null;
                $processed = 0;
                $errors = 0;

                foreach ($records as $record) {
                    try {
                        $record->updateHasilSurvei($golonganId);
                        $processed++;
                    } catch (\Exception $e) {
                        $errors++;
                    }
                }

                Notification::make()
                    ->title("Proses Selesai")
                    ->body("Berhasil: {$processed}, Error: {$errors}")
                    ->success()
                    ->send();
            })
            ->requiresConfirmation(),
    ]),
])
```

## Contoh Usage dalam Controller/Service Class:

```php
<?php

namespace App\Services;

use App\Models\Survei;
use App\Models\SubGolonganPelanggan;

class SurveiScoringService
{
    public function prosesHasilSurvei(Survei $survei, $golonganId = null)
    {
        // Hitung skor total
        $skorTotal = $survei->hitungTotalSkor();
        
        // Tentukan sub golongan
        $subGolongan = SubGolonganPelanggan::rekomendasiSubGolongan($skorTotal, $golonganId);
        
        // Update hasil survei
        $rekomendasi = $survei->updateHasilSurvei($golonganId);
        
        return [
            'survei' => $survei,
            'sub_golongan' => $subGolongan,
            'rekomendasi' => $rekomendasi,
            'skor_total' => $skorTotal,
        ];
    }
    
    public function getStatistikScoring()
    {
        return [
            'total_survei' => Survei::count(),
            'dengan_scoring' => Survei::whereNotNull('skor_total')->count(),
            'direkomendasikan' => Survei::where('hasil_survei', 'direkomendasikan')->count(),
            'perlu_review' => Survei::where('hasil_survei', 'perlu_review')->count(),
            'rata_rata_skor' => Survei::whereNotNull('skor_total')->avg('skor_total'),
        ];
    }
}
```

## Widget untuk Dashboard Scoring:

```php
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Survei;
use App\Models\SubGolonganPelanggan;

class ScoringStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSurvei = Survei::whereNotNull('skor_total')->count();
        $rataRataSkor = Survei::whereNotNull('skor_total')->avg('skor_total');
        $direkomendasikan = Survei::where('hasil_survei', 'direkomendasikan')->count();
        
        return [
            Stat::make('Total Survei dengan Scoring', $totalSurvei)
                ->description('Survei yang sudah dihitung skornya')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('primary'),
                
            Stat::make('Rata-rata Skor', number_format($rataRataSkor, 1))
                ->description('Skor rata-rata survei')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
                
            Stat::make('Direkomendasikan', $direkomendasikan)
                ->description('Survei yang direkomendasikan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('warning'),
        ];
    }
}
```