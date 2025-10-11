<?php

namespace App\Filament\Resources\PelangganResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TagihanBulananRelationManager extends RelationManager
{
    protected static string $relationship = 'tagihanBulanan';
    protected static ?string $title = 'Tagihan Bulanan';
    protected static ?string $recordTitleAttribute = 'periode_tagihan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('periode_tagihan')
                    ->label('Periode Tagihan')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_tagihan')
                    ->label('Tanggal Tagihan')
                    ->required(),

                Forms\Components\TextInput::make('pemakaian_air')
                    ->label('Pemakaian Air (m³)')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('biaya_pemakaian')
                    ->label('Biaya Pemakaian')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

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

                Forms\Components\TextInput::make('total_tagihan')
                    ->label('Total Tagihan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\Select::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum' => 'Belum Bayar',
                        'sebagian' => 'Dibayar Sebagian',
                        'lunas' => 'Lunas',
                    ])
                    ->default('belum'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('periode_tagihan')
            ->columns([
                Tables\Columns\TextColumn::make('periode_tagihan')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_tagihan')
                    ->label('Tanggal Tagihan')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pemakaian_air')
                    ->label('Pemakaian (m³)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_tagihan')
                    ->label('Total Tagihan')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status_pembayaran')
                    ->label('Status')
                    ->colors([
                        'danger' => 'belum',
                        'warning' => 'sebagian',
                        'success' => 'lunas',
                    ]),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('bacaanMeter.angka_meter_sekarang')
                    ->label('Angka Meter')
                    ->numeric(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum' => 'Belum Bayar',
                        'sebagian' => 'Dibayar Sebagian',
                        'lunas' => 'Lunas',
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
            ->defaultSort('periode_tagihan', 'desc');
    }
}
