<?php

namespace App\Filament\Resources\PelangganResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayaran';
    protected static ?string $title = 'Riwayat Pembayaran';
    protected static ?string $recordTitleAttribute = 'nomor_pembayaran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_pembayaran')
                    ->label('Nomor Pembayaran')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\DatePicker::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->required(),

                Forms\Components\TextInput::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\Select::make('metode_bayar')
                    ->label('Metode Pembayaran')
                    ->options([
                        'tunai' => 'Tunai',
                        'transfer' => 'Transfer Bank',
                        'kartu_debit' => 'Kartu Debit',
                        'e_wallet' => 'E-Wallet',
                        'qris' => 'QRIS',
                    ])
                    ->required(),

                Forms\Components\Select::make('status_verifikasi')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'tidak_valid' => 'Tidak Valid',
                    ])
                    ->default('pending'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_pembayaran')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pembayaran')
                    ->label('No. Pembayaran')
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

                Tables\Columns\TextColumn::make('tagihan.nomor_tagihan')
                    ->label('No. Tagihan')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_verifikasi')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'tidak_valid' => 'Tidak Valid',
                    ]),

                Tables\Filters\SelectFilter::make('metode_bayar')
                    ->label('Metode')
                    ->options([
                        'tunai' => 'Tunai',
                        'transfer' => 'Transfer Bank',
                        'kartu_debit' => 'Kartu Debit',
                        'e_wallet' => 'E-Wallet',
                        'qris' => 'QRIS',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            ->defaultSort('tanggal_bayar', 'desc');
    }
}
