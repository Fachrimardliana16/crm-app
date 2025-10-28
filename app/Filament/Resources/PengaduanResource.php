<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaduanResource\Pages;
use App\Models\Pengaduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\FileUpload;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationLabel = 'Pengaduan';
    protected static ?string $pluralModelLabel = 'Pengaduan';
    protected static ?string $modelLabel = 'Pengaduan';
    protected static ?string $navigationGroup = 'Layanan Pelanggan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // Bagian Informasi Pelapor
                Forms\Components\Section::make('Informasi Pengaduan')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_pengaduan')
                            ->label('Nomor Pengaduan')
                            ->default(function () {
                                // Generate nomor pengaduan berdasarkan tanggal hari ini dan urutan
                                $today = now()->format('dmY');
                                $countToday = \App\Models\Pengaduan::whereDate('tanggal_pengaduan', now()->toDateString())->count() + 1;
                                $formattedCount = str_pad($countToday, 2, '0', STR_PAD_LEFT);
                                return "{$today}-{$formattedCount}";
                            })
                            ->disabled()
                            ->dehydrated(true),

                        Forms\Components\TextInput::make('nama_pelapor')
                            ->label('Nama Pelapor')
                            ->placeholder('Masukkan nama pelapor')
                            ->required(),

                        Forms\Components\Hidden::make('tanggal_pengaduan')
                            ->default(now()),

                        Forms\Components\Hidden::make('jam_pengaduan')
                            ->default(now()),

                        Forms\Components\Select::make('jenis_pengaduan')
                            ->label('Jenis Pengaduan')
                             ->options([
                                'Teknis' => 'Teknis',
                                'Administrasi' => 'Administrasi',
                                'Layanan' => 'Layanan',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('uraian_pengaduan')
                            ->label('Uraian Pengaduan')
                            ->rows(4)
                            ->required(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Pendukung')
                            ->image(),

                        Forms\Components\Select::make('prioritas')
                            ->options([
                                'rendah' => 'Rendah',
                                'sedang' => 'Sedang',
                                'tinggi' => 'Tinggi',
                            ])
                            ->label('Prioritas')
                            ->default('sedang')
                            ->required(),

                        Forms\Components\Select::make('status_pengaduan')
                            ->options([
                                'baru' => 'Baru',
                                'proses' => 'Dalam Proses',
                                'selesai' => 'Selesai',
                                'ditolak' => 'Ditolak',
                            ])
                            ->label('Status Pengaduan')
                            ->default('baru')
                            ->required(),
                    ])
                    ->columns(2),

                // Bagian Penanganan
                Forms\Components\Section::make('Penanganan & Dokumentasi')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_target_selesai')
                            ->label('Target Selesai'),

                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai'),

                        Forms\Components\Textarea::make('tindak_lanjut')
                            ->label('Tindak Lanjut')
                            ->rows(3),

                        Forms\Components\Textarea::make('solusi_diberikan')
                            ->label('Solusi Diberikan')
                            ->rows(3),

                        Forms\Components\TextInput::make('nip_petugas_penanganan')
                            ->label('NIP Petugas Penanganan'),

                        Forms\Components\TextInput::make('biaya_penanganan')
                            ->label('Biaya Penanganan')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\FileUpload::make('foto_kondisi_awal')
                            ->label('Foto Kondisi Awal')
                            ->directory('pengaduan/foto-awal')
                            ->image(),

                        Forms\Components\FileUpload::make('foto_kondisi_akhir')
                            ->label('Foto Kondisi Akhir')
                            ->directory('pengaduan/foto-akhir')
                            ->image(),
                    ])
                    ->columns(2),

                // Feedback pelanggan
                Forms\Components\Section::make('Feedback Pelanggan')
                    ->schema([
                        Forms\Components\Select::make('tingkat_kepuasan')
                            ->label('Tingkat Kepuasan')
                            ->options([
                                'sangat_puas' => 'Sangat Puas',
                                'puas' => 'Puas',
                                'cukup' => 'Cukup',
                                'kurang_puas' => 'Kurang Puas',
                                'tidak_puas' => 'Tidak Puas',
                            ]),

                        Forms\Components\Textarea::make('feedback_pelanggan')
                            ->label('Feedback Pelanggan')
                            ->rows(3),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pengaduan')
                    ->label('Nomor')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_pelapor')
                    ->label('Pelapor')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kategori_pengaduan')
                    ->label('Kategori'),

                Tables\Columns\BadgeColumn::make('prioritas')
                    ->label('Prioritas')
                    ->colors([
                        'success' => 'rendah',
                        'warning' => 'sedang',
                        'danger' => 'tinggi',
                    ]),

                Tables\Columns\BadgeColumn::make('status_pengaduan')
                    ->label('Status')
                    ->colors([
                        'gray' => 'baru',
                        'warning' => 'proses',
                        'success' => 'selesai',
                        'danger' => 'ditolak',
                    ]),

                Tables\Columns\TextColumn::make('tanggal_pengaduan')
                    ->label('Tanggal')
                    ->date(),
            ])
            ->defaultSort('tanggal_pengaduan', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status_pengaduan')
                    ->options([
                        'baru' => 'Baru',
                        'proses' => 'Dalam Proses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                    ])
                    ->label('Status'),

                Tables\Filters\SelectFilter::make('prioritas')
                    ->options([
                        'rendah' => 'Rendah',
                        'sedang' => 'Sedang',
                        'tinggi' => 'Tinggi',
                    ])
                    ->label('Prioritas'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPengaduans::route('/'),
            'create' => Pages\CreatePengaduan::route('/create'),
            'edit' => Pages\EditPengaduan::route('/{record}/edit'),
        ];
    }
}
