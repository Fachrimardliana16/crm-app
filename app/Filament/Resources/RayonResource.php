<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RayonResource\Pages;
use App\Filament\Resources\RayonResource\RelationManagers;
use App\Models\Rayon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RayonResource extends Resource
{
    protected static ?string $model = Rayon::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    
    protected static ?string $navigationGroup = 'Master Data';
    
    protected static ?int $navigationSort = 15;
    
    protected static ?string $pluralModelLabel = 'Rayon';
    
    protected static ?string $modelLabel = 'Rayon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar Rayon')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_rayon')
                                    ->label('Kode Rayon')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(2)
                                    ->minLength(2)
                                    ->placeholder('01')
                                    ->helperText('Format: 2 digit (01, 02, 03, dst)')
                                    ->mask('99')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state && strlen($state) < 2) {
                                            $set('kode_rayon', str_pad($state, 2, '0', STR_PAD_LEFT));
                                        }
                                    }),
                                    
                                Forms\Components\TextInput::make('nama_rayon')
                                    ->label('Nama Rayon')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Rayon Pusat Kota'),
                            ]),
                            
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
                                    ->placeholder('Kecamatan A, B, C'),
                                    
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
                    
                Forms\Components\Section::make('Informasi Geografis')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('koordinat_pusat_lat')
                                    ->label('Latitude Pusat')
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->placeholder('-7.2574719')
                                    ->helperText('Koordinat latitude pusat rayon'),
                                    
                                Forms\Components\TextInput::make('koordinat_pusat_lng')
                                    ->label('Longitude Pusat')
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->placeholder('112.7520883')
                                    ->helperText('Koordinat longitude pusat rayon'),
                                    
                                Forms\Components\TextInput::make('radius_coverage')
                                    ->label('Radius Coverage (meter)')
                                    ->numeric()
                                    ->step(1)
                                    ->placeholder('5000')
                                    ->helperText('Radius jangkauan dalam meter'),
                            ]),
                    ]),
                    
                Forms\Components\Section::make('Kapasitas & Pelanggan')
                    ->schema([
                        Forms\Components\Grid::make(2)
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
                                    ->placeholder('1000')
                                    ->helperText('Jumlah maksimal pelanggan'),
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
                Tables\Columns\TextColumn::make('kode_rayon')
                    ->label('Kode')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nama_rayon')
                    ->label('Nama Rayon')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                    
                Tables\Columns\TextColumn::make('wilayah')
                    ->label('Wilayah')
                    ->searchable()
                    ->limit(50),
                    
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
                    
                Tables\Columns\IconColumn::make('status_aktif')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => $record->status_aktif === 'aktif'),
                    
                Tables\Columns\TextColumn::make('subRayons')
                    ->label('Sub Rayon')
                    ->getStateUsing(fn ($record) => $record->subRayons()->count())
                    ->badge()
                    ->color('info')
                    ->alignCenter(),
                    
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
            ->defaultSort('kode_rayon', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            RayonResource\RelationManagers\SubRayonsRelationManager::class,
        ];
    }

    public static function getApiTransformer()
    {
        return \App\Filament\Resources\RayonResource\Api\Transformers\RayonTransformer::class;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRayons::route('/'),
            'create' => Pages\CreateRayon::route('/create'),
            'edit' => Pages\EditRayon::route('/{record}/edit'),
        ];
    }
}
