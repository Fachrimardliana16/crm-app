<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewPendaftaran extends ViewRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print_faktur')
                ->label('Print Faktur')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn () => route('faktur.pembayaran', ['pendaftaran' => $this->record->id_pendaftaran]))
                ->openUrlInNewTab(),
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square')
                ->color('primary'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->color('danger'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Header Section dengan informasi utama
                Section::make('Informasi Pendaftaran')
                    ->description('Data utama pendaftaran pelanggan')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('nomor_registrasi')
                                        ->label('Nomor Registrasi')
                                        ->badge()
                                        ->color('primary')
                                        ->size(TextEntry\TextEntrySize::Large)
                                        ->weight(FontWeight::Bold),

                                    TextEntry::make('tanggal_daftar')
                                        ->label('Tanggal Daftar')
                                        ->date('d F Y')
                                        ->icon('heroicon-o-calendar-days')
                                        ->color('success'),

                                    TextEntry::make('nama_pemohon')
                                        ->label('Nama Pemohon')
                                        ->size(TextEntry\TextEntrySize::Large)
                                        ->weight(FontWeight::SemiBold)
                                        ->icon('heroicon-o-user'),

                                    TextEntry::make('no_hp_pemohon')
                                        ->label('No. HP')
                                        ->icon('heroicon-o-phone')
                                        ->copyable()
                                        ->copyMessage('Nomor HP disalin!')
                                        ->url(fn ($record) => 'tel:' . $record->no_hp_pemohon),

                                    TextEntry::make('jenis_identitas')
                                        ->label('Jenis Identitas')
                                        ->badge()
                                        ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                                    TextEntry::make('nomor_identitas')
                                        ->label('Nomor Identitas')
                                        ->copyable()
                                        ->copyMessage('Nomor identitas disalin!'),
                                ]),
                        ])
                    ])
                    ->collapsible(),

                // Section Layanan
                Section::make('Informasi Layanan')
                    ->description('Detail layanan yang diminta')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('tipeLayanan.nama_tipe_layanan')
                                    ->label('Tipe Layanan')
                                    ->badge()
                                    ->color('info')
                                    ->icon('heroicon-o-wrench-screwdriver'),

                                TextEntry::make('jenisDaftar.nama_jenis_daftar')
                                    ->label('Jenis Daftar')
                                    ->badge()
                                    ->color('warning'),

                                TextEntry::make('tipePendaftaran.nama_tipe_pendaftaran')
                                    ->label('Tipe Pendaftaran')
                                    ->badge()
                                    ->color('success'),
                            ])
                    ])
                    ->collapsible(),

                // Section Lokasi
                Section::make('Informasi Lokasi')
                    ->description('Detail lokasi pemasangan')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('cabang.nama_cabang')
                                    ->label('Cabang')
                                    ->icon('heroicon-o-building-office')
                                    ->badge()
                                    ->color('primary'),

                                TextEntry::make('kelurahan.nama_kelurahan')
                                    ->label('Kelurahan')
                                    ->icon('heroicon-o-map'),

                                TextEntry::make('kelurahan.kecamatan.nama_kecamatan')
                                    ->label('Kecamatan')
                                    ->icon('heroicon-o-map'),

                                TextEntry::make('alamat_pemasangan')
                                    ->label('Alamat Pemasangan')
                                    ->columnSpanFull()
                                    ->icon('heroicon-o-home'),

                                TextEntry::make('keterangan_arah_lokasi')
                                    ->label('Keterangan Arah')
                                    ->columnSpanFull()
                                    ->icon('heroicon-o-map-pin')
                                    ->placeholder('Tidak ada keterangan'),
                            ])
                    ])
                    ->collapsible(),

                // Section Koordinat dan Maps
                Section::make('Koordinat Lokasi')
                    ->description('Posisi GPS lokasi pemasangan')
                    ->icon('heroicon-o-globe-asia-australia')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('latitude_awal')
                                    ->label('Latitude')
                                    ->badge()
                                    ->color('success')
                                    ->placeholder('Belum diset'),

                                TextEntry::make('longitude_awal')
                                    ->label('Longitude')
                                    ->badge()
                                    ->color('success')
                                    ->placeholder('Belum diset'),

                                TextEntry::make('elevasi_awal_mdpl')
                                    ->label('Elevasi (mdpl)')
                                    ->numeric(decimalPlaces: 2)
                                    ->badge()
                                    ->color('info')
                                    ->placeholder('Belum diset'),

                                TextEntry::make('maps_link')
                                    ->label('Lihat di Google Maps')
                                    ->columnSpanFull()
                                    ->getStateUsing(function ($record) {
                                        if ($record->latitude_awal && $record->longitude_awal) {
                                            return "https://www.google.com/maps?q={$record->latitude_awal},{$record->longitude_awal}";
                                        }
                                        return null;
                                    })
                                    ->url(fn ($state) => $state)
                                    ->openUrlInNewTab()
                                    ->color('primary')
                                    ->icon('heroicon-o-map-pin')
                                    ->placeholder('Koordinat belum tersedia'),
                            ])
                    ])
                    ->collapsible(),

                // Section Kondisi Lokasi
                Section::make('Kondisi Lokasi')
                    ->description('Informasi kondisi di lokasi pemasangan')
                    ->icon('heroicon-o-eye')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                IconEntry::make('ada_toren')
                                    ->label('Ada Toren')
                                    ->boolean()
                                    ->getStateUsing(fn ($record) => $record->ada_toren === 'ya')
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger'),

                                IconEntry::make('ada_sumur')
                                    ->label('Ada Sumur')
                                    ->boolean()
                                    ->getStateUsing(fn ($record) => $record->ada_sumur === 'ya')
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger'),
                            ])
                    ])
                    ->collapsible(),

                // Section Biaya
                Section::make('Rincian Biaya')
                    ->description('Detail perhitungan biaya pendaftaran')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('biaya_tipe_layanan')
                                    ->label('Biaya Tipe Layanan')
                                    ->money('IDR')
                                    ->color('info'),

                                TextEntry::make('biaya_jenis_daftar')
                                    ->label('Biaya Jenis Daftar')
                                    ->money('IDR')
                                    ->color('info'),

                                TextEntry::make('biaya_tipe_pendaftaran')
                                    ->label('Biaya Tipe Pendaftaran')
                                    ->money('IDR')
                                    ->color('info'),

                                TextEntry::make('biaya_tambahan')
                                    ->label('Biaya Tambahan')
                                    ->money('IDR')
                                    ->color('warning'),

                                TextEntry::make('subtotal_biaya')
                                    ->label('Subtotal')
                                    ->money('IDR')
                                    ->color('gray'),

                                TextEntry::make('pajak.nama_pajak')
                                    ->label('Jenis Pajak')
                                    ->badge()
                                    ->placeholder('Tidak ada pajak'),

                                TextEntry::make('nilai_pajak')
                                    ->label('Nilai Pajak')
                                    ->money('IDR')
                                    ->color('warning'),

                                TextEntry::make('total_biaya_pendaftaran')
                                    ->label('Total Biaya')
                                    ->money('IDR')
                                    ->size(TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->color('success'),
                            ])
                    ])
                    ->collapsible(),

                // Section Status
                Section::make('Status Pendaftaran')
                    ->description('Status dan informasi pelanggan')
                    ->icon('heroicon-o-check-badge')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                IconEntry::make('is_pelanggan')
                                    ->label('Status Pelanggan')
                                    ->boolean()
                                    ->getStateUsing(fn ($record) => !is_null($record->id_pelanggan))
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-clock')
                                    ->trueColor('success')
                                    ->falseColor('warning'),

                                TextEntry::make('pelanggan.nomor_pelanggan')
                                    ->label('Nomor Pelanggan')
                                    ->badge()
                                    ->color('success')
                                    ->placeholder('Belum menjadi pelanggan'),
                            ])
                    ])
                    ->collapsible(),

                // Section Audit
                Section::make('Informasi Audit')
                    ->description('Riwayat pembuatan dan perubahan data')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('dibuat_oleh')
                                    ->label('Dibuat Oleh')
                                    ->icon('heroicon-o-user'),

                                TextEntry::make('dibuat_pada')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d F Y, H:i')
                                    ->icon('heroicon-o-clock'),

                                TextEntry::make('diperbarui_oleh')
                                    ->label('Diperbarui Oleh')
                                    ->icon('heroicon-o-user')
                                    ->placeholder('Belum pernah diperbarui'),

                                TextEntry::make('diperbarui_pada')
                                    ->label('Diperbarui Pada')
                                    ->dateTime('d F Y, H:i')
                                    ->icon('heroicon-o-clock')
                                    ->placeholder('Belum pernah diperbarui'),
                            ])
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
