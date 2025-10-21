<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpamResource\Pages;
use App\Filament\Resources\SpamResource\RelationManagers;
use App\Models\Spam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Set;

class SpamResource extends Resource
{
    protected static ?string $model = Spam::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'SPAM';

    protected static ?string $modelLabel = 'SPAM';

    protected static ?string $pluralModelLabel = 'SPAM';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi SPAM')
                    ->description('Data Sistem Penyediaan Air Minum')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_spam')
                                    ->label('Kode SPAM')
                                    ->maxLength(20)
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('nama_spam')
                                    ->label('Nama SPAM')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('alamat_spam')
                            ->label('Alamat SPAM')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('kelurahan')
                                    ->label('Kelurahan')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('kode_pos')
                                    ->label('Kode Pos')
                                    ->maxLength(10),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('telepon')
                                    ->label('Telepon')
                                    ->tel()
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(100),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kapasitas_produksi')
                                    ->label('Kapasitas Produksi (L/detik)')
                                    ->numeric()
                                    ->suffix('L/detik'),

                                Forms\Components\Select::make('status_operasional')
                                    ->label('Status Operasional')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'nonaktif' => 'Tidak Aktif',
                                        'maintenance' => 'Maintenance',
                                    ])
                                    ->default('aktif')
                                    ->native(false),
                            ]),

                        Forms\Components\Select::make('sumber_air')
                            ->label('Sumber Air')
                            ->options([
                                'Air Tanah' => 'Air Tanah',
                                'Air Permukaan' => 'Air Permukaan',
                                'Air Hujan' => 'Air Hujan',
                                'Campuran' => 'Campuran',
                            ])
                            ->placeholder('Pilih sumber air')
                            ->native(false),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Lokasi & Area Polygon')
                    ->description('Koordinat dan area cakupan SPAM')
                    ->schema([
                        Map::make('location')
                            ->label('Lokasi & Area Cakupan SPAM')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -7.388119, longitude: 109.358398)
                            ->draggable(true)
                            ->clickable(true)
                            ->zoom(13)
                            ->minZoom(8)
                            ->maxZoom(20)
                            ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                            ->detectRetina(true)
                            
                            // Marker Configuration
                            ->showMarker(true)
                            ->markerColor("#f59e0b")
                            
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
                            ->setColor('#f59e0b')
                            ->setFilledColor('#fef3c7')
                            
                            // Extra styling untuk memberikan ruang yang cukup untuk toolbar
                            ->extraStyles([
                                'min-height: 500px',
                                'height: 500px',
                                'border-radius: 8px',
                                'border: 1px solid #e5e7eb'
                            ]),
                    ]),

                // Hidden fields for audit
                Forms\Components\Hidden::make('dibuat_oleh')
                    ->default(fn() => auth()->user()->name ?? 'System'),
                Forms\Components\Hidden::make('dibuat_pada')
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_spam')
                    ->label('Kode SPAM')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nama_spam')
                    ->label('Nama SPAM')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('alamat_spam')
                    ->label('Alamat')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('kelurahan')
                    ->label('Kelurahan')
                    ->searchable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('kapasitas_produksi')
                    ->label('Kapasitas')
                    ->numeric()
                    ->suffix(' L/detik')
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\BadgeColumn::make('status_operasional')
                    ->label('Status')
                    ->colors([
                        'success' => 'aktif',
                        'danger' => 'nonaktif',
                        'warning' => 'maintenance',
                    ]),
                    
                Tables\Columns\TextColumn::make('sumber_air')
                    ->label('Sumber Air')
                    ->badge()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('dibuat_oleh')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSpam::route('/'),
            'create' => Pages\CreateSpam::route('/create'),
            'edit' => Pages\EditSpam::route('/{record}/edit'),
        ];
    }
}
