<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubBagianResource\Pages;
use App\Models\SubBagian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubBagianResource extends Resource
{
    protected static ?string $model = SubBagian::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Sub Bagian';
    protected static ?string $pluralModelLabel = 'Sub Bagian';
    protected static ?int $navigationSort = 21;
    protected static ?string $navigationGroup = 'Master Organisasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('bagian_id')
                    ->label('Bagian')
                    ->relationship('bagian', 'nama_bagian')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('nama_sub_bagian')
                    ->label('Nama Sub Bagian')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bagian.nama_bagian')
                    ->label('Bagian')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('nama_sub_bagian')
                    ->label('Sub Bagian')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bagian_id')
                    ->label('Filter Bagian')
                    ->relationship('bagian', 'nama_bagian'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubBagians::route('/'),
            'create' => Pages\CreateSubBagian::route('/create'),
            'edit'   => Pages\EditSubBagian::route('/{record}/edit'),
        ];
    }
}
