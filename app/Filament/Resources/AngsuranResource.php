<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AngsuranResource\Pages;
use App\Filament\Resources\AngsuranResource\RelationManagers;
use App\Models\Angsuran;
use App\Models\Rab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class AngsuranResource extends Resource
{
    protected static ?string $model = Angsuran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Angsuran';

    protected static ?string $modelLabel = 'Angsuran';

    protected static ?string $pluralModelLabel = 'Angsuran';

    protected static ?string $navigationGroup = 'Workflow';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data RAB')
                    ->description('Pilih RAB yang akan diangsur')
                    ->schema([
                        Forms\Components\Select::make('id_rab')
                            ->label('RAB')
                            ->relationship('rab', 'id_rab')
                            ->getOptionLabelFromRecordUsing(fn (Rab $record): string => 
                                ($record->pendaftaran ? $record->pendaftaran->nomor_registrasi : 'No Reg') . 
                                " - {$record->nama_pelanggan} (Rp " . 
                                number_format((float) ($record->total_biaya_sambungan_baru ?? 0), 0, ',', '.') . ")"
                            )
                            ->searchable(['nama_pelanggan'])
                            ->preload()
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit'),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Detail Angsuran')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('periode_tagihan')
                                    ->label('Periode Tagihan')
                                    ->helperText('Format: YYYYMM (contoh: 202410)')
                                    ->numeric()
                                    ->minValue(202401)
                                    ->maxValue(203012)
                                    ->required(),
                                    
                                Forms\Components\TextInput::make('angsuran_ke')
                                    ->label('Angsuran Ke-')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required(),
                                    
                                Forms\Components\TextInput::make('nominal_angsuran')
                                    ->label('Nominal Angsuran')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                            ]),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('sisa_pokok')
                                    ->label('Sisa Pokok')
                                    ->numeric()
                                    ->prefix('Rp'),
                                    
                                Forms\Components\Select::make('status_bayar')
                                    ->label('Status Bayar')
                                    ->options([
                                        'belum_bayar' => 'Belum Bayar',
                                        'sudah_bayar' => 'Sudah Bayar',
                                        'terlambat' => 'Terlambat',
                                    ])
                                    ->default('belum_bayar')
                                    ->required(),
                            ]),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_jatuh_tempo')
                                    ->label('Tanggal Jatuh Tempo')
                                    ->required()
                                    ->native(false),
                                    
                                Forms\Components\DatePicker::make('tanggal_bayar')
                                    ->label('Tanggal Bayar')
                                    ->native(false)
                                    ->visible(fn (callable $get) => in_array($get('status_bayar'), ['sudah_bayar', 'terlambat'])),
                            ]),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('denda')
                                    ->label('Denda')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),
                                    
                                Forms\Components\TextInput::make('total_bayar')
                                    ->label('Total Bayar')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->helperText('Nominal angsuran + denda')
                                    ->visible(fn (callable $get) => in_array($get('status_bayar'), ['sudah_bayar', 'terlambat'])),
                            ]),
                    ])
                    ->collapsible(),
                    
                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['rab.pendaftaran']))
            ->columns([
                Tables\Columns\TextColumn::make('rab.pendaftaran.nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                    
                Tables\Columns\TextColumn::make('rab.nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('periode_tagihan_text')
                    ->label('Periode Tagihan')
                    ->sortable('periode_tagihan'),
                    
                Tables\Columns\TextColumn::make('angsuran_ke')
                    ->label('Angsuran Ke-')
                    ->formatStateUsing(fn ($state, $record) => 
                        $state . '/' . ($record->rab ? ($record->rab->jumlah_cicilan ?? '-') : '-')
                    )
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nominal_angsuran')
                    ->label('Nominal Angsuran')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status_bayar')
                    ->label('Status')
                    ->colors([
                        'warning' => 'belum_bayar',
                        'success' => 'sudah_bayar',
                        'danger' => 'terlambat',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'belum_bayar',
                        'heroicon-o-check-circle' => 'sudah_bayar',
                        'heroicon-o-exclamation-triangle' => 'terlambat',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'belum_bayar' => 'Belum Bayar',
                        'sudah_bayar' => 'Sudah Bayar',
                        'terlambat' => 'Terlambat',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d/m/Y')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('denda')
                    ->label('Denda')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('total_bayar')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('periode_tagihan', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status_bayar')
                    ->label('Status Bayar')
                    ->options([
                        'belum_bayar' => 'Belum Bayar',
                        'sudah_bayar' => 'Sudah Bayar',
                        'terlambat' => 'Terlambat',
                    ]),
                    
                Tables\Filters\Filter::make('periode_tagihan')
                    ->form([
                        Forms\Components\TextInput::make('periode_dari')
                            ->label('Periode Dari (YYYYMM)')
                            ->numeric(),
                        Forms\Components\TextInput::make('periode_sampai')
                            ->label('Periode Sampai (YYYYMM)')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['periode_dari'],
                                fn (Builder $query, $periode): Builder => $query->where('periode_tagihan', '>=', $periode),
                            )
                            ->when(
                                $data['periode_sampai'],
                                fn (Builder $query, $periode): Builder => $query->where('periode_tagihan', '<=', $periode),
                            );
                    }),
                    
                Tables\Filters\Filter::make('jatuh_tempo')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_jatuh_tempo', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_jatuh_tempo', '<=', $date),
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
                    
                Tables\Actions\Action::make('edit_nominal')
                    ->label('Edit Nominal')
                    ->icon('heroicon-o-calculator')
                    ->color('info')
                    ->visible(fn (Angsuran $record) => 
                        $record->rab && 
                        $record->rab->mode_cicilan === 'custom' && 
                        $record->status_bayar === 'belum_bayar'
                    )
                    ->form([
                        Forms\Components\TextInput::make('nominal_baru')
                            ->label('Nominal Baru')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(fn (Angsuran $record) => $record->nominal_angsuran),
                            
                        Forms\Components\Textarea::make('alasan_perubahan')
                            ->label('Alasan Perubahan')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (Angsuran $record, array $data) {
                        try {
                            $nominalLama = $record->nominal_angsuran;
                            
                            $record->update([
                                'nominal_angsuran' => $data['nominal_baru'],
                                'catatan' => $record->catatan . "\n\nEdit Nominal: " . 
                                           "Dari Rp " . number_format((float) $nominalLama, 0, ',', '.') . 
                                           " ke Rp " . number_format((float) $data['nominal_baru'], 0, ',', '.') . 
                                           "\nAlasan: " . $data['alasan_perubahan'],
                                'diperbarui_oleh' => auth()->user()->name ?? 'System',
                                'diperbarui_pada' => now(),
                            ]);
                            
                            // Update sisa pokok untuk angsuran berikutnya
                            $record->rab->generateAngsuran();
                            
                            Notification::make()
                                ->title('Nominal angsuran berhasil diubah')
                                ->success()
                                ->send();
                                
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body('Terjadi kesalahan: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                    
                Tables\Actions\Action::make('bayar')
                    ->label('Bayar')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Angsuran $record) => $record->status_bayar === 'belum_bayar')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_bayar')
                            ->label('Tanggal Bayar')
                            ->default(now())
                            ->required()
                            ->native(false),
                            
                        Forms\Components\TextInput::make('denda')
                            ->label('Denda')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                            
                        Forms\Components\TextInput::make('total_bayar')
                            ->label('Total Bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                            
                        Forms\Components\Textarea::make('catatan_pembayaran')
                            ->label('Catatan Pembayaran')
                            ->rows(2),
                    ])
                    ->action(function (Angsuran $record, array $data) {
                        try {
                            $record->update([
                                'status_bayar' => 'sudah_bayar',
                                'tanggal_bayar' => $data['tanggal_bayar'],
                                'denda' => $data['denda'] ?? 0,
                                'total_bayar' => $data['total_bayar'],
                                'catatan' => $record->catatan . "\n\nPembayaran: " . ($data['catatan_pembayaran'] ?? ''),
                                'diperbarui_oleh' => auth()->user()->name ?? 'System',
                                'diperbarui_pada' => now(),
                            ]);
                            
                            Notification::make()
                                ->title('Pembayaran angsuran berhasil dicatat')
                                ->success()
                                ->send();
                                
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body('Terjadi kesalahan: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AngsuranResource\Widgets\AngsuranStatOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAngsurans::route('/'),
            'create' => Pages\CreateAngsuran::route('/create'),
            'edit' => Pages\EditAngsuran::route('/{record}/edit'),
        ];
    }
}
