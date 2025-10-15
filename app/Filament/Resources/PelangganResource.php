<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Filament\Resources\PelangganResource\RelationManagers;
use App\Models\Pelanggan;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Pelanggan';

    protected static ?string $modelLabel = 'Pelanggan';

    protected static ?string $pluralModelLabel = 'Pelanggan';

    protected static ?string $navigationGroup = 'Data Utama';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pelanggan')
                    ->description('Data dasar identitas pelanggan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nomor_pelanggan')
                                    ->label('Nomor Pelanggan')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('nama_pelanggan')
                                    ->label('Nama Pelanggan')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Select::make('jenis_identitas')
                                    ->label('Jenis Identitas')
                                    ->options([
                                        'ktp' => 'KTP',
                                        'sim' => 'SIM',
                                        'passport' => 'Passport',
                                        'kartu_keluarga' => 'Kartu Keluarga',
                                    ]),

                                Forms\Components\TextInput::make('nomor_identitas')
                                    ->label('Nomor Identitas')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('nik')
                                    ->label('NIK')
                                    ->maxLength(16)
                                    ->numeric(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->maxLength(255),

                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir'),
                            ]),
                    ]),

                Section::make('Alamat Pelanggan')
                    ->description('Informasi alamat lengkap pelanggan')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('rt_rw')
                                    ->label('RT/RW')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('kelurahan')
                                    ->label('Kelurahan')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('kota')
                                    ->label('Kota')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('provinsi')
                                    ->label('Provinsi')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('kode_pos')
                                    ->label('Kode Pos')
                                    ->maxLength(10)
                                    ->numeric(),
                            ]),
                    ]),

                Section::make('Kontak')
                    ->description('Informasi kontak pelanggan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('nomor_hp')
                                    ->label('Nomor HP')
                                    ->tel()
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('nomor_telepon')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Status & Klasifikasi')
                    ->description('Status pelanggan dan klasifikasi layanan')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Select::make('status_pelanggan')
                                    ->label('Status Pelanggan')
                                    ->options(function () {
                                        return \App\Models\Status::getStatusOptions('pelanggan');
                                    })
                                    ->required()
                                    ->default('BARU'),

                                Select::make('golongan')
                                    ->label('Golongan')
                                    ->relationship('golonganPelanggan', 'nama_golongan')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('tipe_pelanggan')
                                    ->label('Tipe Pelanggan')
                                    ->options([
                                        'rumah_tangga' => 'Rumah Tangga',
                                        'komersial' => 'Komersial',
                                        'industri' => 'Industri',
                                        'sosial' => 'Sosial',
                                        'pemerintah' => 'Pemerintah',
                                    ]),

                                Select::make('segment')
                                    ->label('Segment')
                                    ->options([
                                        'premium' => 'Premium',
                                        'reguler' => 'Reguler',
                                        'ekonomi' => 'Ekonomi',
                                    ]),
                            ]),
                    ]),

                Section::make('Area & SPAM')
                    ->description('Penugasan area dan SPAM')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('id_area')
                                    ->label('Area')
                                    ->relationship('area', 'nama_area')
                                    ->searchable()
                                    ->preload(),

                                Select::make('id_spam')
                                    ->label('SPAM')
                                    ->relationship('spam', 'nama_spam')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Section::make('Koordinat GPS')
                    ->description('Posisi geografis pelanggan')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.00000001),

                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.00000001),

                                Forms\Components\TextInput::make('elevasi')
                                    ->label('Elevasi (MDPL)')
                                    ->numeric()
                                    ->suffix('meter'),

                                Forms\Components\TextInput::make('kode_gis')
                                    ->label('Kode GIS')
                                    ->maxLength(50),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Select::make('status_gis')
                                    ->label('Status GIS')
                                    ->options([
                                        'valid' => 'Valid',
                                        'belum_divalidasi' => 'Belum Divalidasi',
                                        'tidak_valid' => 'Tidak Valid',
                                    ])
                                    ->required()
                                    ->default('belum_divalidasi'),

                                Forms\Components\DatePicker::make('tgl_validasi_gis')
                                    ->label('Tanggal Validasi'),

                                Forms\Components\TextInput::make('validasi_gis_oleh')
                                    ->label('Divalidasi Oleh')
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('keterangan_gis')
                            ->label('Keterangan GIS')
                            ->rows(2),
                    ])
                    ->collapsible(),

                Section::make('Keterangan')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->rows(3),
                    ])
                    ->collapsible(),

                Section::make('Workflow & Audit')
                    ->description('Informasi workflow dan audit compliance')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('dibuat_oleh')
                                    ->label('Dibuat Oleh')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\DateTimePicker::make('dibuat_pada')
                                    ->label('Dibuat Pada')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\Select::make('status_historis')
                                    ->label('Status Historis')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'nonaktif' => 'Non Aktif',
                                        'arsip' => 'Arsip',
                                    ])
                                    ->default('aktif'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_nonaktif')
                                    ->label('Tanggal Non Aktif'),

                                Forms\Components\DatePicker::make('tanggal_arsip')
                                    ->label('Tanggal Arsip'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pelanggan')
                    ->label('No. Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->alamat . ', ' . $record->kelurahan . ', ' . $record->kecamatan;
                    }),

                Tables\Columns\TextColumn::make('nomor_hp')
                    ->label('No. HP')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\BadgeColumn::make('status_pelanggan')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        $status = \App\Models\Status::getStatusBadge('pelanggan', $state);
                        return $status['label'];
                    })
                    ->colors([
                        'info' => 'BARU',
                        'success' => 'AKTIF',
                        'warning' => 'TUTUP_SEMENTARA',
                        'danger' => 'TUTUP_TETAP',
                        'gray' => 'BONGKAR',
                    ]),

                Tables\Columns\TextColumn::make('golonganPelanggan.nama_golongan')
                    ->label('Golongan')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('tipe_pelanggan')
                    ->label('Tipe')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('kelurahan')
                    ->label('Kelurahan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('area.nama_area')
                    ->label('Area')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('spam.nama_spam')
                    ->label('SPAM')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status_gis')
                    ->label('Status GIS')
                    ->colors([
                        'success' => 'valid',
                        'warning' => 'belum_divalidasi',
                        'danger' => 'tidak_valid',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pelanggan')
                    ->label('Status Pelanggan')
                    ->options(function () {
                        return \App\Models\Status::getStatusOptions('pelanggan');
                    }),

                Tables\Filters\SelectFilter::make('tipe_pelanggan')
                    ->label('Tipe Pelanggan')
                    ->options([
                        'rumah_tangga' => 'Rumah Tangga',
                        'komersial' => 'Komersial',
                        'industri' => 'Industri',
                        'sosial' => 'Sosial',
                        'pemerintah' => 'Pemerintah',
                    ]),

                Tables\Filters\SelectFilter::make('golongan')
                    ->label('Golongan'),

                Tables\Filters\SelectFilter::make('kecamatan')
                    ->label('Kecamatan'),

                Tables\Filters\SelectFilter::make('status_gis')
                    ->label('Status GIS')
                    ->options([
                        'valid' => 'Valid',
                        'belum_divalidasi' => 'Belum Divalidasi',
                        'tidak_valid' => 'Tidak Valid',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('dibuat_pada', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PendaftaranRelationManager::class,
            RelationManagers\TagihanBulananRelationManager::class,
            RelationManagers\PembayaranRelationManager::class,
            RelationManagers\PengaduanRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
