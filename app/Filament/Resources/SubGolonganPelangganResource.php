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

    protected static ?string $navigationGroup = 'Master Data';

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

                Forms\Components\Section::make('Tarif')
                    ->description('Pengaturan tarif untuk sub golongan')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('tarif_dasar')
                                    ->label('Tarif Dasar (Bulanan)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->helperText('Tarif tetap per bulan'),

                                Forms\Components\TextInput::make('tarif_per_m3')
                                    ->label('Tarif Per M³')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->helperText('Tarif per meter kubik'),

                                Forms\Components\TextInput::make('batas_minimum_m3')
                                    ->label('Batas Minimum (M³)')
                                    ->numeric()
                                    ->suffix('M³')
                                    ->default(0)
                                    ->helperText('Pemakaian minimum'),
                            ]),

                        Forms\Components\Fieldset::make('Tarif Progresif')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('tarif_progresif_1')
                                            ->label('Blok 2 (Per M³)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->helperText('Tarif blok kedua'),

                                        Forms\Components\TextInput::make('tarif_progresif_2')
                                            ->label('Blok 3 (Per M³)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->helperText('Tarif blok ketiga'),

                                        Forms\Components\TextInput::make('tarif_progresif_3')
                                            ->label('Blok 4+ (Per M³)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->helperText('Tarif blok keempat dan seterusnya'),
                                    ]),
                            ]),

                        Forms\Components\Fieldset::make('Biaya Tetap')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('biaya_beban_tetap')
                                            ->label('Biaya Beban Tetap')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0)
                                            ->helperText('Biaya tetap bulanan'),

                                        Forms\Components\TextInput::make('biaya_administrasi')
                                            ->label('Biaya Administrasi')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0)
                                            ->helperText('Biaya admin'),

                                        Forms\Components\TextInput::make('biaya_pemeliharaan')
                                            ->label('Biaya Pemeliharaan')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0)
                                            ->helperText('Biaya maintenance'),
                                    ]),
                            ]),
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

                Tables\Columns\TextColumn::make('tarif_dasar')
                    ->label('Tarif Dasar')
                    ->money('IDR')
                    ->sortable()
                    ->placeholder('Tidak ada'),

                Tables\Columns\TextColumn::make('tarif_per_m3')
                    ->label('Tarif/M³')
                    ->money('IDR')
                    ->sortable()
                    ->placeholder('Tidak ada'),

                Tables\Columns\TextColumn::make('batas_minimum_m3')
                    ->label('Min M³')
                    ->suffix(' M³')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable()
                    ->toggleable(),

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubGolonganPelanggans::route('/'),
            'create' => Pages\CreateSubGolonganPelanggan::route('/create'),
            'edit' => Pages\EditSubGolonganPelanggan::route('/{record}/edit'),
        ];
    }
}
