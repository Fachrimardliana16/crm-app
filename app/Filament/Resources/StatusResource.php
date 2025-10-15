<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusResource\Pages;
use App\Filament\Resources\StatusResource\RelationManagers;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusResource extends Resource
{
    protected static ?string $model = Status::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Status';

    protected static ?string $modelLabel = 'Status';

    protected static ?string $pluralModelLabel = 'Status';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Status')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('tabel_referensi')
                                    ->label('Tabel Referensi')
                                    ->options([
                                        'pendaftaran' => 'Pendaftaran',
                                        'survei' => 'Survei',
                                        'rab' => 'RAB',
                                        'instalasi' => 'Instalasi',
                                        'pengaduan' => 'Pengaduan',
                                        'pelanggan' => 'Pelanggan',
                                        'pembayaran' => 'Pembayaran',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('kode_status')
                                    ->label('Kode Status')
                                    ->required()
                                    ->maxLength(50),
                            ]),

                        Forms\Components\TextInput::make('nama_status')
                            ->label('Nama Status')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('deskripsi_status')
                            ->label('Deskripsi Status')
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('Tampilan')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('warna_status')
                                    ->label('Warna Status')
                                    ->options([
                                        'gray' => 'Abu-abu',
                                        'danger' => 'Merah',
                                        'warning' => 'Kuning',
                                        'success' => 'Hijau',
                                        'info' => 'Biru',
                                        'primary' => 'Indigo',
                                        'purple' => 'Ungu',
                                        'pink' => 'Pink',
                                    ])
                                    ->default('gray'),

                                Forms\Components\TextInput::make('urutan_tampil')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(1),

                                Forms\Components\Toggle::make('status_aktif')
                                    ->label('Status Aktif')
                                    ->default(true),
                            ]),
                    ]),

                Forms\Components\Section::make('Keterangan')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->rows(3),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tabel_referensi')
                    ->label('Tabel')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kode_status')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_status')
                    ->label('Nama Status')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('warna_status')
                    ->label('Warna')
                    ->colors([
                        'gray' => 'gray',
                        'danger' => 'danger',
                        'warning' => 'warning',
                        'success' => 'success',
                        'info' => 'info',
                        'primary' => 'primary',
                        'purple' => 'purple',
                        'pink' => 'pink',
                    ]),

                Tables\Columns\TextColumn::make('urutan_tampil')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\IconColumn::make('status_aktif')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('dibuat_pada')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tabel_referensi')
                    ->label('Tabel Referensi')
                    ->options([
                        'pendaftaran' => 'Pendaftaran',
                        'survei' => 'Survei',
                        'rab' => 'RAB',
                        'instalasi' => 'Instalasi',
                        'pengaduan' => 'Pengaduan',
                        'pelanggan' => 'Pelanggan',
                        'pembayaran' => 'Pembayaran',
                    ]),

                Tables\Filters\TernaryFilter::make('status_aktif')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Non-Aktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tabel_referensi')
            ->groups([
                Tables\Grouping\Group::make('tabel_referensi')
                    ->label('Tabel Referensi'),
            ]);
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
            'index' => Pages\ListStatuses::route('/'),
            'create' => Pages\CreateStatus::route('/create'),
            'edit' => Pages\EditStatus::route('/{record}/edit'),
        ];
    }
}
