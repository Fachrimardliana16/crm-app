<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Filament\Resources\PendaftaranResource\RelationManagers;
use App\Models\Pendaftaran;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Afsakar\LeafletMapPicker\LeafletMapPicker;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Pendaftaran';

    protected static ?string $modelLabel = 'Pendaftaran';

    protected static ?string $pluralModelLabel = 'Pendaftaran';

    protected static ?string $navigationGroup = 'Workflow';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pemohon')
                    ->description('Data pemohon yang akan mendaftar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                 Forms\Components\TextInput::make('nomor_registrasi')
                                    ->label('Nomor Registrasi')
                                    ->disabled()
                                    ->dehydrated()
                                    ->placeholder('Auto-generate dari cabang')
                                    ->live(),

                                Forms\Components\DatePicker::make('tanggal_daftar')
                                    ->label('Tanggal Daftar')
                                    ->required()
                                    ->default(now())
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::generateNomorRegistrasi($get, $set);
                                    }),

                                Forms\Components\TextInput::make('nama_pemohon')
                                    ->label('Nama Pemohon')
                                    ->required()
                                    ->maxLength(255)
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        // Trigger generate nomor registrasi jika cabang sudah dipilih
                                        if ($get('id_cabang')) {
                                            self::generateNomorRegistrasi($get, $set);
                                        }
                                    }),

                                Select::make('id_cabang')
                                    ->label('Cabang/Unit')
                                    ->options(function () {
                                        return \App\Models\Cabang::where('status_aktif', true)
                                            ->get()
                                            ->mapWithKeys(function ($cabang) {
                                                $display = $cabang->nama_cabang;
                                                if (!empty($cabang->wilayah_pelayanan)) {
                                                    $display .= ' (Wil Pelayanan: ' . $cabang->wilayah_pelayanan . ')';
                                                }
                                                return [$cabang->id_cabang => $display];
                                            });
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('kode_cabang')
                                            ->label('Kode Cabang')
                                            ->required()
                                            ->maxLength(10)
                                            ->placeholder('Contoh: CKB, UKM'),
                                        Forms\Components\TextInput::make('nama_cabang')
                                            ->label('Nama Cabang')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('wilayah_pelayanan')
                                            ->label('Wilayah Pelayanan')
                                            ->maxLength(255)
                                            ->placeholder('Contoh: Purbalingga Timur'),
                                        Forms\Components\Textarea::make('alamat')
                                            ->label('Alamat')
                                            ->required(),
                                        Forms\Components\TextInput::make('telepon')
                                            ->label('Telepon')
                                            ->tel()
                                            ->maxLength(20),
                                        Forms\Components\TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('kepala_cabang')
                                            ->label('Kepala Cabang')
                                            ->default('-')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('keterangan')
                                            ->label('Keterangan'),
                                    ])
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::generateNomorRegistrasi($get, $set);
                                    }),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('jenis_identitas')
                                    ->label('Jenis Identitas')
                                    ->options([
                                        'ktp' => 'KTP',
                                        'sim' => 'SIM',
                                        'passport' => 'Passport',
                                        'kk' => 'Kartu Keluarga',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('nomor_identitas')
                                    ->label('Nomor Identitas')
                                    ->required()
                                    ->numeric()
                                    ->maxLength(50),
                            ]),

                        Select::make('id_pekerjaan')
                            ->label('Pekerjaan Pemohon')
                            ->relationship('pekerjaan', 'nama_pekerjaan')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_pekerjaan')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('deskripsi'),
                            ]),
                    ]),

                Section::make('Alamat & Lokasi')
                    ->description('Detail alamat dan koordinat pemasangan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('kecamatan_id')
                                    ->label('Kecamatan')
                                    ->options(function () {
                                        return \App\Models\Kecamatan::orderBy('nama_kecamatan')->pluck('nama_kecamatan', 'id_kecamatan');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        // Reset kelurahan ketika kecamatan berubah
                                        $set('id_kelurahan', null);
                                    }),

                                Select::make('id_kelurahan')
                                    ->label('Kelurahan')
                                    ->options(function (callable $get) {
                                        $kecamatanId = $get('kecamatan_id');
                                        if ($kecamatanId) {
                                            return \App\Models\Kelurahan::where('id_kecamatan', $kecamatanId)
                                                ->orderBy('nama_kelurahan')
                                                ->pluck('nama_kelurahan', 'id_kelurahan');
                                        }
                                        return [];
                                    })
                                    ->searchable()
                                    ->disabled(fn (callable $get) => !$get('kecamatan_id'))
                                    ->live()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama_kelurahan')
                                            ->label('Nama Kelurahan')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('kode_kelurahan')
                                            ->label('Kode Kelurahan')
                                            ->required()
                                            ->maxLength(15),
                                        Select::make('id_kecamatan')
                                            ->label('Kecamatan')
                                            ->relationship('kecamatan', 'nama_kecamatan')
                                            ->required()
                                            ->searchable()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('nama_kecamatan')
                                                    ->label('Nama Kecamatan')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('kode_kecamatan')
                                                    ->label('Kode Kecamatan')
                                                    ->required()
                                                    ->maxLength(10),
                                            ]),
                                        Forms\Components\TextInput::make('kode_pos')
                                            ->label('Kode Pos')
                                            ->maxLength(10),
                                    ]),
                            ]),
                        Forms\Components\Textarea::make('alamat_pemasangan')
                            ->label('Alamat Pemasangan')
                            ->required()
                            ->rows(3),

                        Forms\Components\TextInput::make('no_hp_pemohon')
                            ->label('No. HP Pemohon')
                            ->tel()
                            ->numeric()
                            ->maxLength(20),

                        Forms\Components\Textarea::make('keterangan_arah_lokasi')
                            ->label('Keterangan Arah Lokasi')
                            ->rows(2),

                        LeafletMapPicker::make('location')
                            ->label('Lokasi Pemasangan')
                            ->height('400px')
                            ->defaultLocation([-7.388119, 109.358398]) // Purbalingga default
                            ->defaultZoom(14)
                            ->draggable()
                            ->clickable()
                            ->reactive()
                            ->live()
                            ->dehydrated(false)
                            ->myLocationButtonLabel('Lokasi Saya')
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Log untuk debugging - akan dihapus setelah fix
                                \Log::info('LeafletMapPicker state changed:', [
                                    'state' => $state,
                                    'type' => gettype($state),
                                    'count' => is_array($state) ? count($state) : 'N/A'
                                ]);

                                if ($state) {
                                    $lat = null;
                                    $lng = null;

                                    // Handle berbagai format koordinat
                                    if (is_array($state)) {
                                        if (isset($state['lat']) && isset($state['lng'])) {
                                            // Format: ['lat' => -7.123, 'lng' => 109.456]
                                            $lat = $state['lat'];
                                            $lng = $state['lng'];
                                            \Log::info('Format: associative array');
                                        } elseif (count($state) >= 2 && is_numeric($state[0]) && is_numeric($state[1])) {
                                            // Format: [-7.123, 109.456]
                                            $lat = $state[0];
                                            $lng = $state[1];
                                            \Log::info('Format: indexed array');
                                        }
                                    } elseif (is_object($state)) {
                                        if (isset($state->lat) && isset($state->lng)) {
                                            // Format object: {lat: -7.123, lng: 109.456}
                                            $lat = $state->lat;
                                            $lng = $state->lng;
                                            \Log::info('Format: object');
                                        }
                                    }

                                    // Set koordinat jika valid
                                    if ($lat !== null && $lng !== null && is_numeric($lat) && is_numeric($lng)) {
                                        $latRounded = round(floatval($lat), 8);
                                        $lngRounded = round(floatval($lng), 8);

                                        \Log::info('Setting coordinates:', ['lat' => $latRounded, 'lng' => $lngRounded]);

                                        $set('latitude_awal', $latRounded);
                                        $set('longitude_awal', $lngRounded);
                                    } else {
                                        \Log::warning('Invalid coordinates:', ['lat' => $lat, 'lng' => $lng]);
                                    }
                                }
                            })
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('latitude_awal')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->live()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if (is_numeric($state) && !empty($state)) {
                                            $lat = floatval($state);
                                            $lng = $get('longitude_awal');

                                            if (is_numeric($lng) && !empty($lng)) {
                                                $lngVal = floatval($lng);
                                                // Update map location saat manual edit
                                                $set('location', [$lat, $lngVal]);
                                            }
                                        }
                                    })
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('longitude_awal')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->live()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if (is_numeric($state) && !empty($state)) {
                                            $lng = floatval($state);
                                            $lat = $get('latitude_awal');

                                            if (is_numeric($lat) && !empty($lat)) {
                                                $latVal = floatval($lat);
                                                // Update map location saat manual edit
                                                $set('location', [$latVal, $lng]);
                                            }
                                        }
                                    })
                                    ->dehydrated(),                                Forms\Components\TextInput::make('elevasi_awal_mdpl')
                                    ->label('Elevasi (MDPL)')
                                    ->numeric()
                                    ->suffix('meter'),
                            ]),
                    ]),

                Section::make('Detail Layanan')
                    ->description('Jenis layanan dan cabang pendaftaran')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('id_tipe_layanan')
                                    ->label('Tipe Layanan')
                                    ->relationship('tipeLayanan', 'nama_tipe_layanan')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $tipeLayanan = \App\Models\TipeLayanan::find($state);
                                            if ($tipeLayanan) {
                                                $set('biaya_tipe_layanan', $tipeLayanan->biaya_standar ?? 0);
                                                $set('tipe_layanan', $tipeLayanan->nama_tipe_layanan);
                                                self::calculateTotalBiaya($get, $set);
                                            }
                                        }
                                    })
                                    ->createOptionForm([
                                        Forms\Components\Section::make('Create Tipe Layanan')
                                            ->schema([
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('kode_tipe_layanan')
                                                            ->label('Kode Tipe Layanan')
                                                            ->required()
                                                            ->maxLength(3),

                                                        Forms\Components\TextInput::make('nama_tipe_layanan')
                                                            ->label('Nama Tipe Layanan')
                                                            ->required()
                                                            ->maxLength(255),
                                                    ]),

                                                Forms\Components\Textarea::make('deskripsi')
                                                    ->label('Deskripsi')
                                                    ->maxLength(500),

                                                Forms\Components\TextInput::make('biaya_standar')
                                                    ->label('Biaya Standar')
                                                    ->required()
                                                    ->numeric()
                                                    ->prefix('Rp'),

                                                Forms\Components\Toggle::make('status_aktif')
                                                    ->label('Status Aktif')
                                                    ->helperText('Aktifkan jika tipe layanan masih digunakan.')
                                                    ->default(true),
                                            ]),
                                        ]),

                                Select::make('id_jenis_daftar')
                                    ->label('Jenis Daftar')
                                    ->relationship('jenisDaftar', 'nama_jenis_daftar')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $jenisDaftar = \App\Models\JenisDaftar::find($state);
                                            if ($jenisDaftar) {
                                                $set('biaya_jenis_daftar', $jenisDaftar->biaya_tambahan ?? 0);
                                                self::calculateTotalBiaya($get, $set);
                                            }
                                        }
                                    })
                                    ->createOptionForm([
                                    Grid::make(2)
                                            ->schema([
                                         Forms\Components\TextInput::make('kode_jenis_daftar')
                                            ->required()
                                            ->maxLength(3),
                                        Forms\Components\TextInput::make('nama_jenis_daftar')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                        Forms\Components\Textarea::make('deskripsi'),
                                    Grid::make(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('biaya_daftar')
                                            ->numeric()
                                            ->prefix('Rp'),
                                        Forms\Components\TextInput::make('biaya_bulanan_tambahan')
                                            ->numeric()
                                            ->prefix('Rp'),
                                        Forms\Components\TextInput::make('potongan_layanan')
                                            ->numeric()
                                            ->prefix('Rp'),
                                        Forms\Components\TextInput::make('biaya_tambahan')
                                            ->label('Biaya Tambahan')
                                            ->numeric()
                                            ->prefix('Rp'),
                                    ]),
                                        Forms\Components\TextInput::make('lama_proses_hari')
                                            ->required()
                                            ->numeric()
                                            ->maxLength(255),
                                        Forms\Components\Toggle::make('status_aktif')
                                            ->label('Status Aktif')
                                            ->helperText('Aktifkan jika jenis daftar masih digunakan.')
                                            ->default(true),

                                    ]),

                                Select::make('id_tipe_pendaftaran')
                                    ->label('Tipe Pendaftaran')
                                    ->relationship('tipePendaftaran', 'nama_tipe_pendaftaran')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $tipePendaftaran = \App\Models\TipePendaftaran::find($state);
                                            if ($tipePendaftaran) {
                                                $set('biaya_tipe_pendaftaran', $tipePendaftaran->biaya_admin ?? 0);
                                                self::calculateTotalBiaya($get, $set);
                                            }
                                        }
                                    })
                                    ->createOptionForm([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('kode_tipe_pendaftaran')
                                                    ->label('Kode Tipe Pendaftaran')
                                                    ->required()
                                                    ->maxLength(3),

                                                Forms\Components\TextInput::make('nama_tipe_pendaftaran')
                                                    ->label('Nama Tipe Pendaftaran')
                                                    ->required()
                                                    ->maxLength(255),
                                            ]),

                                        Forms\Components\Textarea::make('deskripsi'),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                            Forms\Components\TextInput::make('biaya')
                                                ->numeric()
                                                ->prefix('Rp'),
                                            Forms\Components\TextInput::make('biaya_admin')
                                                ->numeric()
                                                ->prefix('Rp'),
                                        ]),

                                        Forms\Components\Grid::make(4)
                                            ->schema([
                                            Forms\Components\TextInput::make('prioritas')
                                                ->numeric(),
                                            Forms\Components\Toggle::make('status_aktif')
                                                ->label('Status Aktif')
                                                ->helperText('Aktifkan jika tipe pendaftaran masih digunakan.')
                                                ->default(true),
                                            Forms\Components\Toggle::make('otomatis_approve')
                                                ->label('Otomatis Approve')
                                                ->helperText('Jika diaktifkan, pendaftaran dengan tipe ini akan otomatis disetujui.'),
                                            Forms\Components\Toggle::make('perlu_survey')
                                                ->label('Survey Lapangan')
                                                ->helperText('Jika diaktifkan, pendaftaran dengan tipe ini wajib melalui proses survey lapangan.'),
                                        ]),
                                        Forms\Components\TextInput::make('data_pengembalian')
                                            ->label('Data Pengembalian')
                                            ->numeric()
                                            ->prefix('Rp'),

                                    ]),
                            ]),

                        Section::make('Rincian Biaya')
                            ->description('Detail biaya pendaftaran')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('biaya_tipe_layanan')
                                            ->label('Biaya Tipe Layanan')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0)
                                            ->disabled()
                                            ->dehydrated(),

                                        Forms\Components\TextInput::make('biaya_jenis_daftar')
                                            ->label('Biaya Jenis Daftar')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0)
                                            ->disabled()
                                            ->dehydrated(),

                                        Forms\Components\TextInput::make('biaya_tipe_pendaftaran')
                                            ->label('Biaya Tipe Pendaftaran')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0)
                                            ->disabled()
                                            ->dehydrated(),

                                        Forms\Components\Select::make('id_pajak')
                                            ->label('Pajak')
                                            ->relationship('pajak', 'nama_pajak')
                                            ->options(function () {
                                                return \App\Models\Pajak::where('status_aktif', true)
                                                    ->get()
                                                    ->mapWithKeys(function ($pajak) {
                                                        $display = $pajak->nama_pajak;
                                                        if ($pajak->jenis_pajak === 'persentase') {
                                                            $display .= ' (' . $pajak->persentase_pajak . '%)';
                                                        } else {
                                                            $display .= ' (Rp ' . number_format($pajak->nilai_tetap, 0, ',', '.') . ')';
                                                        }
                                                        return [$pajak->id_pajak => $display];
                                                    });
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                self::calculateTotalBiaya($get, $set);
                                            })
                                            ->placeholder('Pilih jenis pajak (opsional)'),
                                    ]),

                                Forms\Components\TextInput::make('total_biaya_pendaftaran')
                                    ->label('GRAND TOTAL')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated()
                                    ->suffixIcon('heroicon-m-currency-dollar')
                                    ->suffixIconColor('success')
                                    ->columnSpanFull(),

                                // Hidden fields untuk menyimpan nilai
                                Forms\Components\Hidden::make('biaya_tambahan')->default(0),
                                Forms\Components\Hidden::make('subtotal_biaya'),
                                Forms\Components\Hidden::make('nilai_pajak'),
                                Forms\Components\Hidden::make('status_pendaftaran')->default('draft'),
                                Forms\Components\Hidden::make('tipe_layanan'),

                                // Audit fields
                                Forms\Components\Hidden::make('dibuat_oleh')
                                    ->default(fn() => auth()->user()->name ?? 'System'),
                                Forms\Components\Hidden::make('dibuat_pada')
                                    ->default(now()),
                            ])
                            ->collapsible(),
                    ]),

                Section::make('Kondisi Lokasi')
                    ->description('Kondisi infrastruktur di lokasi pemasangan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('jumlah_pemakai')
                                    ->label('Jumlah Pemakai SR')
                                    ->numeric()
                                    ->maxLength(2)
                                    ->rules(['min:0', 'max:99'])
                                    ->validationMessages([
                                        'min' => 'Jumlah pemakai tidak boleh kurang dari 0',
                                        'max' => 'Jumlah pemakai tidak boleh lebih dari 99',
                                        'numeric' => 'Jumlah pemakai harus berupa angka',
                                    ])
                                    ->extraInputAttributes([
                                        'oninput' => 'this.value = this.value.replace(/[^0-9]/g, "")',
                                    ]),

                                Select::make('ada_toren')
                                    ->label('Ada Toren Air')
                                    ->options([
                                        'ya' => 'Ya',
                                        'tidak' => 'Tidak',
                                    ])
                                    ->required()
                                    ->default('tidak'),

                                Select::make('ada_sumur')
                                    ->label('Ada Sumur')
                                    ->options([
                                        'ya' => 'Ya',
                                        'tidak' => 'Tidak',
                                    ])
                                    ->required()
                                    ->default('tidak'),
                            ]),
                    ]),

                Section::make('Dokumen & Finansial')
                    ->description('Upload dokumen dan informasi dana')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('scan_identitas_utama')
                                    ->label('Scan Identitas Utama')
                                    ->image()
                                    ->directory('pendaftaran/identitas'),

                                Forms\Components\FileUpload::make('scan_dokumen_mou')
                                    ->label('Scan Dokumen MOU')
                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                    ->directory('pendaftaran/mou'),
                            ]),

                        Forms\Components\TextInput::make('dana_pengembalian')
                            ->label('Dana Pengembalian')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                    ]),

                Section::make('Metadata')
                    ->description('Informasi audit trail')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('dibuat_oleh')
                                    ->label('Dibuat Oleh')
                                    ->required()
                                    ->default(auth()->user()?->name)
                                    ->disabled(),

                                Forms\Components\DateTimePicker::make('dibuat_pada')
                                    ->label('Dibuat Pada')
                                    ->required()
                                    ->default(now())
                                    ->disabled(),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    private static function generateNomorRegistrasi(callable $get, callable $set): void
    {
        $idCabang = $get('id_cabang');
        $tanggalDaftar = $get('tanggal_daftar');

        if (!$idCabang || !$tanggalDaftar) {
            $set('nomor_registrasi', 'Akan di-generate otomatis');
            return;
        }

        try {
            // Ambil data cabang
            $cabang = \App\Models\Cabang::find($idCabang);
            if (!$cabang) {
                $set('nomor_registrasi', 'Akan di-generate otomatis');
                return;
            }

            // Parse tanggal
            $tanggal = \Carbon\Carbon::parse($tanggalDaftar);
            $bulan = $tanggal->format('m');
            $tahun = $tanggal->format('Y');

            // Konversi ke angka romawi untuk preview
            $bulanRomawi = self::convertToRoman((int)$bulan);
            $tahunRomawi = self::convertToRoman((int)$tahun);

            // Format preview: AAA/[AUTO]/B/TTTTT
            $previewNomorRegistrasi = $cabang->kode_cabang . '/[AUTO]/' .
                $bulanRomawi . '/' .
                $tahunRomawi;

            $set('nomor_registrasi', $previewNomorRegistrasi);

        } catch (\Exception $e) {
            // Log error jika diperlukan
            \Log::error('Error generating nomor registrasi preview: ' . $e->getMessage());
            $set('nomor_registrasi', 'Akan di-generate otomatis');
        }
    }

    private static function convertToRoman($number): string
    {
        $map = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );

        $result = '';

        foreach ($map as $roman => $value) {
            $matches = intval($number / $value);
            $result .= str_repeat($roman, $matches);
            $number = $number % $value;
        }

        return $result;
    }

    private static function calculateTotalBiaya(callable $get, callable $set): void
    {
        try {
            $biayaTipeLayanan = (float) ($get('biaya_tipe_layanan') ?? 0);
            $biayaJenisDaftar = (float) ($get('biaya_jenis_daftar') ?? 0);
            $biayaTipePendaftaran = (float) ($get('biaya_tipe_pendaftaran') ?? 0);
            $biayaTambahan = (float) ($get('biaya_tambahan') ?? 0);

            // Subtotal sebelum pajak
            $subtotal = $biayaTipeLayanan + $biayaJenisDaftar + $biayaTipePendaftaran + $biayaTambahan;
            $set('subtotal_biaya', $subtotal);

            // Hitung pajak jika ada
            $nilaiPajak = (float) ($get('nilai_pajak') ?? 0);

            // Jika ada pajak yang dipilih, hitung ulang nilai pajak
            $idPajak = $get('id_pajak');
            if ($idPajak && $subtotal > 0) {
                $pajak = \App\Models\Pajak::find($idPajak);
                if ($pajak) {
                    $nilaiPajak = $pajak->hitungPajak($subtotal);
                    $set('nilai_pajak', $nilaiPajak);
                }
            }

            $total = $subtotal + $nilaiPajak;
            $set('total_biaya_pendaftaran', $total);

        } catch (\Exception $e) {
            \Log::error('Error calculating total biaya: ' . $e->getMessage());
            $set('total_biaya_pendaftaran', 0);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom nomor registrasi dengan nama di bawahnya
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->description(fn ($record): string => $record->nama_pemohon)
                    ->searchable(['nomor_registrasi', 'nama_pemohon'])
                    ->sortable(),

                // Tanggal daftar
                Tables\Columns\TextColumn::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->date('d M Y')
                    ->sortable(),

                // Gabungan Tipe Layanan dan Tipe Pendaftaran
                Tables\Columns\TextColumn::make('tipeLayanan.nama_tipe_layanan')
                    ->label('Layanan')
                    ->description(function ($record) {
                        $tipePendaftaran = $record->tipePendaftaran?->nama_tipe_pendaftaran ?? '-';
                        $jenisDaftar = $record->jenisDaftar?->nama_jenis_daftar ?? '-';
                        return $tipePendaftaran . ' • ' . $jenisDaftar;
                    })
                    ->searchable(['tipeLayanan.nama_tipe_layanan', 'tipePendaftaran.nama_tipe_pendaftaran', 'jenisDaftar.nama_jenis_daftar'])
                    ->wrap(),

                // Gabungan alamat dengan cabang, kecamatan, kelurahan
                Tables\Columns\TextColumn::make('cabang.nama_cabang')
                    ->label('Lokasi')
                    ->description(function ($record) {
                        $kelurahan = $record->kelurahan?->nama_kelurahan ?? '-';
                        $kecamatan = $record->kelurahan?->kecamatan?->nama_kecamatan ?? '-';
                        $alamat = \Str::limit($record->alamat_pemasangan ?? '-', 40);
                        return $kelurahan . ', ' . $kecamatan . "\n" . $alamat;
                    })
                    ->searchable(['cabang.nama_cabang', 'kelurahan.nama_kelurahan', 'alamat_pemasangan'])
                    ->wrap(),

                // Kontak
                Tables\Columns\TextColumn::make('no_hp_pemohon')
                    ->label('No. HP')
                    ->searchable(),

                // GPS dengan link ke maps
                Tables\Columns\TextColumn::make('coordinates')
                    ->label('GPS')
                    ->getStateUsing(function ($record) {
                        if ($record->latitude_awal && $record->longitude_awal) {
                            return 'Lihat Maps';
                        }
                        return 'Tidak ada';
                    })
                    ->url(function ($record) {
                        if ($record->latitude_awal && $record->longitude_awal) {
                            return "https://www.google.com/maps?q={$record->latitude_awal},{$record->longitude_awal}";
                        }
                        return null;
                    })
                    ->openUrlInNewTab()
                    ->color(function ($record) {
                        return ($record->latitude_awal && $record->longitude_awal) ? 'primary' : 'gray';
                    })
                    ->icon(function ($record) {
                        return ($record->latitude_awal && $record->longitude_awal) ? 'heroicon-o-map-pin' : 'heroicon-o-x-mark';
                    })
                    ->tooltip(fn ($record) => $record->latitude_awal && $record->longitude_awal
                        ? "Lat: {$record->latitude_awal}, Lng: {$record->longitude_awal}"
                        : 'Koordinat belum diset'),

                // Total biaya
                Tables\Columns\TextColumn::make('total_biaya_pendaftaran')
                    ->label('Total Biaya')
                    ->money('IDR')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR')
                            ->label('Total'),
                    ]),

                // Status pelanggan
                Tables\Columns\IconColumn::make('is_pelanggan')
                    ->label('Status')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !is_null($record->id_pelanggan))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->tooltip(fn ($record) => $record->id_pelanggan ? 'Sudah menjadi pelanggan' : 'Belum menjadi pelanggan'),

                // Kolom detail tambahan (hidden by default)
                Tables\Columns\TextColumn::make('biaya_tipe_layanan')
                    ->label('Biaya Tipe Layanan')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('biaya_jenis_daftar')
                    ->label('Biaya Jenis Daftar')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('biaya_tipe_pendaftaran')
                    ->label('Biaya Tipe Pendaftaran')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('pajak.nama_pajak')
                    ->label('Pajak')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nilai_pajak')
                    ->label('Nilai Pajak')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('jenis_identitas')
                    ->label('Jenis ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nomor_identitas')
                    ->label('Nomor ID')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('elevasi_awal_mdpl')
                    ->label('Elevasi (mdpl)')
                    ->numeric(decimalPlaces: 2)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('keterangan_arah_lokasi')
                    ->label('Keterangan Lokasi')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('ada_toren')
                    ->label('Ada Toren')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->ada_toren === 'ya')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('ada_sumur')
                    ->label('Ada Sumur')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->ada_sumur === 'ya')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('dibuat_oleh')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('id_cabang')
                    ->label('Cabang')
                    ->relationship('cabang', 'nama_cabang')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('id_tipe_layanan')
                    ->label('Tipe Layanan')
                    ->relationship('tipeLayanan', 'nama_tipe_layanan')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('id_jenis_daftar')
                    ->label('Jenis Daftar')
                    ->relationship('jenisDaftar', 'nama_jenis_daftar')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('id_tipe_pendaftaran')
                    ->label('Tipe Pendaftaran')
                    ->relationship('tipePendaftaran', 'nama_tipe_pendaftaran')
                    ->searchable()
                    ->preload(),

                Filter::make('is_pelanggan')
                    ->label('Status Pelanggan')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('id_pelanggan'))
                    ->toggle(),

                Filter::make('belum_pelanggan')
                    ->label('Belum Pelanggan')
                    ->query(fn (Builder $query): Builder => $query->whereNull('id_pelanggan'))
                    ->toggle(),

                Filter::make('tanggal_daftar')
                    ->form([
                        DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn (Builder $query, $date): Builder => $query->whereDate('tanggal_daftar', '>=', $date))
                            ->when($data['sampai_tanggal'], fn (Builder $query, $date): Builder => $query->whereDate('tanggal_daftar', '<=', $date));
                    }),
            ])
