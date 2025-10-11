<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveiResource\Pages;
use App\Filament\Resources\SurveiResource\RelationManagers;
use App\Models\Survei;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveiResource extends Resource
{
    protected static ?string $model = Survei::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Survei';

    protected static ?string $modelLabel = 'Survei';

    protected static ?string $pluralModelLabel = 'Survei';

    protected static ?string $navigationGroup = 'Workflow PDAM';

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
            'index' => Pages\ListSurveis::route('/'),
            'create' => Pages\CreateSurvei::route('/create'),
            'edit' => Pages\EditSurvei::route('/{record}/edit'),
        ];
    }
}
