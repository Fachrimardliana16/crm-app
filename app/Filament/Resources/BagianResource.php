<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BagianResource\Pages;
use App\Models\Bagian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class BagianResource extends Resource
{
    protected static ?string $model = Bagian::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Bagian';
    protected static ?string $pluralModelLabel = 'Bagian';
    protected static ?string $modelLabel = 'Bagian';
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Master Organisasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Bagian')
                    ->schema([
                        TextInput::make('kode')
                            ->label('Kode')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('KEU, UMUM, TEK')
                            ->helperText('Gunakan huruf kapital tanpa spasi'),

                        TextInput::make('nama_bagian')
                            ->label('Nama Bagian')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Keuangan, Teknik, dll'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('nama_bagian')
                    ->label('Nama Bagian')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('subBagian_count')
                    ->label('Jumlah Sub Bagian')
                    ->counts('subBagian')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('nama_bagian')
                    ->form([
                        TextInput::make('nama_bagian')->placeholder('Cari nama bagian...'),
                    ])
                    ->query(function ($query, $data) {
                        if ($data['nama_bagian']) {
                            return $query->where('nama_bagian', 'like', '%' . $data['nama_bagian'] . '%');
                        }
                    }),
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
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index'  => Pages\ListBagians::route('/'),
            'create' => Pages\CreateBagian::route('/create'),
            'edit'   => Pages\EditBagian::route('/{record}/edit'),
        ];
    }
}
