<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CabangResource\Pages;
use App\Models\Cabang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;

class CabangResource extends Resource
{
    protected static ?string $model = Cabang::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Cabang/Unit';

    protected static ?string $modelLabel = 'Cabang/Unit';

    protected static ?string $pluralModelLabel = 'Cabang/Unit';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Cabang/Unit')
                    ->description('Data cabang atau unit pelayanan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_cabang')
                                    ->label('Kode Cabang')
                                    ->required()
                                    ->maxLength(10)
                                    ->placeholder('Contoh: CKB, UKM')
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('nama_cabang')
                                    ->label('Nama Cabang')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\TextInput::make('wilayah_pelayanan')
                            ->label('Wilayah Pelayanan')
                            ->maxLength(255)
                            ->placeholder('Contoh: Purbalingga Timur, Purbalingga Barat'),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('telepon')
                                    ->label('Telepon')
                                    ->tel()
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->rows(3),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kepala_cabang')
                                    ->label('Kepala Cabang')
                                    ->maxLength(255)
                                    ->placeholder('Nama kepala cabang/unit'),

                                Forms\Components\Toggle::make('status_aktif')
                                    ->label('Status Aktif')
                                    ->default(true),
                            ]),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(2),
                    ]),

                Forms\Components\Section::make('Lokasi & Geometri')
                    ->description('Informasi lokasi dan area cakupan cabang')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step('any')
                                    ->placeholder('Contoh: -7.2575'),

                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step('any')
                                    ->placeholder('Contoh: 112.7521'),
                            ]),

                        Forms\Components\Textarea::make('polygon_area')
                            ->label('Area Polygon (WKT)')
                            ->placeholder('Contoh: POLYGON((112.7521 -7.2575, 112.7522 -7.2576, ...))')
                            ->rows(3)
                            ->hint('Format Well-Known Text (WKT) untuk polygon area cakupan cabang'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_cabang')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_cabang')
                    ->label('Nama Cabang')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('wilayah_pelayanan')
                    ->label('Wilayah Pelayanan')
                    ->searchable()
                    ->placeholder('Tidak diisi'),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kepala_cabang')
                    ->label('Kepala Cabang')
                    ->searchable()
                    ->placeholder('Belum diisi'),

                Tables\Columns\IconColumn::make('status_aktif')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('latitude')
                    ->label('Koordinat')
                    ->formatStateUsing(function ($record) {
                        if ($record->latitude && $record->longitude) {
                            return number_format($record->latitude, 6) . ', ' . number_format($record->longitude, 6);
                        }
                        return '-';
                    })
                    ->tooltip('Latitude, Longitude')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('polygon_area_size')
                    ->label('Area Polygon')
                    ->formatStateUsing(function ($record) {
                        $area = $record->polygon_area_size;
                        if ($area) {
                            if ($area > 1000000) {
                                return number_format($area / 1000000, 2) . ' km²';
                            }
                            return number_format($area, 0) . ' m²';
                        }
                        return '-';
                    })
                    ->tooltip('Luas area polygon dalam meter persegi atau kilometer persegi')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_aktif')
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Tidak Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('kode_cabang', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getApiTransformer()
    {
        return \App\Filament\Resources\CabangResource\Api\Transformers\CabangTransformer::class;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCabangs::route('/'),
            'create' => Pages\CreateCabang::route('/create'),
            'edit' => Pages\EditCabang::route('/{record}/edit'),
        ];
    }
}
