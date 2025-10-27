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

    // Override to hide relation managers on view page
    public function getRelationManagers(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Survei')
                ->icon('heroicon-o-pencil'),

            Actions\Action::make('refresh_scoring')
                ->label('Refresh Scoring')
                ->icon('heroicon-o-calculator')
                ->color('info')
                ->visible(fn () => $this->record->status_survei === 'draft')
                ->action(function () {
                    try {
                        $this->record->updateHasilSurvei();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Scoring Berhasil Diperbarui')
                            ->body("Skor Total: {$this->record->skor_total}, Kategori: {$this->record->kategori_golongan}")
                            ->success()
                            ->send();
                            
                        $this->record->refresh();
                        
                        // Redirect to refresh the page
                        return redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                        
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal Memperbarui Scoring')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Refresh Scoring Survei')
                ->modalDescription('Sistem akan menghitung ulang total skor dan menentukan golongan/sub golongan berdasarkan parameter yang sudah diisi.'),

            Actions\Action::make('setujui')
                ->label('Setujui Survei')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn () => $this->record->status_survei === 'draft' && ($this->record->skor_total > 0 || !empty($this->record->rekomendasi_teknis)))
                ->action(function () {
                    $this->record->update([
                        'status_survei' => 'disetujui',
                        'diperbarui_oleh' => auth()->id(),
                        'diperbarui_pada' => now(),
                    ]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Survei Disetujui')
                        ->body('Hasil survei telah disetujui')
                        ->success()
                        ->send();
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
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Survei Ditolak')
                        ->body('Hasil survei telah ditolak')
                        ->warning()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Tolak Hasil Survei')
                ->modalDescription('Apakah Anda yakin ingin menolak hasil survei ini?'),

            Actions\Action::make('survei_ulang')
                ->label('Survei Ulang')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn () => in_array($this->record->status_survei, ['draft', 'ditolak']))
                ->action(function () {
                    $this->record->update([
                        'status_survei' => 'draft',
                        'rekomendasi_teknis' => 'Perlu Survey Ulang',
                        'diperbarui_oleh' => auth()->id(),
                        'diperbarui_pada' => now(),
                    ]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Survei Diatur Ulang')
                        ->body('Status survei diubah untuk survey ulang')
                        ->warning()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Survei Ulang')
                ->modalDescription('Survei akan diatur untuk dilakukan ulang. Status akan diubah ke draft.'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load relasi yang diperlukan
        $this->record->load(['rekomendasiSubGolongan', 'pendaftaran', 'pelanggan', 'spam']);
        return $data;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pendaftaran')
                    ->description('Data dari pendaftaran yang akan disurvei')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\TextEntry::make('pendaftaran.nomor_registrasi')
                            ->label('No. Registrasi')
                            ->badge()
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('pendaftaran.nama_pemohon')
                            ->label('Nama Pemohon'),
                        Infolists\Components\TextEntry::make('pelanggan.nama_pelanggan')
                            ->label('Nama Pelanggan'),
                        Infolists\Components\TextEntry::make('pelanggan.nomor_pelanggan')
                            ->label('No. Pelanggan')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('pendaftaran.alamat_pemasangan')
                            ->label('Alamat Pemasangan')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('pelanggan.alamat')
                            ->label('Alamat Pelanggan')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('pendaftaran.no_hp_pemohon')
                            ->label('No. HP Pemohon'),
                        Infolists\Components\TextEntry::make('pendaftaran.jenis_identitas')
                            ->label('Jenis Identitas')
                            ->badge(),
                        Infolists\Components\TextEntry::make('pendaftaran.nomor_identitas')
                            ->label('Nomor Identitas'),
                        Infolists\Components\TextEntry::make('pendaftaran.tanggal_daftar')
                            ->label('Tanggal Daftar')
                            ->date('d F Y')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('pendaftaran.kelurahan.nama_kelurahan')
                            ->label('Kelurahan'),
                        Infolists\Components\TextEntry::make('pendaftaran.kelurahan.kecamatan.nama_kecamatan')
                            ->label('Kecamatan'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Informasi Survei')
                    ->description('Status dan informasi survei yang sedang berlangsung')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\TextEntry::make('tanggal_survei')
                            ->label('Tanggal Survei')
                            ->date('d F Y')
                            ->placeholder('Belum dijadwalkan')
                            ->badge()
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('nip_surveyor')
                            ->label('NIP Surveyor')
                            ->placeholder('Belum ditentukan')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('status_survei')
                            ->label('Status Survei')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'warning',
                                'disetujui' => 'success',
                                'ditolak' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('spam.nama_spam')
                            ->label('SPAM')
                            ->placeholder('Belum ditentukan')
                            ->badge()
                            ->color('primary'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Parameter Survei')
                    ->description('Parameter yang digunakan dalam penilaian survei')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('masterLuasTanah.nama')
                                    ->label('Luas Tanah')
                                    ->formatStateUsing(fn ($record) => $record?->masterLuasTanah ? $record->masterLuasTanah->nama . ' (Skor: ' . $record->masterLuasTanah->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterLuasBangunan.nama')
                                    ->label('Luas Bangunan')
                                    ->formatStateUsing(fn ($record) => $record?->masterLuasBangunan ? $record->masterLuasBangunan->nama . ' (Skor: ' . $record->masterLuasBangunan->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterLokasiBangunan.nama')
                                    ->label('Lokasi Bangunan')
                                    ->formatStateUsing(fn ($record) => $record?->masterLokasiBangunan ? $record->masterLokasiBangunan->nama . ' (Skor: ' . $record->masterLokasiBangunan->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterDindingBangunan.nama')
                                    ->label('Dinding Bangunan')
                                    ->formatStateUsing(fn ($record) => $record?->masterDindingBangunan ? $record->masterDindingBangunan->nama . ' (Skor: ' . $record->masterDindingBangunan->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterLantaiBangunan.nama')
                                    ->label('Lantai Bangunan')
                                    ->formatStateUsing(fn ($record) => $record?->masterLantaiBangunan ? $record->masterLantaiBangunan->nama . ' (Skor: ' . $record->masterLantaiBangunan->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterAtapBangunan.nama')
                                    ->label('Atap Bangunan')
                                    ->formatStateUsing(fn ($record) => $record?->masterAtapBangunan ? $record->masterAtapBangunan->nama . ' (Skor: ' . $record->masterAtapBangunan->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterPagarBangunan.nama')
                                    ->label('Pagar Bangunan')
                                    ->formatStateUsing(fn ($record) => $record?->masterPagarBangunan ? $record->masterPagarBangunan->nama . ' (Skor: ' . $record->masterPagarBangunan->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterKondisiJalan.nama')
                                    ->label('Kondisi Jalan')
                                    ->formatStateUsing(fn ($record) => $record?->masterKondisiJalan ? $record->masterKondisiJalan->nama . ' (Skor: ' . $record->masterKondisiJalan->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterDayaListrik.nama')
                                    ->label('Daya Listrik')
                                    ->formatStateUsing(fn ($record) => $record?->masterDayaListrik ? $record->masterDayaListrik->nama . ' (Skor: ' . $record->masterDayaListrik->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterFungsiRumah.nama')
                                    ->label('Fungsi Rumah')
                                    ->formatStateUsing(fn ($record) => $record?->masterFungsiRumah ? $record->masterFungsiRumah->nama . ' (Skor: ' . $record->masterFungsiRumah->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                                Infolists\Components\TextEntry::make('masterKepemilikanKendaraan.nama')
                                    ->label('Kepemilikan Kendaraan')
                                    ->formatStateUsing(fn ($record) => $record?->masterKepemilikanKendaraan ? $record->masterKepemilikanKendaraan->nama . ' (Skor: ' . $record->masterKepemilikanKendaraan->skor . ')' : '-')
                                    ->placeholder('Belum dipilih'),
                            ]),
                    ])
                    ->visible(fn () => $this->record->masterLuasTanah || $this->record->masterLuasBangunan || $this->record->masterLokasiBangunan || $this->record->masterDindingBangunan || $this->record->masterLantaiBangunan || $this->record->masterAtapBangunan || $this->record->masterPagarBangunan || $this->record->masterKondisiJalan || $this->record->masterDayaListrik || $this->record->masterFungsiRumah || $this->record->masterKepemilikanKendaraan),

                Infolists\Components\Section::make('Hasil Scoring & Rekomendasi')
                    ->description('Hasil perhitungan skor dan rekomendasi golongan pelanggan')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\TextEntry::make('skor_total')
                            ->label('Skor Total')
                            ->formatStateUsing(fn ($state) => ($state > 0 ? $state . ' poin' : 'Belum dihitung'))
                            ->badge()
                            ->color(fn ($record) => match (true) {
                                $record->skor_total >= 100 => 'success',
                                $record->skor_total >= 75 => 'warning',
                                $record->skor_total >= 50 => 'info',
                                $record->skor_total > 0 => 'danger',
                                default => 'gray'
                            }),
                        Infolists\Components\TextEntry::make('kategori_golongan')
                            ->label('Kategori Golongan')
                            ->formatStateUsing(fn ($state) => $state ?: 'Belum ditentukan')
                            ->badge()
                            ->color(fn ($record) => match (true) {
                                str_contains($record->kategori_golongan ?? '', 'A') => 'success',
                                str_contains($record->kategori_golongan ?? '', 'B') => 'warning',
                                str_contains($record->kategori_golongan ?? '', 'C') => 'info',
                                str_contains($record->kategori_golongan ?? '', 'D') => 'danger',
                                default => 'secondary'
                            }),
                        Infolists\Components\TextEntry::make('rekomendasi_sub_golongan_text')
                            ->label('Sub Golongan Rekomendasi')
                            ->formatStateUsing(function ($state, $record) {
                                // Coba ambil dari relasi dulu, jika tidak ada gunakan dari kolom text
                                if ($record->rekomendasi_sub_golongan_id && $record->relationLoaded('rekomendasiSubGolongan') && $record->rekomendasiSubGolongan) {
                                    return $record->rekomendasiSubGolongan->nama_sub_golongan;
                                }
                                // Fallback: ambil nama dari text yang ada (parsing dari "Nama (range)")
                                if ($state) {
                                    return explode(' (', $state)[0] ?? $state;
                                }
                                return 'Belum ditentukan';
                            })
                            ->badge()
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('hasil_survei')
                            ->label('Hasil Survei')
                            ->formatStateUsing(fn ($state) => match($state) {
                                'direkomendasikan' => 'Direkomendasikan',
                                'tidak_direkomendasikan' => 'Tidak Direkomendasikan',
                                'perlu_review' => 'Perlu Review',
                                default => 'Belum ditentukan'
                            })
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'direkomendasikan' => 'success',
                                'tidak_direkomendasikan' => 'danger',
                                'perlu_review' => 'warning',
                                default => 'secondary',
                            }),
                        Infolists\Components\TextEntry::make('rekomendasi_sub_golongan_text')
                            ->label('Detail Rekomendasi')
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => $state ?: 'Belum ada rekomendasi'),
                    ])
                    ->columns(2)
                    ->visible(fn () => $this->record->skor_total > 0 || !empty($this->record->kategori_golongan) || !empty($this->record->rekomendasi_sub_golongan_id)),

                Infolists\Components\Section::make('Koordinat & Lokasi')
                    ->description('Koordinat lokasi hasil verifikasi survei')
                    ->collapsible()
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
                        Infolists\Components\TextEntry::make('google_maps_link')
                            ->label('Link Google Maps')
                            ->formatStateUsing(function ($record) {
                                if ($record->latitude_terverifikasi && $record->longitude_terverifikasi) {
                                    $url = "https://www.google.com/maps?q={$record->latitude_terverifikasi},{$record->longitude_terverifikasi}";
                                    return new \Illuminate\Support\HtmlString("<a href='{$url}' target='_blank' class='text-blue-600 hover:text-blue-800 underline'>Buka di Google Maps</a>");
                                }
                                return 'Koordinat belum tersedia';
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn () => !empty($this->record->latitude_terverifikasi) || !empty($this->record->longitude_terverifikasi)),

                Infolists\Components\Section::make('Catatan Teknis')
                    ->description('Catatan dan rekomendasi teknis dari surveyor')
                    ->collapsible()
                    ->schema([
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
                            ->formatStateUsing(fn ($state) => $state ?: 'Belum ada rekomendasi'),
                        Infolists\Components\TextEntry::make('catatan_teknis')
                            ->label('Catatan Teknis')
                            ->placeholder('Tidak ada catatan')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->visible(fn () => !empty($this->record->rekomendasi_teknis) || !empty($this->record->catatan_teknis)),

                Infolists\Components\Section::make('Dokumentasi Foto')
                    ->description('Foto-foto pendukung hasil survei')
                    ->collapsible()
                    ->collapsed()
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
                    ->visible(fn () => !empty($this->record->foto_peta_lokasi) || !empty($this->record->foto_tanah_bangunan) || !empty($this->record->foto_dinding) || !empty($this->record->foto_lantai) || !empty($this->record->foto_atap) || !empty($this->record->foto_pagar) || !empty($this->record->foto_jalan) || !empty($this->record->foto_meteran_listrik)),

                Infolists\Components\Section::make('Informasi Sistem')
                    ->description('Informasi audit dan riwayat perubahan')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Infolists\Components\TextEntry::make('dibuat_oleh')
                            ->label('Dibuat Oleh'),
                        Infolists\Components\TextEntry::make('dibuat_pada')
                            ->label('Dibuat Pada')
                            ->dateTime('d F Y, H:i'),
                        Infolists\Components\TextEntry::make('diperbarui_oleh')
                            ->label('Diperbarui Oleh')
                            ->placeholder('Belum ada perubahan'),
                        Infolists\Components\TextEntry::make('diperbarui_pada')
                            ->label('Diperbarui Pada')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('Belum diperbarui'),
                    ])
                    ->columns(2),
            ]);
    }
}