->actions([
    Tables\Actions\ActionGroup::make([
        Tables\Actions\ViewAction::make()
            ->icon('heroicon-o-eye')
            ->color('info'),

        Tables\Actions\EditAction::make()
            ->icon('heroicon-o-pencil-square')
            ->color('warning'),

        Tables\Actions\Action::make('print_faktur')
            ->label('Print Faktur')
            ->icon('heroicon-o-printer')
            ->color('success')
            ->url(fn ($record) => route('faktur.pembayaran', ['pendaftaran' => $record->id_pendaftaran]))
            ->openUrlInNewTab(),

        // Action untuk menyetujui pendaftaran dan membuat pelanggan
        Action::make('setujui_pendaftaran')
            ->label('Setujui & Buat Pelanggan')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->visible(fn ($record) => $record->status_pendaftaran === 'draft' && is_null($record->id_pelanggan))
            ->requiresConfirmation()
            ->modalHeading('Setujui Pendaftaran')
            ->modalDescription('Apakah Anda yakin ingin menyetujui pendaftaran ini? Sistem akan otomatis membuat data pelanggan.')
            ->action(function ($record) {
                // Buat pelanggan baru dari data pendaftaran
                $pelanggan = \App\Models\Pelanggan::create([
                    'nomor_pelanggan' => 'PLG-' . now()->format('YmdHis'),
                    'nama_pelanggan' => $record->nama_pemohon,
                    'nik' => $record->nik_pemohon,
                    'jenis_identitas' => $record->jenis_identitas,
                    'nomor_identitas' => $record->nomor_identitas,
                    'alamat' => $record->alamat_pemasangan,
                    'kelurahan' => $record->kelurahan_pemasangan,
                    'kecamatan' => 'Perlu Diisi',
                    'status_pelanggan' => 'aktif',
                    'golongan' => 'standar',
                    'tipe_pelanggan' => 'domestik',
                    'segment' => 'reguler',
                    'latitude' => $record->latitude_awal,
                    'longitude' => $record->longitude_awal,
                    'elevasi' => $record->elevasi_awal_mdpl,
                    'status_gis' => 'draft',
                    'dibuat_oleh' => auth()->user()->name,
                    'dibuat_pada' => now(),
                ]);

                // Update pendaftaran dengan ID pelanggan
                $record->update([
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'status_pendaftaran' => 'disetujui'
                ]);

                Notification::make()
                    ->title('Pendaftaran Disetujui!')
                    ->body("Pelanggan baru dengan nomor {$pelanggan->nomor_pelanggan} telah dibuat.")
                    ->success()
                    ->send();
            }),

        // Action untuk memproses ke tahap survei
        Action::make('proses_survei')
            ->label('Proses Survei')
            ->icon('heroicon-o-magnifying-glass')
            ->color('warning')
            ->visible(fn ($record) => $record->status_pendaftaran === 'disetujui')
            ->action(function ($record) {
                $record->update(['status_pendaftaran' => 'survei']);
                Notification::make()
                    ->title('Status diperbarui ke Tahap Survei')
                    ->success()
                    ->send();
            }),

        // Action terakhir, biasanya diletakkan paling bawah dalam group: Delete
        Tables\Actions\DeleteAction::make()
            ->icon('heroicon-o-trash')
            ->color('danger'),
    ])
    // Anda bisa menambahkan label untuk dropdown, misalnya:
    ->label('Opsi Pendaftaran')
    ->icon('heroicon-o-ellipsis-vertical') // Ikon titik tiga vertikal
    ->color('gray')
])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    BulkAction::make('print_multiple_faktur')
                        ->label('Print Multiple Faktur')
                        ->icon('heroicon-o-printer')
                        ->color('success')
                        ->action(function (Collection $records) {
                            // Generate multiple URLs untuk print
                            $urls = $records->map(function ($record) {
                                return route('faktur.pembayaran', ['pendaftaran' => $record->id_pendaftaran]);
                            })->toArray();

                            // JavaScript untuk membuka multiple tabs
                            $script = "
                                const urls = " . json_encode($urls) . ";
                                urls.forEach((url, index) => {
                                    setTimeout(() => {
                                        window.open(url, '_blank');
                                    }, index * 500); // Delay 500ms antar tab
                                });
                            ";

                            session()->flash('print_script', $script);

                            Notification::make()
                                ->title('Faktur Disiapkan')
                                ->body(count($records) . ' faktur akan dibuka di tab baru.')
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('approve_and_create_customer')
                        ->label('Setujui & Buat Pelanggan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Setujui Pendaftaran')
                        ->modalDescription('Apakah Anda yakin ingin menyetujui pendaftaran ini dan membuat data pelanggan?')
                        ->action(function (Collection $records) {
                            $created = 0;
                            $already_approved = 0;

                            foreach ($records as $pendaftaran) {
                                // Skip if already has pelanggan
                                if ($pendaftaran->id_pelanggan) {
                                    $already_approved++;
                                    continue;
                                }

                                // Generate nomor pelanggan
                                $tahun = date('Y');
                                $lastCustomer = Pelanggan::whereYear('created_at', $tahun)
                                    ->orderBy('nomor_pelanggan', 'desc')
                                    ->first();

                                if ($lastCustomer && preg_match('/(\d{4})(\d{6})/', $lastCustomer->nomor_pelanggan, $matches)) {
                                    $urutan = intval($matches[2]) + 1;
                                } else {
                                    $urutan = 1;
                                }

                                $nomorPelanggan = $tahun . str_pad($urutan, 6, '0', STR_PAD_LEFT);

                                // Create pelanggan
                                $pelanggan = Pelanggan::create([
                                    'nomor_pelanggan' => $nomorPelanggan,
                                    'nama_pelanggan' => $pendaftaran->nama_pemohon,
                                    'nik' => $pendaftaran->nik_pemohon,
                                    'alamat' => $pendaftaran->alamat_pemasangan,
                                    'kelurahan' => $pendaftaran->kelurahan_pemasangan,
                                    'kecamatan' => $pendaftaran->kecamatan_pemasangan,
                                    'nomor_hp' => $pendaftaran->no_hp_pemohon,
                                    'status_pelanggan' => 'aktif',
                                    'golongan' => 'rumah_tangga', // Default golongan
                                    'latitude' => $pendaftaran->latitude_awal,
                                    'longitude' => $pendaftaran->longitude_awal,
                                ]);

                                // Update pendaftaran
                                $pendaftaran->update([
                                    'id_pelanggan' => $pelanggan->id,
                                ]);

                                $created++;
                            }

                            if ($created > 0) {
                                Notification::make()
                                    ->title('Berhasil!')
                                    ->body("$created pendaftaran berhasil disetujui dan dibuat data pelanggan.")
                                    ->success()
                                    ->send();
                            }

                            if ($already_approved > 0) {
                                Notification::make()
                                    ->title('Informasi')
                                    ->body("$already_approved pendaftaran sudah memiliki data pelanggan.")
                                    ->warning()
                                    ->send();
                            }
                        })
                        ->visible(function (Collection $records = null) {
                            if (!$records) return false;
                            return $records->some(fn ($record) => is_null($record->id_pelanggan));
                        }),
                ]),
            ])
            ->defaultSort('tanggal_daftar', 'desc');
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
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'view' => Pages\ViewPendaftaran::route('/{record}'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
