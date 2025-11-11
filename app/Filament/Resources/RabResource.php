<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RabResource\Pages;
use App\Filament\Resources\RabResource\RelationManagers;
use App\Models\Rab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RabResource extends Resource
{
    protected static ?string $model = Rab::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'RAB';

    protected static ?string $modelLabel = 'RAB';

    protected static ?string $pluralModelLabel = 'RAB';

    protected static ?string $navigationGroup = 'Workflow';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_input')
                            ->label('Tanggal Input')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Data Pendaftaran')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_pendaftaran')
                                    ->label('Pendaftaran')
                                    ->relationship('pendaftaran', 'nomor_registrasi')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nomor_registrasi} - {$record->nama_pemohon}")
                                    ->searchable(['nomor_registrasi', 'nama_pemohon'])
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $pendaftaran = \App\Models\Pendaftaran::with(['pelanggan', 'cabang', 'survei'])->find($state);
                                            if ($pendaftaran) {
                                                // Set pelanggan info
                                                if ($pendaftaran->id_pelanggan) {
                                                    $set('id_pelanggan', $pendaftaran->id_pelanggan);
                                                }
                                                
                                                // Auto-fill data from pendaftaran
                                                $set('nama_pelanggan', $pendaftaran->nama_pemohon);
                                                $set('alamat_pelanggan', $pendaftaran->alamat_pemasangan);
                                                $set('telepon_pelanggan', $pendaftaran->no_hp_pemohon);
                                                
                                                // Set kantor cabang
                                                if ($pendaftaran->cabang) {
                                                    $set('kantor_cabang', $pendaftaran->cabang->nama_cabang);
                                                }
                                                
                                                // Set golongan tarif from survei rekomendasi
                                                if ($pendaftaran->survei) {
                                                    $set('golongan_tarif', $pendaftaran->survei->rekomendasi_sub_golongan_text);
                                                }
                                            }
                                        }
                                    }),
                                    
                                Forms\Components\Select::make('status_rab')
                                    ->label('Status RAB')
                                    ->options([
                                        'draft' => 'Draft',
                                        'review' => 'Review',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                        'final' => 'Final',
                                    ])
                                    ->default('draft')
                                    ->required(),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Informasi Langganan')
                    ->description('Sub Rayon dan No. Langganan akan diassign setelah pemasangan selesai')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('jenis_biaya_sambungan')
                                    ->label('Jenis Biaya Sambungan')
                                    ->options([
                                        'standar' => 'Standar',
                                        'non_standar' => 'Non Standar',
                                    ])
                                    ->required()
                                    ->default('standar'),
                                    
                                Forms\Components\TextInput::make('golongan_tarif')
                                    ->label('Golongan Tarif')
                                    ->readOnly()
                                    ->helperText('Diambil dari rekomendasi survei'),
                            ]),
                            
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\TextInput::make('kantor_cabang')
                                    ->label('Kantor Cabang/Unit')
                                    ->readOnly()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Data Pelanggan')
                    ->description('Data ini akan otomatis terisi dari pendaftaran yang dipilih')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_pelanggan')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255),
                                    
                                Forms\Components\TextInput::make('telepon_pelanggan')
                                    ->label('Telepon')
                                    ->tel()
                                    ->maxLength(20),
                                    
                                Forms\Components\Textarea::make('alamat_pelanggan')
                                    ->label('Alamat')
                                    ->rows(2)
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Rincian Uang Muka')
                    ->description('Input biaya perencanaan dan perhitungan akan otomatis')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('perencanaan')
                                    ->label('Perencanaan dll')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateUangMuka($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('jumlah_uang_muka')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'bg-gray-50 font-semibold']),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Biaya Instalasi')
                    ->description('Input rincian biaya instalasi dan total akan dihitung otomatis')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('pengerjaan_tanah')
                                    ->label('Pengerjaan Tanah')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateInstalasi($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('tenaga_kerja')
                                    ->label('Tenaga Kerja')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateInstalasi($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('pipa_accessories')
                                    ->label('Pipa dan Accessories')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateInstalasi($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('jumlah_instalasi')
                                    ->label('Jumlah Total Instalasi')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'bg-gray-50 font-semibold']),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Rincian Piutang')
                    ->description('Perhitungan piutang dan total biaya sambungan baru')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('pembulatan_piutang')
                                    ->label('Pembulatan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculatePiutang($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('uang_muka')
                                    ->label('Uang Muka')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculatePiutang($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('piutang_na')
                                    ->label('Piutang Non Air (Piutang NA)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->helperText('Total yang harus dibayar dikurangi Uang Muka')
                                    ->extraAttributes(['class' => 'bg-gray-50 font-semibold']),
                                    
                                Forms\Components\TextInput::make('total_piutang')
                                    ->label('Total Piutang')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'bg-gray-50 font-semibold']),
                                    
                                Forms\Components\TextInput::make('pajak_piutang')
                                    ->label('Pajak (%)')
                                    ->numeric()
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->live(debounce: 1000)
                                    ->helperText('Masukkan persentase pajak (contoh: 10 untuk 10%)')
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculatePiutang($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('total_biaya_sambungan_baru')
                                    ->label('Total Biaya Sambungan Baru')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'bg-green-50 font-bold text-lg text-green-800 border-green-300'])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Metode Pembayaran')
                    ->description('Pilih metode pembayaran: lunas langsung atau cicilan/angsuran')
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Select::make('tipe_pembayaran')
                                    ->label('Tipe Pembayaran')
                                    ->options([
                                        'lunas' => 'Lunas/Cash',
                                        'cicilan' => 'Cicilan/Angsuran',
                                    ])
                                    ->default('lunas')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set, $state) {
                                        if ($state === 'lunas') {
                                            // Reset cicilan fields when lunas selected
                                            $set('jumlah_cicilan', null);
                                            $set('nominal_per_cicilan', null);
                                            $set('periode_mulai_cicilan', null);
                                        } else {
                                            // Set default values for cicilan
                                            if (!$get('jumlah_cicilan')) {
                                                $set('jumlah_cicilan', 3);
                                            }
                                            if (!$get('periode_mulai_cicilan')) {
                                                $set('periode_mulai_cicilan', (int) now()->format('Ym'));
                                            }
                                            self::calculateCicilan($get, $set);
                                        }
                                    })
                                    ->columnSpanFull(),
                            ]),
                            
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('jumlah_cicilan')
                                    ->label('Jumlah Cicilan')
                                    ->options([
                                        3 => '3 bulan',
                                        6 => '6 bulan',
                                        12 => '12 bulan',
                                        24 => '24 bulan',
                                        36 => '36 bulan',
                                    ])
                                    ->default(3)
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateCicilan($get, $set);
                                        // Reset custom data when jumlah changed
                                        if ($get('mode_cicilan') === 'custom') {
                                            self::resetCustomAngsuranData($get, $set);
                                        }
                                    })
                                    ->visible(fn (callable $get) => $get('tipe_pembayaran') === 'cicilan'),
                                    
                                Forms\Components\Select::make('mode_cicilan')
                                    ->label('Mode Cicilan')
                                    ->options([
                                        'auto' => 'Auto (Nominal Sama)',
                                        'custom' => 'Custom (Nominal Berbeda)',
                                    ])
                                    ->default('auto')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set, $state) {
                                        if ($state === 'custom') {
                                            self::initCustomAngsuranData($get, $set);
                                        } else {
                                            $set('custom_angsuran_data', null);
                                            self::calculateCicilan($get, $set);
                                        }
                                    })
                                    ->visible(fn (callable $get) => $get('tipe_pembayaran') === 'cicilan'),
                                    
                                Forms\Components\TextInput::make('periode_mulai_cicilan')
                                    ->label('Periode Mulai Cicilan')
                                    ->helperText('Format: YYYYMM (contoh: 202410)')
                                    ->numeric()
                                    ->minValue(202401)
                                    ->maxValue(203012)
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateCicilan($get, $set);
                                    })
                                    ->visible(fn (callable $get) => $get('tipe_pembayaran') === 'cicilan'),
                            ]),
                            
                        // Auto Cicilan Display
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\TextInput::make('nominal_per_cicilan')
                                    ->label('Nominal Per Cicilan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'bg-gray-50 font-semibold'])
                                    ->helperText('Dihitung otomatis dari total biaya dibagi jumlah cicilan')
                                    ->visible(fn (callable $get) => 
                                        $get('tipe_pembayaran') === 'cicilan' && 
                                        $get('mode_cicilan') === 'auto'
                                    ),
                            ]),
                            
                        // Custom Cicilan Input
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Repeater::make('custom_angsuran_data')
                                    ->label('Detail Angsuran Custom')
                                    ->schema([
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('periode')
                                                    ->label('Periode')
                                                    ->readOnly()
                                                    ->extraAttributes(['class' => 'bg-gray-50']),
                                                    
                                                Forms\Components\TextInput::make('nominal')
                                                    ->label('Nominal')
                                                    ->numeric()
                                                    ->prefix('Rp')
                                                    ->required()
                                                    ->live(debounce: 1000)
                                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                                        self::validateCustomTotal($get, $set);
                                                    }),
                                                    
                                                Forms\Components\TextInput::make('catatan')
                                                    ->label('Catatan')
                                                    ->placeholder('Catatan untuk periode ini...'),
                                            ]),
                                    ])
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->visible(fn (callable $get) => 
                                        $get('tipe_pembayaran') === 'cicilan' && 
                                        $get('mode_cicilan') === 'custom'
                                    )
                                    ->columnSpanFull(),
                                    
                                Forms\Components\Placeholder::make('custom_validation_info')
                                    ->label('Validasi Total')
                                    ->content(function (callable $get) {
                                        $customData = $get('custom_angsuran_data') ?? [];
                                        $totalCustom = collect($customData)->sum('nominal');
                                        $totalBiaya = (float) ($get('total_biaya_sambungan_baru') ?? 0);
                                        $selisih = $totalCustom - $totalBiaya;
                                        
                                        if (abs($selisih) < 0.01) {
                                            return "✅ Total Custom: Rp " . number_format($totalCustom, 0, ',', '.') . " (SESUAI)";
                                        } else {
                                            $status = $selisih > 0 ? 'KELEBIHAN' : 'KEKURANGAN';
                                            return "❌ Total Custom: Rp " . number_format($totalCustom, 0, ',', '.') . 
                                                   " | Target: Rp " . number_format($totalBiaya, 0, ',', '.') . 
                                                   " | {$status}: Rp " . number_format(abs($selisih), 0, ',', '.');
                                        }
                                    })
                                    ->visible(fn (callable $get) => 
                                        $get('tipe_pembayaran') === 'cicilan' && 
                                        $get('mode_cicilan') === 'custom'
                                    ),
                            ]),
                            
                        Forms\Components\Textarea::make('catatan_pembayaran')
                            ->label('Catatan Pembayaran')
                            ->rows(2)
                            ->placeholder('Catatan khusus mengenai pembayaran atau cicilan...')
                            ->visible(fn (callable $get) => $get('tipe_pembayaran') === 'cicilan')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('catatan_rab')
                            ->label('Catatan RAB')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    protected static function calculateUangMuka(callable $get, callable $set): void
    {
        $perencanaan = (float) ($get('perencanaan') ?? 0);
        // Uang muka = perencanaan (for now, bisa ditambah field lain jika diperlukan)
        $set('jumlah_uang_muka', $perencanaan);
        
        // Trigger piutang calculation with small delay to prevent rapid recalculation
        self::calculatePiutang($get, $set);
    }

    protected static function calculateInstalasi(callable $get, callable $set): void
    {
        $pengerjaanTanah = (float) ($get('pengerjaan_tanah') ?? 0);
        $tenagaKerja = (float) ($get('tenaga_kerja') ?? 0);
        $pipaAccessories = (float) ($get('pipa_accessories') ?? 0);
        
        $jumlahInstalasi = $pengerjaanTanah + $tenagaKerja + $pipaAccessories;
        $set('jumlah_instalasi', $jumlahInstalasi);
        
        // Trigger piutang calculation with small delay to prevent rapid recalculation
        self::calculatePiutang($get, $set);
    }

    protected static function calculatePiutang(callable $get, callable $set): void
    {
        $jumlahUangMuka = (float) ($get('jumlah_uang_muka') ?? 0);
        $jumlahInstalasi = (float) ($get('jumlah_instalasi') ?? 0);
        $pembulatanPiutang = (float) ($get('pembulatan_piutang') ?? 0);
        $uangMuka = (float) ($get('uang_muka') ?? 0);
        $pajakPersentase = (float) ($get('pajak_piutang') ?? 0);
        
        // Total yang harus dibayar = jumlah uang muka + jumlah instalasi + pembulatan
        $totalYangHarusDibayar = $jumlahUangMuka + $jumlahInstalasi + $pembulatanPiutang;
        
        // Piutang NA = total yang harus dibayar dikurangi uang muka
        $piutangNA = $totalYangHarusDibayar - $uangMuka;
        $set('piutang_na', $piutangNA);
        
        // Total piutang
        $totalPiutang = $piutangNA;
        $set('total_piutang', $totalPiutang);
        
        // Hitung pajak berdasarkan persentase dari total piutang
        $nominalPajak = 0;
        if ($pajakPersentase > 0 && $totalPiutang > 0) {
            $nominalPajak = ($totalPiutang * $pajakPersentase) / 100;
        }
        
        // Total biaya sambungan baru = total piutang + nominal pajak
        $totalBiayaSambunganBaru = $totalPiutang + $nominalPajak;
        $set('total_biaya_sambungan_baru', $totalBiayaSambunganBaru);
        
        // Trigger cicilan calculation if applicable
        self::calculateCicilan($get, $set);
    }

    protected static function calculateCicilan(callable $get, callable $set): void
    {
        $tipePembayaran = $get('tipe_pembayaran');
        $modeCicilan = $get('mode_cicilan');
        
        if ($tipePembayaran !== 'cicilan' || $modeCicilan === 'custom') {
            return;
        }
        
        $totalBiayaSambunganBaru = (float) ($get('total_biaya_sambungan_baru') ?? 0);
        $jumlahCicilan = (int) ($get('jumlah_cicilan') ?? 1);
        
        if ($totalBiayaSambunganBaru > 0 && $jumlahCicilan > 0) {
            $nominalPerCicilan = $totalBiayaSambunganBaru / $jumlahCicilan;
            $set('nominal_per_cicilan', $nominalPerCicilan);
        }
    }

    protected static function initCustomAngsuranData(callable $get, callable $set): void
    {
        $jumlahCicilan = (int) ($get('jumlah_cicilan') ?? 3);
        $periodeStart = (int) ($get('periode_mulai_cicilan') ?? now()->format('Ym'));
        $totalBiaya = (float) ($get('total_biaya_sambungan_baru') ?? 0);
        $nominalDefault = $totalBiaya > 0 ? $totalBiaya / $jumlahCicilan : 0;
        
        $customData = [];
        
        for ($i = 1; $i <= $jumlahCicilan; $i++) {
            $tanggalPeriode = \Carbon\Carbon::createFromFormat('Ym', $periodeStart)->addMonths($i - 1);
            $periode = $tanggalPeriode->format('F Y');
            
            $customData[] = [
                'periode' => "Cicilan ke-{$i} ({$periode})",
                'nominal' => $nominalDefault,
                'catatan' => '',
            ];
        }
        
        $set('custom_angsuran_data', $customData);
    }

    protected static function resetCustomAngsuranData(callable $get, callable $set): void
    {
        self::initCustomAngsuranData($get, $set);
    }

    protected static function validateCustomTotal(callable $get, callable $set): void
    {
        // This method will trigger the reactive validation in the Placeholder
        // No need to do anything here since the validation is handled in the content callback
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['pendaftaran']))
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('jenis_biaya_sambungan')
                    ->label('Jenis Biaya')
                    ->colors([
                        'primary' => 'standar',
                        'warning' => 'non_standar',
                    ]),
                    
                Tables\Columns\TextColumn::make('tanggal_input')
                    ->label('Tanggal Input')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status_rab')
                    ->label('Status RAB')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'review',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'primary' => 'final',
                    ])
                    ->icons([
                        'heroicon-o-pencil' => 'draft',
                        'heroicon-o-clock' => 'review',
                        'heroicon-o-check-circle' => 'approved',
                        'heroicon-o-x-circle' => 'rejected',
                        'heroicon-o-check-badge' => 'final',
                    ]),
                    
                Tables\Columns\TextColumn::make('total_biaya_sambungan_baru')
                    ->label('Total Biaya Sambungan')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('tipe_pembayaran')
                    ->label('Tipe Pembayaran')
                    ->colors([
                        'success' => 'lunas',
                        'warning' => 'cicilan',
                    ])
                    ->icons([
                        'heroicon-o-banknotes' => 'lunas',
                        'heroicon-o-credit-card' => 'cicilan',
                    ])
                    ->formatStateUsing(function (string $state, $record): string {
                        if ($state === 'cicilan' && $record && $record->mode_cicilan === 'custom') {
                            return 'Cicilan Custom';
                        }
                        return match ($state) {
                            'lunas' => 'Lunas',
                            'cicilan' => 'Cicilan Auto',
                            default => $state,
                        };
                    }),
                    
                Tables\Columns\BadgeColumn::make('mode_cicilan')
                    ->label('Mode Cicilan')
                    ->colors([
                        'info' => 'auto',
                        'warning' => 'custom',
                    ])
                    ->icons([
                        'heroicon-o-calculator' => 'auto',
                        'heroicon-o-cog-6-tooth' => 'custom',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'auto' => 'Auto',
                        'custom' => 'Custom',
                        default => '-',
                    })
                    ->visible(fn ($record) => $record && $record->tipe_pembayaran === 'cicilan')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('jumlah_cicilan')
                    ->label('Jumlah Cicilan')
                    ->formatStateUsing(fn ($state, $record) => 
                        $record && $record->tipe_pembayaran === 'cicilan' 
                            ? ($state ? $state . ' bulan' : '-') 
                            : '-'
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('nominal_per_cicilan')
                    ->label('Nominal Per Cicilan')
                    ->money('IDR')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record || $record->tipe_pembayaran !== 'cicilan') {
                            return '-';
                        }
                        if ($record->mode_cicilan === 'custom') {
                            return 'Bervariasi';
                        }
                        return 'Rp ' . number_format($state, 0, ',', '.');
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('golongan_tarif')
                    ->label('Golongan Tarif')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('dibuat_oleh')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_rab')
                    ->label('Status RAB')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'final' => 'Final',
                    ]),
                    
                Tables\Filters\SelectFilter::make('jenis_biaya_sambungan')
                    ->label('Jenis Biaya Sambungan')
                    ->options([
                        'standar' => 'Standar',
                        'non_standar' => 'Non Standar',
                    ]),
                    
                Tables\Filters\SelectFilter::make('tipe_pembayaran')
                    ->label('Tipe Pembayaran')
                    ->options([
                        'lunas' => 'Lunas',
                        'cicilan' => 'Cicilan',
                    ]),
                    
                Tables\Filters\Filter::make('tanggal_input')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_input', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_input', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye'),
                    
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil'),
                
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('print_faktur_laser')
                        ->label('🖨️ Print Faktur (Laser/Inkjet)')
                        ->icon('heroicon-o-printer')
                        ->color('info')
                        ->url(fn ($record) => route('rab.print-faktur', ['id' => $record->id_rab]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('download_pdf_laser')
                        ->label('📥 Download PDF (Laser/Inkjet)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn ($record) => route('rab.download-pdf', ['id' => $record->id_rab]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('print_faktur_dotmatrix')
                        ->label('🖨️ Print Faktur (Dot Matrix)')
                        ->icon('heroicon-o-printer')
                        ->color('warning')
                        ->url(fn ($record) => route('rab.print-faktur-dotmatrix', ['id' => $record->id_rab]))
                        ->openUrlInNewTab(),

                    Tables\Actions\Action::make('download_pdf_dotmatrix')
                        ->label('📥 Download PDF (Dot Matrix)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('warning')
                        ->url(fn ($record) => route('rab.download-pdf-dotmatrix', ['id' => $record->id_rab]))
                        ->openUrlInNewTab(),
                    
                    Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Rab $record) => $record->status_rab === 'proses')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui RAB')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui RAB ini?')
                    ->action(function (Rab $record) {
                        $oldStatus = $record->status_rab;
                        $record->update(['status_rab' => 'disetujui']);
                        
                        // Send workflow notifications
                        $notificationService = app(\App\Services\WorkflowNotificationService::class);
                        $notificationService->rabStatusChanged($record, $oldStatus, 'disetujui');
                        
                        \Filament\Notifications\Notification::make()
                            ->title('RAB telah disetujui')
                            ->success()
                            ->send();
                    }),
                    
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (Rab $record) => $record->status_rab === 'proses')
                    ->form([
                        Forms\Components\Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Rab $record, array $data) {
                        $oldStatus = $record->status_rab;
                        $record->update([
                            'status_rab' => 'ditolak',
                            'catatan_rab' => 'DITOLAK: ' . $data['alasan_penolakan'],
                        ]);
                        
                        // Send workflow notifications
                        $notificationService = app(\App\Services\WorkflowNotificationService::class);
                        $notificationService->rabStatusChanged($record, $oldStatus, 'ditolak');
                        
                        \Filament\Notifications\Notification::make()
                            ->title('RAB telah ditolak')
                            ->warning()
                            ->send();
                    }),
                ])
                ->label('More')
                ->icon('heroicon-m-ellipsis-horizontal')
                ->size('sm')
                ->button()
                ->outlined(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                    
                    Tables\Actions\BulkAction::make('print_multiple_faktur_laser')
                        ->label('🖨️ Print Multiple (Laser/Inkjet)')
                        ->icon('heroicon-o-printer')
                        ->color('info')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $ids = $records->pluck('id_rab')->toArray();
                            return redirect()->route('rab.print-multiple', ['ids' => $ids]);
                        }),

                    Tables\Actions\BulkAction::make('download_multiple_pdf_laser')
                        ->label('📥 Download PDF Multiple (Laser/Inkjet)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $ids = $records->pluck('id_rab')->toArray();
                            return redirect()->route('rab.download-multiple-pdf', ['ids' => $ids]);
                        }),

                    Tables\Actions\BulkAction::make('print_multiple_faktur_dotmatrix')
                        ->label('🖨️ Print Multiple (Dot Matrix)')
                        ->icon('heroicon-o-printer')
                        ->color('warning')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $ids = $records->pluck('id_rab')->toArray();
                            return redirect()->route('rab.print-multiple-dotmatrix', ['ids' => $ids]);
                        }),

                    Tables\Actions\BulkAction::make('download_multiple_pdf_dotmatrix')
                        ->label('📥 Download PDF Multiple (Dot Matrix)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('warning')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $ids = $records->pluck('id_rab')->toArray();
                            return redirect()->route('rab.download-multiple-pdf-dotmatrix', ['ids' => $ids]);
                        }),
                ]),
            ])
            ->defaultSort('tanggal_rab_dibuat', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRabs::route('/'),
            'create' => Pages\CreateRab::route('/create'),
            'view' => Pages\ViewRab::route('/{record}'),
            'edit' => Pages\EditRab::route('/{record}/edit'),
        ];
    }
}
