<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubRayonResource\Pages;
use App\Filament\Resources\SubRayonResource\RelationManagers;
use App\Models\SubRayon;
use App\Models\Rayon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Set;

class SubRayonResource extends Resource
{
    protected static ?string $model = SubRayon::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    
    protected static ?string $navigationGroup = 'Master Data';
    
    protected static ?int $navigationSort = 16;
    
    protected static ?string $pluralModelLabel = 'Sub Rayon';
    
    protected static ?string $modelLabel = 'Sub Rayon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar Sub Rayon')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_rayon')
                                    ->label('Rayon')
                                    ->required()
                                    ->relationship('rayon', 'nama_rayon')
                                    ->getOptionLabelFromRecordUsing(fn (Rayon $record): string => "[{$record->kode_rayon}] {$record->nama_rayon}")
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('kode_rayon')
                                            ->label('Kode Rayon')
                                            ->required()
                                            ->maxLength(2)
                                            ->default(fn () => Rayon::getNextKodeRayon()),
                                        Forms\Components\TextInput::make('nama_rayon')
                                            ->label('Nama Rayon')
                                            ->required(),
                                    ])
                                    ->native(false),
                                    
                                Forms\Components\TextInput::make('kode_sub_rayon')
                                    ->label('Kode Sub Rayon')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(4)
                                    ->minLength(4)
                                    ->placeholder('0001')
                                    ->helperText('Format: 4 digit (0001, 0002, 0003, dst)')
                                    ->mask('9999')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state && strlen($state) < 4) {
                                            $set('kode_sub_rayon', str_pad($state, 4, '0', STR_PAD_LEFT));
                                        }
                                    })
                                    ->default(fn () => SubRayon::getNextKodeSubRayon()),
                            ]),
                            
                        Forms\Components\TextInput::make('nama_sub_rayon')
                            ->label('Nama Sub Rayon')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Sub Rayon A')
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('wilayah')
                                    ->label('Wilayah')
                                    ->maxLength(255)
                                    ->placeholder('RT 01-05, RW 01-03'),
                                    
                                Forms\Components\Select::make('status_aktif')
                                    ->label('Status')
                                    ->required()
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'nonaktif' => 'Tidak Aktif',
                                    ])
                                    ->default('aktif')
                                    ->native(false),
                            ]),
                    ]),
                    
                Forms\Components\Section::make('Lokasi & Area Polygon')
                    ->description('Koordinat dan area cakupan sub rayon')
                    ->schema([
                        Map::make('location')
                            ->label('Lokasi & Area Sub Rayon')
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -7.388119, longitude: 109.358398)
                            ->draggable(true)
                            ->clickable(true)
                            ->zoom(14)
                            ->minZoom(12)
                            ->maxZoom(20)
                            ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                            ->detectRetina(true)
                            
                            // Marker Configuration
                            ->showMarker(true)
                            ->markerColor("#06b6d4")
                            
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
                            ->setColor('#06b6d4')
                            ->setFilledColor('#cffafe')
                            
                            // Extra styling untuk memberikan ruang yang cukup untuk toolbar
                            ->extraStyles([
                                'min-height: 500px',
                                'height: 500px',
                                'border-radius: 8px',
                                'border: 1px solid #e5e7eb'
                            ]),
                    ]),
                    
                Forms\Components\Section::make('Kapasitas & Pelanggan')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('jumlah_pelanggan')
                                    ->label('Jumlah Pelanggan Aktif')
                                    ->numeric()
                                    ->default(0)
                                    ->readOnly()
                                    ->helperText('Akan diupdate otomatis'),
                                    
                                Forms\Components\TextInput::make('kapasitas_maksimal')
                                    ->label('Kapasitas Maksimal')
                                    ->numeric()
                                    ->placeholder('500')
                                    ->helperText('Jumlah maksimal pelanggan'),
                                    
                                Forms\Components\TextInput::make('nomor_pelanggan_terakhir')
                                    ->label('Nomor Pelanggan Terakhir')
                                    ->numeric()
                                    ->default(0)
                                    ->readOnly()
                                    ->helperText('Counter untuk nomor urut pelanggan'),
                            ]),
                    ]),
                    
                Forms\Components\Section::make('Keterangan Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->maxLength(1000)
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Informasi Sistem')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('dibuat_oleh')
                                    ->label('Dibuat Oleh')
                                    ->default(auth()->user()?->name)
                                    ->required()
                                    ->maxLength(255)
                                    ->readOnly(),
                                    
                                Forms\Components\DateTimePicker::make('dibuat_pada')
                                    ->label('Dibuat Pada')
                                    ->default(now())
                                    ->required()
                                    ->readOnly(),
                            ]),
                    ])
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rayon.kode_rayon')
                    ->label('Rayon')
                    ->badge()
                    ->color('secondary')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('kode_sub_rayon')
                    ->label('Kode')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nama_sub_rayon')
                    ->label('Nama Sub Rayon')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                    
                Tables\Columns\TextColumn::make('kode_gabungan')
                    ->label('Kode Gabungan')
                    ->badge()
                    ->color('info')
                    ->getStateUsing(fn ($record) => $record->kode_gabungan)
                    ->tooltip('Format: Rayon + 2 digit terakhir Sub Rayon'),
                    
                Tables\Columns\TextColumn::make('wilayah')
                    ->label('Wilayah')
                    ->searchable()
                    ->limit(40),
                    
                Tables\Columns\TextColumn::make('jumlah_pelanggan')
                    ->label('Pelanggan')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($record) => $record->isAtCapacity() ? 'danger' : 'success'),
                    
                Tables\Columns\TextColumn::make('kapasitas_maksimal')
                    ->label('Kapasitas')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->placeholder('Tidak Terbatas'),
                    
                Tables\Columns\TextColumn::make('nomor_pelanggan_terakhir')
                    ->label('Nomor Terakhir')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('warning'),
                    
                Tables\Columns\IconColumn::make('status_aktif')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => $record->status_aktif === 'aktif'),
                    
                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('dibuat_oleh')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_rayon')
                    ->label('Rayon')
                    ->relationship('rayon', 'nama_rayon')
                    ->getOptionLabelFromRecordUsing(fn (Rayon $record): string => "[{$record->kode_rayon}] {$record->nama_rayon}")
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\SelectFilter::make('status_aktif')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Tidak Aktif',
                    ])
                    ->placeholder('Semua Status'),
                    
                Tables\Filters\Filter::make('at_capacity')
                    ->label('Mencapai Kapasitas')
                    ->query(fn (Builder $query): Builder => $query->whereRaw('jumlah_pelanggan >= kapasitas_maksimal'))
                    ->toggle(),
                    
                Tables\Filters\Filter::make('has_coordinates')
                    ->label('Ada Koordinat')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('koordinat_pusat_lat')->whereNotNull('koordinat_pusat_lng'))
                    ->toggle(),
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
            ->defaultSort('kode_sub_rayon', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
            'index' => Pages\ListSubRayons::route('/'),
            'create' => Pages\CreateSubRayon::route('/create'),
            'edit' => Pages\EditSubRayon::route('/{record}/edit'),
        ];
    }
}
