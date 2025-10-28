<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterStatusPengaduanResource\Pages;
use App\Models\MasterStatusPengaduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MasterStatusPengaduanResource extends Resource
{
    protected static ?string $model = MasterStatusPengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationGroup = 'Master Data Pengaduan';
    protected static ?string $navigationLabel = 'Status Pengaduan';
    protected static ?string $pluralModelLabel = 'Status Pengaduan';
    protected static ?string $modelLabel = 'Status Pengaduan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Status')
                    ->description('Atur daftar status yang digunakan dalam proses penanganan pengaduan.')
                    ->schema([
                        Forms\Components\TextInput::make('kode_status')
                            ->label('Kode Status')
                            ->placeholder('Misal: ST01')
                            ->required()
                            ->maxLength(20)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('nama_status')
                            ->label('Nama Status')
                            ->placeholder('Contoh: Diterima, Ditolak, Selesai')
                            ->required()
                            ->maxLength(100)
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Metadata')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('dibuat_oleh')
                            ->label('Dibuat Oleh')
                            ->default(auth()->user()?->name ?? 'System')
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('dibuat_pada')
                            ->label('Dibuat Pada')
                            ->default(now())
                            ->disabled()
                            ->dehydrated(true),

                        Forms\Components\TextInput::make('diperbarui_oleh')
                            ->label('Diperbarui Oleh')
                            ->default(auth()->user()?->name ?? 'System')
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('diperbarui_pada')
                            ->label('Diperbarui Pada')
                            ->default(now())
                            ->disabled()
                            ->dehydrated(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('kode_status')
                    ->label('Kode')
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_status')
                    ->label('Nama Status')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('dibuat_oleh')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('diperbarui_pada')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
              //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('warning'),
                Tables\Actions\DeleteAction::make()->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasterStatusPengaduans::route('/'),
            'create' => Pages\CreateMasterStatusPengaduan::route('/create'),
            'edit' => Pages\EditMasterStatusPengaduan::route('/{record}/edit'),
        ];
    }
}
