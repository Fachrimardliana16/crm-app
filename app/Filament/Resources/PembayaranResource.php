<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use App\Models\TagihanRab;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?string $pluralModelLabel = 'Pembayaran';
    protected static ?string $navigationGroup = 'Workflow PDAM';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pembayaran')
                    ->description('Data pembayaran pelanggan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_tagihan')
                                    ->label('Tagihan RAB')
                                    ->relationship('tagihan', 'nomor_tagihan')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                Forms\Components\Select::make('id_pelanggan')
                                    ->label('Pelanggan')
                                    ->relationship('pelanggan', 'nama_pelanggan')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('nomor_pembayaran')
                                    ->label('Nomor Pembayaran')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\DatePicker::make('tanggal_bayar')
                                    ->label('Tanggal Bayar')
                                    ->required()
                                    ->default(now()),

                                Forms\Components\Select::make('metode_bayar')
                                    ->label('Metode Pembayaran')
                                    ->options([
                                        'tunai' => 'Tunai',
                                        'transfer' => 'Transfer Bank',
                                        'kartu_debit' => 'Kartu Debit',
                                        'kartu_kredit' => 'Kartu Kredit',
                                        'e_wallet' => 'E-Wallet',
                                        'qris' => 'QRIS',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Detail Pembayaran')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('jumlah_bayar')
                                    ->label('Jumlah Bayar')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Forms\Components\TextInput::make('biaya_admin')
                                    ->label('Biaya Admin')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                Forms\Components\Select::make('status_verifikasi')
                                    ->label('Status Verifikasi')
                                    ->options([
                                        'pending' => 'Pending',
                                        'valid' => 'Valid',
                                        'tidak_valid' => 'Tidak Valid',
                                    ])
                                    ->default('pending')
                                    ->required(),
                            ]),

                        Forms\Components\TextInput::make('nip_petugas_loket')
                            ->label('NIP Petugas Loket'),

                        Forms\Components\FileUpload::make('bukti_bayar')
                            ->label('Bukti Pembayaran')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->directory('pembayaran/bukti'),

                        Forms\Components\Textarea::make('catatan_pembayaran')
                            ->label('Catatan Pembayaran')
                            ->rows(3),
                    ]),

                Section::make('Metadata')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('dibuat_oleh')
                                    ->label('Dibuat Oleh')
                                    ->required(),

                                Forms\Components\DateTimePicker::make('dibuat_pada')
                                    ->label('Dibuat Pada')
                                    ->default(now()),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pembayaran')
                    ->label('No. Pembayaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tagihan.nomor_tagihan')
                    ->label('No. Tagihan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('metode_bayar')
                    ->label('Metode')
                    ->badge()
                    ->colors([
                        'primary' => 'tunai',
                        'success' => 'transfer',
                        'warning' => 'kartu_debit',
                        'info' => 'e_wallet',
                    ]),

                Tables\Columns\BadgeColumn::make('status_verifikasi')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'valid',
                        'danger' => 'tidak_valid',
                    ]),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_verifikasi')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'tidak_valid' => 'Tidak Valid',
                    ]),

                SelectFilter::make('metode_bayar')
                    ->label('Metode Pembayaran')
                    ->options([
                        'tunai' => 'Tunai',
                        'transfer' => 'Transfer Bank',
                        'kartu_debit' => 'Kartu Debit',
                        'kartu_kredit' => 'Kartu Kredit',
                        'e_wallet' => 'E-Wallet',
                        'qris' => 'QRIS',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}
