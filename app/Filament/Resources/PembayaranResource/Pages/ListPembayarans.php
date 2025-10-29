<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPembayarans extends ListRecords
{
    protected static string $resource = PembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('report')
                ->label('Report')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->form([
                    // === PILIH JENIS REPORT ===
                    Forms\Components\Select::make('jenis_report')
                        ->label('Pilih Jenis Report')
                        ->options([
                            'user' => 'Report Per User',
                            'kas' => 'Report Per Kas',
                            'penerimaan' => 'Report Penerimaan',
                            'penerimaan-lppa-unit' => 'Report Penerimaan LPPA per Unit',
                            'bppl-pelanggan' => 'Report BPPL Pelanggan',
                            'pelunasan angsuran' => 'Report Pelunasan Angsuran',
                        ])
                        ->required()
                        ->reactive(),

                    // === REPORT PER USER ===
                    Forms\Components\Select::make('user_id')
                        ->label('Pilih User')
                        ->options([
                            '1' => 'Ahmad Fauzi',
                            '2' => 'Siti Nurhaliza',
                            '3' => 'Budi Santoso',
                            '4' => 'Dewi Lestari',
                            '5' => 'Rudi Hartono',
                        ])
                        ->searchable()
                        ->required()
                        ->visible(fn (Get $get) => $get('jenis_report') === 'user'),

                    Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('start_date_user')
                                ->label('Tanggal Mulai')
                                ->default(now())
                                ->required(),
                            Forms\Components\DatePicker::make('end_date_user')
                                ->label('Tanggal Selesai')
                                ->default(now())
                                ->required(),
                        ])
                        ->visible(fn (Get $get) => $get('jenis_report') === 'user'),

                    // === REPORT PER KAS ===
                    Forms\Components\Select::make('kas_id')
                        ->label('Pilih Kas')
                        ->options([
                            'kas1' => 'Kas Utama',
                            'kas2' => 'Kas Cabang 1',
                            'kas3' => 'Kas Cabang 2',
                            'kas4' => 'Kas Operasional',
                        ])
                        ->searchable()
                        ->required()
                        ->visible(fn (Get $get) => $get('jenis_report') === 'kas'),

                    Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('start_date_kas')
                                ->label('Tanggal Mulai')
                                ->default(now())
                                ->required(),
                            Forms\Components\DatePicker::make('end_date_kas')
                                ->label('Tanggal Selesai')
                                ->default(now())
                                ->required(),
                        ])
                        ->visible(fn (Get $get) => $get('jenis_report') === 'kas'),

                    // === REPORT PENERIMAAN ===
                    Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('start_date_penerimaan')
                                ->label('Tanggal Mulai')
                                ->default(now())
                                ->required(),
                            Forms\Components\DatePicker::make('end_date_penerimaan')
                                ->label('Tanggal Selesai')
                                ->default(now())
                                ->required(),
                        ])
                        ->visible(fn (Get $get) => $get('jenis_report') === 'penerimaan'),

                    Forms\Components\Select::make('cabang_penerimaan')
                        ->label('Cabang')
                        ->options([
                            'semua' => 'Semua Cabang',
                            'pusat' => 'Kantor Pusat',
                            'unit1' => 'Unit 1',
                            'unit2' => 'Unit 2',
                        ])
                        ->default('semua')
                        ->visible(fn (Get $get) => $get('jenis_report') === 'penerimaan'),

                    // === REPORT PENERIMAAN LPPA PER UNIT ===
                    Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('start_date_lppa_unit')
                                ->label('Tanggal Mulai')
                                ->default(now())
                                ->required(),
                            Forms\Components\DatePicker::make('end_date_lppa_unit')
                                ->label('Tanggal Selesai')
                                ->default(now())
                                ->required(),
                        ])
                        ->visible(fn (Get $get) => $get('jenis_report') === 'penerimaan-lppa-unit'),

                    Forms\Components\Select::make('unit')
                        ->label('Unit')
                        ->options([
                            'semua' => 'Semua Unit',
                            'unit1' => 'Unit 1',
                            'unit2' => 'Unit 2',
                            'unit3' => 'Unit 3',
                        ])
                        ->default('semua')
                        ->visible(fn (Get $get) => $get('jenis_report') === 'penerimaan-lppa-unit'),

                    // === REPORT BPPL PELANGGAN ===
                    Forms\Components\TextInput::make('no_pelanggan')
                        ->label('No. Pelanggan')
                        ->placeholder('Contoh: PLG001234')
                        ->required()
                        ->visible(fn (Get $get) => $get('jenis_report') === 'bppl-pelanggan'),

                    Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('start_date_bppl')
                                ->label('Tanggal Mulai')
                                ->default(now()->startOfYear())
                                ->required(),
                            Forms\Components\DatePicker::make('end_date_bppl')
                                ->label('Tanggal Selesai')
                                ->default(now())
                                ->required(),
                        ])
                        ->visible(fn (Get $get) => $get('jenis_report') === 'bppl-pelanggan'),

                    // === REPORT PELUNASAN ANGSURAN ===
                    Forms\Components\TextInput::make('no_angsuran')
                        ->label('No. Angsuran')
                        ->placeholder('Contoh: ANG-2025-001')
                        ->required()
                        ->visible(fn (Get $get) => $get('jenis_report') === 'pelunasan angsuran'),

                    Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('start_date_angsuran')
                                ->label('Tanggal Mulai')
                                ->default(now())
                                ->required(),
                            Forms\Components\DatePicker::make('end_date_angsuran')
                                ->label('Tanggal Selesai')
                                ->default(now())
                                ->required(),
                        ])
                        ->visible(fn (Get $get) => $get('jenis_report') === 'pelunasan angsuran'),
                ])
                ->action(function (array $data): void {
                    $jenis = $data['jenis_report'];

                    match ($jenis) {
                        'user' => Notification::make()
                            ->title('Export Report Per User')
                            ->body("User: {$data['user_id']}, dari {$data['start_date_user']} sampai {$data['end_date_user']}.")
                            ->success()
                            ->send(),

                        'kas' => Notification::make()
                            ->title('Export Report Per Kas')
                            ->body("Kas: {$data['kas_id']}, dari {$data['start_date_kas']} sampai {$data['end_date_kas']}.")
                            ->success()
                            ->send(),

                        'penerimaan' => Notification::make()
                            ->title('Export Report Penerimaan')
                            ->body("Dari {$data['start_date_penerimaan']} sampai {$data['end_date_penerimaan']}, cabang: {$data['cabang_penerimaan']}.")
                            ->success()
                            ->send(),

                        'penerimaan-lppa-unit' => Notification::make()
                            ->title('Export Report Penerimaan LPPA per Unit')
                            ->body("Dari {$data['start_date_lppa_unit']} sampai {$data['end_date_lppa_unit']}, unit: {$data['unit']}.")
                            ->success()
                            ->send(),

                        'bppl-pelanggan' => Notification::make()
                            ->title('Export Report BPPL Pelanggan')
                            ->body("No. Pelanggan: {$data['no_pelanggan']}, dari {$data['start_date_bppl']} sampai {$data['end_date_bppl']}.")
                            ->success()
                            ->send(),

                        'pelunasan angsuran' => Notification::make()
                            ->title('Export Report Pelunasan Angsuran')
                            ->body("No. Angsuran: {$data['no_angsuran']}, dari {$data['start_date_angsuran']} sampai {$data['end_date_angsuran']}.")
                            ->success()
                            ->send(),

                        default => null,
                    };

                    // TODO: Ganti notifikasi dengan logika export (PDF/Excel) berdasarkan $data dan $jenis
                }),
        ];
    }
}
