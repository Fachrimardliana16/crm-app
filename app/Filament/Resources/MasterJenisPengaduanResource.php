<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterJenisPengaduanResource\Pages;
use App\Filament\Resources\MasterJenisPengaduanResource\RelationManagers;
use App\Models\MasterJenisPengaduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterJenisPengaduanResource extends Resource
{
    protected static ?string $model = MasterJenisPengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Master Data Pengaduan';

    protected static ?string $navigationLabel = 'Jenis Pengaduan';

    protected static ?string $pluralModelLabel = 'Jenis Pengaduan';

    protected static ?string $modelLabel = 'Jenis Pengaduan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jenis Pengaduan')
                    ->description('Masukkan data jenis pengaduan yang digunakan dalam sistem.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('kode_jenis')
                            ->label('Kode Jenis')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('Contoh: ADM, TEKNIS, DLL')
                            ->prefixIcon('heroicon-o-identification'),

                        Forms\Components\TextInput::make('nama_jenis')
                            ->label('Nama Jenis Pengaduan')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: Pengaduan Administrasi / Air Tidak Mengalir')
                            ->prefixIcon('heroicon-o-pencil-square'),

                        Forms\Components\Select::make('id_prioritas_pengaduan')
                            ->label('Prioritas Otomatis')
                            ->relationship('prioritas', 'nama_prioritas')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih prioritas default')
                            ->prefixIcon('heroicon-o-bolt'),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Jenis Pengaduan')
                            ->placeholder('Tuliskan penjelasan singkat mengenai jenis pengaduan ini...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Metadata')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('dibuat_oleh')
                            ->label('Dibuat Oleh')
                            ->readOnly()
                            ->default(fn() => auth()->user()->name ?? 'SYSTEM'),
                        Forms\Components\DateTimePicker::make('dibuat_pada')
                            ->label('Dibuat Pada')
                            ->default(now())
                            ->readOnly(),
                        Forms\Components\TextInput::make('diperbarui_oleh')
                            ->label('Diperbarui Oleh')
                            ->readOnly()
                            ->dehydrated(false),
                        Forms\Components\DateTimePicker::make('diperbarui_pada')
                            ->label('Diperbarui Pada')
                            ->readOnly()
                            ->dehydrated(false),
                    ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('kode_jenis')
                ->label('Kode')
                ->badge()
                ->color('info')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('nama_jenis')
                ->label('Nama Jenis Pengaduan')
                ->searchable()
                ->wrap(),

            Tables\Columns\TextColumn::make('prioritas.nama_prioritas')
                ->label('Prioritas Otomatis')
                ->badge()
                ->color(fn($record) => match($record->prioritas->nama_prioritas ?? null) {
                    'Tinggi' => 'danger',
                    'Sedang' => 'warning',
                    'Rendah' => 'success',
                    default => 'gray',
                })
                ->sortable(),

            Tables\Columns\TextColumn::make('dibuat_pada')
                ->label('Dibuat Pada')
                ->dateTime('d M Y H:i')
                ->sortable(),

            Tables\Columns\TextColumn::make('diperbarui_pada')
                ->label('Diperbarui')
                ->dateTime('d M Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])->columns([
            Tables\Columns\TextColumn::make('kode_jenis')
                ->label('Kode')
                ->badge()
                ->color('info')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('nama_jenis')
                ->label('Nama Jenis Pengaduan')
                ->searchable()
                ->wrap(),

            Tables\Columns\TextColumn::make('prioritas.nama_prioritas')
                ->label('Prioritas Otomatis')
                ->badge()
                ->color(fn($record) => match($record->prioritas->nama_prioritas ?? null) {
                    'Tinggi' => 'danger',
                    'Sedang' => 'warning',
                    'Rendah' => 'success',
                    default => 'gray',
                })
                ->sortable(),

            Tables\Columns\TextColumn::make('dibuat_pada')
                ->label('Dibuat Pada')
                ->dateTime('d M Y H:i')
                ->sortable(),

            Tables\Columns\TextColumn::make('diperbarui_pada')
                ->label('Diperbarui')
                ->dateTime('d M Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
           ->filters([
                Tables\Filters\SelectFilter::make('id_prioritas_pengaduan')
                    ->label('Prioritas')
                    ->relationship('prioritas', 'nama_prioritas'),
            ])
           ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Data Terpilih')
                        ->color('danger'),
                ]),
            ])
            ->emptyStateHeading('Belum ada data jenis pengaduan')
            ->emptyStateDescription('Tambahkan jenis pengaduan baru untuk mulai mengelola kategori laporan.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
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
            'index' => Pages\ListMasterJenisPengaduans::route('/'),
            'create' => Pages\CreateMasterJenisPengaduan::route('/create'),
            'edit' => Pages\EditMasterJenisPengaduan::route('/{record}/edit'),
        ];
    }
}
