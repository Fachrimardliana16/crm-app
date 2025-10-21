<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Models\SubGolonganPelanggan;
use App\Models\GolonganPelanggan;

class TentukanSubGolonganAction
{
    public static function make(): Action
    {
        return Action::make('tentukan_sub_golongan')
            ->label('Tentukan Sub Golongan')
            ->icon('heroicon-o-calculator')
            ->color('primary')
            ->form([
                Select::make('id_golongan_pelanggan')
                    ->label('Golongan Pelanggan Target')
                    ->options(GolonganPelanggan::aktif()->pluck('nama_golongan', 'id_golongan_pelanggan'))
                    ->searchable()
                    ->nullable()
                    ->helperText('Kosongkan untuk mencari di semua golongan'),
                
                TextInput::make('skor_manual')
                    ->label('Skor Manual (Opsional)')
                    ->numeric()
                    ->helperText('Kosongkan untuk menggunakan skor otomatis dari parameter survei'),
            ])
            ->action(function (array $data, $record) {
                try {
                    // Gunakan skor manual atau hitung otomatis
                    $skorTotal = $data['skor_manual'] ?? $record->hitungTotalSkor();
                    $golonganId = $data['id_golongan_pelanggan'] ?? null;

                    // Tentukan sub golongan
                    $subGolongan = SubGolonganPelanggan::rekomendasiSubGolongan($skorTotal, $golonganId);

                    if ($subGolongan) {
                        // Update record survei
                        $kategoriGolongan = self::kategoriGolonganBySkor($skorTotal);
                        
                        $record->update([
                            'skor_total' => $skorTotal,
                            'hasil_survei' => 'direkomendasikan',
                            'kategori_golongan' => $kategoriGolongan,
                        ]);

                        // Kirim notifikasi berhasil
                        Notification::make()
                            ->title('Sub Golongan Berhasil Ditentukan')
                            ->body("Rekomendasi: {$subGolongan->nama_sub_golongan} (Skor: {$skorTotal}, Kategori: {$kategoriGolongan})")
                            ->success()
                            ->duration(10000)
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('lihat_detail')
                                    ->label('Lihat Detail')
                                    ->url(route('filament.admin.resources.sub-golongan-pelanggans.edit', $subGolongan))
                                    ->openUrlInNewTab(),
                            ])
                            ->send();

                    } else {
                        // Update sebagai perlu review
                        $record->update([
                            'skor_total' => $skorTotal,
                            'hasil_survei' => 'perlu_review',
                            'kategori_golongan' => self::kategoriGolonganBySkor($skorTotal),
                        ]);

                        Notification::make()
                            ->title('Tidak Ada Sub Golongan yang Cocok')
                            ->body("Skor: {$skorTotal}. Silakan review manual atau sesuaikan parameter survei.")
                            ->warning()
                            ->send();
                    }

                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error')
                        ->body('Terjadi kesalahan: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            })
            ->requiresConfirmation()
            ->modalHeading('Tentukan Sub Golongan Pelanggan')
            ->modalDescription('Sistem akan menghitung skor total berdasarkan parameter survei dan menentukan sub golongan yang sesuai.')
            ->modalSubmitActionLabel('Tentukan Sub Golongan');
    }

    private static function kategoriGolonganBySkor($skor)
    {
        if ($skor >= 100) {
            return 'A';
        } elseif ($skor >= 75) {
            return 'B';
        } elseif ($skor >= 50) {
            return 'C';
        } else {
            return 'D';
        }
    }
}