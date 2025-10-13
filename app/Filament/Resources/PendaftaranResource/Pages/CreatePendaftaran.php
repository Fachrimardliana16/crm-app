<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Exception;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate unique registration number
        $data['nomor_registrasi'] = $this->generateUniqueNomorRegistrasi($data);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return parent::handleRecordCreation($data);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // If still duplicate, try regenerating the number
            $data['nomor_registrasi'] = $this->generateUniqueNomorRegistrasi($data, true);

            try {
                return parent::handleRecordCreation($data);
            } catch (Exception $e) {
                Notification::make()
                    ->title('Gagal Menyimpan Pendaftaran')
                    ->body('Terjadi kesalahan saat menyimpan data pendaftaran. Silakan coba lagi.')
                    ->danger()
                    ->send();

                throw $e;
            }
        } catch (Exception $e) {
            Notification::make()
                ->title('Gagal Menyimpan Pendaftaran')
                ->body('Terjadi kesalahan saat menyimpan data pendaftaran. Silakan coba lagi.')
                ->danger()
                ->send();

            throw $e;
        }
    }

    private function generateUniqueNomorRegistrasi(array $data, bool $forceNew = false): string
    {
        $cabang = \App\Models\Cabang::find($data['id_cabang']);
        $tanggalDaftar = \Carbon\Carbon::parse($data['tanggal_daftar']);

        if (!$cabang) {
            throw new Exception('Cabang tidak ditemukan');
        }

        $bulanRomawi = $this->convertToRoman($tanggalDaftar->month);
        $tahunRomawi = $this->convertToRoman($tanggalDaftar->year);

        // Get the highest existing sequence for this cabang, month, and year
        $existingRecords = \App\Models\Pendaftaran::where('nomor_registrasi', 'like',
            $cabang->kode_cabang . '/%/' . $bulanRomawi . '/' . $tahunRomawi)
            ->get();

        $maxSequence = 0;
        foreach ($existingRecords as $record) {
            $parts = explode('/', $record->nomor_registrasi);
            if (count($parts) >= 2) {
                $sequence = $this->convertFromRoman($parts[1]);
                if ($sequence > $maxSequence) {
                    $maxSequence = $sequence;
                }
            }
        }

        // If forcing new or if there are existing records, increment
        if ($forceNew || $maxSequence > 0) {
            $maxSequence++;
        } else {
            $maxSequence = 1;
        }

        $nomorUrut = $this->convertToRoman($maxSequence);

        return $cabang->kode_cabang . '/' . $nomorUrut . '/' . $bulanRomawi . '/' . $tahunRomawi;
    }

    private function convertToRoman(int $number): string
    {
        $map = [
            1000 => 'M', 900 => 'CM', 500 => 'D', 400 => 'CD',
            100 => 'C', 90 => 'XC', 50 => 'L', 40 => 'XL',
            10 => 'X', 9 => 'IX', 5 => 'V', 4 => 'IV', 1 => 'I'
        ];

        $result = '';
        foreach ($map as $value => $roman) {
            while ($number >= $value) {
                $result .= $roman;
                $number -= $value;
            }
        }

        return $result;
    }

    private function convertFromRoman(string $roman): int
    {
        $map = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];

        $result = 0;
        $i = 0;

        foreach ($map as $romanNumeral => $value) {
            while (substr($roman, $i, strlen($romanNumeral)) === $romanNumeral) {
                $result += $value;
                $i += strlen($romanNumeral);
            }
        }

        return $result;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        // Return null to disable default notification
        return null;
    }

    protected function afterCreate(): void
    {
        // Create survei record automatically after pendaftaran is created
        $this->createSurveiRecord();

        // Inject JavaScript to show modal popup
        $recordId = $this->record->id_pendaftaran;
        $nomorRegistrasi = $this->record->nomor_registrasi;
        $viewUrl = $this->getResource()::getUrl('view', ['record' => $this->record]);
        $printUrl = route('faktur.pembayaran', ['pendaftaran' => $recordId]);

        $this->js("
            // Create modal HTML
            const modalHtml = `
                <div id='success-modal' class='fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50' style='z-index: 9999;'>
                    <div class='bg-white dark:bg-gray-900 rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all'>
                        <div class='p-6'>
                            <!-- Header with success icon -->
                            <div class='flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 dark:bg-green-900 rounded-full'>
                                <svg class='w-6 h-6 text-green-600 dark:text-green-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class='text-lg font-semibold text-gray-900 dark:text-gray-100 text-center mb-2'>
                                Pendaftaran Berhasil Dibuat
                            </h3>

                            <!-- Message -->
                            <p class='text-gray-600 dark:text-gray-400 text-center mb-6'>
                                Data pendaftaran telah berhasil disimpan dengan nomor registrasi: <strong>{$nomorRegistrasi}</strong><br>
                                <small class='text-green-600 dark:text-green-400'>✅ Data survei otomatis dibuat dan siap dijadwalkan</small>
                            </p>

                            <!-- Action buttons -->
                            <div class='flex flex-col space-y-3'>
                                <a href='{$viewUrl}'
                                   class='w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-center transition-colors font-medium text-decoration-none'>
                                    👁️ Lihat Detail Pendaftaran
                                </a>

                                <a href='{$printUrl}'
                                   target='_blank'
                                   class='w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-center transition-colors font-medium text-decoration-none'>
                                    🖨️ Print Faktur
                                </a>

                                <button onclick='closeSuccessModal()'
                                        class='w-full px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-center transition-colors font-medium'>
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Function to close modal
            window.closeSuccessModal = function() {
                const modal = document.getElementById('success-modal');
                if (modal) {
                    modal.remove();
                }
            };

            // Close modal on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSuccessModal();
                }
            });

            // Close modal when clicking outside
            document.getElementById('success-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeSuccessModal();
                }
            });
        ");
    }

    /**
     * Create survei record automatically after pendaftaran is created
     */
    private function createSurveiRecord(): void
    {
        try {
            \App\Models\Survei::create([
                'id_survei' => \Illuminate\Support\Str::uuid()->toString(),
                'id_pendaftaran' => $this->record->id_pendaftaran,
                'id_pelanggan' => $this->record->id_pelanggan,
                'status_survei' => 'draft',
                'dibuat_oleh' => auth()->id(),
                'dibuat_pada' => now(),
            ]);
        } catch (Exception $e) {
            // Log error but don't break the flow
            \Illuminate\Support\Facades\Log::error('Failed to create survei record: ' . $e->getMessage());
        }
    }
}
