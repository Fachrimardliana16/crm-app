<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RabResource\Pages;
use App\Filament\Resources\RabResource\RelationManagers;
use App\Models\Rab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RabResource extends Resource
{
    protected static ?string $model = Rab::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'RAB';

    protected static ?string $modelLabel = 'RAB';

    protected static ?string $pluralModelLabel = 'RAB';

    protected static ?string $navigationGroup = 'Workflow';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_input')
                            ->label('Tanggal Input')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Data Pendaftaran')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_pendaftaran')
                                    ->label('Pendaftaran')
                                    ->relationship('pendaftaran', 'nomor_registrasi')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nomor_registrasi} - {$record->nama_pemohon}")
                                    ->searchable(['nomor_registrasi', 'nama_pemohon'])
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $pendaftaran = \App\Models\Pendaftaran::with(['pelanggan', 'cabang', 'survei'])->find($state);
                                            if ($pendaftaran) {
                                                // Set pelanggan info
                                                if ($pendaftaran->id_pelanggan) {
                                                    $set('id_pelanggan', $pendaftaran->id_pelanggan);
                                                }
                                                
                                                // Auto-fill data from pendaftaran
                                                $set('nama_pelanggan', $pendaftaran->nama_pemohon);
                                                $set('alamat_pelanggan', $pendaftaran->alamat_pemasangan);
                                                $set('telepon_pelanggan', $pendaftaran->no_hp_pemohon);
                                                
                                                // Set kantor cabang
                                                if ($pendaftaran->cabang) {
                                                    $set('kantor_cabang', $pendaftaran->cabang->nama_cabang);
                                                }
                                                
                                                // Set golongan tarif from survei rekomendasi
                                                if ($pendaftaran->survei) {
                                                    $set('golongan_tarif', $pendaftaran->survei->rekomendasi_sub_golongan_text);
                                                }
                                            }
                                        }
                                    }),
                                    
                                Forms\Components\Select::make('status_rab')
                                    ->label('Status RAB')
                                    ->options([
                                        'draft' => 'Draft',
                                        'review' => 'Review',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                        'final' => 'Final',
                                    ])
                                    ->default('draft')
                                    ->required(),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Informasi Langganan')
                    ->description('Sub Rayon dan No. Langganan akan diassign setelah pemasangan selesai')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('jenis_biaya_sambungan')
                                    ->label('Jenis Biaya Sambungan')
                                    ->options([
                                        'standar' => 'Standar',
                                        'non_standar' => 'Non Standar',
                                    ])
                                    ->required()
                                    ->default('standar'),
                                    
                                Forms\Components\TextInput::make('golongan_tarif')
                                    ->label('Golongan Tarif')
                                    ->readOnly()
                                    ->helperText('Diambil dari rekomendasi survei'),
                            ]),
                            
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\TextInput::make('kantor_cabang')
                                    ->label('Kantor Cabang/Unit')
                                    ->readOnly()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Data Pelanggan')
                    ->description('Data ini akan otomatis terisi dari pendaftaran yang dipilih')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_pelanggan')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255),
                                    
                                Forms\Components\TextInput::make('telepon_pelanggan')
                                    ->label('Telepon')
                                    ->tel()
                                    ->maxLength(20),
                                    
                                Forms\Components\Textarea::make('alamat_pelanggan')
                                    ->label('Alamat')
                                    ->rows(2)
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Rincian Uang Muka')
                    ->description('Input biaya perencanaan dan perhitungan akan otomatis')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('perencanaan')
                                    ->label('Perencanaan dll')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateUangMuka($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('jumlah_uang_muka')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'bg-gray-50 font-semibold']),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Biaya Instalasi')
                    ->description('Input rincian biaya instalasi dan total akan dihitung otomatis')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('pengerjaan_tanah')
                                    ->label('Pengerjaan Tanah')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateInstalasi($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('tenaga_kerja')
                                    ->label('Tenaga Kerja')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateInstalasi($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('pipa_accessories')
                                    ->label('Pipa dan Accessories')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateInstalasi($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('jumlah_instalasi')
                                    ->label('Jumlah Total Instalasi')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'bg-gray-50 font-semibold']),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Rincian Piutang')
                    ->description('Perhitungan piutang dan total biaya sambungan baru')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('pembulatan_piutang')
                                    ->label('Pembulatan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculatePiutang($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('uang_muka')
                                    ->label('Uang Muka')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live(debounce: 1000)
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculatePiutang($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('piutang_na')
                                    ->label('Piutang Non Air (Piutang NA)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->helperText('Total yang harus dibayar dikurangi Uang Muka')
                                    ->extraAttributes(['class' => 'bg-gray-50 font-semibold']),
                                    
                                Forms\Components\TextInput::make('total_piutang')
                                    ->label('Total Piutang')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'bg-gray-50 font-semibold']),
                                    
                                Forms\Components\TextInput::make('pajak_piutang')
                                    ->label('Pajak (%)')
                                    ->numeric()
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->live(debounce: 1000)
                                    ->helperText('Masukkan persentase pajak (contoh: 10 untuk 10%)')
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculatePiutang($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('total_biaya_sambungan_baru')
                                    ->label('Total Biaya Sambungan Baru')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'bg-green-50 font-bold text-lg text-green-800 border-green-300'])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('catatan_rab')
                            ->label('Catatan RAB')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    protected static function calculateUangMuka(callable $get, callable $set): void
    {
        $perencanaan = (float) ($get('perencanaan') ?? 0);
        // Uang muka = perencanaan (for now, bisa ditambah field lain jika diperlukan)
        $set('jumlah_uang_muka', $perencanaan);
        
        // Trigger piutang calculation with small delay to prevent rapid recalculation
        self::calculatePiutang($get, $set);
    }

    protected static function calculateInstalasi(callable $get, callable $set): void
    {
        $pengerjaanTanah = (float) ($get('pengerjaan_tanah') ?? 0);
        $tenagaKerja = (float) ($get('tenaga_kerja') ?? 0);
        $pipaAccessories = (float) ($get('pipa_accessories') ?? 0);
        
        $jumlahInstalasi = $pengerjaanTanah + $tenagaKerja + $pipaAccessories;
        $set('jumlah_instalasi', $jumlahInstalasi);
        
        // Trigger piutang calculation with small delay to prevent rapid recalculation
        self::calculatePiutang($get, $set);
    }

    protected static function calculatePiutang(callable $get, callable $set): void
    {
        $jumlahUangMuka = (float) ($get('jumlah_uang_muka') ?? 0);
        $jumlahInstalasi = (float) ($get('jumlah_instalasi') ?? 0);
        $pembulatanPiutang = (float) ($get('pembulatan_piutang') ?? 0);
        $uangMuka = (float) ($get('uang_muka') ?? 0);
        $pajakPersentase = (float) ($get('pajak_piutang') ?? 0);
        
        // Total yang harus dibayar = jumlah uang muka + jumlah instalasi + pembulatan
        $totalYangHarusDibayar = $jumlahUangMuka + $jumlahInstalasi + $pembulatanPiutang;
        
        // Piutang NA = total yang harus dibayar dikurangi uang muka
        $piutangNA = $totalYangHarusDibayar - $uangMuka;
        $set('piutang_na', $piutangNA);
        
        // Total piutang
        $totalPiutang = $piutangNA;
        $set('total_piutang', $totalPiutang);
        
        // Hitung pajak berdasarkan persentase dari total piutang
        $nominalPajak = 0;
        if ($pajakPersentase > 0 && $totalPiutang > 0) {
            $nominalPajak = ($totalPiutang * $pajakPersentase) / 100;
        }
        
        // Total biaya sambungan baru = total piutang + nominal pajak
        $totalBiayaSambunganBaru = $totalPiutang + $nominalPajak;
        $set('total_biaya_sambungan_baru', $totalBiayaSambunganBaru);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('jenis_biaya_sambungan')
                    ->label('Jenis Biaya')
                    ->colors([
                        'primary' => 'standar',
                        'warning' => 'non_standar',
                    ]),
                    
                Tables\Columns\TextColumn::make('tanggal_input')
                    ->label('Tanggal Input')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status_rab')
                    ->label('Status RAB')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'review',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'primary' => 'final',
                    ])
                    ->icons([
                        'heroicon-o-pencil' => 'draft',
                        'heroicon-o-clock' => 'review',
                        'heroicon-o-check-circle' => 'approved',
                        'heroicon-o-x-circle' => 'rejected',
                        'heroicon-o-check-badge' => 'final',
                    ]),
                    
                Tables\Columns\TextColumn::make('total_biaya_sambungan_baru')
                    ->label('Total Biaya Sambungan')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('golongan_tarif')
                    ->label('Golongan Tarif')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('dibuat_oleh')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_rab')
                    ->label('Status RAB')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'final' => 'Final',
                    ]),
                    
                Tables\Filters\SelectFilter::make('jenis_biaya_sambungan')
                    ->label('Jenis Biaya Sambungan')
                    ->options([
                        'standar' => 'Standar',
                        'non_standar' => 'Non Standar',
                    ]),
                    
                Tables\Filters\Filter::make('tanggal_input')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_input', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_input', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye'),
                    
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil'),
                    
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Rab $record) => $record->status_rab === 'proses')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui RAB')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui RAB ini?')
                    ->action(function (Rab $record) {
                        $oldStatus = $record->status_rab;
                        $record->update(['status_rab' => 'disetujui']);
                        
                        // Send workflow notifications
                        $notificationService = app(\App\Services\WorkflowNotificationService::class);
                        $notificationService->rabStatusChanged($record, $oldStatus, 'disetujui');
                        
                        \Filament\Notifications\Notification::make()
                            ->title('RAB telah disetujui')
                            ->success()
                            ->send();
                    }),
                    
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (Rab $record) => $record->status_rab === 'proses')
                    ->form([
                        Forms\Components\Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Rab $record, array $data) {
                        $oldStatus = $record->status_rab;
                        $record->update([
                            'status_rab' => 'ditolak',
                            'catatan_rab' => 'DITOLAK: ' . $data['alasan_penolakan'],
                        ]);
                        
                        // Send workflow notifications
                        $notificationService = app(\App\Services\WorkflowNotificationService::class);
                        $notificationService->rabStatusChanged($record, $oldStatus, 'ditolak');
                        
                        \Filament\Notifications\Notification::make()
                            ->title('RAB telah ditolak')
                            ->warning()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('tanggal_rab_dibuat', 'desc');
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
            'index' => Pages\ListRabs::route('/'),
            'create' => Pages\CreateRab::route('/create'),
            'view' => Pages\ViewRab::route('/{record}'),
            'edit' => Pages\EditRab::route('/{record}/edit'),
        ];
    }
}
