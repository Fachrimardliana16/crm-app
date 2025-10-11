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
                Forms\Components\TextInput::make('nama_spam')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('wilayah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('aktif'),
                Forms\Components\TextInput::make('dibuat_oleh')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('dibuat_pada')
                    ->required(),
                Forms\Components\TextInput::make('diperbarui_oleh')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('diperbarui_pada'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_spam'),
                Tables\Columns\TextColumn::make('nama_spam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wilayah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dibuat_oleh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('diperbarui_oleh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('diperbarui_pada')
                    ->dateTime()
                    ->sortable(),
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
