<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KasResource\Pages;
use App\Models\Kas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Section;
use Illuminate\Support\Str;

class KasResource extends Resource
{
    protected static ?string $model = Kas::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Master Kas';

    protected static ?string $pluralModelLabel = 'Master Kas';

    protected static ?string $modelLabel = 'Kas';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kas')
                    ->description('Data utama master kas')
                    ->schema([
                        TextInput::make('kode')
                            ->label('Kode Kas')
                            ->required()
                            ->maxLength(3),

                        TextInput::make('nama_kas')
                            ->label('Nama Kas')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Kas Utama, Kas Kecil, dll'),

                        Toggle::make('status')
                            ->label('Status Aktif')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                    ])
                    ->columns(2),

                    Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('tunggakan')
                            ->label('Tunggakan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->step(0.01),

                        Forms\Components\TextInput::make('biaya_admin')
                            ->label('Biaya Admin')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->step(0.01),

                        Forms\Components\Toggle::make('deposit_mode')
                            ->label('Mode Deposit')
                            ->helperText('Aktifkan jika kas ini digunakan untuk deposit nasabah')
                            ->onColor('success')
                            ->offColor('danger'),
                    ])
                    ->columns(2)
                    ->collapsible(),
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

                TextColumn::make('nama_kas')
                    ->label('Nama Kas')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(40)
                    ->tooltip(function ($record) {
                        return $record->alamat;
                    })
                    ->toggleable(),

                TextColumn::make('tunggakan')
                    ->label('Tunggakan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('biaya_admin')
                    ->label('Biaya Admin')
                    ->money('IDR'),

                IconColumn::make('deposit_mode')
                    ->label('Deposit')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->placeholder('Semua'),
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
            'index'  => Pages\ListKas::route('/'),
            'create' => Pages\CreateKas::route('/create'),
            'edit'   => Pages\EditKas::route('/{record}/edit'),
        ];
    }
}
