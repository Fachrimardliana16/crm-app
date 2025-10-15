<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Actions\Action as PageAction;
use Filament\Notifications\Notification;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\TagihanBulanan;
use Illuminate\Support\Facades\Auth;

class PembayaranPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Pembayaran Rekening';
    protected static ?string $title = 'Pembayaran Rekening Air';
    protected static string $view = 'filament.pages.pembayaran-page';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 1;

    public ?array $data = [];
    public ?array $pelangganData = [];
    public ?array $tagihanData = [];
    public ?array $allTagihan = [];
    public bool $showRekeningData = false;

    public function mount(): void
    {
        try {
            $this->form->fill();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Inisialisasi')
                ->body('Terjadi kesalahan saat memuat halaman: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            PageAction::make('cariPelanggan')
                ->label('Cari Pelanggan')
                ->icon('heroicon-o-users')
                ->color('warning')
                ->modal()
                ->modalHeading('Cari Pelanggan')
                ->modalWidth('md')
                ->form([
                    TextInput::make('modal_nama')
                        ->label('Nama Pelanggan')
                        ->placeholder('Masukkan nama pelanggan'),
                    TextInput::make('modal_alamat')
                        ->label('Alamat')
                        ->placeholder('Masukkan alamat'),
                ])
                ->modalSubmitActionLabel('Cari')
                ->action(function (array $data) {
                    $this->cariPelangganModal($data);
                }),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form Pencarian
                Section::make('Pencarian Pelanggan')
                    ->schema([
                        // Input Nomor Pelanggan dengan Button Cari
                        TextInput::make('search_input')
                            ->label('Nomor Pelanggan')
                            ->placeholder('Masukan nomor pelanggan')
                            ->live(debounce: 500)
                            ->suffixAction(
                                Action::make('cariManual')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->color('warning')
                                    ->size('lg')
                                    ->action('cariManual')
                            )
                            ->afterStateUpdated(function ($state) {
                                if (strlen($state) >= 3) {
                                    $this->cariPelangganOtomatis($state);
                                } else {
                                    $this->resetPelangganData();
                                }
                            }),
                    ]),

                // Layout 2 Kolom: Detail Rekening | Pembayaran
                Grid::make(2)->schema([
                    // Detail Rekening
                    Section::make('Detail Rekening')
                        ->schema([
                            Placeholder::make('customer_info')
                                ->content(fn() => $this->getDetailRekeningCard())
                        ])
                        ->visible(fn() => $this->showRekeningData),

                    // Pembayaran
                    Section::make('Pembayaran')
                        ->schema([
                            // Pilih Tagihan
                            Radio::make('selected_tagihan')
                                ->label('Pilih Tagihan')
                                ->options(fn() => $this->getTagihanOptions())
                                ->descriptions(fn() => $this->getTagihanDescriptions())
                                ->live()
                                ->afterStateUpdated(function ($state) {
                                    $this->loadSelectedTagihan($state);
                                })
                                ->visible(fn() => !empty($this->allTagihan)),

                            // Metode Pembayaran
                            Radio::make('metode_pembayaran')
                                ->label('Metode Pembayaran')
                                ->options([
                                    'cash' => '💵 Cash/Tunai',
                                    'qris' => '📱 QRIS',
                                    'debit' => '💳 Kartu Debit',
                                    'credit' => '💎 Kartu Kredit',
                                ])
                                ->default('cash')
                                ->live()
                                ->inline()
                                ->visible(fn() => !empty($this->data['selected_tagihan'])),

                            // Jenis Pembayaran
                            Radio::make('jenis_bayar')
                                ->label('Jenis Pembayaran')
                                ->options([
                                    'lunas' => '✅ Bayar Lunas',
                                    'sebagian' => '⚡ Bayar Sebagian',
                                ])
                                ->default('lunas')
                                ->live()
                                ->inline()
                                ->visible(fn() => !empty($this->data['selected_tagihan'])),

                            // Jumlah bayar sebagian
                            TextInput::make('jumlah_bayar_custom')
                                ->label('Jumlah yang Dibayar')
                                ->numeric()
                                ->prefix('Rp')
                                ->placeholder('0')
                                ->visible(fn() => ($this->data['jenis_bayar'] ?? '') === 'sebagian')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function () {
                                    $this->hitungKembalian();
                                }),

                            // Input Cash
                            TextInput::make('uang_diterima')
                                ->label('💸 Uang yang Diterima')
                                ->numeric()
                                ->prefix('Rp')
                                ->placeholder('Masukkan jumlah uang...')
                                ->visible(fn() => ($this->data['metode_pembayaran'] ?? '') === 'cash' && !empty($this->data['selected_tagihan']))
                                ->live(onBlur: true)
                                ->afterStateUpdated(function () {
                                    $this->hitungKembalian();
                                }),

                            // Summary Pembayaran
                            Placeholder::make('payment_summary')
                                ->content(fn() => $this->getPaymentSummaryCard())
                                ->visible(fn() => !empty($this->data['selected_tagihan'])),

                            // Tombol Proses
                            Actions::make([
                                Action::make('prosesTransaksi')
                                    ->label('🚀 PROSES PEMBAYARAN')
                                    ->color('success')
                                    ->size('xl')
                                    ->action('prosesPembayaran')
                                    ->visible(fn() => !empty($this->data['selected_tagihan']))
                                    ->requiresConfirmation()
                                    ->modalHeading('Konfirmasi Pembayaran')
                                    ->modalDescription(fn() => $this->getConfirmationMessage())
                                    ->modalSubmitActionLabel('Ya, Proses Pembayaran'),
                            ])->fullWidth(),
                        ])
                        ->visible(fn() => $this->showRekeningData),
                ]),
            ])
            ->statePath('data');
    }

    public function cariManual(): void
    {
        try {
            $search = $this->data['search_input'] ?? '';

            if (empty($search)) {
                Notification::make()
                    ->title('Error Pencarian')
                    ->body('Silakan masukkan nomor pelanggan untuk mencari')
                    ->warning()
                    ->send();
                return;
            }

            if (strlen($search) < 3) {
                Notification::make()
                    ->title('Error Pencarian')
                    ->body('Minimal 3 karakter untuk pencarian')
                    ->warning()
                    ->send();
                return;
            }

            $this->cariPelangganOtomatis($search);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->handleDatabaseError($e);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Sistem')
                ->body('Terjadi kesalahan saat mencari pelanggan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function cariPelangganModal(array $data): void
    {
        try {
            $nama = $data['modal_nama'] ?? '';
            $alamat = $data['modal_alamat'] ?? '';

            // Validasi input minimal
            if (empty($nama) && empty($alamat)) {
                Notification::make()
                    ->title('Error Pencarian')
                    ->body('Silakan masukkan nama atau alamat untuk mencari pelanggan')
                    ->warning()
                    ->send();
                return;
            }

            $query = Pelanggan::query();

            if ($nama) {
                $query->where('nama_pelanggan', 'like', '%' . $nama . '%');
            }

            if ($alamat) {
                $query->where('alamat', 'like', '%' . $alamat . '%');
            }

            $pelanggan = $query->first();

            if ($pelanggan) {
                $this->loadPelangganData($pelanggan);
                $this->data['search_input'] = $pelanggan->nama_pelanggan;

                Notification::make()
                    ->title('Pelanggan Ditemukan!')
                    ->body("Data {$pelanggan->nama_pelanggan} berhasil dimuat")
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Pelanggan Tidak Ditemukan')
                    ->body('Tidak ada pelanggan yang sesuai dengan kriteria pencarian')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Sistem')
                ->body('Terjadi kesalahan saat mencari pelanggan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function cariPelangganOtomatis(string $search): void
    {
        try {
            if (empty($search) || strlen($search) < 3) {
                $this->resetPelangganData();
                return;
            }

            // Cari berdasarkan nomor pelanggan
            $pelanggan = Pelanggan::where(function ($query) use ($search) {
                $query->where('nomor_pelanggan', 'like', '%' . $search . '%')
                      ->orWhere('nama_pelanggan', 'like', '%' . $search . '%');
            })->first();

            if ($pelanggan) {
                $this->loadPelangganData($pelanggan);

                Notification::make()
                    ->title('Pelanggan Ditemukan!')
                    ->body("Data {$pelanggan->nama_pelanggan} berhasil dimuat")
                    ->success()
                    ->send();
            } else {
                $this->resetPelangganData();

                // Hanya tampilkan notifikasi jika pencarian cukup spesifik
                if (strlen($search) >= 5) {
                    Notification::make()
                        ->title('Pelanggan Tidak Ditemukan')
                        ->body('Tidak ada pelanggan yang sesuai dengan pencarian')
                        ->warning()
                        ->send();
                }
            }
        } catch (\Exception $e) {
            $this->resetPelangganData();

            Notification::make()
                ->title('Error Sistem')
                ->body('Terjadi kesalahan saat mencari pelanggan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function getDetailRekeningCard(): string
    {
        if (empty($this->pelangganData)) {
            return '<div class="text-center p-8 text-gray-500">
                        <div class="text-4xl mb-2">👤</div>
                        <p>Belum ada data pelanggan</p>
                        <p class="text-sm">Gunakan form pencarian di atas</p>
                    </div>';
        }

        $tagihan = $this->tagihanData;
        $content = "
            <div class='space-y-6'>
                <!-- Info Pelanggan -->
                <div class='bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500'>
                    <h3 class='text-lg font-bold text-blue-900 mb-2'>{$this->pelangganData['nama_pelanggan']}</h3>
                    <p class='text-blue-700 mb-1'>No. Pelanggan: <strong>{$this->pelangganData['nomor_pelanggan']}</strong></p>
                    <p class='text-blue-600 text-sm'>{$this->pelangganData['alamat']}</p>
                </div>";

        if (!empty($tagihan)) {
            $jatuhTempo = \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->format('d M Y');
            $isOverdue = \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->isPast();
            $statusColor = $isOverdue ? 'red' : 'green';
            $statusText = $isOverdue ? '🔴 TERLAMBAT' : '🟢 NORMAL';

            $content .= "
                <!-- Detail Tagihan Terpilih -->
                <div class='bg-white border rounded-lg overflow-hidden'>
                    <div class='bg-gray-50 px-4 py-3 border-b'>
                        <div class='flex justify-between items-center'>
                            <h4 class='font-semibold text-gray-800'>Periode {$tagihan['periode_tagihan']}</h4>
                            <span class='text-{$statusColor}-600 font-bold text-sm'>{$statusText}</span>
                        </div>
                        <p class='text-sm text-gray-600'>Jatuh tempo: {$jatuhTempo}</p>
                    </div>

                    <div class='p-4'>
                        <div class='grid grid-cols-2 gap-4 mb-4'>
                            <div class='text-center bg-blue-50 p-3 rounded'>
                                <div class='text-xl font-bold text-blue-600'>{$tagihan['pemakaian_air']}</div>
                                <div class='text-sm text-blue-500'>m³ air</div>
                            </div>
                            <div class='text-center bg-green-50 p-3 rounded'>
                                <div class='text-xl font-bold text-green-600'>Rp " . number_format($tagihan['total_tagihan'], 0, ',', '.') . "</div>
                                <div class='text-sm text-green-500'>Total tagihan</div>
                            </div>
                        </div>

                        <div class='space-y-2 text-sm'>
                            <div class='flex justify-between'><span>Biaya Pemakaian:</span><span>Rp " . number_format($tagihan['biaya_pemakaian'], 0, ',', '.') . "</span></div>
                            <div class='flex justify-between'><span>Biaya Beban:</span><span>Rp " . number_format($tagihan['biaya_beban'] ?? 0, 0, ',', '.') . "</span></div>
                            <div class='flex justify-between'><span>Biaya Admin:</span><span>Rp " . number_format($tagihan['biaya_administrasi'], 0, ',', '.') . "</span></div>";

            if (($tagihan['biaya_denda'] ?? 0) > 0) {
                $content .= "<div class='flex justify-between text-red-600'><span>Denda:</span><span>Rp " . number_format($tagihan['biaya_denda'], 0, ',', '.') . "</span></div>";
            }

            $content .= "
                            <hr class='my-2'>
                            <div class='flex justify-between font-bold text-lg'><span>Total:</span><span>Rp " . number_format($tagihan['total_tagihan'], 0, ',', '.') . "</span></div>
                        </div>
                    </div>
                </div>";
        }

        $content .= "</div>";
        return $content;
    }

    public function scanQRCode(): void
    {
        Notification::make()
            ->title('Fitur Scan QR Code')
            ->body('Fitur scan QR code akan segera tersedia!')
            ->info()
            ->send();
    }

    public function resetPelangganData(): void
    {
        $this->pelangganData = [];
        $this->tagihanData = [];
        $this->allTagihan = [];
        $this->showRekeningData = false;
        $this->data['selected_tagihan'] = null;
        $this->data['search_input'] = null;
    }

    private function loadPelangganData(Pelanggan $pelanggan): void
    {
        try {
            $this->pelangganData = $pelanggan->toArray();

            // Load semua tagihan yang belum lunas
            $this->allTagihan = TagihanBulanan::where('id_pelanggan', $pelanggan->id_pelanggan)
                ->where('status_pembayaran', '!=', 'lunas')
                ->orderBy('periode_tagihan', 'desc')
                ->get()
                ->toArray();

            if (!empty($this->allTagihan)) {
                $this->showRekeningData = true;
                // Auto select tagihan terbaru
                $this->data['selected_tagihan'] = $this->allTagihan[0]['id_tagihan_bulanan'];
                $this->loadSelectedTagihan($this->allTagihan[0]['id_tagihan_bulanan']);
            } else {
                $this->showRekeningData = false;

                Notification::make()
                    ->title('Tidak Ada Tagihan')
                    ->body('Tidak ada tagihan yang belum lunas untuk pelanggan ini')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            $this->resetPelangganData();

            Notification::make()
                ->title('Error Memuat Data')
                ->body('Terjadi kesalahan saat memuat data pelanggan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function loadSelectedTagihan(string $tagihanId): void
    {
        try {
            $tagihan = collect($this->allTagihan)->firstWhere('id_tagihan_bulanan', $tagihanId);

            if ($tagihan) {
                $this->tagihanData = $tagihan;
                $this->data['total_tagihan'] = $tagihan['total_tagihan'];
                $this->data['jumlah_bayar'] = $tagihan['total_tagihan'];
            } else {
                Notification::make()
                    ->title('Error Tagihan')
                    ->body('Tagihan yang dipilih tidak ditemukan')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Memuat Tagihan')
                ->body('Terjadi kesalahan saat memuat tagihan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getTagihanOptions(): array
    {
        $options = [];
        foreach ($this->allTagihan as $tagihan) {
            $options[$tagihan['id_tagihan_bulanan']] =
                "Periode {$tagihan['periode_tagihan']} - Rp " . number_format($tagihan['total_tagihan'], 0, ',', '.');
        }
        return $options;
    }

    public function getTagihanDescriptions(): array
    {
        $descriptions = [];
        foreach ($this->allTagihan as $tagihan) {
            $jatuhTempo = \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->format('d/m/Y');
            $isOverdue = \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->isPast();
            $status = $isOverdue ? '🔴 Terlambat' : '🟢 Normal';

            $descriptions[$tagihan['id_tagihan_bulanan']] =
                "Jatuh tempo: {$jatuhTempo} | Pemakaian: {$tagihan['pemakaian_air']} m³ | Status: {$status}";
        }
        return $descriptions;
    }

    private function getCustomerInfoCard(): string
    {
        if (empty($this->pelangganData)) {
            return '<div class="text-center p-4 text-gray-500">Belum ada data pelanggan</div>';
        }

        return "
            <div class='bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-lg border-l-4 border-blue-500'>
                <div class='grid grid-cols-1 md:grid-cols-2 gap-4'>
                    <div>
                        <h3 class='text-lg font-bold text-blue-900'>{$this->pelangganData['nama_pelanggan']}</h3>
                        <p class='text-blue-700'>No. Pelanggan: <strong>{$this->pelangganData['nomor_pelanggan']}</strong></p>
                        <p class='text-blue-600'>{$this->pelangganData['alamat']}</p>
                    </div>
                    <div class='text-right'>
                        <span class='inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium'>
                            {$this->pelangganData['status_pelanggan']}
                        </span>
                    </div>
                </div>
            </div>
        ";
    }

    private function getTagihanDetailCard(): string
    {
        if (empty($this->tagihanData)) {
            return '<div class="text-center p-4 text-gray-500">Pilih tagihan terlebih dahulu</div>';
        }

        $tagihan = $this->tagihanData;
        $jatuhTempo = \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->format('d M Y');
        $isOverdue = \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->isPast();
        $statusColor = $isOverdue ? 'red' : 'green';
        $statusText = $isOverdue ? '🔴 TERLAMBAT' : '🟢 NORMAL';

        return "
            <div class='bg-white border rounded-lg overflow-hidden'>
                <div class='bg-gray-50 px-4 py-3 border-b'>
                    <div class='flex justify-between items-center'>
                        <h4 class='font-semibold text-gray-800'>Periode {$tagihan['periode_tagihan']}</h4>
                        <span class='text-{$statusColor}-600 font-bold text-sm'>{$statusText}</span>
                    </div>
                    <p class='text-sm text-gray-600'>Jatuh tempo: {$jatuhTempo}</p>
                </div>

                <div class='p-4'>
                    <div class='grid grid-cols-2 gap-4 mb-4'>
                        <div class='text-center bg-blue-50 p-3 rounded'>
                            <div class='text-2xl font-bold text-blue-600'>{$tagihan['pemakaian_air']}</div>
                            <div class='text-sm text-blue-500'>m³ air</div>
                        </div>
                        <div class='text-center bg-green-50 p-3 rounded'>
                            <div class='text-2xl font-bold text-green-600'>Rp " . number_format($tagihan['total_tagihan'], 0, ',', '.') . "</div>
                            <div class='text-sm text-green-500'>Total tagihan</div>
                        </div>
                    </div>

                    <div class='space-y-2 text-sm'>
                        <div class='flex justify-between'><span>Biaya Pemakaian:</span><span>Rp " . number_format($tagihan['biaya_pemakaian'], 0, ',', '.') . "</span></div>
                        <div class='flex justify-between'><span>Biaya Beban:</span><span>Rp " . number_format($tagihan['biaya_beban'] ?? 0, 0, ',', '.') . "</span></div>
                        <div class='flex justify-between'><span>Biaya Admin:</span><span>Rp " . number_format($tagihan['biaya_administrasi'], 0, ',', '.') . "</span></div>";

        if (($tagihan['biaya_denda'] ?? 0) > 0) {
            $return .= "<div class='flex justify-between text-red-600'><span>Denda:</span><span>Rp " . number_format($tagihan['biaya_denda'], 0, ',', '.') . "</span></div>";
        }

        $return .= "
                        <hr class='my-2'>
                        <div class='flex justify-between font-bold text-lg'><span>Total:</span><span>Rp " . number_format($tagihan['total_tagihan'], 0, ',', '.') . "</span></div>
                    </div>
                </div>
            </div>
        ";

        return $return;
    }

    private function getPaymentSummaryCard(): string
    {
        if (empty($this->tagihanData)) {
            return '';
        }

        $totalTagihan = $this->tagihanData['total_tagihan'];
        $metode = $this->data['metode_pembayaran'] ?? 'cash';
        $jenisBayar = $this->data['jenis_bayar'] ?? 'lunas';

        $jumlahBayar = $jenisBayar === 'lunas'
            ? $totalTagihan
            : ($this->data['jumlah_bayar_custom'] ?? 0);

        $uangDiterima = $this->data['uang_diterima'] ?? 0;
        $kembalian = $metode === 'cash' ? max(0, $uangDiterima - $jumlahBayar) : 0;
        $sisaTagihan = max(0, $totalTagihan - $jumlahBayar);

        $metodeIcon = match($metode) {
            'cash' => '💵',
            'qris' => '📱',
            'debit' => '💳',
            'credit' => '💎',
            default => '💰'
        };

        $statusPayment = $jumlahBayar >= $totalTagihan ? '✅ LUNAS' : '⚡ SEBAGIAN';
        $statusColor = $jumlahBayar >= $totalTagihan ? 'green' : 'orange';

        $card = "
            <div class='bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-lg border'>
                <div class='text-center mb-4'>
                    <div class='text-3xl mb-2'>{$metodeIcon}</div>
                    <h3 class='text-lg font-bold text-gray-800'>Ringkasan Pembayaran</h3>
                    <span class='inline-block px-3 py-1 bg-{$statusColor}-100 text-{$statusColor}-800 rounded-full text-sm font-medium'>
                        {$statusPayment}
                    </span>
                </div>

                <div class='space-y-3'>
                    <div class='flex justify-between py-2 border-b'>
                        <span>Total Tagihan:</span>
                        <span class='font-semibold'>Rp " . number_format($totalTagihan, 0, ',', '.') . "</span>
                    </div>
                    <div class='flex justify-between py-2 border-b'>
                        <span>Jumlah Bayar:</span>
                        <span class='font-bold text-blue-600'>Rp " . number_format($jumlahBayar, 0, ',', '.') . "</span>
                    </div>";

        if ($metode === 'cash' && $uangDiterima > 0) {
            $kembalianColor = $kembalian >= 0 ? 'green' : 'red';
            $kembalianText = $kembalian >= 0 ? number_format($kembalian, 0, ',', '.') : 'KURANG';

            $card .= "
                    <div class='flex justify-between py-2 border-b'>
                        <span>Uang Diterima:</span>
                        <span class='font-semibold'>Rp " . number_format($uangDiterima, 0, ',', '.') . "</span>
                    </div>
                    <div class='flex justify-between py-2 border-b'>
                        <span>Kembalian:</span>
                        <span class='font-bold text-{$kembalianColor}-600'>Rp {$kembalianText}</span>
                    </div>";
        }

        if ($sisaTagihan > 0) {
            $card .= "
                    <div class='flex justify-between py-2 bg-yellow-50 px-3 rounded'>
                        <span class='text-yellow-700'>Sisa Tagihan:</span>
                        <span class='font-bold text-yellow-600'>Rp " . number_format($sisaTagihan, 0, ',', '.') . "</span>
                    </div>";
        }

        $card .= "
                </div>
            </div>
        ";

        return $card;
    }

    private function getConfirmationMessage(): string
    {
        if (empty($this->tagihanData)) {
            return 'Tidak ada data tagihan';
        }

        $totalTagihan = $this->tagihanData['total_tagihan'];
        $metode = $this->data['metode_pembayaran'] ?? 'cash';
        $jenisBayar = $this->data['jenis_bayar'] ?? 'lunas';

        $jumlahBayar = $jenisBayar === 'lunas'
            ? $totalTagihan
            : ($this->data['jumlah_bayar_custom'] ?? 0);

        $pelanggan = $this->pelangganData['nama_pelanggan'] ?? '';
        $periode = $this->tagihanData['periode_tagihan'] ?? '';

        return "Anda akan memproses pembayaran untuk:\n\n" .
               "Pelanggan: {$pelanggan}\n" .
               "Periode: {$periode}\n" .
               "Jumlah Bayar: Rp " . number_format($jumlahBayar, 0, ',', '.') . "\n" .
               "Metode: " . strtoupper($metode) . "\n\n" .
               "Apakah Anda yakin ingin melanjutkan?";
    }

    public function hitungKembalian(): void
    {
        // Method akan dipanggil otomatis saat form berubah
    }

    public function prosesPembayaran(): void
    {
        try {
            // Validasi data tagihan
            if (empty($this->tagihanData)) {
                Notification::make()
                    ->title('Error Validasi')
                    ->body('Tidak ada data tagihan yang dipilih')
                    ->danger()
                    ->send();
                return;
            }

            // Validasi data pelanggan
            if (empty($this->pelangganData)) {
                Notification::make()
                    ->title('Error Validasi')
                    ->body('Data pelanggan tidak ditemukan')
                    ->danger()
                    ->send();
                return;
            }

            $totalTagihan = $this->tagihanData['total_tagihan'];
            $metode = $this->data['metode_pembayaran'] ?? 'cash';
            $jenisBayar = $this->data['jenis_bayar'] ?? 'lunas';

            // Validasi metode pembayaran
            if (!in_array($metode, ['cash', 'qris', 'debit', 'credit'])) {
                Notification::make()
                    ->title('Error Validasi')
                    ->body('Metode pembayaran tidak valid')
                    ->danger()
                    ->send();
                return;
            }

            $jumlahBayar = $jenisBayar === 'lunas'
                ? $totalTagihan
                : ($this->data['jumlah_bayar_custom'] ?? 0);

            // Validasi jumlah bayar
            if ($jumlahBayar <= 0) {
                Notification::make()
                    ->title('Error Validasi')
                    ->body('Jumlah pembayaran harus lebih dari 0')
                    ->danger()
                    ->send();
                return;
            }

            if ($jumlahBayar > $totalTagihan) {
                Notification::make()
                    ->title('Error Validasi')
                    ->body('Jumlah pembayaran tidak boleh melebihi total tagihan')
                    ->danger()
                    ->send();
                return;
            }

            // Validasi khusus untuk metode cash
            if ($metode === 'cash') {
                $uangDiterima = $this->data['uang_diterima'] ?? 0;

                if ($uangDiterima <= 0) {
                    Notification::make()
                        ->title('Error Validasi')
                        ->body('Silakan masukkan jumlah uang yang diterima')
                        ->danger()
                        ->send();
                    return;
                }

                if ($uangDiterima < $jumlahBayar) {
                    Notification::make()
                        ->title('Uang Kurang!')
                        ->body('Uang yang diterima kurang dari jumlah yang harus dibayar')
                        ->danger()
                        ->send();
                    return;
                }
            }

            // Proses pembayaran
            $pembayaran = Pembayaran::create([
                'id_pelanggan' => $this->pelangganData['id_pelanggan'],
                'id_tagihan' => $this->tagihanData['id_tagihan_bulanan'],
                'nomor_pembayaran' => Pembayaran::generateNomorPembayaran(),
                'tanggal_bayar' => now(),
                'jumlah_bayar' => $jumlahBayar,
                'metode_bayar' => $metode,
                'jenis_pembayaran' => 'rekening',
                'total_tagihan' => $totalTagihan,
                'uang_diterima' => $metode === 'cash' ? ($this->data['uang_diterima'] ?? null) : null,
                'kembalian' => $metode === 'cash' ?
                    max(0, ($this->data['uang_diterima'] ?? 0) - $jumlahBayar) : null,
                'sisa_tagihan' => max(0, $totalTagihan - $jumlahBayar),
                'periode_pembayaran' => $this->tagihanData['periode_tagihan'],
                'status_verifikasi' => 'valid',
                'nip_petugas_loket' => Auth::user()->nip ?? Auth::id(),
                'dibuat_oleh' => Auth::id(),
                'dibuat_pada' => now(),
            ]);

            // Update status tagihan jika lunas
            if ($jumlahBayar >= $totalTagihan) {
                TagihanBulanan::where('id_tagihan_bulanan', $this->tagihanData['id_tagihan_bulanan'])
                    ->update([
                        'status_pembayaran' => 'lunas',
                        'tanggal_bayar' => now(),
                        'jumlah_bayar' => $jumlahBayar,
                    ]);
            }

            $statusBayar = $jumlahBayar >= $totalTagihan ? 'LUNAS' : 'SEBAGIAN';
            $pesan = "🎉 Pembayaran {$statusBayar}!\n\n";
            $pesan .= "No. Transaksi: {$pembayaran->nomor_pembayaran}\n";
            $pesan .= "Pelanggan: {$this->pelangganData['nama_pelanggan']}\n";
            $pesan .= "Periode: {$this->tagihanData['periode_tagihan']}\n";
            $pesan .= "Jumlah Bayar: Rp " . number_format($jumlahBayar, 0, ',', '.') . "\n";
            $pesan .= "Metode: " . strtoupper($metode);

            if ($metode === 'cash') {
                $kembalian = max(0, ($this->data['uang_diterima'] ?? 0) - $jumlahBayar);
                $pesan .= "\nKembalian: Rp " . number_format($kembalian, 0, ',', '.');
            }

            if ($jumlahBayar < $totalTagihan) {
                $sisaTagihan = $totalTagihan - $jumlahBayar;
                $pesan .= "\n\n⚠️ Sisa Tagihan: Rp " . number_format($sisaTagihan, 0, ',', '.');
            }

            Notification::make()
                ->title('Pembayaran Berhasil!')
                ->body($pesan)
                ->success()
                ->duration(8000)
                ->send();

            // Reset form untuk transaksi berikutnya
            $this->resetPelangganData();
            $this->form->fill([]);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Sistem')
                ->body('Gagal memproses pembayaran: ' . $e->getMessage())
                ->danger()
                ->duration(10000)
                ->send();
        }
    }

    private function getPelangganInfo(): string
    {
        if (empty($this->pelangganData)) {
            return 'Belum ada data pelanggan';
        }

        return "
            <strong>Nama:</strong> {$this->pelangganData['nama_pelanggan']}<br>
            <strong>Nomor:</strong> {$this->pelangganData['nomor_pelanggan']}<br>
            <strong>Alamat:</strong> {$this->pelangganData['alamat']}<br>
            <strong>Status:</strong> {$this->pelangganData['status_pelanggan']}
        ";
    }

    private function getTagihanInfo(): string
    {
        if (empty($this->tagihanData)) {
            return 'Belum ada data tagihan';
        }

        return "
            <strong>Periode:</strong> {$this->tagihanData['periode_tagihan']}<br>
            <strong>Pemakaian:</strong> {$this->tagihanData['pemakaian_air']} m³<br>
            <strong>Total Tagihan:</strong> Rp " . number_format($this->tagihanData['total_tagihan'], 0, ',', '.') . "<br>
            <strong>Jatuh Tempo:</strong> {$this->tagihanData['jatuh_tempo']}
        ";
    }

    private function getKembalianInfo(): string
    {
        $jumlahBayar = $this->data['jenis_pembayaran_type'] === 'semua'
            ? ($this->data['total_tagihan'] ?? 0)
            : ($this->data['jumlah_bayar'] ?? 0);

        $uangDiterima = $this->data['uang_diterima'] ?? 0;
        $kembalian = max(0, $uangDiterima - $jumlahBayar);

        $color = $kembalian >= 0 ? 'text-green-600' : 'text-red-600';
        $status = $kembalian >= 0 ? '' : ' (Kurang)';

        return "<span class='{$color} font-semibold'>Rp " . number_format($kembalian, 0, ',', '.') . $status . "</span>";
    }

    private function getNonCashInfo(): string
    {
        $metode = strtoupper($this->data['metode_pembayaran'] ?? '');
        return "
            <div class='text-center p-4 border-2 border-dashed border-blue-200 rounded-lg bg-blue-50'>
                <div class='text-blue-500 text-2xl mb-2'>ℹ️</div>
                <p class='text-sm text-blue-600'>Pembayaran akan diproses melalui <strong>{$metode}</strong></p>
                <p class='text-xs text-blue-500'>Hubungi petugas untuk melanjutkan transaksi</p>
            </div>
        ";
    }

    /**
     * Handle database connection errors
     */
    private function handleDatabaseError(\Exception $e): void
    {
        if (str_contains($e->getMessage(), 'Connection refused') ||
            str_contains($e->getMessage(), 'Connection timed out')) {
            Notification::make()
                ->title('Error Koneksi Database')
                ->body('Tidak dapat terhubung ke database. Silakan coba lagi nanti.')
                ->danger()
                ->duration(10000)
                ->send();
        } else {
            Notification::make()
                ->title('Error Database')
                ->body('Terjadi kesalahan pada database: ' . $e->getMessage())
                ->danger()
                ->duration(10000)
                ->send();
        }
    }

    /**
     * Validate required data before processing
     */
    private function validateRequiredData(): array
    {
        $errors = [];

        if (empty($this->pelangganData)) {
            $errors[] = 'Data pelanggan tidak ditemukan';
        }

        if (empty($this->tagihanData)) {
            $errors[] = 'Data tagihan tidak ditemukan';
        }

        if (!isset($this->data['metode_pembayaran'])) {
            $errors[] = 'Metode pembayaran belum dipilih';
        }

        return $errors;
    }

    /**
     * Check if system is ready for transaction
     */
    public function checkSystemReady(): bool
    {
        try {
            // Test database connection
            \DB::connection()->getPdo();

            // Test required models
            Pelanggan::first();
            TagihanBulanan::first();

            return true;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Sistem Tidak Siap')
                ->body('Sistem tidak dapat digunakan saat ini. Silakan hubungi administrator.')
                ->danger()
                ->persistent()
                ->send();

            return false;
        }
    }
}
