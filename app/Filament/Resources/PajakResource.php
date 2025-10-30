<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PajakResource\Pages;
use App\Models\Pajak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class PajakResource extends Resource
{
    protected static ?string $model = Pajak::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Pajak';

    protected static ?string $modelLabel = 'Pajak';

    protected static ?string $pluralModelLabel = 'Pajak';

    protected static ?string $navigationGroup = 'Master Pembayaran';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pajak')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_pajak')
                                    ->label('Kode Pajak')
                                    ->required()
                                    ->maxLength(10)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Contoh: PPN, PPH'),

                                Forms\Components\TextInput::make('nama_pajak')
                                    ->label('Nama Pajak')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Pajak Pertambahan Nilai'),
                            ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('jenis_pajak')
                                    ->label('Jenis Pajak')
                                    ->options([
                                        'persentase' => 'Persentase',
                                        'nilai_tetap' => 'Nilai Tetap',
                                    ])
                                    ->required()
                                    ->default('persentase')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Reset nilai ketika jenis berubah
                                        if ($state === 'persentase') {
                                            $set('nilai_tetap', null);
                                        } else {
                                            $set('persentase_pajak', 0);
                                        }
                                    }),

                                Forms\Components\TextInput::make('persentase_pajak')
                                    ->label('Persentase (%)')
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix('%')
                                    ->placeholder('11.00')
                                    ->visible(fn(callable $get) => $get('jenis_pajak') === 'persentase')
                                    ->required(fn(callable $get) => $get('jenis_pajak') === 'persentase'),

                                Forms\Components\TextInput::make('nilai_tetap')
                                    ->label('Nilai Tetap')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('50000')
                                    ->visible(fn(callable $get) => $get('jenis_pajak') === 'nilai_tetap')
                                    ->required(fn(callable $get) => $get('jenis_pajak') === 'nilai_tetap'),
                            ]),

                        Forms\Components\Toggle::make('status_aktif')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Aktifkan pajak ini untuk digunakan dalam transaksi'),
                    ]),

                Section::make('Preview Perhitungan')
                    ->description('Simulasi perhitungan pajak')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('simulasi_nilai_dasar')
                                    ->label('Nilai Dasar (Simulasi)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('1000000')
                                    ->live()
                                    ->dehydrated(false),

                                Forms\Components\TextInput::make('simulasi_hasil_pajak')
                                    ->label('Hasil Pajak')
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->formatStateUsing(function (callable $get) {
                                        $nilaiDasar = $get('simulasi_nilai_dasar');
                                        $jenisPajak = $get('jenis_pajak');
                                        $persentase = $get('persentase_pajak');
                                        $nilaiTetap = $get('nilai_tetap');

                                        if (!$nilaiDasar) return 0;

                                        if ($jenisPajak === 'persentase' && $persentase) {
                                            return $nilaiDasar * ($persentase / 100);
                                        } elseif ($jenisPajak === 'nilai_tetap' && $nilaiTetap) {
                                            return $nilaiTetap;
                                        }

                                        return 0;
                                    }),

                                Forms\Components\TextInput::make('simulasi_total')
                                    ->label('Total (Dasar + Pajak)')
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->formatStateUsing(function (callable $get) {
                                        $nilaiDasar = (float)($get('simulasi_nilai_dasar') ?? 0);
                                        $jenisPajak = $get('jenis_pajak');
                                        $persentase = $get('persentase_pajak');
                                        $nilaiTetap = $get('nilai_tetap');

                                        $hasilPajak = 0;
                                        if ($jenisPajak === 'persentase' && $persentase) {
                                            $hasilPajak = $nilaiDasar * ($persentase / 100);
                                        } elseif ($jenisPajak === 'nilai_tetap' && $nilaiTetap) {
                                            $hasilPajak = $nilaiTetap;
                                        }

                                        return $nilaiDasar + $hasilPajak;
                                    }),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_pajak')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_pajak')
                    ->label('Nama Pajak')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('jenis_pajak')
                    ->label('Jenis')
                    ->colors([
                        'success' => 'persentase',
                        'warning' => 'nilai_tetap',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'persentase' => 'Persentase',
                        'nilai_tetap' => 'Nilai Tetap',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('persentase_pajak')
                    ->label('Persentase')
                    ->suffix('%')
                    ->sortable()
                    ->visible(fn() => true)
                    ->getStateUsing(function ($record) {
                        return $record->jenis_pajak === 'persentase' ? $record->persentase_pajak : '-';
                    }),

                Tables\Columns\TextColumn::make('nilai_tetap')
                    ->label('Nilai Tetap')
                    ->money('IDR')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->jenis_pajak === 'nilai_tetap' ? $record->nilai_tetap : null;
                    }),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\IconColumn::make('status_aktif')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_pajak')
                    ->label('Jenis Pajak')
                    ->options([
                        'persentase' => 'Persentase',
                        'nilai_tetap' => 'Nilai Tetap',
                    ]),

                Tables\Filters\SelectFilter::make('status_aktif')
                    ->label('Status')
                    ->options([
                        true => 'Aktif',
                        false => 'Non-Aktif',
                    ]),
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
            ->defaultSort('nama_pajak', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPajaks::route('/'),
            'create' => Pages\CreatePajak::route('/create'),
            'edit' => Pages\EditPajak::route('/{record}/edit'),
        ];
    }
}
