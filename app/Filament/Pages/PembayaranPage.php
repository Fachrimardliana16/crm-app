<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
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
use Illuminate\Support\HtmlString;

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
        $this->form->fill();
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
                ->action(fn (array $data) => $this->cariPelangganModal($data)),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // === PENCARIAN ===
                Section::make('Halaman Transaksi Pembayaran Rekening Air')
                    ->schema([
                        TextInput::make('search_input')
                            ->label('Nomor Pelanggan')
                            ->placeholder('Masukkan 8 digit nomor pelanggan')
                            ->required()
                            ->numeric()
                            ->maxLength(8)
                            ->rule('digits:8')
                            ->validationMessages(['digits' => 'Harus tepat 8 digit.'])
                            ->hint(fn ($state) => $this->getSearchHint($state))
                            ->live(debounce: 400)
                            ->afterStateUpdated(fn ($state) => $this->limitInputLength($state))
                            ->extraAttributes([
                                'x-data' => '{}',
                                'x-ref' => 'searchWrapper',
                                'x-init' => '
                                    $nextTick(() => {
                                        const input = $refs.searchWrapper.querySelector("input");
                                        if (input) {
                                            input.focus();
                                            input.select();
                                        }
                                    });
                                ',
                                'x-on:input' => '
                                    const input = $refs.searchWrapper.querySelector("input");
                                    if (input.value.length > 8) {
                                        input.value = input.value.slice(0, 8);
                                        input.dispatchEvent(new Event("input"));
                                    }
                                ',
                                'x-on:keydown.enter.prevent' => '
                                    const input = $refs.searchWrapper.querySelector("input");
                                    if (input && input.value.length === 8) {
                                        $wire.cariOtomatis(input.value);
                                    }
                                ',
                            ]),
                    ]),

                // === LAYOUT 2 KOLOM ===
                Grid::make(2)->schema([
                    // Kiri: Detail Rekening
                    Section::make('Detail Rekening')
                        ->schema([
                            Placeholder::make('customer_info')
                                ->content(fn () => new HtmlString($this->getDetailRekeningCard()))
                        ])
                        ->visible(fn () => $this->showRekeningData),

                    // Kanan: Pembayaran
                    Section::make('Pembayaran')
                        ->schema([
                            Radio::make('selected_tagihan')
                                ->label('Pilih Tagihan')
                                ->options(fn () => $this->getTagihanOptions())
                                ->descriptions(fn () => $this->getTagihanDescriptions())
                                ->live()
                                ->afterStateUpdated(fn ($state) => $this->loadSelectedTagihan($state))
                                ->visible(fn () => !empty($this->allTagihan)),

                            Radio::make('metode_pembayaran')
                                ->label('Metode Pembayaran')
                                ->options([
                                    'cash' => 'Cash/Tunai',
                                    'qris' => 'QRIS',
                                    'debit' => 'Kartu Debit',
                                    'credit' => 'Kartu Kredit',
                                ])
                                ->default('cash')
                                ->live()
                                ->inline()
                                ->visible(fn () => !empty($this->data['selected_tagihan'])),

                            Radio::make('jenis_bayar')
                                ->label('Jenis Pembayaran')
                                ->options([
                                    'lunas' => 'Bayar Lunas',
                                    'sebagian' => 'Bayar Sebagian',
                                ])
                                ->default('lunas')
                                ->live()
                                ->inline()
                                ->visible(fn () => !empty($this->data['selected_tagihan'])),

                            TextInput::make('jumlah_bayar_custom')
                                ->label('Jumlah yang Dibayar')
                                ->numeric()
                                ->prefix('Rp')
                                ->placeholder('0')
                                ->visible(fn () => ($this->data['jenis_bayar'] ?? '') === 'sebagian')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->hitungKembalian()),

                            TextInput::make('uang_diterima')
                                ->label('Uang yang Diterima')
                                ->numeric()
                                ->prefix('Rp')
                                ->placeholder('Masukkan jumlah uang...')
                                ->visible(fn () => ($this->data['metode_pembayaran'] ?? '') === 'cash' && !empty($this->data['selected_tagihan']))
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->hitungKembalian()),

                            Placeholder::make('payment_summary')
                                ->content(fn () => new HtmlString($this->getPaymentSummaryCard()))
                                ->visible(fn () => !empty($this->data['selected_tagihan'])),

                            Actions::make([
                                Action::make('prosesTransaksi')
                                    ->label('PROSES PEMBAYARAN')
                                    ->color('success')
                                    ->size('xl')
                                    ->action('prosesPembayaran')
                                    ->visible(fn () => !empty($this->data['selected_tagihan']))
                                    ->requiresConfirmation()
                                    ->modalHeading('Konfirmasi Pembayaran')
                                    ->modalDescription(fn () => $this->getConfirmationMessage())
                                    ->modalSubmitActionLabel('Ya, Proses Sekarang'),
                            ])->fullWidth(),
                        ])
                        ->visible(fn () => $this->showRekeningData),
                ]),
            ])
            ->statePath('data');
    }

    // === CARI OTOMATIS (Enter) ===
    public function cariOtomatis(string $nomor): void
    {
        if (strlen($nomor) !== 8 || !ctype_digit($nomor)) {
            Notification::make()
                ->title('Nomor Tidak Valid')
                ->body('Masukkan tepat 8 digit angka.')
                ->danger()
                ->send();
            return;
        }

        $this->cariPelangganOtomatis($nomor);
    }

    // === HINT DINAMIS ===
    private function getSearchHint($state): string
    {
        $len = strlen($state ?? '');
        return match (true) {
            $len === 0 => 'Ketik 8 digit nomor pelanggan',
            $len < 8   => "Lanjutkan... ($len/8)",
            $len === 8 => 'Tekan Enter untuk mencari',
            default    => 'Maksimal 8 digit!',
        };
    }

    // === BATASI INPUT 8 DIGIT ===
    private function limitInputLength($state): void
    {
        if (strlen($state) > 8) {
            Notification::make()
                ->title('Input Dibatasi')
                ->body('Nomor pelanggan maksimal 8 digit.')
                ->warning()
                ->send();
        }
    }

    // === CARI PELANGGAN ===
    public function cariPelangganOtomatis(string $search): void
    {
        try {
            $pelanggan = Pelanggan::where('nomor_pelanggan', $search)
                ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                ->first();

            if ($pelanggan) {
                $this->loadPelangganData($pelanggan);
                $this->data['search_input'] = $pelanggan->nomor_pelanggan;
                Notification::make()
                    ->title('Pelanggan Ditemukan!')
                    ->body($pelanggan->nama_pelanggan)
                    ->success()
                    ->send();
            } else {
                $this->resetPelangganData();
                Notification::make()
                    ->title('Tidak Ditemukan')
                    ->body('Nomor pelanggan tidak terdaftar.')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            $this->resetPelangganData();
            Notification::make()
                ->title('Error')
                ->body('Gagal mencari: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    // === CARI VIA MODAL ===
    public function cariPelangganModal(array $data): void
    {
        try {
            $query = Pelanggan::query();
            if (!empty($data['modal_nama'])) {
                $query->where('nama_pelanggan', 'like', '%' . $data['modal_nama'] . '%');
            }
            if (!empty($data['modal_alamat'])) {
                $query->where('alamat', 'like', '%' . $data['modal_alamat'] . '%');
            }

            $pelanggan = $query->first();

            if ($pelanggan) {
                $this->loadPelangganData($pelanggan);
                $this->data['search_input'] = $pelanggan->nomor_pelanggan;
                Notification::make()->title('Berhasil')->body('Pelanggan dimuat')->success()->send();
            } else {
                Notification::make()->title('Tidak Ditemukan')->body('Coba kriteria lain')->warning()->send();
            }
        } catch (\Exception $e) {
            Notification::make()->title('Error')->body($e->getMessage())->danger()->send();
        }
    }

    // === LOAD DATA PELANGGAN ===
    private function loadPelangganData(Pelanggan $pelanggan): void
    {
        $this->pelangganData = $pelanggan->toArray();

        $this->allTagihan = TagihanBulanan::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->where('status_pembayaran', '!=', 'lunas')
            ->orderBy('periode_tagihan', 'desc')
            ->get()
            ->toArray();

        if (!empty($this->allTagihan)) {
            $this->showRekeningData = true;
            $this->data['selected_tagihan'] = $this->allTagihan[0]['id_tagihan_bulanan'];
            $this->loadSelectedTagihan($this->allTagihan[0]['id_tagihan_bulanan']);
        } else {
            $this->showRekeningData = false;
            Notification::make()->title('Tidak Ada Tagihan')->body('Semua tagihan sudah lunas')->info()->send();
        }
    }

    public function loadSelectedTagihan(string $id): void
    {
        $tagihan = collect($this->allTagihan)->firstWhere('id_tagihan_bulanan', $id);
        if ($tagihan) {
            $this->tagihanData = $tagihan;
            $this->data['total_tagihan'] = $tagihan['total_tagihan'];
            $this->data['jumlah_bayar'] = $tagihan['total_tagihan'];
        }
    }

    public function getTagihanOptions(): array
    {
        return collect($this->allTagihan)
            ->mapWithKeys(fn ($t) => [
                $t['id_tagihan_bulanan'] => "{$t['periode_tagihan']} - Rp " . number_format($t['total_tagihan'], 0, ',', '.')
            ])
            ->toArray();
    }

    public function getTagihanDescriptions(): array
    {
        return collect($this->allTagihan)
            ->mapWithKeys(fn ($t) => [
                $t['id_tagihan_bulanan'] =>
                    "Jatuh tempo: " . \Carbon\Carbon::parse($t['jatuh_tempo'])->format('d/m/Y') .
                    " | Pakai: {$t['pemakaian_air']} m³ | " .
                    (\Carbon\Carbon::parse($t['jatuh_tempo'])->isPast() ? 'Terlambat' : 'Normal')
            ])
            ->toArray();
    }

    private function getDetailRekeningCard(): string
    {
        if (empty($this->pelangganData)) {
            return '<div class="text-center p-8 text-gray-500">Gunakan pencarian di atas</div>';
        }

        $tagihan = $this->tagihanData;
        $jatuhTempo = \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->format('d M Y');
        $isOverdue = \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->isPast();
        $status = $isOverdue ? 'TERLAMBAT' : 'NORMAL';

        return view('filament.components.rekening-card', [
            'pelanggan' => $this->pelangganData,
            'tagihan' => $tagihan,
            'jatuhTempo' => $jatuhTempo,
            'status' => $status,
            'isOverdue' => $isOverdue,
        ])->render();
    }

    private function getPaymentSummaryCard(): string
    {
        if (empty($this->tagihanData)) return '';

        $total = $this->tagihanData['total_tagihan'];
        $jenis = $this->data['jenis_bayar'] ?? 'lunas';
        $metode = $this->data['metode_pembayaran'] ?? 'cash';
        $bayar = $jenis === 'lunas' ? $total : ($this->data['jumlah_bayar_custom'] ?? 0);
        $diterima = $this->data['uang_diterima'] ?? 0;
        $kembalian = $metode === 'cash' ? max(0, $diterima - $bayar) : 0;
        $sisa = max(0, $total - $bayar);

        return view('filament.components.payment-summary', [
            'total' => $total,
            'bayar' => $bayar,
            'diterima' => $diterima,
            'kembalian' => $kembalian,
            'sisa' => $sisa,
            'metode' => $metode,
            'status' => $bayar >= $total ? 'LUNAS' : 'SEBAGIAN',
        ])->render();
    }

    private function getConfirmationMessage(): string
    {
        $pelanggan = $this->pelangganData['nama_pelanggan'] ?? '';
        $periode = $this->tagihanData['periode_tagihan'] ?? '';
        $bayar = $this->data['jenis_bayar'] === 'lunas'
            ? $this->tagihanData['total_tagihan']
            : ($this->data['jumlah_bayar_custom'] ?? 0);
        $metode = strtoupper($this->data['metode_pembayaran'] ?? 'cash');

        return "Proses pembayaran:\n\nPelanggan: $pelanggan\nPeriode: $periode\nJumlah: Rp " . number_format($bayar, 0, ',', '.') . "\nMetode: $metode\n\nLanjutkan?";
    }

    public function hitungKembalian(): void { /* Auto via live() */ }

    public function prosesPembayaran(): void
    {
        // Implementasi proses pembayaran (sama seperti sebelumnya)
    }

    public function resetPelangganData(): void
    {
        $this->pelangganData = [];
        $this->tagihanData = [];
        $this->allTagihan = [];
        $this->showRekeningData = false;
        $this->data['selected_tagihan'] = null;
        $this->data['search_input'] = null;
        $this->form->fill($this->data);
    }
}
