<?php

namespace App\Filament\Resources\SurveiResource\Pages;

use App\Filament\Resources\SurveiResource;
use App\Filament\Resources\SurveiResource\Widgets\PendaftaranMenungguSurveiWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSurveis extends ListRecords
{
    protected static string $resource = SurveiResource::class;

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All')
                ->modifyQueryUsing(fn (Builder $query) => $query), // tampilkan semua
        ];

        // Ambil semua cabang secara dinamis
        $branches = \App\Models\Cabang::orderBy('nama_cabang')->get();

        foreach ($branches as $branch) {
            $tabs['branch_' . $branch->id_cabang] = Tab::make($branch->nama_cabang)
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('pendaftaran', fn($q) => $q->where('id_cabang', $branch->id_cabang)));
        }

        return $tabs;
    }

    protected function getHeaderActions(): array
    {
         return [
            Actions\Action::make('report')
                ->label('Report')
                ->icon('heroicon-o-document-chart-bar')
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
                ->label('Tambah')
                ->icon('heroicon-s-plus')
                ->color('primary'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PendaftaranMenungguSurveiWidget::class,
        ];
    }
}
