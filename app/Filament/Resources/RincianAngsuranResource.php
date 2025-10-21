<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RincianAngsuranResource\Pages;
use App\Models\RincianAngsuran;
use App\Models\TagihanRab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;

class RincianAngsuranResource extends Resource
{
    protected static ?string $model = RincianAngsuran::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Rincian Angsuran';
    protected static ?string $modelLabel = 'Rincian Angsuran';
    protected static ?string $pluralModelLabel = 'Rincian Angsuran';
    protected static ?string $navigationGroup = 'Workflow';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Angsuran')
                    ->description('Data rincian angsuran tagihan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_tagihan')
                                    ->label('Tagihan RAB')
                                    ->relationship('tagihan', 'nomor_tagihan')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('nomor_angsuran')
                                    ->label('Angsuran Ke-')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('jumlah_angsuran')
                                    ->label('Jumlah Angsuran')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Forms\Components\DatePicker::make('tanggal_jatuh_tempo')
                                    ->label('Jatuh Tempo')
                                    ->required(),

                                Forms\Components\Select::make('status_bayar')
                                    ->label('Status Bayar')
                                    ->options([
                                        'belum' => 'Belum Bayar',
                                        'lunas' => 'Lunas',
                                        'terlambat' => 'Terlambat',
                                    ])
                                    ->default('belum')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Detail Pembayaran')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_bayar')
                                    ->label('Tanggal Bayar'),

                                Forms\Components\TextInput::make('denda')
                                    ->label('Denda')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                Forms\Components\TextInput::make('total_bayar')
                                    ->label('Total Bayar')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tagihan.nomor_tagihan')
                    ->label('No. Tagihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tagihan.pelanggan.nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nomor_angsuran')
                    ->label('Angsuran Ke-')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('jumlah_angsuran')
                    ->label('Jumlah Angsuran')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('denda')
                    ->label('Denda')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_bayar')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->getStateUsing(fn ($record) => $record->jumlah_angsuran + $record->denda),

                Tables\Columns\TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->is_overdue && $record->status_bayar !== 'lunas' ? 'danger' : null),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status_bayar')
                    ->label('Status')
                    ->colors([
                        'warning' => 'belum',
                        'success' => 'lunas',
                        'danger' => 'terlambat',
                    ]),

                Tables\Columns\IconColumn::make('is_overdue')
                    ->label('Lewat Tempo')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->is_overdue && $record->status_bayar !== 'lunas')
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),
            ])
            ->filters([
                SelectFilter::make('status_bayar')
                    ->label('Status Bayar')
                    ->options([
                        'belum' => 'Belum Bayar',
                        'lunas' => 'Lunas',
                        'terlambat' => 'Terlambat',
                    ]),

                Tables\Filters\Filter::make('jatuh_tempo')
                    ->form([
                        Forms\Components\DatePicker::make('jatuh_tempo_from')
                            ->label('Jatuh Tempo Dari'),
                        Forms\Components\DatePicker::make('jatuh_tempo_until')
                            ->label('Jatuh Tempo Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['jatuh_tempo_from'], fn ($query, $date) => $query->where('tanggal_jatuh_tempo', '>=', $date))
                            ->when($data['jatuh_tempo_until'], fn ($query, $date) => $query->where('tanggal_jatuh_tempo', '<=', $date));
                    }),

                Tables\Filters\Filter::make('overdue')
                    ->label('Lewat Jatuh Tempo')
                    ->query(fn ($query) => $query->where('tanggal_jatuh_tempo', '<', now())->where('status_bayar', '!=', 'lunas')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal_jatuh_tempo', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRincianAngsurans::route('/'),
            'create' => Pages\CreateRincianAngsuran::route('/create'),
            'edit' => Pages\EditRincianAngsuran::route('/{record}/edit'),
        ];
    }
}
