<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\View\TablesRenderHook;
use filament\widgets\Tables\Actions\CreateAction;

class ListPendaftarans extends ListRecords
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make()
                ->label('Tambah')
                ->icon('heroicon-s-plus')
                ->color('primary'),
        ];
    }

    // public function mount(): void
    // {
    //     FilamentView::registerRenderHook(
    //         TablesRenderHook::TOOLBAR_START,
    //         function () {
    //             return Blade::render('<x-filament::button tag="a" href="{{ $link }}">Add New</x-filament::button>', [
    //                 'link' => self::$resource::getUrl('create')
    //             ]);
    //         }
    //     );

    //     parent::mount();
    // }

    protected function getHeaderWidgets(): array
    {
        return [
            PendaftaranResource\Widgets\PendaftaranStatOverview::class,
        ];
    }

     public  function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('Semua')
                ->modifyQueryUsing(fn (Builder $query) => $query), // tampilkan semua
        ];

        // Ambil semua cabang secara dinamis
        $branches = \App\Models\Cabang::orderBy('nama_cabang')->get();

        foreach ($branches as $branch) {
            $tabs['branch_' . $branch->id_cabang] = Tab::make($branch->nama_cabang)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('id_cabang', $branch->id_cabang));
        }

        return $tabs;
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
