<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipeLayananResource\Pages;
use App\Models\TipeLayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class TipeLayananResource extends Resource
{
    protected static ?string $model = TipeLayanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Tipe Layanan';

    protected static ?string $modelLabel = 'Tipe Layanan';

    protected static ?string $pluralModelLabel = 'Tipe Layanan';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Tipe Layanan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_tipe_layanan')
                                    ->label('Nama Tipe Layanan')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Sambungan Baru, Sambungan Pindah'),

                                Forms\Components\TextInput::make('kode_tipe_layanan')
                                    ->label('Kode Tipe Layanan')
                                    ->maxLength(10)
                                    ->placeholder('Contoh: SB, SP, PK'),
                            ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status_aktif')
                                    ->label('Status')
                                    ->options([
                                        true => 'Aktif',
                                        false => 'Non-Aktif',
                                    ])
                                    ->default(true)
                                    ->required(),

                                Forms\Components\TextInput::make('biaya_standar')
                                    ->label('Biaya Standar')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_tipe_layanan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_tipe_layanan')
                    ->label('Nama Tipe Layanan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('biaya_standar')
                    ->label('Biaya Standar')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

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
                Tables\Filters\SelectFilter::make('status_aktif')
                    ->label('Status')
                    ->options([
                        true => 'Aktif',
                        false => 'Non-Aktif',
                    ]),
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
            ->defaultSort('nama_tipe_layanan', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipeLayanans::route('/'),
            'create' => Pages\CreateTipeLayanan::route('/create'),
            'edit' => Pages\EditTipeLayanan::route('/{record}/edit'),
        ];
    }
}
