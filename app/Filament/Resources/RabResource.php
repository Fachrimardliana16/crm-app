<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RabResource\Pages;
use App\Filament\Resources\RabResource\RelationManagers;
use App\Models\Rab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RabResource extends Resource
{
    protected static ?string $model = Rab::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'RAB';

    protected static ?string $modelLabel = 'RAB';

    protected static ?string $pluralModelLabel = 'RAB';

    protected static ?string $navigationGroup = 'Workflow PDAM';

    protected static ?int $navigationSort = 3;

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
            'index' => Pages\ListRabs::route('/'),
            'create' => Pages\CreateRab::route('/create'),
            'edit' => Pages\EditRab::route('/{record}/edit'),
        ];
    }
}
