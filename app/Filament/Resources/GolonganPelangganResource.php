<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GolonganPelangganResource\Pages;
use App\Filament\Resources\GolonganPelangganResource\RelationManagers;
use App\Models\GolonganPelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GolonganPelangganResource extends Resource
{
    protected static ?string $model = GolonganPelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Golongan Pelanggan';

    protected static ?string $modelLabel = 'Golongan Pelanggan';

    protected static ?string $pluralModelLabel = 'Golongan Pelanggan';

    protected static ?string $navigationGroup = 'Master Survei';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Golongan')
                    ->description('Data dasar golongan pelanggan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_golongan')
                                    ->label('Kode Golongan')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('SOC, KOM, IND, dll')
                                    ->maxLength(10),

                                Forms\Components\TextInput::make('nama_golongan')
                                    ->label('Nama Golongan')
                                    ->required()
                                    ->placeholder('Sosial, Komersial, Industri, dll')
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true),

                                Forms\Components\TextInput::make('urutan')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Untuk mengurutkan tampilan golongan'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_golongan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('nama_golongan')
                    ->label('Nama Golongan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

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
            ->defaultSort('urutan');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getApiTransformer()
    {
        return \App\Filament\Resources\GolonganPelangganResource\Api\Transformers\GolonganPelangganTransformer::class;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGolonganPelanggans::route('/'),
            'create' => Pages\CreateGolonganPelanggan::route('/create'),
            'edit' => Pages\EditGolonganPelanggan::route('/{record}/edit'),
        ];
    }
}
