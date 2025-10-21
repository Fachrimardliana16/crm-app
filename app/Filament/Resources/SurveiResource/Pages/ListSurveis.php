<?php

namespace App\Filament\Resources\SurveiResource\Pages;

use App\Filament\Resources\SurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveis extends ListRecords
{
    protected static string $resource = SurveiResource::class;

    protected function getHeaderActions(): array
    {
         return [
            Actions\Action::make('report')
                ->label('Report Survei')
                ->color('danger')
                ->modalHeading('Filter Laporan Pendaftaran')
                ->modalSubmitActionLabel('Buat Laporan')
                ->modalDescription('Sesuaikan filter yang anda butuhkan')
                ->form([
                    \Filament\Forms\Components\Section::make('Periode Tanggal')
                        ->schema([
                            \Filament\Forms\Components\Grid::make(2)
                                ->schema([
                                    \Filament\Forms\Components\DatePicker::make('start_date')
                                        ->label('Tanggal Mulai')
                                        ->placeholder('Pilih tanggal mulai')
                                        ->default(now()->startOfMonth())
                                        ->helperText('Tanggal default adalah awal bulan ini.')
                                        ->required()
                                        ->prefixIcon('heroicon-o-calendar')
                                        ->displayFormat('d/m/Y')
                                        ->native(false), // Use Filament's datepicker for consistency
                                    \Filament\Forms\Components\DatePicker::make('end_date')
                                        ->label('Tanggal Selesai')
                                        ->placeholder('Pilih tanggal selesai')
                                        ->prefixIcon('heroicon-o-calendar')
                                        ->default(now()->endOfMonth())
                                        ->helperText('Tanggal default adalah akhir bulan ini.')
                                        ->required()
                                        ->displayFormat('d/m/Y')
                                        ->native(false),
                                ]),
                        ])
                        ->collapsible() // Allow collapsing for better space management
                        ->icon('heroicon-o-calendar'),
                ])// Add icon for visual cue
                ->action(function (array $data) {
                    $this->generateReportSurvei($data);
                }),
            Actions\CreateAction::make()
                ->label('Tambah Baru')
                ->color('success'),
        ];
    }
}
