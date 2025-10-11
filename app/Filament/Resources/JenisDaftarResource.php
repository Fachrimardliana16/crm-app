<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisDaftarResource\Pages;
use App\Models\JenisDaftar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class JenisDaftarResource extends Resource
{
    protected static ?string $model = JenisDaftar::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Jenis Daftar';

    protected static ?string $modelLabel = 'Jenis Daftar';

    protected static ?string $pluralModelLabel = 'Jenis Daftar';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Jenis Daftar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_jenis_daftar')
                                    ->label('Nama Jenis Daftar')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Standar, Non Standar'),

                                Forms\Components\TextInput::make('kode_jenis_daftar')
                                    ->label('Kode Jenis Daftar')
                                    ->maxLength(10)
                                    ->placeholder('Contoh: STD, NS'),
                            ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('status_aktif')
                                    ->label('Status')
                                    ->options([
                                        true => 'Aktif',
                                        false => 'Non-Aktif',
                                    ])
                                    ->default(true)
                                    ->required(),

                                Forms\Components\TextInput::make('biaya_tambahan')
                                    ->label('Biaya Tambahan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0'),

                                Forms\Components\TextInput::make('lama_proses_hari')
                                    ->label('Lama Proses (Hari)')
                                    ->numeric()
                                    ->suffix('hari')
                                    ->placeholder('0'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_jenis_daftar')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_jenis_daftar')
                    ->label('Nama Jenis Daftar')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('biaya_tambahan')
                    ->label('Biaya Tambahan')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('lama_proses_hari')
                    ->label('Lama Proses')
                    ->suffix(' hari')
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
            ->defaultSort('nama_jenis_daftar', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJenisDaftars::route('/'),
            'create' => Pages\CreateJenisDaftar::route('/create'),
            'edit' => Pages\EditJenisDaftar::route('/{record}/edit'),
        ];
    }
}
