<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaResource\Pages;
use App\Filament\Resources\AreaResource\RelationManagers;
use App\Models\Area;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Area';

    protected static ?string $modelLabel = 'Area';

    protected static ?string $pluralModelLabel = 'Area';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Area')
                    ->description('Data dasar area pelayanan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_area')
                                    ->label('Kode Area')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(10),

                                Forms\Components\TextInput::make('nama_area')
                                    ->label('Nama Area')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('deskripsi_area')
                            ->label('Deskripsi Area')
                            ->rows(3),

                        Forms\Components\Select::make('status_area')
                            ->label('Status Area')
                            ->options([
                                'aktif' => 'Aktif',
                                'non_aktif' => 'Non Aktif',
                                'maintenance' => 'Maintenance',
                            ])
                            ->required()
                            ->default('aktif'),
                    ]),

                Forms\Components\Section::make('Alamat Area')
                    ->description('Informasi lokasi area')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kelurahan')
                                    ->label('Kelurahan')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('kota')
                                    ->label('Kota')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('provinsi')
                                    ->label('Provinsi')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('kode_pos')
                                    ->label('Kode Pos')
                                    ->maxLength(10),
                            ]),
                    ]),

                Forms\Components\Section::make('Koordinat & Coverage')
                    ->description('Informasi geografis dan kapasitas')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('koordinat_pusat_lat')
                                    ->label('Latitude Pusat')
                                    ->numeric()
                                    ->step(0.00000001),

                                Forms\Components\TextInput::make('koordinat_pusat_lng')
                                    ->label('Longitude Pusat')
                                    ->numeric()
                                    ->step(0.00000001),

                                Forms\Components\TextInput::make('radius_coverage')
                                    ->label('Radius Coverage (KM)')
                                    ->numeric()
                                    ->step(0.1)
                                    ->suffix('km'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('jumlah_pelanggan')
                                    ->label('Jumlah Pelanggan Saat Ini')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled(),

                                Forms\Components\TextInput::make('kapasitas_maksimal')
                                    ->label('Kapasitas Maksimal')
                                    ->numeric()
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Section::make('Keterangan')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->rows(3),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_area')
                    ->label('Kode Area')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_area')
                    ->label('Nama Area')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status_area')
                    ->label('Status')
                    ->colors([
                        'success' => 'aktif',
                        'danger' => 'non_aktif',
                        'warning' => 'maintenance',
                    ]),

                Tables\Columns\TextColumn::make('full_address')
                    ->label('Lokasi')
                    ->getStateUsing(fn ($record) =>
                        trim($record->kelurahan . ', ' . $record->kecamatan . ', ' . $record->kota, ', ')
                    )
                    ->wrap(),

                Tables\Columns\TextColumn::make('capacity_info')
                    ->label('Kapasitas')
                    ->getStateUsing(fn ($record) =>
                        $record->jumlah_pelanggan . ' / ' . $record->kapasitas_maksimal
                    ),

                Tables\Columns\TextColumn::make('capacity_percentage')
                    ->label('Utilitas')
                    ->getStateUsing(fn ($record) => $record->capacity_percentage . '%')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 90 => 'danger',
                        $state >= 70 => 'warning',
                        default => 'success'
                    }),

                Tables\Columns\IconColumn::make('has_coordinates')
                    ->label('GPS')
                    ->boolean()
                    ->getStateUsing(fn ($record) =>
                        $record->koordinat_pusat_lat && $record->koordinat_pusat_lng
                    ),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_area')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'non_aktif' => 'Non Aktif',
                        'maintenance' => 'Maintenance',
                    ]),

                Tables\Filters\SelectFilter::make('kota')
                    ->label('Kota')
                    ->options(function () {
                        return \App\Models\Area::distinct('kota')
                            ->whereNotNull('kota')
                            ->pluck('kota', 'kota')
                            ->toArray();
                    }),
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
            ->defaultSort('nama_area');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getApiTransformer()
    {
        return \App\Filament\Resources\AreaResource\Api\Transformers\AreaTransformer::class;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAreas::route('/'),
            'create' => Pages\CreateArea::route('/create'),
            'edit' => Pages\EditArea::route('/{record}/edit'),
        ];
    }
}
