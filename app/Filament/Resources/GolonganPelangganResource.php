<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GolonganPelangganResource\Pages;
use App\Filament\Resources\GolonganPelangganResource\RelationManagers;
use App\Models\GolonganPelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GolonganPelangganResource extends Resource
{
    protected static ?string $model = GolonganPelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Golongan Pelanggan';

    protected static ?string $modelLabel = 'Golongan Pelanggan';

    protected static ?string $pluralModelLabel = 'Golongan Pelanggan';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Golongan')
                    ->description('Data dasar golongan pelanggan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_golongan')
                                    ->label('Kode Golongan')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(10),

                                Forms\Components\TextInput::make('nama_golongan')
                                    ->label('Nama Golongan')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('deskripsi_golongan')
                            ->label('Deskripsi Golongan')
                            ->rows(3),

                        Forms\Components\Toggle::make('status_aktif')
                            ->label('Status Aktif')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('Tarif Dasar')
                    ->description('Pengaturan tarif dasar dan batas pemakaian')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('tarif_dasar')
                                    ->label('Tarif Dasar')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Forms\Components\TextInput::make('batas_minimum')
                                    ->label('Batas Minimum (M³)')
                                    ->numeric()
                                    ->suffix('M³')
                                    ->required(),

                                Forms\Components\TextInput::make('batas_maksimum')
                                    ->label('Batas Maksimum (M³)')
                                    ->numeric()
                                    ->suffix('M³'),
                            ]),
                    ]),

                Forms\Components\Section::make('Tarif Progresif')
                    ->description('Tarif progresif untuk pemakaian di atas batas minimum')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('tarif_progresif_1')
                                    ->label('Tarif Progresif 1')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('tarif_progresif_2')
                                    ->label('Tarif Progresif 2')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('tarif_progresif_3')
                                    ->label('Tarif Progresif 3')
                                    ->numeric()
                                    ->prefix('Rp'),
                            ]),
                    ]),

                Forms\Components\Section::make('Biaya Tambahan')
                    ->description('Biaya tetap dan administrasi')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('biaya_beban_tetap')
                                    ->label('Biaya Beban Tetap')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                Forms\Components\TextInput::make('biaya_administrasi')
                                    ->label('Biaya Administrasi')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                Forms\Components\TextInput::make('biaya_pemeliharaan')
                                    ->label('Biaya Pemeliharaan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),
                            ]),
                    ]),

                Forms\Components\Section::make('Periode Berlaku')
                    ->description('Masa berlaku tarif')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('berlaku_sejak')
                                    ->label('Berlaku Sejak')
                                    ->required()
                                    ->default(now()),

                                Forms\Components\DatePicker::make('berlaku_hingga')
                                    ->label('Berlaku Hingga')
                                    ->after('berlaku_sejak'),
                            ]),
                    ]),

                Forms\Components\Section::make('Keterangan')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->rows(3),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_golongan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_golongan')
                    ->label('Nama Golongan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tarif_dasar')
                    ->label('Tarif Dasar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('batas_minimum')
                    ->label('Batas Min')
                    ->suffix(' M³')
                    ->sortable(),

                Tables\Columns\IconColumn::make('status_aktif')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('berlaku_sejak')
                    ->label('Berlaku Sejak')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('berlaku_hingga')
                    ->label('Berlaku Hingga')
                    ->date()
                    ->placeholder('Tidak terbatas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan_count')
                    ->label('Jumlah Pelanggan')
                    ->counts('pelanggan')
                    ->badge(),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status_aktif')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Non-Aktif'),

                Tables\Filters\Filter::make('berlaku_sekarang')
                    ->label('Berlaku Saat Ini')
                    ->query(fn (Builder $query) => $query->berlaku()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('lihat_tarif')
                    ->label('Lihat Tarif')
                    ->icon('heroicon-o-calculator')
                    ->color('info')
                    ->modalHeading('Struktur Tarif')
                    ->modalContent(fn ($record) => view('filament.modals.tarif-structure', compact('record')))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('nama_golongan');
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
            'index' => Pages\ListGolonganPelanggans::route('/'),
            'create' => Pages\CreateGolonganPelanggan::route('/create'),
            'edit' => Pages\EditGolonganPelanggan::route('/{record}/edit'),
        ];
    }
}
