<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanBulananResource\Pages;
use App\Filament\Resources\TagihanBulananResource\RelationManagers;
use App\Models\TagihanBulanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TagihanBulananResource extends Resource
{
    protected static ?string $model = TagihanBulanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Tagihan Bulanan';

    protected static ?string $modelLabel = 'Tagihan Bulanan';

    protected static ?string $pluralModelLabel = 'Tagihan Bulanan';

    protected static ?string $navigationGroup = 'Billing & Payment';

    protected static ?int $navigationSort = 1;

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
            'index' => Pages\ListTagihanBulanans::route('/'),
            'create' => Pages\CreateTagihanBulanan::route('/create'),
            'edit' => Pages\EditTagihanBulanan::route('/{record}/edit'),
        ];
    }
}
