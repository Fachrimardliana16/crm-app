<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterDayaListrikResource\Pages;
use App\Filament\Resources\MasterDayaListrikResource\RelationManagers;
use App\Models\MasterDayaListrik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterDayaListrikResource extends Resource
{
    protected static ?string $model = MasterDayaListrik::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Daya Listrik';

    public static function form(Form $form): Form
    {
       return $form
        ->schema([
            Forms\Components\Section::make('Informasi Daya Listrik')
                ->description('Masukkan data daya listrik beserta rentangnya.')
                ->schema([
                    Forms\Components\Grid::make(12)
                        ->schema([
                            Forms\Components\TextInput::make('kode')
                                ->label('Kode Unik')
                                ->required()
                                ->maxLength(4)
                                ->prefix('#')
                                ->placeholder('Masukkan kode unik (max 4 karakter)')
                                ->autofocus()
                                ->rules(['alpha_dash'])
                                ->suffixAction(
                                    Forms\Components\Actions\Action::make('hint_kode')
                                        ->icon('heroicon-o-information-circle')
                                        ->color('primary')
                                        ->action(function () {
                                            \Filament\Notifications\Notification::make()
                                                ->title('Petunjuk Kode')
                                                ->body('Gunakan kode unik yang mudah diingat, maksimal 20 karakter.')
                                                ->info()
                                                ->send();
                                        })
                                )
                                ->columnSpan(4),
                            Forms\Components\TextInput::make('nama')
                                ->label('Nama Daya')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: 450 VA, 900 VA, 1300 VA')
                                ->helperText('Nama akan ditampilkan di daftar.')
                                ->live(onBlur: true)
                                ->columnSpan(8),
                        ]),
                    Forms\Components\Grid::make(12)
                        ->schema([
                            Forms\Components\TextInput::make('range_min')
                                ->label('Range Min (VA)')
                                ->numeric()
                                ->placeholder('Contoh: 450')
                                ->suffix('VA')
                                ->minValue(0)
                                ->inputMode('numeric')
                                ->helperText('Nilai minimum daya listrik.')
                                ->extraInputAttributes(['style' => 'border-color: #10b981;'])
                                ->columnSpan(6),
                            Forms\Components\TextInput::make('range_max')
                                ->label('Range Max (VA)')
                                ->numeric()
                                ->placeholder('Contoh: 1300')
                                ->suffix('VA')
                                ->minValue(0)
                                ->inputMode('numeric')
                                ->helperText('Nilai maksimum daya listrik.')
                                ->extraInputAttributes(['style' => 'border-color: #10b981;'])
                                ->columnSpan(6),
                        ]),
                    Forms\Components\Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->placeholder('Masukkan keterangan tambahan...')
                        ->rows(3)
                        ->columnSpanFull()
                        ->hint('Opsional: Tambahkan detail tentang daya listrik ini.'),
                ])
                ->collapsible()
                ->compact(),
            Forms\Components\Section::make('Nilai dan Status')
                ->description('Atur skor, urutan, dan status aktif.')
                ->schema([
                    Forms\Components\Grid::make(12)
                        ->schema([
                            Forms\Components\TextInput::make('skor')
                                ->label('Skor Penilaian')
                                ->numeric()
                                ->default(0)
                                ->placeholder('0-100')
                                ->minValue(0)
                                ->maxValue(100)
                                ->suffixIcon('heroicon-o-star')
                                ->helperText('Masukkan skor antara 0 hingga 100.')
                                ->inputMode('numeric')
                                ->extraInputAttributes(['style' => 'border-color: #facc15;'])
                                ->live(debounce: 500)
                                ->columnSpan(4),
                            Forms\Components\TextInput::make('urutan')
                                ->label('Prioritas Urutan')
                                ->numeric()
                                ->default(0)
                                ->placeholder('Masukkan angka urutan')
                                ->minValue(0)
                                ->maxValue(999)
                                ->prefix('#')
                                ->inputMode('numeric')
                                ->suffixAction(
                                    Forms\Components\Actions\Action::make('hint_urutan')
                                        ->icon('heroicon-o-information-circle')
                                        ->color('primary')
                                        ->action(function () {
                                            \Filament\Notifications\Notification::make()
                                                ->title('Petunjuk Urutan')
                                                ->body('Angka lebih kecil berarti prioritas lebih tinggi.')
                                                ->info()
                                                ->send();
                                        })
                                )
                                ->extraInputAttributes(['style' => 'border-color: #3b82f6;'])
                                ->columnSpan(4),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->inline(false)
                                ->helperText('Aktifkan untuk menampilkan di daftar publik.')
                                ->extraAttributes(['class' => 'cursor-pointer'])
                                ->columnSpan(4),
                        ]),
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
                Tables\Columns\TextColumn::make('range_min')
                    ->searchable(),
                Tables\Columns\TextColumn::make('range_max')
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
            'index' => Pages\ListMasterDayaListriks::route('/'),
            'create' => Pages\CreateMasterDayaListrik::route('/create'),
            'edit' => Pages\EditMasterDayaListrik::route('/{record}/edit'),
        ];
    }
}
