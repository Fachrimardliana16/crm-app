<?php

namespace App\Filament\Resources\SurveiResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Pendaftaran;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class PendaftaranMenungguSurveiWidget extends BaseWidget
{
    protected static ?string $heading = 'Pendaftaran Menunggu Survei';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pendaftaran::query()
                    ->where('status_pendaftaran', 'draft')
                    ->whereNull('id_pelanggan')
                    ->whereDoesntHave('survei')
                    ->with(['cabang', 'kelurahan.kecamatan', 'tipeLayanan', 'tipePendaftaran', 'jenisDaftar'])
                    ->orderBy('tanggal_daftar', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->description(fn ($record): string => $record->nama_pemohon)
                    ->searchable(['nomor_registrasi', 'nama_pemohon'])
                    ->sortable()
                    ->weight('medium'),

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
                        $alamat = \Str::limit($record->alamat_pemasangan ?? '-', 30);
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
                            return 'Maps';
                        }
                        return '-';
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
                        \Filament\Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn (Builder $query, $date): Builder => $query->whereDate('tanggal_daftar', '>=', $date))
                            ->when($data['sampai_tanggal'], fn (Builder $query, $date): Builder => $query->whereDate('tanggal_daftar', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('filament.admin.resources.pendaftarans.view', ['record' => $record->id_pendaftaran]))
                    ->openUrlInNewTab(),

                Action::make('buat_survei')
                    ->label('Buat Survei')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Buat Survei dari Pendaftaran')
                    ->modalDescription(function ($record) {
                        return "Apakah Anda yakin ingin membuat survei untuk pendaftaran {$record->nomor_registrasi} atas nama {$record->nama_pemohon}?";
                    })
                    ->action(function ($record) {
                        try {
                            // Cek apakah sudah ada survei untuk pendaftaran ini
                            $existingSurvei = \App\Models\Survei::where('id_pendaftaran', $record->id_pendaftaran)->first();
                            
                            if ($existingSurvei) {
                                Notification::make()
                                    ->title('Survei sudah ada!')
                                    ->body("Pendaftaran {$record->nomor_registrasi} sudah memiliki survei. Anda akan diarahkan ke halaman edit survei.")
                                    ->warning()
                                    ->send();
                                
                                // Redirect ke halaman edit survei yang sudah ada
                                return redirect()->route('filament.admin.resources.surveis.edit', ['record' => $existingSurvei->id_survei]);
                            }

                            // Generate ID survei dengan UUID
                            $surveiId = \Str::uuid();

                            // Buat survei baru
                            $survei = \App\Models\Survei::create([
                                'id_survei' => $surveiId,
                                'id_pendaftaran' => $record->id_pendaftaran,
                                'id_pelanggan' => null, // Akan diisi setelah pelanggan dibuat
                                'nip_surveyor' => auth()->user()->email ?? 'SYSTEM', // Gunakan email user atau SYSTEM
                                'tanggal_survei' => now(),
                                'status_survei' => 'draft',
                                'latitude_terverifikasi' => $record->latitude_awal,
                                'longitude_terverifikasi' => $record->longitude_awal,
                                'elevasi_terverifikasi_mdpl' => $record->elevasi_awal_mdpl,
                                'dibuat_oleh' => auth()->user()->name,
                                'dibuat_pada' => now(),
                            ]);

                            // Update status pendaftaran
                            $record->update(['status_pendaftaran' => 'survei']);

                            Notification::make()
                                ->title('Survei berhasil dibuat!')
                                ->body("Survei baru telah dibuat dari pendaftaran {$record->nomor_registrasi}. Anda akan diarahkan ke halaman edit survei.")
                                ->success()
                                ->send();

                            // Redirect ke halaman edit survei yang baru dibuat
                            return redirect()->route('filament.admin.resources.surveis.edit', ['record' => $survei->id_survei]);
                            
                        } catch (\Exception $e) {
                            \Log::error('Error creating survei: ' . $e->getMessage(), [
                                'pendaftaran_id' => $record->id_pendaftaran,
                                'user' => auth()->user()->email,
                                'trace' => $e->getTraceAsString()
                            ]);
                            
                            Notification::make()
                                ->title('Error!')
                                ->body('Terjadi kesalahan saat membuat survei: ' . $e->getMessage())
                                ->danger()
                                ->send();
                                
                            return null;
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('buat_survei_bulk')
                        ->label('Buat Survei untuk Dipilih')
                        ->icon('heroicon-o-magnifying-glass')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Buat Survei Massal')
                        ->modalDescription('Apakah Anda yakin ingin membuat survei untuk semua pendaftaran yang dipilih?')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $created = 0;
                            $alreadyExists = 0;

                            foreach ($records as $record) {
                                // Cek apakah sudah ada survei
                                $existingSurvei = \App\Models\Survei::where('id_pendaftaran', $record->id_pendaftaran)->first();
                                
                                if ($existingSurvei) {
                                    $alreadyExists++;
                                    continue;
                                }

                                // Buat survei baru
                                $surveiId = \Str::uuid();
                                \App\Models\Survei::create([
                                    'id_survei' => $surveiId,
                                    'id_pendaftaran' => $record->id_pendaftaran,
                                    'id_pelanggan' => null, // Akan diisi setelah pelanggan dibuat
                                    'nip_surveyor' => auth()->user()->email ?? 'SYSTEM', // Gunakan email user atau SYSTEM
                                    'tanggal_survei' => now(),
                                    'status_survei' => 'draft',
                                    'latitude_terverifikasi' => $record->latitude_awal,
                                    'longitude_terverifikasi' => $record->longitude_awal,
                                    'elevasi_terverifikasi_mdpl' => $record->elevasi_awal_mdpl,
                                    'dibuat_oleh' => auth()->user()->name,
                                    'dibuat_pada' => now(),
                                ]);

                                // Update status pendaftaran
                                $record->update(['status_pendaftaran' => 'survei']);
                                $created++;
                            }

                            if ($created > 0) {
                                Notification::make()
                                    ->title('Survei berhasil dibuat!')
                                    ->body("$created survei baru telah dibuat.")
                                    ->success()
                                    ->send();
                            }

                            if ($alreadyExists > 0) {
                                Notification::make()
                                    ->title('Informasi')
                                    ->body("$alreadyExists pendaftaran sudah memiliki survei.")
                                    ->warning()
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->defaultSort('tanggal_daftar', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}