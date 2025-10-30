<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubGolonganPelangganResource\Pages;
use App\Filament\Resources\SubGolonganPelangganResource\RelationManagers;
use App\Models\SubGolonganPelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubGolonganPelangganResource extends Resource
{
    protected static ?string $model = SubGolonganPelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Sub Golongan Pelanggan';

    protected static ?string $modelLabel = 'Sub Golongan Pelanggan';

    protected static ?string $pluralModelLabel = 'Sub Golongan Pelanggan';

    protected static ?string $navigationGroup = 'Master Survei';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Sub Golongan')
                    ->description('Data sub golongan pelanggan')
                    ->schema([
                        Forms\Components\Select::make('id_golongan_pelanggan')
                            ->label('Golongan Pelanggan')
                            ->relationship('golonganPelanggan', 'nama_golongan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $golongan = \App\Models\GolonganPelanggan::find($state);
                                    if ($golongan) {
                                        // Generate kode otomatis
                                        $count = \App\Models\SubGolonganPelanggan::where('id_golongan_pelanggan', $state)->count();
                                        $kode = $golongan->kode_golongan . '-' . str_pad($count + 1, 2, '0', STR_PAD_LEFT);
                                        $set('kode_sub_golongan', $kode);
                                    }
                                }
                            }),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_sub_golongan')
                                    ->label('Kode Sub Golongan')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('SOC-01, KOM-01, dll')
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('nama_sub_golongan')
                                    ->label('Nama Sub Golongan')
                                    ->required()
                                    ->placeholder('Sosial Umum, Sosial Khusus, dll')
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Tarif PDAM Purbalingga')
                    ->description('Pengaturan tarif progresif per 10 m³ dan biaya tetap sub golongan')
                    ->schema([
                        Forms\Components\Fieldset::make('Biaya Tetap Sub Golongan')
                            ->schema([
                                Forms\Components\TextInput::make('biaya_tetap_subgolongan')
                                    ->label('Biaya Tetap Sub Golongan')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->helperText('Biaya tetap bulanan berdasarkan sub golongan pelanggan')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Fieldset::make('Tarif Progresif per 10 M³')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('tarif_blok_1')
                                            ->label('Blok 1 (0-10 m³)')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->helperText('Tarif untuk pemakaian 0 sampai 10 m³'),

                                        Forms\Components\TextInput::make('tarif_blok_2')
                                            ->label('Blok 2 (11-20 m³)')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->helperText('Tarif untuk pemakaian 11 sampai 20 m³'),
                                    ]),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('tarif_blok_3')
                                            ->label('Blok 3 (21-30 m³)')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->helperText('Tarif untuk pemakaian 21 sampai 30 m³'),

                                        Forms\Components\TextInput::make('tarif_blok_4')
                                            ->label('Blok 4 (>30 m³)')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->helperText('Tarif untuk pemakaian di atas 30 m³'),
                                    ]),
                            ]),

                        Forms\Components\Placeholder::make('info_tarif')
                            ->label('Informasi Perhitungan')
                            ->content('Tarif total = Biaya Tetap Sub Golongan + Tarif Danameter + Tarif Progresif berdasarkan volume pemakaian')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Sistem Scoring Survei')
                    ->description('Pengaturan range skor untuk menentukan sub golongan berdasarkan hasil survei pelanggan')
                    ->schema([
                        Forms\Components\Toggle::make('gunakan_scoring')
                            ->label('Gunakan Sistem Scoring Otomatis')
                            ->default(true)
                            ->live()
                            ->helperText('Jika diaktifkan, sistem akan otomatis menentukan sub golongan berdasarkan skor survei')
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('skor_minimum')
                                    ->label('Skor Minimum')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->helperText('Skor minimum untuk masuk ke sub golongan ini')
                                    ->visible(fn(Forms\Get $get) => $get('gunakan_scoring')),

                                Forms\Components\TextInput::make('skor_maksimum')
                                    ->label('Skor Maksimum')
                                    ->numeric()
                                    ->minValue(0)
                                    ->helperText('Skor maksimum (kosongkan jika tidak terbatas)')
                                    ->visible(fn(Forms\Get $get) => $get('gunakan_scoring')),
                            ]),

                        Forms\Components\TextInput::make('prioritas_scoring')
                            ->label('Prioritas Scoring')
                            ->numeric()
                            ->default(0)
                            ->helperText('Prioritas jika ada overlap range skor (semakin tinggi semakin prioritas)')
                            ->visible(fn(Forms\Get $get) => $get('gunakan_scoring')),

                        Forms\Components\Textarea::make('kriteria_scoring')
                            ->label('Kriteria Scoring')
                            ->rows(3)
                            ->maxLength(1000)
                            ->helperText('Deskripsi kriteria dan parameter yang digunakan untuk scoring')
                            ->visible(fn(Forms\Get $get) => $get('gunakan_scoring'))
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('info_scoring')
                            ->label('Informasi Scoring')
                            ->content('Parameter survei yang dinilai: Luas Tanah, Luas Bangunan, Lokasi Bangunan, Material Dinding, Lantai, Atap, Pagar, Kondisi Jalan, Daya Listrik, Fungsi Rumah, dan Kepemilikan Kendaraan. Total skor maksimum sekitar 100-150 poin.')
                            ->visible(fn(Forms\Get $get) => $get('gunakan_scoring'))
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true),

                                Forms\Components\TextInput::make('urutan')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Untuk mengurutkan tampilan'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('golonganPelanggan.nama_golongan')
                    ->label('Golongan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('kode_sub_golongan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('nama_sub_golongan')
                    ->label('Nama Sub Golongan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('biaya_tetap_subgolongan')
                    ->label('Biaya Tetap')
                    ->money('IDR')
                    ->sortable()
                    ->placeholder('Tidak ada'),

                Tables\Columns\TextColumn::make('tarif_blok_1')
                    ->label('Blok 1 (0-10m³)')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tarif_blok_2')
                    ->label('Blok 2 (11-20m³)')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tarif_blok_3')
                    ->label('Blok 3 (21-30m³)')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tarif_blok_4')
                    ->label('Blok 4 (>30m³)')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('gunakan_scoring')
                    ->label('Auto Scoring')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('scoring_range_display')
                    ->label('Range Skor')
                    ->badge()
                    ->color(fn($record) => $record->gunakan_scoring ? 'info' : 'gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('prioritas_scoring')
                    ->label('Prioritas')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_golongan_pelanggan')
                    ->label('Golongan')
                    ->relationship('golonganPelanggan', 'nama_golongan')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Non-Aktif')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('gunakan_scoring')
                    ->label('Gunakan Scoring')
                    ->boolean()
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('urutan')
            ->groups([
                Tables\Grouping\Group::make('golonganPelanggan.nama_golongan')
                    ->label('Golongan Pelanggan')
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getApiTransformer()
    {
        return \App\Filament\Resources\SubGolonganPelangganResource\Api\Transformers\SubGolonganPelangganTransformer::class;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubGolonganPelanggans::route('/'),
            'create' => Pages\CreateSubGolonganPelanggan::route('/create'),
            'edit' => Pages\EditSubGolonganPelanggan::route('/{record}/edit'),
        ];
    }
}
