<?php

namespace App\Filament\Resources\PelangganResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PendaftaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pendaftaran';
    protected static ?string $title = 'Riwayat Pendaftaran';
    protected static ?string $recordTitleAttribute = 'nama_pemohon';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_pemohon')
                    ->label('Nama Pemohon')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('status_pendaftaran')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'survei' => 'Tahap Survei',
                        'rab' => 'Tahap RAB',
                        'instalasi' => 'Tahap Instalasi',
                        'selesai' => 'Selesai',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->required(),

                Forms\Components\TextInput::make('tipe_layanan')
                    ->label('Tipe Layanan')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_pemohon')
            ->columns([
                Tables\Columns\TextColumn::make('nama_pemohon')
                    ->label('Nama Pemohon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'disetujui',
                        'danger' => 'ditolak',
                        'warning' => 'survei',
                        'info' => 'rab',
                        'primary' => 'instalasi',
                        'success' => 'selesai',
                    ]),

                Tables\Columns\TextColumn::make('tipe_layanan')
                    ->label('Tipe Layanan'),

                Tables\Columns\TextColumn::make('alamat_pemasangan')
                    ->label('Alamat Pemasangan')
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'survei' => 'Tahap Survei',
                        'rab' => 'Tahap RAB',
                        'instalasi' => 'Tahap Instalasi',
                        'selesai' => 'Selesai',
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
            ]);
    }
}
