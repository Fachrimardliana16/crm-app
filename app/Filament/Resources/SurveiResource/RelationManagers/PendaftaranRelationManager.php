<?php

namespace App\Filament\Resources\SurveiResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Components\Tab;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Pendaftaran;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class PendaftaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pendaftaranDraft';

    protected static ?string $title = 'Pendaftaran Menunggu Survei';

    protected static ?string $label = 'Pendaftaran Draft';

    protected static ?string $pluralLabel = 'Pendaftaran Draft';

    protected static ?string $recordTitleAttribute = 'nomor_registrasi';

    // Override untuk menggunakan query custom yang tidak tergantung pada relationship
    protected function getTableQuery(): Builder
    {
        return Pendaftaran::query()
            ->where('status_pendaftaran', 'draft')
            ->whereNull('id_pelanggan')
            ->whereDoesntHave('survei')
            ->with(['cabang', 'kelurahan.kecamatan', 'tipeLayanan', 'tipePendaftaran', 'jenisDaftar']);
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('Semua')
                ->modifyQueryUsing(fn (Builder $query) => $query), // tampilkan semua pendaftaran menunggu survei
        ];

        // Ambil semua cabang secara dinamis
        $branches = \App\Models\Cabang::orderBy('nama_cabang')->get();

        foreach ($branches as $branch) {
            // Hitung pendaftaran menunggu survei per cabang
            $count = Pendaftaran::where('status_pendaftaran', 'draft')
                ->whereNull('id_pelanggan')
                ->whereDoesntHave('survei')
                ->where('id_cabang', $branch->id_cabang)
                ->count();

            $tabs['branch_' . $branch->id_cabang] = Tab::make($branch->nama_cabang)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('id_cabang', $branch->id_cabang))
                ->badge($count > 0 ? $count : null);
        }

        return $tabs;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_registrasi')
                    ->label('Nomor Registrasi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_pemohon')
                    ->label('Nama Pemohon')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->required(),
                Forms\Components\Textarea::make('alamat_pemasangan')
                    ->label('Alamat Pemasangan')
                    ->required()
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_registrasi')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->description(fn ($record): string => $record->nama_pemohon)
                    ->searchable(['nomor_registrasi', 'nama_pemohon'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipeLayanan.nama_tipe_layanan')
                    ->label('Layanan')
                    ->description(function ($record) {
                        $tipePendaftaran = $record->tipePendaftaran?->nama_tipe_pendaftaran ?? '-';
                        $jenisDaftar = $record->jenisDaftar?->nama_jenis_daftar ?? '-';
                        return $tipePendaftaran . ' • ' . $jenisDaftar;
                    })
                    ->searchable(['tipeLayanan.nama_tipe_layanan', 'tipePendaftaran.nama_tipe_pendaftaran', 'jenisDaftar.nama_jenis_daftar'])
                    ->wrap(),

                Tables\Columns\TextColumn::make('cabang.nama_cabang')
                    ->label('Lokasi')
                    ->description(function ($record) {
                        $kelurahan = $record->kelurahan?->nama_kelurahan ?? '-';
                        $kecamatan = $record->kelurahan?->kecamatan?->nama_kecamatan ?? '-';
                        $alamat = \Str::limit($record->alamat_pemasangan ?? '-', 40);
                        return $kelurahan . ', ' . $kecamatan . "\n" . $alamat;
                    })
                    ->searchable(['cabang.nama_cabang', 'kelurahan.nama_kelurahan', 'alamat_pemasangan'])
                    ->wrap(),

                Tables\Columns\TextColumn::make('no_hp_pemohon')
                    ->label('No. HP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('coordinates')
                    ->label('Koordinat')
                    ->getStateUsing(function ($record) {
                        if ($record->latitude_awal && $record->longitude_awal) {
                            return 'Lihat Maps';
                        }
                        return 'Tidak ada';
                    })
                    ->url(function ($record) {
                        if ($record->latitude_awal && $record->longitude_awal) {
                            return "https://www.google.com/maps?q={$record->latitude_awal},{$record->longitude_awal}";
                        }
                        return null;
                    })
                    ->openUrlInNewTab()
                    ->color(function ($record) {
                        return ($record->latitude_awal && $record->longitude_awal) ? 'primary' : 'gray';
                    })
                    ->icon(function ($record) {
                        return ($record->latitude_awal && $record->longitude_awal) ? 'heroicon-o-map-pin' : 'heroicon-o-x-mark';
                    })
                    ->tooltip(fn ($record) => $record->latitude_awal && $record->longitude_awal
                        ? "Lat: {$record->latitude_awal}, Lng: {$record->longitude_awal}"
                        : 'Koordinat belum diset'),

                Tables\Columns\TextColumn::make('total_biaya_pendaftaran')
                    ->label('Total Biaya')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_cabang')
                    ->label('Cabang')
                    ->relationship('cabang', 'nama_cabang')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('id_tipe_layanan')
                    ->label('Tipe Layanan')
                    ->relationship('tipeLayanan', 'nama_tipe_layanan')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('tanggal_daftar')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn (Builder $query, $date): Builder => $query->whereDate('tanggal_daftar', '>=', $date))
                            ->when($data['sampai_tanggal'], fn (Builder $query, $date): Builder => $query->whereDate('tanggal_daftar', '<=', $date));
                    }),
            ])
            ->headerActions([
                // Tidak perlu create action karena ini hanya untuk view
            ])
            ->actions([
            ActionGroup::make([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('info'),

                Action::make('buat_survei')
                    ->label('Buat Survei')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('success')
                    ->action(function ($record) {
                        // Cek apakah survei sudah ada
                        $existingSurvei = \App\Models\Survei::where('id_pendaftaran', $record->id_pendaftaran)->first();

                        if ($existingSurvei) {
                            Notification::make()
                                ->title('Survei sudah ada!')
                                ->body('Pendaftaran ini sudah memiliki survei.')
                                ->warning()
                                ->send();
                            return;
                        }
                      
                        // Redirect ke halaman CREATE dengan data pendaftaran sebagai parameter
                        return redirect()->to(
                            \App\Filament\Resources\SurveiResource::getUrl('create', [
                                'id_pendaftaran' => $record->id_pendaftaran
                            ])
                        );
                      
                    }),
            ])
            ->label('Aksi')
            ->icon('heroicon-m-cog-6-tooth')
            ->color('gray')
        ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tidak ada bulk action untuk keamanan
                ]),
            ])
            ->defaultSort('tanggal_daftar', 'desc');
    }
}
