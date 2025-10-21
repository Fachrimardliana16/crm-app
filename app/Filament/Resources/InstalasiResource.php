<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstalasiResource\Pages;
use App\Models\Instalasi;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class InstalasiResource extends Resource
{
    protected static ?string $model = Instalasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Instalasi';
    protected static ?string $modelLabel = 'Instalasi';
    protected static ?string $pluralModelLabel = 'Instalasi';
    protected static ?string $navigationGroup = 'Workflow';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Instalasi')
                    ->description('Data dasar instalasi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_pendaftaran')
                                    ->label('Pendaftaran')
                                    ->relationship('pendaftaran', 'nama_pemohon')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('id_pelanggan')
                                    ->label('Pelanggan')
                                    ->relationship('pelanggan', 'nama_pelanggan')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('nip_teknisi')
                                    ->label('NIP Teknisi')
                                    ->required(),

                                Forms\Components\DatePicker::make('tanggal_instalasi')
                                    ->label('Tanggal Instalasi')
                                    ->required(),

                                Forms\Components\Select::make('status_instalasi')
                                    ->label('Status Instalasi')
                                    ->options([
                                        'terjadwal' => 'Terjadwal',
                                        'progres' => 'Dalam Progres',
                                        'selesai' => 'Selesai',
                                        'ditunda' => 'Ditunda',
                                    ])
                                    ->default('terjadwal')
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('jam_mulai')
                                    ->label('Jam Mulai'),

                                Forms\Components\TimePicker::make('jam_selesai')
                                    ->label('Jam Selesai'),
                            ]),
                    ]),

                Section::make('Detail Teknis Meter')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nomor_meter')
                                    ->label('Nomor Meter'),

                                Forms\Components\TextInput::make('merk_meter')
                                    ->label('Merk Meter'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('posisi_meter_latitude')
                                    ->label('Latitude Meter')
                                    ->numeric()
                                    ->step(0.00000001),

                                Forms\Components\TextInput::make('posisi_meter_longitude')
                                    ->label('Longitude Meter')
                                    ->numeric()
                                    ->step(0.00000001),

                                Forms\Components\TextInput::make('elevasi_meter_mdpl')
                                    ->label('Elevasi (MDPL)')
                                    ->numeric()
                                    ->suffix('meter'),
                            ]),
                    ]),

                Section::make('Dokumentasi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('foto_instalasi_sebelum')
                                    ->label('Foto Sebelum Instalasi')
                                    ->image()
                                    ->directory('instalasi/before'),

                                Forms\Components\FileUpload::make('foto_instalasi_sesudah')
                                    ->label('Foto Sesudah Instalasi')
                                    ->image()
                                    ->directory('instalasi/after'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('foto_meter_terpasang')
                                    ->label('Foto Meter Terpasang')
                                    ->image()
                                    ->directory('instalasi/meter'),

                                Forms\Components\FileUpload::make('berita_acara_instalasi')
                                    ->label('Berita Acara Instalasi')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->directory('instalasi/documents'),
                            ]),

                        Forms\Components\Textarea::make('catatan_instalasi')
                            ->label('Catatan Instalasi')
                            ->rows(3),
                    ]),

                Section::make('Metadata')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('dibuat_oleh')
                                    ->label('Dibuat Oleh')
                                    ->required(),

                                Forms\Components\DateTimePicker::make('dibuat_pada')
                                    ->label('Dibuat Pada')
                                    ->default(now()),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.nama_pemohon')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan.nomor_pelanggan')
                    ->label('No. Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nip_teknisi')
                    ->label('Teknisi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_instalasi')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status_instalasi')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'terjadwal',
                        'warning' => 'progres',
                        'success' => 'selesai',
                        'danger' => 'ditunda',
                    ]),

                Tables\Columns\TextColumn::make('nomor_meter')
                    ->label('No. Meter')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_instalasi')
                    ->label('Status')
                    ->options([
                        'terjadwal' => 'Terjadwal',
                        'progres' => 'Dalam Progres',
                        'selesai' => 'Selesai',
                        'ditunda' => 'Ditunda',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstalasis::route('/'),
            'create' => Pages\CreateInstalasi::route('/create'),
            'edit' => Pages\EditInstalasi::route('/{record}/edit'),
        ];
    }
}
