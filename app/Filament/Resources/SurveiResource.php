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
use Afsakar\LeafletMapPicker\LeafletMapPicker;

class SurveiResource extends Resource
{
    protected static ?string $model = Survei::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Survei';

    protected static ?string $modelLabel = 'Survei';

    protected static ?string $pluralModelLabel = 'Survei';

    protected static ?string $navigationGroup = 'Workflow PDAM';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->schema([
                        Forms\Components\Select::make('id_pendaftaran')
                            ->label('Pilih Pendaftaran')
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
                                if ($state) {
                                    $pendaftaran = \App\Models\Pendaftaran::find($state);
                                    if ($pendaftaran) {
                                        $set('id_pelanggan', $pendaftaran->id_pelanggan);
                                    }
                                }
                            })
                            ->required()
                            ->helperText('Ketik nomor registrasi untuk mencari')
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

                        Forms\Components\Hidden::make('id_pelanggan'),
                    ]),

                Forms\Components\Section::make('Detail Survei')
                    ->schema([
                        Forms\Components\Select::make('id_spam')
                            ->label('SPAM')
                            ->relationship('spam', 'nama_spam')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('nip_surveyor')
                            ->label('NIP Surveyor')
                            ->default(auth()->user()->email ?? auth()->id())
                            ->readOnly()
                            ->maxLength(20)
                            ->helperText('Terisi otomatis sesuai user yang login'),

                        Forms\Components\DatePicker::make('tanggal_survei')
                            ->label('Tanggal Survei')
                            ->default(now())
                            ->native(false),

                        Forms\Components\Select::make('status_survei')
                            ->label('Status Survei')
                            ->options([
                                'draft' => 'Draft',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('draft')
                            ->required()
                            ->live(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Lokasi & Koordinat')
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
                            ->label('Latitude Terverifikasi')
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
                            ->label('Longitude Terverifikasi')
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
                    ])
                    ->columns(2)
                    ->visible(fn (Forms\Get $get) => in_array($get('status_survei'), ['draft', 'disetujui'])),

                Forms\Components\Section::make('Parameter Survei')
                    ->schema([
                        Forms\Components\Select::make('master_luas_tanah_id')
                            ->label('Luas Tanah')
                            ->relationship('masterLuasTanah', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_luas_bangunan_id')
                            ->label('Luas Bangunan')
                            ->relationship('masterLuasBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_lokasi_bangunan_id')
                            ->label('Lokasi Bangunan')
                            ->relationship('masterLokasiBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_dinding_bangunan_id')
                            ->label('Dinding Bangunan')
                            ->relationship('masterDindingBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_lantai_bangunan_id')
                            ->label('Lantai Bangunan')
                            ->relationship('masterLantaiBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_atap_bangunan_id')
                            ->label('Atap Bangunan')
                            ->relationship('masterAtapBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_pagar_bangunan_id')
                            ->label('Pagar Bangunan')
                            ->relationship('masterPagarBangunan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_kondisi_jalan_id')
                            ->label('Kondisi Jalan')
                            ->relationship('masterKondisiJalan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_daya_listrik_id')
                            ->label('Daya Listrik')
                            ->relationship('masterDayaListrik', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_fungsi_rumah_id')
                            ->label('Fungsi/Status Rumah')
                            ->relationship('masterFungsiRumah', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),

                        Forms\Components\Select::make('master_kepemilikan_kendaraan_id')
                            ->label('Kepemilikan Kendaraan')
                            ->relationship('masterKepemilikanKendaraan', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (Skor: {$record->skor})")
                            ->live()
                            ->searchable(),
                    ])
                    ->columns(3)
                    ->visible(fn (Forms\Get $get) => in_array($get('status_survei'), ['draft', 'disetujui']))
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        // Auto calculate score when any parameter changes
                        $totalScore = 0;

                        $masterIds = [
                            'master_luas_tanah_id',
                            'master_luas_bangunan_id',
                            'master_lokasi_bangunan_id',
                            'master_dinding_bangunan_id',
                            'master_lantai_bangunan_id',
                            'master_atap_bangunan_id',
                            'master_pagar_bangunan_id',
                            'master_kondisi_jalan_id',
                            'master_daya_listrik_id',
                            'master_fungsi_rumah_id',
                            'master_kepemilikan_kendaraan_id',
                        ];

                        foreach ($masterIds as $masterId) {
                            $id = $get($masterId);
                            if ($id) {
                                $tableName = str_replace('_id', '', $masterId);
                                $record = \DB::table($tableName)->where('id', $id)->first();
                                if ($record) {
                                    $totalScore += $record->skor;
                                }
                            }
                        }

                        $set('skor_total', $totalScore);

                        // Set kategori golongan berdasarkan skor
                        if ($totalScore >= 91) {
                            $set('kategori_golongan', 'A');
                        } elseif ($totalScore >= 71) {
                            $set('kategori_golongan', 'B');
                        } elseif ($totalScore >= 51) {
                            $set('kategori_golongan', 'C');
                        } elseif ($totalScore >= 30) {
                            $set('kategori_golongan', 'D');
                        } else {
                            $set('kategori_golongan', null);
                        }
                    }),

                Forms\Components\Section::make('Dokumentasi Foto')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_peta_lokasi')
                            ->label('Foto Peta Lokasi')
                            ->image()
                            ->directory('survei/foto')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('foto_tanah_bangunan')
                            ->label('Foto Tanah & Bangunan')
                            ->image()
                            ->directory('survei/foto')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('foto_dinding')
                            ->label('Foto Dinding Bangunan')
                            ->image()
                            ->directory('survei/foto')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('foto_lantai')
                            ->label('Foto Lantai Bangunan')
                            ->image()
                            ->directory('survei/foto')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('foto_atap')
                            ->label('Foto Atap Bangunan')
                            ->image()
                            ->directory('survei/foto')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('foto_pagar')
                            ->label('Foto Pagar Bangunan')
                            ->image()
                            ->directory('survei/foto')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('foto_jalan')
                            ->label('Foto Kondisi Jalan')
                            ->image()
                            ->directory('survei/foto')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('foto_meteran_listrik')
                            ->label('Foto Meteran/Daya Listrik')
                            ->image()
                            ->directory('survei/foto')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->maxSize(5120),
                    ])
                    ->columns(4)
                    ->visible(fn (Forms\Get $get) => in_array($get('status_survei'), ['draft', 'disetujui'])),

                Forms\Components\Section::make('Hasil Survei')
                    ->schema([
                        Forms\Components\Select::make('hasil_survei')
                            ->label('Hasil Survei')
                            ->options([
                                'direkomendasikan' => 'Direkomendasikan',
                                'tidak_direkomendasikan' => 'Tidak Direkomendasikan',
                                'perlu_review' => 'Perlu Review Ulang',
                            ])
                            ->live()
                            ->columnSpanFull(),

                        // Grid 1: Skor Total dan Kategori Golongan (2 grid sejajar)
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('skor_total')
                                    ->label('Skor Total')
                                    ->numeric()
                                    ->disabled()
                                    ->suffix('poin'),

                                Forms\Components\Select::make('kategori_golongan')
                                    ->label('Kategori Golongan')
                                    ->options([
                                        'A' => 'Golongan A (Skor 91-110)',
                                        'B' => 'Golongan B (Skor 71-90)',
                                        'C' => 'Golongan C (Skor 51-70)',
                                        'D' => 'Golongan D (Skor 30-50)',
                                    ])
                                    ->disabled(),
                            ]),

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
                            ->default(fn() => auth()->user()->name ?? 'System'),
                        Forms\Components\Hidden::make('dibuat_pada')
                            ->default(now()),
                        Forms\Components\Hidden::make('diperbarui_oleh'),
                        Forms\Components\Hidden::make('diperbarui_pada'),
                    ])
                    ->visible(fn (Forms\Get $get) => in_array($get('status_survei'), ['draft', 'disetujui'])),
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

                Tables\Columns\TextColumn::make('pendaftaran.created_at')
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
            ->defaultSort('pendaftaran.created_at', 'desc')
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
