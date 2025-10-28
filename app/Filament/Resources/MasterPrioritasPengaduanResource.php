<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterPrioritasPengaduanResource\Pages;
use App\Models\MasterPrioritasPengaduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MasterPrioritasPengaduanResource extends Resource
{
    protected static ?string $model = MasterPrioritasPengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'Master Data Pengaduan';
    protected static ?string $navigationLabel = 'Prioritas Pengaduan';
    protected static ?string $pluralModelLabel = 'Prioritas Pengaduan';
    protected static ?string $modelLabel = 'Prioritas Pengaduan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Prioritas')
                    ->description('Atur detail level prioritas dan SLA penanganannya.')
                    ->schema([
                        Forms\Components\TextInput::make('kode_prioritas')
                            ->label('Kode Prioritas')
                            ->placeholder('Misal: P1, P2, P3')
                            ->required()
                            ->maxLength(20)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('nama_prioritas')
                            ->label('Nama Prioritas')
                            ->placeholder('Contoh: Urgent, Normal, Low')
                            ->required()
                            ->maxLength(50)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('sla_jam')
                            ->label('SLA (Jam)')
                            ->numeric()
                            ->suffix('Jam')
                            ->required()
                            ->default(24)
                            ->helperText('Waktu penyelesaian maksimal berdasarkan prioritas.')
                            ->columnSpan(1),

                        Forms\Components\ColorPicker::make('warna_tampilan')
                            ->label('Warna Tampilan')
                            ->required()
                            ->default('#808080')
                            ->helperText('Digunakan untuk menandai warna prioritas di tabel.')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Metadata')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('dibuat_oleh')
                            ->maxLength(255)
                            ->default(auth()->user()?->name ?? 'System'),

                        Forms\Components\DateTimePicker::make('dibuat_pada')
                            ->default(now())
                            ->disabled()
                            ->dehydrated(true),

                        Forms\Components\TextInput::make('diperbarui_oleh')
                            ->maxLength(255)
                            ->default(auth()->user()?->name ?? 'System'),

                        Forms\Components\DateTimePicker::make('diperbarui_pada')
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
                Tables\Columns\TextColumn::make('kode_prioritas')
                    ->label('Kode')
                    ->color('info')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('nama_prioritas')
                    ->label('Nama Prioritas')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('sla_jam')
                    ->label('SLA (Jam)')
                    ->sortable()
                    ->numeric()
                    ->badge()
                    ->color('info'),

                Tables\Columns\ColorColumn::make('warna_tampilan')
                    ->label('Warna Tampilan'),

                Tables\Columns\TextColumn::make('dibuat_oleh')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('diperbarui_pada')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sla_jam')
                    ->label('Filter SLA')
                    ->options([
                        '24' => '24 Jam',
                        '48' => '48 Jam',
                        '72' => '72 Jam',
                    ]),
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
            ->striped()
            ->defaultSort('sla_jam');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasterPrioritasPengaduans::route('/'),
            'create' => Pages\CreateMasterPrioritasPengaduan::route('/create'),
            'edit' => Pages\EditMasterPrioritasPengaduan::route('/{record}/edit'),
        ];
    }
}
