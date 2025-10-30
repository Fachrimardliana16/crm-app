<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterAtapBangunanResource\Pages;
use App\Filament\Resources\MasterAtapBangunanResource\RelationManagers;
use App\Models\MasterAtapBangunan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterAtapBangunanResource extends Resource
{
    protected static ?string $model = MasterAtapBangunan::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Atap Bangunan';

    public static function form(Form $form): Form
    {
       return $form
        ->schema([
            Forms\Components\Section::make('Informasi Dasar')
                ->description('Masukkan informasi utama untuk data ini.')
                ->schema([
                    Forms\Components\TextInput::make('kode')
                                ->label('Kode Unik')
                                ->required()
                                ->maxLength(4)
                                ->placeholder('Masukkan kode unik (max 4 karakter)')
                                ->autofocus()
                                ->rules(['alpha_dash'])
                                ->suffixAction(
                                    Forms\Components\Actions\Action::make('hint_kode')
                                        ->icon('heroicon-o-information-circle')
                                        ->color('primary')
                                        ->action(function () {
                                            \Filament\Notifications\Notification::make()
                                                ->title('Ketentuan Kode')
                                                ->body('Gunakan kode yang mudah diingat.')
                                                ->info()
                                                ->send();
                                        })
                                    ),
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Masukkan nama lengkap')
                        ->suffixAction(
                                    Forms\Components\Actions\Action::make('hint_kode')
                                        ->icon('heroicon-o-information-circle')
                                        ->color('primary')
                                        ->action(function () {
                                            \Filament\Notifications\Notification::make()
                                                ->title('Ketentuan Nama')
                                                ->body('Masukan Nama Pagar Bangunan.')
                                                ->info()
                                                ->send();
                                        })
                                )
                        ->live(onBlur: true),
                ])
                ->columns(2)
                ->collapsible(),
            Forms\Components\Section::make('Konfigurasi')
                ->description('Atur detail dan prioritas data.')
                ->schema([
                    Forms\Components\Grid::make(12)
                        ->schema([
                            Forms\Components\TextInput::make('skor')
                                ->label('Skor Penilaian')
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->placeholder('0-100')
                                ->minValue(0)
                                ->maxValue(100)
                                ->prefix('Skor')
                                ->helperText('Masukkan skor antara 0 hingga 100 untuk menilai performa.')
                                ->inputMode('numeric')
                                ->extraInputAttributes(['style' => 'border-color: #facc15;'])
                                ->live(debounce: 500)
                                ->columnSpan(4),
                            Forms\Components\TextInput::make('urutan')
                                ->label('Prioritas Urutan')
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->placeholder('Masukkan angka urutan')
                                ->minValue(0)
                                ->maxValue(999)
                                ->helperText('Angka lebih kecil berarti prioritas lebih tinggi.')
                                ->inputMode('numeric')
                                ->extraInputAttributes(['style' => 'border-color: #3b82f6;'])
                                ->columnSpan(4),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->required()
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->helperText('Aktifkan untuk menampilkan item di daftar publik.')
                                ->extraAttributes(['class' => 'cursor-pointer'])
                                ->columnSpan(4),
                        ]),
                    Forms\Components\Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->placeholder('Jelaskan detail tentang data ini...')
                        ->rows(3)
                        ->columnSpanFull()
                        ->hint('Opsional: Tambahkan deskripsi untuk informasi lebih lanjut.'),
                ])
                ->collapsible()
                ->compact(),
        ])
        ->statePath('data');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('skor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('urutan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListMasterAtapBangunans::route('/'),
            'create' => Pages\CreateMasterAtapBangunan::route('/create'),
            'edit' => Pages\EditMasterAtapBangunan::route('/{record}/edit'),
        ];
    }
}
