<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveiResource\Pages;
use App\Filament\Resources\SurveiResource\RelationManagers;
use App\Models\Survei;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection;
use Afsakar\LeafletMapPicker\LeafletMapPicker;

class SurveiResource extends Resource
{
    protected static ?string $model = Survei::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Survei';

    protected static ?string $modelLabel = 'Survei';

    protected static ?string $pluralModelLabel = 'Survei';

    protected static ?string $navigationGroup = 'Workflow';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('id_pendaftaran')
                            ->label('Nomor Registrasi Pendaftaran')
                            ->relationship('pendaftaran', 'nomor_registrasi')
                            ->searchable(['nomor_registrasi'])
                            ->getSearchResultsUsing(fn (string $search): array =>
                                \App\Models\Pendaftaran::where('nomor_registrasi', 'like', "%{$search}%")
                                    ->limit(10)
                                    ->pluck('nomor_registrasi', 'id_pendaftaran')
                                    ->toArray()
                            )
                            ->getOptionLabelUsing(fn ($value): ?string =>
                                \App\Models\Pendaftaran::find($value)?->nomor_registrasi
                            )
                            ->live()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // Reset id_pelanggan terlebih dahulu
                                $set('id_pelanggan', null);

                                if ($state) {
                                    $pendaftaran = \App\Models\Pendaftaran::find($state);
                                    if ($pendaftaran) {
                                        // Jika pendaftaran sudah memiliki id_pelanggan, gunakan yang ada
                                        if ($pendaftaran->id_pelanggan) {
                                            $set('id_pelanggan', $pendaftaran->id_pelanggan);
                                        } else {
                                            // Jika belum ada pelanggan, buat pelanggan baru berdasarkan data pendaftaran
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
                                                    'status_pelanggan' => 'calon_pelanggan', // status awal
                                                    'latitude' => $pendaftaran->latitude_awal,
                                                    'longitude' => $pendaftaran->longitude_awal,
                                                    'elevasi' => $pendaftaran->elevasi_awal_mdpl,
                                                    'status_gis' => 'belum_divalidasi',
                                                    'status_historis' => 'aktif',
                                                    'dibuat_oleh' => auth()->user()->name ?? 'System',
                                                    'dibuat_pada' => now(),
                                                ]);

                                                // Update pendaftaran dengan id_pelanggan yang baru dibuat
                                                $pendaftaran->update(['id_pelanggan' => $pelanggan->id_pelanggan]);

                                                $set('id_pelanggan', $pelanggan->id_pelanggan);

                                                \Filament\Notifications\Notification::make()
                                                    ->title('Pelanggan Baru Dibuat')
                                                    ->body("Pelanggan '{$pelanggan->nama_pelanggan}' berhasil dibuat otomatis dari data pendaftaran")
                                                    ->success()
                                                    ->send();

                                            } catch (\Exception $e) {
                                                \Filament\Notifications\Notification::make()
                                                    ->title('Gagal Membuat Pelanggan')
                                                    ->body('Terjadi error saat membuat data pelanggan: ' . $e->getMessage())
                                                    ->danger()
                                                    ->send();
                                            }
                                        }
                                    }
                                }
                            })
                            ->required()
                            ->helperText('Ketik nomor registrasi untuk mencari')
                            ->validationAttribute('Pendaftaran')
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('info_pelanggan')
                            ->label('Informasi Pelanggan')
                            ->content(function (Forms\Get $get) {
                                if ($pendaftaranId = $get('id_pendaftaran')) {
                                    $pendaftaran = \App\Models\Pendaftaran::find($pendaftaranId);
                                    if ($pendaftaran) {
                                        return new \Illuminate\Support\HtmlString("
                                            <div class='space-y-1'>
                                                <div><strong>Nama Pemohon:</strong> {$pendaftaran->nama_pemohon}</div>
                                                <div><strong>Alamat Pemasangan:</strong> {$pendaftaran->alamat_pemasangan}</div>
                                                <div><strong>No. HP:</strong> {$pendaftaran->no_hp_pemohon}</div>
                                                <div><strong>NIK:</strong> {$pendaftaran->nomor_identitas}</div>
                                                <div><strong>Tanggal Daftar:</strong> " . date('d/m/Y', strtotime($pendaftaran->tanggal_daftar)) . "</div>
                                            </div>
                                        ");
                                    }
                                }
                                return 'Pilih pendaftaran terlebih dahulu';
                            })
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('id_pelanggan')
                            ->label('ID Pelanggan')
                            ->disabled()
                            ->visible(fn (Forms\Get $get) => !empty($get('id_pelanggan')))
                            ->helperText('Terisi otomatis saat memilih pendaftaran')
                            ->dehydrated()
                            ->required()
                            ->rule('required', function (Forms\Get $get) {
                                if (empty($get('id_pendaftaran'))) {
                                    return 'Pilih pendaftaran terlebih dahulu';
                                }
                                return true;
                            }),
                    ]),

                Forms\Components\Section::make('Lokasi & Koordinat')
                    ->collapsible()
                    ->schema([
                        LeafletMapPicker::make('location')
                            ->label('Pilih Lokasi (Klik pada Peta)')
                            ->columnSpanFull()
                            ->default([
                                'lat' => -6.200000,
                                'lng' => 106.816666
                            ])
                            ->live()
                            ->afterStateUpdated(function (callable $set, $state): void {
                                if ($state && isset($state['lat']) && isset($state['lng'])) {
                                    $set('latitude_terverifikasi', $state['lat']);
                                    $set('longitude_terverifikasi', $state['lng']);
                                    $set('lokasi_map', $state);
                                }
                            })
                            ->reactive(),

                        Forms\Components\TextInput::make('latitude_terverifikasi')
                            ->label('Latitude')
                            ->helperText('Koordinat latitude terverifikasi dari peta')
                            ->numeric()
                            ->step('0.00000001')
                            ->live()
                            ->afterStateUpdated(function (callable $set, callable $get, $state): void {
                                $lng = $get('longitude_terverifikasi');
                                if ($state && $lng) {
                                    $set('lokasi_map', ['lat' => (float) $state, 'lng' => (float) $lng]);
                                }
                            }),

                        Forms\Components\TextInput::make('longitude_terverifikasi')
                            ->label('Longitude')
                            ->helperText('Koordinat longitude terverifikasi dari peta')
                            ->numeric()
                            ->step('0.00000001')
                            ->live()
                            ->afterStateUpdated(function (callable $set, callable $get, $state): void {
                                $lat = $get('latitude_terverifikasi');
                                if ($state && $lat) {
                                    $set('lokasi_map', ['lat' => (float) $lat, 'lng' => (float) $state]);
                                }
                            }),

                        Forms\Components\TextInput::make('elevasi_terverifikasi_mdpl')
                            ->label('Elevasi Terverifikasi (MDPL)')
                            ->numeric()
                            ->step('0.01')
                            ->suffix('meter'),

                        Forms\Components\TextInput::make('jarak_pemasangan')
                            ->label('Jarak Pemasangan')
                            ->numeric()
                            ->step('0.01')
                            ->suffix('meter'),

                         Forms\Components\Select::make('id_spam')
                            ->label('SPAM')
                            ->relationship('spam', 'nama_spam')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn (Forms\Get $get) => in_array($get('status_survei'), ['draft', 'disetujui'])),

                Forms\Components\Section::make('Parameter Survei')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('master_luas_tanah_id')
                            ->label('Luas Tanah')
                            ->relationship('masterLuasTanah', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('master_luas_bangunan_id')
                            ->label('Luas Bangunan')
                            ->relationship('masterLuasBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('master_lokasi_bangunan_id')
                            ->label('Lokasi Bangunan')
                            ->relationship('masterLokasiBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('master_dinding_bangunan_id')
                            ->label('Dinding Bangunan')
                            ->relationship('masterDindingBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('master_lantai_bangunan_id')
                            ->label('Lantai Bangunan')
                            ->relationship('masterLantaiBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('master_atap_bangunan_id')
                            ->label('Atap Bangunan')
                            ->relationship('masterAtapBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('master_pagar_bangunan_id')
                            ->label('Pagar Bangunan')
                            ->relationship('masterPagarBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),


                        Forms\Components\Select::make('master_kondisi_jalan_id')
                            ->label('Kondisi Jalan')
                            ->relationship('masterKondisiJalan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('master_daya_listrik_id')
                            ->label('Daya Listrik')
                            ->relationship('masterDayaListrik', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('master_fungsi_rumah_id')
                            ->label('Fungsi/Status Rumah')
                            ->relationship('masterFungsiRumah', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('master_kepemilikan_kendaraan_id')
                            ->label('Kepemilikan Kendaraan')
                            ->relationship('masterKepemilikanKendaraan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable()
                            ->preload(),

                    ])
                    ->columns(3)
                    ->visible(fn (Forms\Get $get) => in_array($get('status_survei'), ['draft', 'disetujui']))
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        // Auto calculate score when any parameter changes
                        $totalScore = 0;

                        // Get scores from master data relationships
                        $masterIds = [
                            'master_luas_tanah_id' => \App\Models\MasterLuasTanah::class,
                            'master_luas_bangunan_id' => \App\Models\MasterLuasBangunan::class,
                            'master_lokasi_bangunan_id' => \App\Models\MasterLokasiBangunan::class,
                            'master_dinding_bangunan_id' => \App\Models\MasterDindingBangunan::class,
                            'master_lantai_bangunan_id' => \App\Models\MasterLantaiBangunan::class,
                            'master_atap_bangunan_id' => \App\Models\MasterAtapBangunan::class,
                            'master_pagar_bangunan_id' => \App\Models\MasterPagarBangunan::class,
                            'master_kondisi_jalan_id' => \App\Models\MasterKondisiJalan::class,
                            'master_daya_listrik_id' => \App\Models\MasterDayaListrik::class,
                            'master_fungsi_rumah_id' => \App\Models\MasterFungsiRumah::class,
                            'master_kepemilikan_kendaraan_id' => \App\Models\MasterKepemilikanKendaraan::class,
                        ];

                        foreach ($masterIds as $fieldName => $modelClass) {
                            $id = $get($fieldName);
                            if ($id) {
                                $record = $modelClass::find($id);
                                if ($record && $record->skor) {
                                    $totalScore += $record->skor;
                                }
                            }
                        }

                        $set('skor_total', $totalScore);

                        // Set kategori golongan berdasarkan relasi sub golongan (tidak hardcode)
                        if ($totalScore > 0) {
                            $subGolonganForCategory = \App\Models\SubGolonganPelanggan::whereNotNull('skor_minimum')
                                ->whereNotNull('skor_maksimum')
                                ->where('skor_minimum', '<=', $totalScore)
                                ->where('skor_maksimum', '>=', $totalScore)
                                ->with('golonganPelanggan')
                                ->first();

                            if ($subGolonganForCategory && $subGolonganForCategory->golonganPelanggan) {
                                $kategori = $subGolonganForCategory->golonganPelanggan->nama_golongan . ' (Skor ' . $subGolonganForCategory->skor_minimum . '-' . $subGolonganForCategory->skor_maksimum . ')';
                                $set('kategori_golongan', $kategori);
                            } else {
                                $set('kategori_golongan', 'Tidak ada golongan yang sesuai (Skor ' . $totalScore . ')');
                            }
                        } else {
                            $set('kategori_golongan', null);
                        }

                        // Tentukan rekomendasi sub golongan
                        if ($totalScore > 0) {
                            $subGolongan = \App\Models\SubGolonganPelanggan::rekomendasiSubGolongan($totalScore);

                            if ($subGolongan) {
                                $set('rekomendasi_sub_golongan_id', $subGolongan->id_sub_golongan_pelanggan);
                                $set('rekomendasi_sub_golongan_text', $subGolongan->nama_sub_golongan . ' (' . $subGolongan->scoring_range_display . ')');
                                $set('hasil_survei', 'direkomendasikan');
                            } else {
                                $set('rekomendasi_sub_golongan_id', null);
                                $set('rekomendasi_sub_golongan_text', 'Tidak ada sub golongan yang sesuai');
                                $set('hasil_survei', 'perlu_review');
                            }
                        } else {
                            $set('rekomendasi_sub_golongan_id', null);
                            $set('rekomendasi_sub_golongan_text', null);
                            $set('hasil_survei', null);
                        }
                    }),

                Forms\Components\Section::make('Dokumentasi Foto')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('foto_peta_lokasi')
                                    ->label('Foto Peta Lokasi')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->directory('survei/foto'),

                                Forms\Components\FileUpload::make('foto_tanah_bangunan')
                                    ->label('Foto Tanah & Bangunan')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->directory('survei/foto'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('foto_dinding')
                                    ->label('Foto Dinding')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->directory('survei/foto'),

                                Forms\Components\FileUpload::make('foto_lantai')
                                    ->label('Foto Lantai')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->directory('survei/foto'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('foto_atap')
                                    ->label('Foto Atap')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->directory('survei/foto'),

                                Forms\Components\FileUpload::make('foto_pagar')
                                    ->label('Foto Pagar')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->directory('survei/foto'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('foto_jalan')
                                    ->label('Foto Jalan')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->directory('survei/foto'),

                                Forms\Components\FileUpload::make('foto_meteran_listrik')
                                    ->label('Foto Meteran Listrik')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->directory('survei/foto'),
                            ]),
                    ])
                    ->visible(fn (Forms\Get $get) => in_array($get('status_survei'), ['draft', 'disetujui'])),

                Forms\Components\Section::make('Hasil Survei')
                    ->collapsible()
                    ->schema([
                        // Grid 1: Skor Total dan Kategori Golongan (2 grid sejajar)
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('skor_total')
                                    ->label('Skor Total')
                                    ->numeric()
                                    ->disabled()
                                    ->suffix('poin')
                                    ->helperText('Dihitung otomatis dari parameter survei'),

                                Forms\Components\TextInput::make('kategori_golongan')
                                    ->label('Kategori Golongan')
                                    ->disabled()
                                    ->placeholder('Akan terisi otomatis berdasarkan skor')
                                    ->helperText('Kategori golongan sesuai dengan sub golongan yang direkomendasikan'),
                            ]),

                        // Grid 2: Rekomendasi Sub Golongan
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Placeholder::make('rekomendasi_sub_golongan_text')
                                    ->label('Rekomendasi Sub Golongan')
                                    ->content(fn (Forms\Get $get) => $get('rekomendasi_sub_golongan_text') ?: 'Belum ada rekomendasi - lengkapi parameter survei')
                                    ->extraAttributes([
                                        'class' => 'text-sm font-medium text-primary-600'
                                    ]),

                                Forms\Components\Hidden::make('rekomendasi_sub_golongan_id'),
                            ]),

                        // Action Button untuk Auto Generate Sub Golongan
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generate_sub_golongan')
                                ->label('Generate Rekomendasi Sub Golongan')
                                ->icon('heroicon-o-calculator')
                                ->color('primary')
                                ->action(function (callable $set, callable $get) {
                                    $skorTotal = $get('skor_total') ?? 0;

                                    if ($skorTotal > 0) {
                                        $subGolongan = \App\Models\SubGolonganPelanggan::rekomendasiSubGolongan($skorTotal);

                                        if ($subGolongan) {
                                            $set('rekomendasi_sub_golongan_id', $subGolongan->id_sub_golongan_pelanggan);
                                            $set('rekomendasi_sub_golongan_text', $subGolongan->nama_sub_golongan . ' (' . $subGolongan->scoring_range_display . ')');
                                            $set('hasil_survei', 'direkomendasikan');

                                            \Filament\Notifications\Notification::make()
                                                ->title('Rekomendasi Berhasil Dihasilkan')
                                                ->body("Sub Golongan: {$subGolongan->nama_sub_golongan}")
                                                ->success()
                                                ->send();
                                        } else {
                                            $set('rekomendasi_sub_golongan_text', 'Tidak ada sub golongan yang sesuai dengan skor ' . $skorTotal);
                                            $set('hasil_survei', 'perlu_review');

                                            \Filament\Notifications\Notification::make()
                                                ->title('Tidak Ada Sub Golongan yang Sesuai')
                                                ->body("Skor {$skorTotal} tidak masuk dalam range sub golongan manapun")
                                                ->warning()
                                                ->send();
                                        }
                                    } else {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Parameter Belum Lengkap')
                                            ->body('Lengkapi parameter survei terlebih dahulu')
                                            ->warning()
                                            ->send();
                                    }
                                })
                                ->visible(fn (Forms\Get $get) => $get('skor_total') > 0),
                        ])
                        ->columnSpanFull(),

                        Forms\Components\Select::make('hasil_survei')
                            ->label('Hasil Survei')
                            ->options([
                                'direkomendasikan' => 'Direkomendasikan',
                                'tidak_direkomendasikan' => 'Tidak Direkomendasikan',
                                'perlu_review' => 'Perlu Review Ulang',
                            ])
                            ->live()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('catatan_teknis')
                            ->label('Catatan Survei')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('rekomendasi_teknis')
                            ->label('Rekomendasi')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),



                        // Hidden fields for additional data
                        Forms\Components\Hidden::make('subrayon'),
                        Forms\Components\Hidden::make('nilai_survei'),
                        Forms\Components\Hidden::make('golongan_survei'),
                        Forms\Components\Hidden::make('kelas_survei_input'),
                        Forms\Components\Hidden::make('lokasi_map'),

                        // Audit fields
                        Forms\Components\Hidden::make('dibuat_oleh')
                            ->default(fn() => auth()->user()->name ?? auth()->user()->email ?? 'System')
                            ->dehydrateStateUsing(fn($state) => $state ?: (auth()->user()->name ?? auth()->user()->email ?? 'System')),
                        Forms\Components\Hidden::make('dibuat_pada')
                            ->default(now())
                            ->dehydrateStateUsing(fn($state) => $state ?: now()),
                        Forms\Components\Hidden::make('diperbarui_oleh'),
                        Forms\Components\Hidden::make('diperbarui_pada'),
                    ])
                    ->visible(fn (Forms\Get $get) => in_array($get('status_survei'), ['draft', 'disetujui'])),

                Forms\Components\Section::make('Audit trail')
                    ->collapsible(true)
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_survei')
                            ->label('Tanggal Survei')
                            ->default(now())
                            ->native(false)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Diatur otomatis oleh sistem'),

                        Forms\Components\TextInput::make('nip_surveyor')
                            ->label('NIP Surveyor')
                            ->default(auth()->user()->email ?? auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(20)
                            ->helperText('Diatur otomatis sesuai user yang login'),

                        Forms\Components\Select::make('status_survei')
                            ->label('Status Survei')
                            ->options([
                                'draft' => 'Draft',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('draft')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->live()
                            ->helperText('Status dikelola oleh sistem'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('pelanggan.alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status_survei')
                    ->label('Status Survei')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'disetujui',
                        'danger' => 'ditolak',
                    ])
                    ->icons([
                        'heroicon-o-document' => 'draft',
                        'heroicon-o-check-badge' => 'disetujui',
                        'heroicon-o-x-circle' => 'ditolak',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_survei')
                    ->label('Tanggal Survei')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('Belum dijadwalkan'),

                Tables\Columns\TextColumn::make('nip_surveyor')
                    ->label('Surveyor')
                    ->searchable()
                    ->placeholder('Belum ditentukan')
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('rekomendasi_teknis')
                    ->label('Rekomendasi')
                    ->colors([
                        'success' => 'Layak',
                        'danger' => 'Tidak Layak',
                        'warning' => 'Perlu Perbaikan',
                        'secondary' => 'Perlu Survey Ulang',
                    ])
                    ->placeholder('Belum ada')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('nilai_survei')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('skor_total')
                    ->label('Skor Total')
                    ->numeric()
                    ->sortable()
                    ->suffix(' poin')
                    ->placeholder('0')
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        $record->skor_total >= 100 => 'success',
                        $record->skor_total >= 75 => 'warning',
                        $record->skor_total >= 50 => 'info',
                        default => 'danger'
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('kategori_golongan')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn ($record) => match ($record->kategori_golongan) {
                        'A' => 'success',
                        'B' => 'warning',
                        'C' => 'info',
                        'D' => 'danger',
                        default => 'secondary'
                    })
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('rekomendasiSubGolongan.nama_sub_golongan')
                    ->label('Sub Golongan')
                    ->searchable()
                    ->placeholder('Belum ditentukan')
                    ->badge()
                    ->color('primary')
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->rekomendasi_sub_golongan_text)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pendaftaran.dibuat_pada')
                    ->label('Tgl Pendaftaran')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_survei')
                    ->label('Status Survei')
                    ->options([
                        'draft' => 'Draft',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('rekomendasi_teknis')
                    ->label('Rekomendasi')
                    ->options([
                        'Layak' => 'Layak',
                        'Tidak Layak' => 'Tidak Layak',
                        'Perlu Perbaikan' => 'Perlu Perbaikan',
                        'Perlu Survey Ulang' => 'Perlu Survey Ulang',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('tanggal_survei')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_survei', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_survei', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari_tanggal'] ?? null) {
                            $indicators['dari_tanggal'] = 'Dari: ' . \Carbon\Carbon::parse($data['dari_tanggal'])->format('d/m/Y');
                        }
                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators['sampai_tanggal'] = 'Sampai: ' . \Carbon\Carbon::parse($data['sampai_tanggal'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),

                Tables\Filters\SelectFilter::make('kategori_golongan')
                    ->label('Kategori Golongan')
                    ->options([
                        'A' => 'Golongan A (≥100 poin)',
                        'B' => 'Golongan B (75-99 poin)',
                        'C' => 'Golongan C (50-74 poin)',
                        'D' => 'Golongan D (<50 poin)',
                    ]),

                Tables\Filters\SelectFilter::make('rekomendasi_sub_golongan_id')
                    ->label('Sub Golongan')
                    ->relationship('rekomendasiSubGolongan', 'nama_sub_golongan')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('ada_rekomendasi')
                    ->label('Ada Rekomendasi Sub Golongan')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('rekomendasi_sub_golongan_id'))
                    ->toggle(),

                Tables\Filters\Filter::make('skor_tinggi')
                    ->label('Skor Tinggi (≥75)')
                    ->query(fn (Builder $query): Builder => $query->where('skor_total', '>=', 75))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\Action::make('input_hasil')
                    ->label('Input Hasil')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status_survei === 'draft')
                    ->url(fn ($record) => self::getUrl('edit', ['record' => $record])),

                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil'),

                Tables\Actions\Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->status_survei === 'draft' && !empty($record->rekomendasi_teknis))
                    ->action(function ($record) {
                        $record->update([
                            'status_survei' => 'disetujui',
                            'diperbarui_oleh' => auth()->id(),
                            'diperbarui_pada' => now(),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Hasil Survei')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui hasil survei ini?'),

                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status_survei === 'draft')
                    ->action(function ($record) {
                        $record->update([
                            'status_survei' => 'ditolak',
                            'diperbarui_oleh' => auth()->id(),
                            'diperbarui_pada' => now(),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Hasil Survei')
                    ->modalDescription('Apakah Anda yakin ingin menolak hasil survei ini?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('bulk_generate_sub_golongan')
                        ->label('Generate Sub Golongan')
                        ->icon('heroicon-o-calculator')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('id_golongan_pelanggan')
                                ->label('Filter Golongan Pelanggan')
                                ->options(\App\Models\GolonganPelanggan::aktif()->pluck('nama_golongan', 'id_golongan_pelanggan'))
                                ->searchable()
                                ->nullable()
                                ->helperText('Kosongkan untuk mencari di semua golongan'),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $golonganId = $data['id_golongan_pelanggan'] ?? null;
                            $processed = 0;
                            $errors = 0;
                            $notFound = 0;

                            foreach ($records as $record) {
                                try {
                                    $skorTotal = $record->hitungTotalSkor();

                                    if ($skorTotal > 0) {
                                        $subGolongan = \App\Models\SubGolonganPelanggan::rekomendasiSubGolongan($skorTotal, $golonganId);

                                        if ($subGolongan) {
                                            $record->update([
                                                'skor_total' => $skorTotal,
                                                'rekomendasi_sub_golongan_id' => $subGolongan->id_sub_golongan_pelanggan,
                                                'rekomendasi_sub_golongan_text' => $subGolongan->nama_sub_golongan . ' (' . $subGolongan->scoring_range_display . ')',
                                                'hasil_survei' => 'direkomendasikan',
                                                'kategori_golongan' => $skorTotal >= 100 ? 'A' : ($skorTotal >= 75 ? 'B' : ($skorTotal >= 50 ? 'C' : 'D')),
                                            ]);
                                            $processed++;
                                        } else {
                                            $notFound++;
                                            $record->update([
                                                'skor_total' => $skorTotal,
                                                'hasil_survei' => 'perlu_review',
                                                'kategori_golongan' => $skorTotal >= 100 ? 'A' : ($skorTotal >= 75 ? 'B' : ($skorTotal >= 50 ? 'C' : 'D')),
                                            ]);
                                        }
                                    } else {
                                        $errors++;
                                    }
                                } catch (\Exception $e) {
                                    $errors++;
                                }
                            }

                            \Filament\Notifications\Notification::make()
                                ->title("Proses Generate Sub Golongan Selesai")
                                ->body("Berhasil: {$processed}, Tidak ditemukan: {$notFound}, Error: {$errors}")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Generate Sub Golongan untuk Survei Terpilih')
                        ->modalDescription('Sistem akan menghitung skor dan menentukan sub golongan untuk semua survei yang dipilih.')
                        ->modalSubmitActionLabel('Generate Sub Golongan'),

                    Tables\Actions\BulkAction::make('batch_approve')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function ($record) {
                                if ($record->status_survei === 'draft' && !empty($record->rekomendasi_teknis)) {
                                    $record->update([
                                        'status_survei' => 'disetujui',
                                        'diperbarui_oleh' => auth()->id(),
                                        'diperbarui_pada' => now(),
                                    ]);
                                }
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Setujui Hasil Survei Terpilih')
                        ->modalDescription('Hanya survei dengan status draft dan sudah ada rekomendasi yang akan disetujui.'),

                    Tables\Actions\BulkAction::make('batch_reject')
                        ->label('Tolak Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function ($record) {
                                if ($record->status_survei === 'draft') {
                                    $record->update([
                                        'status_survei' => 'ditolak',
                                        'diperbarui_oleh' => auth()->id(),
                                        'diperbarui_pada' => now(),
                                    ]);
                                }
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Hasil Survei Terpilih')
                        ->modalDescription('Hanya survei dengan status draft yang akan ditolak.'),
                ]),
            ])
            ->defaultSort('pendaftaran.dibuat_pada', 'desc')
            ->persistSortInSession()
            ->persistFiltersInSession();
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
            'index' => Pages\ListSurveis::route('/'),
            'create' => Pages\CreateSurvei::route('/create'),
            'view' => Pages\ViewSurvei::route('/{record}'),
            'edit' => Pages\EditSurvei::route('/{record}/edit'),
        ];
    }
}
