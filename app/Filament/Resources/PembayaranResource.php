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
    protected static ?string $navigationLabel = 'Data Pembayaran';
    protected static ?string $modelLabel = 'Data Pembayaran';
    protected static ?string $pluralModelLabel = 'Data Pembayaran';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 2;

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

                                Forms\Components\Select::make('jenis_pembayaran')
                                    ->label('Jenis Pembayaran')
                                    ->options([
                                        'rekening' => 'Pembayaran Rekening',
                                        'pendaftaran' => 'Pembayaran Pendaftaran',
                                        'lainnya' => 'Pembayaran Lainnya',
                                    ])
                                    ->default('rekening'),

                                Forms\Components\Select::make('metode_bayar')
                                    ->label('Metode Pembayaran')
                                    ->options([
                                        'cash' => 'Cash/Tunai',
                                        'qris' => 'QRIS',
                                        'debit' => 'Kartu Debit',
                                        'credit' => 'Kartu Kredit',
                                        'transfer' => 'Transfer Bank',
                                        'e_wallet' => 'E-Wallet',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Detail Pembayaran')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('total_tagihan')
                                    ->label('Total Tagihan')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('jumlah_bayar')
                                    ->label('Jumlah Bayar')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Forms\Components\TextInput::make('sisa_tagihan')
                                    ->label('Sisa Tagihan')
                                    ->numeric()
                                    ->prefix('Rp'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('uang_diterima')
                                    ->label('Uang Diterima (Cash)')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('kembalian')
                                    ->label('Kembalian')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('biaya_admin')
                                    ->label('Biaya Admin')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('periode_pembayaran')
                                    ->label('Periode Pembayaran'),

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

                Tables\Columns\TextColumn::make('pelanggan.nomor_pelanggan')
                    ->label('No. Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('jenis_pembayaran')
                    ->label('Jenis')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'rekening' => 'Rekening',
                        'pendaftaran' => 'Pendaftaran',
                        'lainnya' => 'Lainnya',
                        default => 'Rekening',
                    })
                    ->colors([
                        'primary' => 'rekening',
                        'success' => 'pendaftaran',
                        'info' => 'lainnya',
                    ]),

                Tables\Columns\TextColumn::make('periode_pembayaran')
                    ->label('Periode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_tagihan')
                    ->label('Total Tagihan')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sisa_tagihan')
                    ->label('Sisa')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success'),

                Tables\Columns\BadgeColumn::make('metode_bayar')
                    ->label('Metode')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->colors([
                        'success' => 'cash',
                        'primary' => 'qris',
                        'warning' => 'debit',
                        'info' => 'credit',
                        'secondary' => ['transfer', 'e_wallet'],
                    ]),

                Tables\Columns\BadgeColumn::make('status_verifikasi')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'valid',
                        'danger' => 'tidak_valid',
                    ]),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('jenis_pembayaran')
                    ->label('Jenis Pembayaran')
                    ->options([
                        'rekening' => 'Pembayaran Rekening',
                        'pendaftaran' => 'Pembayaran Pendaftaran',
                        'lainnya' => 'Pembayaran Lainnya',
                    ]),

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
                        'cash' => 'Cash/Tunai',
                        'qris' => 'QRIS',
                        'debit' => 'Kartu Debit',
                        'credit' => 'Kartu Kredit',
                        'transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet',
                    ]),

                Tables\Filters\Filter::make('sisa_tagihan')
                    ->label('Belum Lunas')
                    ->query(fn ($query) => $query->where('sisa_tagihan', '>', 0)),
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
