<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KecamatanResource\Pages;
use App\Filament\Resources\KecamatanResource\RelationManagers;
use App\Models\Kecamatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KecamatanResource extends Resource
{
    protected static ?string $model = Kecamatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Kecamatan';

    protected static ?string $modelLabel = 'Kecamatan';

    protected static ?string $pluralModelLabel = 'Kecamatan';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kecamatan')
                    ->description('Data kecamatan di Kabupaten Purbalingga')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_kecamatan')
                                    ->label('Kode Kecamatan')
                                    ->required()
                                    ->maxLength(10)
                                    ->placeholder('Contoh: PBG01')
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('nama_kecamatan')
                                    ->label('Nama Kecamatan')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kota')
                                    ->label('Kota/Kabupaten')
                                    ->required()
                                    ->maxLength(255)
                                    ->default('Kabupaten Purbalingga'),

                                Forms\Components\TextInput::make('provinsi')
                                    ->label('Provinsi')
                                    ->required()
                                    ->maxLength(255)
                                    ->default('Jawa Tengah'),
                            ]),

                        Forms\Components\Toggle::make('status_aktif')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_kecamatan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_kecamatan')
                    ->label('Nama Kecamatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kota')
                    ->label('Kota/Kabupaten')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('provinsi')
                    ->label('Provinsi')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('kelurahans_count')
                    ->label('Jumlah Kelurahan')
                    ->counts('kelurahans')
                    ->badge()
                    ->color('success'),

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
            ->defaultSort('kode_kecamatan');
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
            'index' => Pages\ListKecamatans::route('/'),
            'create' => Pages\CreateKecamatan::route('/create'),
            'edit' => Pages\EditKecamatan::route('/{record}/edit'),
        ];
    }
}
