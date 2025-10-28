<?php

namespace App\Filament\Resources\SurveiResource\Pages;

use App\Filament\Resources\SurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSurvei extends CreateRecord
{
    protected static string $resource = SurveiResource::class;

    // Override to hide relation managers on create page
    public function getRelationManagers(): array
    {
        return [];
    }

    // Mount method untuk menghandle pre-fill data dari URL
    public function mount(): void
    {
        parent::mount();
        
        // Check if we have id_pendaftaran from URL parameter
        $idPendaftaran = request()->get('id_pendaftaran');
        
        if ($idPendaftaran) {
            $pendaftaran = \App\Models\Pendaftaran::find($idPendaftaran);
            
            if ($pendaftaran) {
                // Pre-fill form dengan data pendaftaran
                $this->form->fill([
                    'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                    'latitude_terverifikasi' => $pendaftaran->latitude_awal,
                    'longitude_terverifikasi' => $pendaftaran->longitude_awal,
                    'elevasi_terverifikasi_mdpl' => $pendaftaran->elevasi_awal_mdpl,
                    'lokasi_map' => $pendaftaran->latitude_awal && $pendaftaran->longitude_awal ? [
                        'lat' => (float) $pendaftaran->latitude_awal,
                        'lng' => (float) $pendaftaran->longitude_awal
                    ] : null,
                    'tanggal_survei' => now()->format('Y-m-d'),
                    'nip_surveyor' => auth()->user()->email ?? auth()->id(),
                    'status_survei' => 'draft',
                ]);
                
                // Tampilkan notifikasi bahwa data berhasil dimuat
                \Filament\Notifications\Notification::make()
                    ->title('Data Pendaftaran Dimuat')
                    ->body("Data dari pendaftaran {$pendaftaran->nomor_registrasi} berhasil dimuat ke form survei")
                    ->success()
                    ->send();
            }
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default values
        $data['dibuat_oleh'] = auth()->id();
        $data['dibuat_pada'] = now();

        // Set Trial section fields (these are auto-managed by system)
        $data['tanggal_survei'] = now()->format('Y-m-d');
        $data['nip_surveyor'] = auth()->user()->email ?? auth()->id();
        $data['status_survei'] = 'draft';

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Generate UUID for id_survei
        $data['id_survei'] = \Illuminate\Support\Str::uuid()->toString();

        $survei = static::getModel()::create($data);
        
        // Jika ada id_pendaftaran, lakukan pemrosesan additional
        if (!empty($data['id_pendaftaran'])) {
            $pendaftaran = \App\Models\Pendaftaran::find($data['id_pendaftaran']);
            if ($pendaftaran) {
                // 1. Update status pendaftaran menjadi 'survei'
                $pendaftaran->update(['status_pendaftaran' => 'survei']);
                
                // 2. Jika pendaftaran belum punya pelanggan, buat pelanggan baru
                if (!$pendaftaran->id_pelanggan) {
                    try {
                        $pelanggan = \App\Models\Pelanggan::create([
                            'nomor_pelanggan' => \App\Models\Pelanggan::generateSimpleNomorPelanggan(),
                            'nama_pelanggan' => $pendaftaran->nama_pemohon,
                            'alamat' => $pendaftaran->alamat_pemasangan,
                            'nomor_hp' => $pendaftaran->no_hp_pemohon,
                            'kelurahan' => $pendaftaran->kelurahan?->nama_kelurahan ?? null,
                            'kecamatan' => $pendaftaran->kelurahan?->kecamatan?->nama_kecamatan ?? null,
                            'jenis_identitas' => $pendaftaran->jenis_identitas,
                            'nomor_identitas' => $pendaftaran->nomor_identitas,
                            'status_pelanggan' => 'calon_pelanggan',
                            'latitude' => $pendaftaran->latitude_awal,
                            'longitude' => $pendaftaran->longitude_awal,
                            'elevasi' => $pendaftaran->elevasi_awal_mdpl,
                            'status_gis' => 'belum_divalidasi',
                            'status_historis' => 'aktif',
                            'dibuat_oleh' => auth()->user()->name ?? 'System',
                            'dibuat_pada' => now(),
                        ]);

                        // Update pendaftaran dengan id_pelanggan
                        $pendaftaran->update(['id_pelanggan' => $pelanggan->id_pelanggan]);
                        
                        // Update survei dengan id_pelanggan
                        $survei->update(['id_pelanggan' => $pelanggan->id_pelanggan]);

                        \Filament\Notifications\Notification::make()
                            ->title('Pelanggan Baru Dibuat')
                            ->body("Pelanggan '{$pelanggan->nama_pelanggan}' berhasil dibuat otomatis")
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal Membuat Pelanggan')
                            ->body('Terjadi error saat membuat pelanggan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                } else {
                    // Jika sudah ada pelanggan, update survei dengan id_pelanggan
                    $survei->update(['id_pelanggan' => $pendaftaran->id_pelanggan]);
                }
                
                \Filament\Notifications\Notification::make()
                    ->title('Status Pendaftaran Diperbarui')
                    ->body("Status pendaftaran {$pendaftaran->nomor_registrasi} berhasil diperbarui menjadi 'survei'")
                    ->success()
                    ->send();
            }
        }
        
        // Update scoring after creation
        $this->updateSurveiScoring($survei);

        return $survei;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    private function updateSurveiScoring($survei): void
    {
        try {
            // Gunakan method dari model untuk menghitung scoring
            $survei->updateHasilSurvei();
            
            // Notification untuk debug
            \Filament\Notifications\Notification::make()
                ->title('Scoring Updated')
                ->body("Skor total: {$survei->skor_total}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Error Update Scoring')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function afterCreate(): void
    {
        // Send workflow notifications
        $notificationService = app(\App\Services\WorkflowNotificationService::class);
        $notificationService->surveiCreated($this->record);
    }
}
