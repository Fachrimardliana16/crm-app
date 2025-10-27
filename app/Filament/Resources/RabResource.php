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
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_pendaftaran')
                                    ->label('Pendaftaran')
                                    ->relationship('pendaftaran', 'nomor_registrasi')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $pendaftaran = \App\Models\Pendaftaran::find($state);
                                            if ($pendaftaran && $pendaftaran->id_pelanggan) {
                                                $set('id_pelanggan', $pendaftaran->id_pelanggan);
                                            }
                                        }
                                    }),
                                    
                                Forms\Components\Select::make('id_pelanggan')
                                    ->label('Pelanggan')
                                    ->relationship('pelanggan', 'nama_pelanggan')
                                    ->searchable()
                                    ->preload()
                                    ->disabled()
                                    ->dehydrated(),
                                    
                                Forms\Components\DatePicker::make('tanggal_rab_dibuat')
                                    ->label('Tanggal RAB Dibuat')
                                    ->default(now())
                                    ->required()
                                    ->native(false),
                                    
                                Forms\Components\Select::make('status_rab')
                                    ->label('Status RAB')
                                    ->options([
                                        'draft' => 'Draft',
                                        'proses' => 'Proses',
                                        'disetujui' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                    ])
                                    ->default('draft')
                                    ->required(),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Rincian Biaya Konstruksi')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('total_biaya_konstruksi')
                                    ->label('Total Biaya Konstruksi')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateSubTotal($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('total_biaya_administrasi')
                                    ->label('Total Biaya Administrasi')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateSubTotal($get, $set);
                                    }),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Perhitungan Total')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('sub_total_awal')
                                    ->label('Sub Total Awal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(),
                                    
                                Forms\Components\TextInput::make('nilai_pajak')
                                    ->label('Nilai Pajak')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateTotal($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('total_rab_bruto')
                                    ->label('Total RAB Bruto')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(),
                                    
                                Forms\Components\TextInput::make('pembulatan')
                                    ->label('Pembulatan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::calculateFinalTotal($get, $set);
                                    }),
                                    
                                Forms\Components\TextInput::make('total_final_rab')
                                    ->label('Total Final RAB')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated()
                                    ->extraAttributes(['class' => 'font-bold text-lg']),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('uang_muka')
                                    ->label('Uang Muka')
                                    ->numeric()
                                    ->prefix('Rp'),
                                    
                                Forms\Components\TextInput::make('biaya_sb')
                                    ->label('Biaya SB')
                                    ->numeric()
                                    ->prefix('Rp'),
                                    
                                Forms\Components\TextInput::make('piutang_non_adir')
                                    ->label('Piutang Non ADIR')
                                    ->numeric()
                                    ->prefix('Rp'),
                                    
                                Forms\Components\TextInput::make('jumlah_angsuran')
                                    ->label('Jumlah Angsuran')
                                    ->numeric()
                                    ->suffix('kali'),
                                    
                                Forms\Components\Select::make('status_pembayaran')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        'belum' => 'Belum Bayar',
                                        'sebagian' => 'Sebagian',
                                        'lunas' => 'Lunas',
                                    ])
                                    ->default('belum'),
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

    protected static function calculateSubTotal(callable $get, callable $set): void
    {
        $konstruksi = (float) ($get('total_biaya_konstruksi') ?? 0);
        $administrasi = (float) ($get('total_biaya_administrasi') ?? 0);
        $subTotal = $konstruksi + $administrasi;
        
        $set('sub_total_awal', $subTotal);
        
        // Calculate total bruto
        $pajak = (float) ($get('nilai_pajak') ?? 0);
        $totalBruto = $subTotal + $pajak;
        $set('total_rab_bruto', $totalBruto);
        
        // Calculate final total
        $pembulatan = (float) ($get('pembulatan') ?? 0);
        $totalFinal = $totalBruto + $pembulatan;
        $set('total_final_rab', $totalFinal);
    }

    protected static function calculateTotal(callable $get, callable $set): void
    {
        $subTotal = (float) ($get('sub_total_awal') ?? 0);
        $pajak = (float) ($get('nilai_pajak') ?? 0);
        $totalBruto = $subTotal + $pajak;
        
        $set('total_rab_bruto', $totalBruto);
        
        // Calculate final total
        $pembulatan = (float) ($get('pembulatan') ?? 0);
        $totalFinal = $totalBruto + $pembulatan;
        $set('total_final_rab', $totalFinal);
    }

    protected static function calculateFinalTotal(callable $get, callable $set): void
    {
        $totalBruto = (float) ($get('total_rab_bruto') ?? 0);
        $pembulatan = (float) ($get('pembulatan') ?? 0);
        $totalFinal = $totalBruto + $pembulatan;
        
        $set('total_final_rab', $totalFinal);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tanggal_rab_dibuat')
                    ->label('Tanggal RAB')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status_rab')
                    ->label('Status RAB')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'proses',
                        'success' => 'disetujui',
                        'danger' => 'ditolak',
                    ])
                    ->icons([
                        'heroicon-o-pencil' => 'draft',
                        'heroicon-o-clock' => 'proses',
                        'heroicon-o-check-circle' => 'disetujui',
                        'heroicon-o-x-circle' => 'ditolak',
                    ]),
                    
                Tables\Columns\TextColumn::make('total_final_rab')
                    ->label('Total RAB')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status_pembayaran')
                    ->label('Status Bayar')
                    ->colors([
                        'danger' => 'belum',
                        'warning' => 'sebagian',
                        'success' => 'lunas',
                    ])
                    ->icons([
                        'heroicon-o-x-circle' => 'belum',
                        'heroicon-o-clock' => 'sebagian',
                        'heroicon-o-check-circle' => 'lunas',
                    ]),
                    
                Tables\Columns\TextColumn::make('jumlah_angsuran')
                    ->label('Angsuran')
                    ->suffix('x')
                    ->alignCenter(),
                    
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
                        'proses' => 'Proses',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum' => 'Belum Bayar',
                        'sebagian' => 'Sebagian',
                        'lunas' => 'Lunas',
                    ]),
                    
                Tables\Filters\Filter::make('tanggal_rab')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_rab_dibuat', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_rab_dibuat', '<=', $date),
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
