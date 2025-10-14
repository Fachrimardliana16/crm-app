<?php

namespace App\Filament\Resources\RayonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\SubRayon;

class SubRayonsRelationManager extends RelationManager
{
    protected static string $relationship = 'subRayons';

    protected static ?string $title = 'Sub Rayon';

    protected static ?string $modelLabel = 'Sub Rayon';

    protected static ?string $pluralModelLabel = 'Sub Rayon';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
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
                            }),
                            
                        Forms\Components\TextInput::make('nama_sub_rayon')
                            ->label('Nama Sub Rayon')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Sub Rayon A'),
                    ]),
                    
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(500)
                    ->rows(2)
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
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('kapasitas_maksimal')
                            ->label('Kapasitas Maksimal')
                            ->numeric()
                            ->placeholder('500')
                            ->helperText('Jumlah maksimal pelanggan'),
                            
                        Forms\Components\TextInput::make('jumlah_pelanggan')
                            ->label('Jumlah Pelanggan')
                            ->numeric()
                            ->default(0)
                            ->readOnly()
                            ->helperText('Akan diupdate otomatis'),
                    ]),
                    
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->maxLength(1000)
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_sub_rayon')
            ->columns([
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
                    
                Tables\Columns\TextColumn::make('wilayah')
                    ->label('Wilayah')
                    ->searchable()
                    ->limit(30),
                    
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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['dibuat_oleh'] = auth()->user()?->name ?? 'System';
                        $data['dibuat_pada'] = now();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['diperbarui_oleh'] = auth()->user()?->name ?? 'System';
                        $data['diperbarui_pada'] = now();
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('kode_sub_rayon', 'asc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}