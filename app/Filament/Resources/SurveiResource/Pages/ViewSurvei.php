<?php

namespace App\Filament\Resources\SurveiResource\Pages;

use App\Filament\Resources\SurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewSurvei extends ViewRecord
{
    protected static string $resource = SurveiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Survei')
                ->icon('heroicon-o-pencil'),

            Actions\Action::make('setujui')
                ->label('Setujui Survei')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => $this->record->status_survei === 'draft' && !empty($this->record->rekomendasi_teknis))
                ->action(function () {
                    $this->record->update([
                        'status_survei' => 'disetujui',
                        'diperbarui_oleh' => auth()->id(),
                        'diperbarui_pada' => now(),
                    ]);
                })
                ->requiresConfirmation()
                ->modalHeading('Setujui Hasil Survei')
                ->modalDescription('Apakah Anda yakin ingin menyetujui hasil survei ini?'),

            Actions\Action::make('tolak')
                ->label('Tolak Survei')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->record->status_survei === 'draft')
                ->action(function () {
                    $this->record->update([
                        'status_survei' => 'ditolak',
                        'diperbarui_oleh' => auth()->id(),
                        'diperbarui_pada' => now(),
                    ]);
                })
                ->requiresConfirmation()
                ->modalHeading('Tolak Hasil Survei')
                ->modalDescription('Apakah Anda yakin ingin menolak hasil survei ini?'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pendaftaran')
                    ->schema([
                        Infolists\Components\TextEntry::make('pendaftaran.nomor_registrasi')
                            ->label('No. Registrasi'),
                        Infolists\Components\TextEntry::make('pelanggan.nama_pelanggan')
                            ->label('Nama Pelanggan'),
                        Infolists\Components\TextEntry::make('pelanggan.alamat')
                            ->label('Alamat')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('pendaftaran.created_at')
                            ->label('Tanggal Daftar')
                            ->date('d F Y'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Status & Jadwal Survei')
                    ->schema([
                        Infolists\Components\TextEntry::make('status_survei')
                            ->label('Status Survei')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'warning',
                                'disetujui' => 'success',
                                'ditolak' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('tanggal_survei')
                            ->label('Tanggal Survei')
                            ->date('d F Y')
                            ->placeholder('Belum dijadwalkan'),
                        Infolists\Components\TextEntry::make('nip_surveyor')
                            ->label('NIP Surveyor')
                            ->placeholder('Belum ditentukan'),
                        Infolists\Components\TextEntry::make('spam.nama_spam')
                            ->label('SPAM')
                            ->placeholder('Belum ditentukan'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Hasil Survei')
                    ->schema([
                        Infolists\Components\TextEntry::make('subrayon')
                            ->label('Sub Rayon'),
                        Infolists\Components\TextEntry::make('nilai_survei')
                            ->label('Nilai Survei')
                            ->placeholder('Belum dinilai'),
                        Infolists\Components\TextEntry::make('golongan_survei')
                            ->label('Golongan Survei'),
                        Infolists\Components\TextEntry::make('kelas_survei_input')
                            ->label('Kelas Survei'),
                        Infolists\Components\TextEntry::make('rekomendasi_teknis')
                            ->label('Rekomendasi Teknis')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'Layak' => 'success',
                                'Tidak Layak' => 'danger',
                                'Perlu Perbaikan' => 'warning',
                                'Perlu Survey Ulang' => 'secondary',
                                default => 'gray',
                            })
                            ->placeholder('Belum ada rekomendasi'),
                        Infolists\Components\TextEntry::make('catatan_teknis')
                            ->label('Catatan Teknis')
                            ->placeholder('Tidak ada catatan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn () => $this->record->status_survei !== 'draft' || !empty($this->record->nilai_survei)),

                Infolists\Components\Section::make('Koordinat & Lokasi')
                    ->schema([
                        Infolists\Components\TextEntry::make('latitude_terverifikasi')
                            ->label('Latitude')
                            ->placeholder('Belum diukur'),
                        Infolists\Components\TextEntry::make('longitude_terverifikasi')
                            ->label('Longitude')
                            ->placeholder('Belum diukur'),
                        Infolists\Components\TextEntry::make('elevasi_terverifikasi_mdpl')
                            ->label('Elevasi (MDPL)')
                            ->suffix(' meter')
                            ->placeholder('Belum diukur'),
                        Infolists\Components\TextEntry::make('jarak_pemasangan')
                            ->label('Jarak Pemasangan')
                            ->suffix(' meter')
                            ->placeholder('Belum diukur'),
                    ])
                    ->columns(2)
                    ->visible(fn () => $this->record->status_survei !== 'draft' || !empty($this->record->latitude_terverifikasi)),

                Infolists\Components\Section::make('Dokumentasi Foto')
                    ->schema([
                        Infolists\Components\ImageEntry::make('foto_peta_lokasi')
                            ->label('Peta Lokasi')
                            ->visibility('private'),
                        Infolists\Components\ImageEntry::make('foto_tanah_bangunan')
                            ->label('Tanah/Bangunan')
                            ->visibility('private'),
                        Infolists\Components\ImageEntry::make('foto_dinding')
                            ->label('Dinding')
                            ->visibility('private'),
                        Infolists\Components\ImageEntry::make('foto_lantai')
                            ->label('Lantai')
                            ->visibility('private'),
                        Infolists\Components\ImageEntry::make('foto_atap')
                            ->label('Atap')
                            ->visibility('private'),
                        Infolists\Components\ImageEntry::make('foto_pagar')
                            ->label('Pagar')
                            ->visibility('private'),
                        Infolists\Components\ImageEntry::make('foto_jalan')
                            ->label('Jalan')
                            ->visibility('private'),
                        Infolists\Components\ImageEntry::make('foto_meteran_listrik')
                            ->label('Meteran Listrik')
                            ->visibility('private'),
                    ])
                    ->columns(2)
                    ->visible(fn () => $this->record->status_survei !== 'draft' || !empty($this->record->foto_peta_lokasi)),

                Infolists\Components\Section::make('Informasi Sistem')
                    ->schema([
                        Infolists\Components\TextEntry::make('dibuat_pada')
                            ->label('Dibuat Pada')
                            ->dateTime('d F Y, H:i'),
                        Infolists\Components\TextEntry::make('diperbarui_pada')
                            ->label('Diperbarui Pada')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('Belum diperbarui'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
