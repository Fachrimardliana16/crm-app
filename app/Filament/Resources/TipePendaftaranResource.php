<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipePendaftaranResource\Pages;
use App\Models\TipePendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class TipePendaftaranResource extends Resource
{
    protected static ?string $model = TipePendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Tipe Pendaftaran';

    protected static ?string $modelLabel = 'Tipe Pendaftaran';

    protected static ?string $pluralModelLabel = 'Tipe Pendaftaran';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Tipe Pendaftaran')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_tipe_pendaftaran')
                                    ->label('Nama Tipe Pendaftaran')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Online, Offline, Express'),

                                Forms\Components\TextInput::make('kode_tipe_pendaftaran')
                                    ->label('Kode Tipe Pendaftaran')
                                    ->maxLength(10)
                                    ->placeholder('Contoh: ON, OFF, EXP'),
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

                                Forms\Components\TextInput::make('biaya_admin')
                                    ->label('Biaya Admin')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0'),

                                Forms\Components\TextInput::make('prioritas')
                                    ->label('Prioritas')
                                    ->numeric()
                                    ->placeholder('1')
                                    ->helperText('Semakin kecil angka, semakin tinggi prioritas'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('perlu_survei')
                                    ->label('Perlu Survei')
                                    ->default(true)
                                    ->helperText('Apakah tipe ini memerlukan tahap survei?'),

                                Forms\Components\Toggle::make('otomatis_approve')
                                    ->label('Otomatis Approve')
                                    ->default(false)
                                    ->helperText('Apakah tipe ini otomatis disetujui?'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_tipe_pendaftaran')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_tipe_pendaftaran')
                    ->label('Nama Tipe Pendaftaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('biaya_admin')
                    ->label('Biaya Admin')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('prioritas')
                    ->label('Prioritas')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'warning',
                        '3' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('perlu_survei')
                    ->label('Perlu Survei')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('otomatis_approve')
                    ->label('Auto Approve')
                    ->boolean()
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

                Tables\Filters\SelectFilter::make('perlu_survei')
                    ->label('Perlu Survei')
                    ->options([
                        true => 'Ya',
                        false => 'Tidak',
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
            ->defaultSort('prioritas', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipePendaftarans::route('/'),
            'create' => Pages\CreateTipePendaftaran::route('/create'),
            'edit' => Pages\EditTipePendaftaran::route('/{record}/edit'),
        ];
    }
}
