<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanRabResource\Pages;
use App\Models\TagihanRab;
use App\Models\Rab;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;

class TagihanRabResource extends Resource
{
    protected static ?string $model = TagihanRab::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Tagihan RAB';
    protected static ?string $modelLabel = 'Tagihan RAB';
    protected static ?string $pluralModelLabel = 'Tagihan RAB';
    protected static ?string $navigationGroup = 'Workflow PDAM';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Tagihan')
                    ->description('Data tagihan RAB')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_rab')
                                    ->label('RAB')
                                    ->relationship('rab', 'id_rab')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('id_pelanggan')
                                    ->label('Pelanggan')
                                    ->relationship('pelanggan', 'nama_pelanggan')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('nomor_tagihan')
                                    ->label('Nomor Tagihan')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\DatePicker::make('tanggal_terbit')
                                    ->label('Tanggal Terbit')
                                    ->required()
                                    ->default(now()),

                                Forms\Components\DatePicker::make('jatuh_tempo')
                                    ->label('Jatuh Tempo')
                                    ->required()
                                    ->default(now()->addDays(30)),
                            ]),
                    ]),

                Section::make('Detail Tagihan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('total_tertagih')
                                    ->label('Total Tertagih')
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
                                    ->default('belum')
                                    ->required(),
                            ]),

                        Forms\Components\Textarea::make('catatan_tagihan')
                            ->label('Catatan Tagihan')
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
                Tables\Columns\TextColumn::make('nomor_tagihan')
                    ->label('No. Tagihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rab.total_final_rab')
                    ->label('Total RAB')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('total_tertagih')
                    ->label('Total Tertagih')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_terbit')
                    ->label('Tanggal Terbit')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->is_overdue ? 'danger' : null),

                Tables\Columns\BadgeColumn::make('status_pembayaran')
                    ->label('Status')
                    ->colors([
                        'danger' => 'belum',
                        'warning' => 'sebagian',
                        'success' => 'lunas',
                    ]),

                Tables\Columns\TextColumn::make('sisa_tagihan')
                    ->label('Sisa Tagihan')
                    ->money('IDR')
                    ->color(fn ($record) => $record->sisa_tagihan > 0 ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum' => 'Belum Bayar',
                        'sebagian' => 'Dibayar Sebagian',
                        'lunas' => 'Lunas',
                    ]),

                Tables\Filters\Filter::make('jatuh_tempo')
                    ->form([
                        Forms\Components\DatePicker::make('jatuh_tempo_from')
                            ->label('Jatuh Tempo Dari'),
                        Forms\Components\DatePicker::make('jatuh_tempo_until')
                            ->label('Jatuh Tempo Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['jatuh_tempo_from'], fn ($query, $date) => $query->where('jatuh_tempo', '>=', $date))
                            ->when($data['jatuh_tempo_until'], fn ($query, $date) => $query->where('jatuh_tempo', '<=', $date));
                    }),
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
            'index' => Pages\ListTagihanRabs::route('/'),
            'create' => Pages\CreateTagihanRab::route('/create'),
            'edit' => Pages\EditTagihanRab::route('/{record}/edit'),
        ];
    }
}
