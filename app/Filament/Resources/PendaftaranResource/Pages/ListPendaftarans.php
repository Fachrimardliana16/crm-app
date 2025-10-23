<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ListPendaftarans extends ListRecords
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mou')
                ->label('Unduh MOU')
                ->color('info'),
            Actions\Action::make('report')
                ->label('Report')
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
                        ->icon('heroicon-o-calendar'), // Add icon for visual cue

                    // Grouping filter fields in a separate section with a 2-column grid for balance
                    \Filament\Forms\Components\Section::make('Filter Pencarian')
                        ->schema([
                            \Filament\Forms\Components\Grid::make(2) // 2 columns for better distribution
                                ->schema([
                                    \Filament\Forms\Components\Select::make('cabang_unit')
                                        ->label('Cabang/Unit')
                                        ->placeholder('Pilih cabang/unit')
                                        ->helperText('Filter berdasarkan cabang/unit (opsional).')
                                        ->options(function () {
                                            return \App\Models\Cabang::pluck('nama_cabang', 'id_cabang')->toArray();
                                        })
                                        ->multiple() // Enable multiple selection
                                        ->searchable()
                                        ->preload()
                                        ->allowHtml() // Allow HTML in options for better formatting if needed
                                        ->extraInputAttributes([
                                            'data-actions' => json_encode([
                                                'selectAll' => true,
                                                'deselectAll' => true,
                                            ]), // Custom attribute to enable Select All/Deselect All
                                        ])
                                        ->columnSpan(1),
                                     \Filament\Forms\Components\Select::make('kecamatan')
                                        ->label('Kecamatan')
                                        ->placeholder('Pilih kecamatan')
                                        ->helperText('Filter berdasarkan kecamatan (opsional).')
                                        ->options(function () {
                                            return \App\Models\Kecamatan::pluck('nama_kecamatan', 'id_kecamatan')->toArray();
                                        })
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->allowHtml()
                                        ->extraInputAttributes([
                                            'data-actions' => json_encode([
                                                'selectAll' => true,
                                                'deselectAll' => true,
                                            ]),
                                        ])
                                        ->reactive() // Make the field reactive to trigger updates
                                        ->columnSpan(1),
                                    \Filament\Forms\Components\Select::make('kelurahan')
                                        ->label('Kelurahan')
                                        ->placeholder('Pilih kelurahan')
                                        ->helperText('Filter berdasarkan kelurahan (opsional).')
                                        ->options(function (\Filament\Forms\Get $get) {
                                            $kecamatanIds = $get('kecamatan') ?? [];
                                            if (empty($kecamatanIds)) {
                                                return \App\Models\Kelurahan::pluck('nama_kelurahan', 'id_kelurahan')->toArray();
                                            }
                                            return \App\Models\Kelurahan::whereIn('id_kecamatan', $kecamatanIds)
                                                ->pluck('nama_kelurahan', 'id_kelurahan')
                                                ->toArray();
                                        })
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->allowHtml()
                                        ->extraInputAttributes([
                                            'data-actions' => json_encode([
                                                'selectAll' => true,
                                                'deselectAll' => true,
                                            ]),
                                        ])
                                        ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                            // Optional: Reset kelurahan if needed, but usually handled by options
                                            if (empty($state)) {
                                                $set('kelurahan', []);
                                            }
                                        })
                                        ->reactive() // Make the field reactive to respond to changes
                                        ->columnSpan(1),
                                    \Filament\Forms\Components\Select::make('tipe_pelayanan')
                                        ->label('Tipe Pelayanan')
                                        ->placeholder('Pilih tipe pelayanan')
                                        ->helperText('Filter berdasarkan tipe pelayanan (opsional).')
                                        ->options(function () {
                                            return \App\Models\TipeLayanan::pluck('nama_tipe_layanan', 'id_tipe_layanan')->toArray();
                                        })
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->allowHtml()
                                        ->extraInputAttributes([
                                            'data-actions' => json_encode([
                                                'selectAll' => true,
                                                'deselectAll' => true,
                                            ]),
                                        ])
                                        ->columnSpan(1),
                                    \Filament\Forms\Components\Select::make('jenis_daftar')
                                        ->label('Jenis Pendaftaran')
                                        ->placeholder('Pilih jenis pendaftaran')
                                        ->helperText('Filter berdasarkan jenis pendaftaran (opsional).')
                                        ->options(function () {
                                            return \App\Models\JenisDaftar::pluck('nama_jenis_daftar', 'id_jenis_daftar')->toArray();
                                        })
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->allowHtml()
                                        ->extraInputAttributes([
                                            'data-actions' => json_encode([
                                                'selectAll' => true,
                                                'deselectAll' => true,
                                            ]),
                                        ])
                                        ->columnSpan(1),
                                    \Filament\Forms\Components\Select::make('tipe_pendaftaran')
                                        ->label('Tipe Pendaftaran')
                                        ->placeholder('Pilih tipe pendaftaran')
                                        ->helperText('Filter berdasarkan tipe pendaftaran (opsional).')
                                        ->options(function () {
                                            return \App\Models\TipePendaftaran::pluck('nama_tipe_pendaftaran', 'id_tipe_pendaftaran')->toArray();
                                        })
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->allowHtml()
                                        ->extraInputAttributes([
                                            'data-actions' => json_encode([
                                                'selectAll' => true,
                                                'deselectAll' => true,
                                            ]),
                                        ])
                                        ->columnSpan(1), // Full width for the last field to balance the layout
                                ]),
                        ])
                        ->collapsible()
                        ->icon('heroicon-o-funnel'), // Filter icon for visual clarity
                ])
                ->action(function (array $data) {
                    $this->generateReportPendaftaran($data);
                }),
            Actions\CreateAction::make()
                ->label('Tambah Baru')
                ->color('success'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua Pendaftaran')
                ->icon('heroicon-o-list-bullet')
                ->badge(fn () => \App\Models\Pendaftaran::count()),

            'belum_pelanggan' => Tab::make('Belum Jadi Pelanggan')
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('id_pelanggan'))
                ->badge(fn () => \App\Models\Pendaftaran::whereNull('id_pelanggan')->count())
                ->badgeColor('warning'),

            'sudah_pelanggan' => Tab::make('Sudah Jadi Pelanggan')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('id_pelanggan'))
                ->badge(fn () => \App\Models\Pendaftaran::whereNotNull('id_pelanggan')->count())
                ->badgeColor('success'),

            'bulan_ini' => Tab::make('Bulan Ini')
                ->icon('heroicon-o-calendar-days')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereMonth('tanggal_daftar', now()->month)
                    ->whereYear('tanggal_daftar', now()->year))
                ->badge(fn () => \App\Models\Pendaftaran::whereMonth('tanggal_daftar', now()->month)
                    ->whereYear('tanggal_daftar', now()->year)
                    ->count())
                ->badgeColor('info'),

            'hari_ini' => Tab::make('Hari Ini')
                ->icon('heroicon-o-sun')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('tanggal_daftar', now()))
                ->badge(fn () => \App\Models\Pendaftaran::whereDate('tanggal_daftar', now())->count())
                ->badgeColor('primary'),
        ];
    }

    public function generateReportPendaftaran(array $data)
    {
        try {
            // Validate dates
            $startDate = Carbon::parse($data['start_date'])->startOfDay();
            $endDate = Carbon::parse($data['end_date'])->endOfDay();

            // Build query parameters for URL
            $queryParams = [
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ];

            // Add optional filters if they exist
            if (!empty($data['cabang_unit'])) {
                $queryParams['cabang_unit'] = $data['cabang_unit'];
            }

            if (!empty($data['kecamatan'])) {
                $queryParams['kecamatan'] = $data['kecamatan'];
            }

            if (!empty($data['kelurahan'])) {
                $queryParams['kelurahan'] = $data['kelurahan'];
            }

            if (!empty($data['tipe_pelayanan'])) {
                $queryParams['tipe_pelayanan'] = $data['tipe_pelayanan'];
            }

            if (!empty($data['jenis_daftar'])) {
                $queryParams['jenis_daftar'] = $data['jenis_daftar'];
            }

            if (!empty($data['tipe_pendaftaran'])) {
                $queryParams['tipe_pendaftaran'] = $data['tipe_pendaftaran'];
            }

            // Build the URL
            $downloadUrl = route('reports.pendaftaran.pdf', $queryParams);

            // Show success notification with download link
            Notification::make()
                ->title('Laporan siap diunduh')
                ->success()
                ->body('Klik untuk mengunduh laporan PDF')
                ->actions([
                    \Filament\Notifications\Actions\Action::make('download')
                        ->label('Download PDF')
                        ->url($downloadUrl)
                        ->openUrlInNewTab()
                        ->button()
                ])
                ->persistent()
                ->send();

            // Also redirect to download URL in new tab using JavaScript
            $this->js("window.open('{$downloadUrl}', '_blank');");

        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal membuat laporan')
                ->danger()
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->send();
        }
    }
}
