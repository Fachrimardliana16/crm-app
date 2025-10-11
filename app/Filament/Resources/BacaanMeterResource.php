<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BacaanMeterResource\Pages;
use App\Filament\Resources\BacaanMeterResource\RelationManagers;
use App\Models\BacaanMeter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BacaanMeterResource extends Resource
{
    protected static ?string $model = BacaanMeter::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationLabel = 'Bacaan Meter';

    protected static ?string $modelLabel = 'Bacaan Meter';

    protected static ?string $pluralModelLabel = 'Bacaan Meter';

    protected static ?string $navigationGroup = 'Billing & Payment';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListBacaanMeters::route('/'),
            'create' => Pages\CreateBacaanMeter::route('/create'),
            'edit' => Pages\EditBacaanMeter::route('/{record}/edit'),
        ];
    }
}
