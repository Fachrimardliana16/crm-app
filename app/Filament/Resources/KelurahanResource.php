<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelurahanResource\Pages;
use App\Filament\Resources\KelurahanResource\RelationManagers;
use App\Models\Kelurahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Set;

class KelurahanResource extends Resource
{
    protected static ?string $model = Kelurahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Kelurahan';

    protected static ?string $modelLabel = 'Kelurahan';

    protected static ?string $pluralModelLabel = 'Kelurahan';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kelurahan')
                    ->description('Data kelurahan di kecamatan terpilih')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_kelurahan')
                                    ->label('Kode Kelurahan')
                                    ->required()
                                    ->maxLength(15)
                                    ->placeholder('Contoh: PBG01001')
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('nama_kelurahan')
                                    ->label('Nama Kelurahan')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_kecamatan')
                                    ->label('Kecamatan')
                                    ->relationship('kecamatan', 'nama_kecamatan')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('kode_kecamatan')
                                            ->label('Kode Kecamatan')
                                            ->required()
                                            ->maxLength(10),
                                        Forms\Components\TextInput::make('nama_kecamatan')
                                            ->label('Nama Kecamatan')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('provinsi')
                                            ->label('Provinsi')
                                            ->required()
                                            ->default('Jawa Tengah'),
                                    ]),

                                Forms\Components\TextInput::make('kode_pos')
                                    ->label('Kode Pos')
                                    ->maxLength(10)
                                    ->placeholder('53xxx'),
                            ]),

                        Forms\Components\Toggle::make('status_aktif')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                    ]),

                Forms\Components\Section::make('Lokasi & Area Polygon')
                    ->description('Koordinat dan area cakupan kelurahan')
                    ->schema([
                        Map::make('location')
                            ->label('Lokasi & Area Kelurahan')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -7.388119, longitude: 109.358398)
                            ->draggable(true)
                            ->clickable(true)
                            ->zoom(14)
                            ->minZoom(10)
                            ->maxZoom(20)
                            ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                            ->detectRetina(true)
                            
                            // Marker Configuration
                            ->showMarker(true)
                            ->markerColor("#10b981")
                            
                            // Controls
                            ->showFullscreenControl(true)
                            ->showZoomControl(true)
                            
                            // GeoMan Integration for Polygon Drawing
                            ->geoMan(true)
                            ->geoManEditable(true)
                            ->geoManPosition('topleft')
                            ->drawMarker(false)
                            ->drawPolygon(true)
                            ->drawPolyline(false)
                            ->drawCircle(false)
                            ->drawRectangle(true)
                            ->drawText(false)
                            ->dragMode(true)
                            ->cutPolygon(true)
                            ->editPolygon(true)
                            ->deleteLayer(true)
                            ->setColor('#10b981')
                            ->setFilledColor('#d1fae5')
                            
                            // Extra styling untuk memberikan ruang yang cukup untuk toolbar
                            ->extraStyles([
                                'min-height: 500px',
                                'height: 500px',
                                'border-radius: 8px',
                                'border: 1px solid #e5e7eb'
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_kelurahan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_kelurahan')
                    ->label('Nama Kelurahan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kode_pos')
                    ->label('Kode Pos')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('status_aktif')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_kecamatan')
                    ->label('Kecamatan')
                    ->relationship('kecamatan', 'nama_kecamatan')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('status_aktif')
                    ->label('Status Aktif')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('kode_kelurahan');
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
            'index' => Pages\ListKelurahans::route('/'),
            'create' => Pages\CreateKelurahan::route('/create'),
            'edit' => Pages\EditKelurahan::route('/{record}/edit'),
        ];
    }
}
