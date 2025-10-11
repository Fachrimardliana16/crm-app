<?php

namespace App\Filament\Resources\PelangganResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PengaduanRelationManager extends RelationManager
{
    protected static string $relationship = 'pengaduan';
    protected static ?string $title = 'Riwayat Pengaduan';
    protected static ?string $recordTitleAttribute = 'nomor_pengaduan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_pengaduan')
                    ->label('Nomor Pengaduan')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\DatePicker::make('tanggal_pengaduan')
                    ->label('Tanggal Pengaduan')
                    ->required(),

                Forms\Components\TimePicker::make('jam_pengaduan')
                    ->label('Jam Pengaduan')
                    ->required(),

                Forms\Components\Select::make('kategori_pengaduan')
                    ->label('Kategori')
                    ->options([
                        'teknis' => 'Teknis',
                        'administrasi' => 'Administrasi',
                        'layanan' => 'Layanan',
                        'tagihan' => 'Tagihan',
                        'kualitas_air' => 'Kualitas Air',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),

                Forms\Components\Select::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'rendah' => 'Rendah',
                        'normal' => 'Normal',
                        'tinggi' => 'Tinggi',
                        'darurat' => 'Darurat',
                    ])
                    ->default('normal'),

                Forms\Components\Textarea::make('uraian_pengaduan')
                    ->label('Uraian Pengaduan')
                    ->required()
                    ->rows(3),

                Forms\Components\Select::make('status_pengaduan')
                    ->label('Status')
                    ->options([
                        'diterima' => 'Diterima',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'ditutup' => 'Ditutup',
                    ])
                    ->default('diterima'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_pengaduan')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pengaduan')
                    ->label('No. Pengaduan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_pengaduan')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori_pengaduan')
                    ->label('Kategori')
                    ->badge(),

                Tables\Columns\BadgeColumn::make('prioritas')
                    ->label('Prioritas')
                    ->colors([
                        'secondary' => 'rendah',
                        'primary' => 'normal',
                        'warning' => 'tinggi',
                        'danger' => 'darurat',
                    ]),

                Tables\Columns\BadgeColumn::make('status_pengaduan')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'diterima',
                        'warning' => 'diproses',
                        'success' => 'selesai',
                        'danger' => 'ditutup',
                    ]),

                Tables\Columns\TextColumn::make('uraian_pengaduan')
                    ->label('Uraian')
                    ->limit(50),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pengaduan')
                    ->label('Status')
                    ->options([
                        'diterima' => 'Diterima',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'ditutup' => 'Ditutup',
                    ]),

                Tables\Filters\SelectFilter::make('prioritas')
                    ->label('Prioritas')
                    ->options([
                        'rendah' => 'Rendah',
                        'normal' => 'Normal',
                        'tinggi' => 'Tinggi',
                        'darurat' => 'Darurat',
                    ]),

                Tables\Filters\SelectFilter::make('kategori_pengaduan')
                    ->label('Kategori')
                    ->options([
                        'teknis' => 'Teknis',
                        'administrasi' => 'Administrasi',
                        'layanan' => 'Layanan',
                        'tagihan' => 'Tagihan',
                        'kualitas_air' => 'Kualitas Air',
                        'lainnya' => 'Lainnya',
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
            ->defaultSort('tanggal_pengaduan', 'desc');
    }
}
