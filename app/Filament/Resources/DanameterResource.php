<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DanameterResource\Pages;
use App\Filament\Resources\DanameterResource\RelationManagers;
use App\Models\Danameter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DanameterResource extends Resource
{
    protected static ?string $model = Danameter::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Danameter';

    protected static ?string $modelLabel = 'Danameter';

    protected static ?string $pluralModelLabel = 'Danameter';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Danameter')
                    ->description('Data tarif berdasarkan diameter pipa')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_danameter')
                                    ->label('Kode Danameter')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('D05, D75, D10, D15, dll')
                                    ->maxLength(10)
                                    ->helperText('Kode singkat diameter pipa (gunakan format D + angka)'),

                                Forms\Components\TextInput::make('diameter_pipa')
                                    ->label('Diameter Pipa')
                                    ->required()
                                    ->placeholder('Diameter 1/2", Diameter 3/4", dll')
                                    ->maxLength(20)
                                    ->helperText('Deskripsi diameter pipa'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('tarif_danameter')
                                    ->label('Tarif Danameter')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->helperText('Tarif bulanan berdasarkan diameter pipa'),

                                Forms\Components\TextInput::make('urutan')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Urutan tampilan (semakin kecil semakin atas)'),
                            ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Deskripsi tambahan untuk tarif danameter ini')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Apakah tarif danameter ini aktif digunakan?'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_danameter')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('diameter_pipa')
                    ->label('Diameter Pipa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tarif_danameter')
                    ->label('Tarif Danameter')
                    ->money('IDR')
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
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
            ->defaultSort('urutan')
            ->emptyStateHeading('Belum ada data danameter')
            ->emptyStateDescription('Silakan tambahkan data danameter untuk mulai mengelola tarif berdasarkan diameter pipa.')
            ->emptyStateIcon('heroicon-o-cog-6-tooth');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getApiTransformer()
    {
        return \App\Filament\Resources\DanameterResource\Api\Transformers\DanameterTransformer::class;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDanameters::route('/'),
            'create' => Pages\CreateDanameter::route('/create'),
            'edit' => Pages\EditDanameter::route('/{record}/edit'),
        ];
    }
}
