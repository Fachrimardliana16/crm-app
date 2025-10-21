<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendaftaran</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            margin: 0;
            padding: 15px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            margin-bottom: 5px;
            color: #2563eb;
        }

        .header .subtitle {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .filter-section {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .filter-row:last-child {
            margin-bottom: 0;
        }

        .filter-label {
            font-weight: bold;
            min-width: 120px;
            color: #374151;
        }

        .filter-value {
            color: #1f2937;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 6px 4px;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            background-color: #1f2937;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .data-table tr:hover {
            background-color: #f3f4f6;
        }

        .no-col {
            width: 4%;
            text-align: center;
        }

        .date-col {
            width: 8%;
        }

        .cabang-col {
            width: 12%;
        }

        .area-col {
            width: 10%;
        }

        .nama-col {
            width: 15%;
        }

        .alamat-col {
            width: 20%;
        }

        .tipe-col {
            width: 10%;
        }

        .status-col {
            width: 8%;
            text-align: center;
        }

        .biaya-col {
            width: 10%;
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #6b7280;
        }

        .summary-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-item {
            text-align: center;
        }

        .summary-number {
            font-size: 14px;
            font-weight: bold;
            color: #1d4ed8;
        }

        .summary-label {
            font-size: 10px;
            color: #374151;
            margin-top: 2px;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }

        .status-sudah {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-belum {
            background-color: #fef3c7;
            color: #92400e;
        }

        @page {
            margin: 15mm;
            size: A4 landscape;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="font-family: 'Times New Roman', Times, serif; font-size: 18pt; color: #1a3c6d; margin: 5px 0;">Perumda
            Air Minum Tirta Perwira</h2>
        <h2 style="font-family: 'Times New Roman', Times, serif; font-size: 14pt; color: #2d5a9e; margin: 5px 0;">
            Kabupaten Purbalingga</h2>
        <hr style="border: 0; border-top: 2px solid #1a3c6d; width: 100px; margin: 15px auto;">
        <h3
            style="font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #333; margin: 10px 0; text-transform;">
            Laporan Data Pendaftaran</h3>

    </div>

    <!-- Filter Information -->
    <div class="filter-section">
        <span class="filter-label">Periode:</span>
        <span class="filter-value">{{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}</span>
        @if (!empty($filters['cabang_unit']))
            <div class="filter-row">
                <span class="filter-label">Cabang/Unit:</span>
                <span
                    class="filter-value">{{ \App\Models\Cabang::whereIn('id_cabang', $filters['cabang_unit'])->pluck('nama_cabang')->implode(', ') }}</span>
            </div>
        @endif

        @if (!empty($filters['kecamatan']))
            <div class="filter-row">
                <span class="filter-label">Kecamatan:</span>
                <span
                    class="filter-value">{{ \App\Models\Kecamatan::whereIn('id_kecamatan', $filters['kecamatan'])->pluck('nama_kecamatan')->implode(', ') }}</span>
            </div>
        @endif

        @if (!empty($filters['kelurahan']))
            <div class="filter-row">
                <span class="filter-label">Kelurahan:</span>
                <span
                    class="filter-value">{{ \App\Models\Kelurahan::whereIn('id_kelurahan', $filters['kelurahan'])->pluck('nama_kelurahan')->implode(', ') }}</span>
            </div>
        @endif

        @if (!empty($filters['tipe_pelayanan']))
            <div class="filter-row">
                <span class="filter-label">Tipe Pelayanan:</span>
                <span
                    class="filter-value">{{ \App\Models\TipeLayanan::whereIn('id_tipe_layanan', $filters['tipe_pelayanan'])->pluck('nama_tipe_layanan')->implode(', ') }}</span>
            </div>
        @endif

        @if (!empty($filters['jenis_daftar']))
            <div class="filter-row">
                <span class="filter-label">Jenis Pendaftaran:</span>
                <span
                    class="filter-value">{{ \App\Models\JenisDaftar::whereIn('id_jenis_daftar', $filters['jenis_daftar'])->pluck('nama_jenis_daftar')->implode(', ') }}</span>
            </div>
        @endif

        @if (!empty($filters['tipe_pendaftaran']))
            <div class="filter-row">
                <span class="filter-label">Tipe Pendaftaran:</span>
                <span
                    class="filter-value">{{ \App\Models\TipePendaftaran::whereIn('id_tipe_pendaftaran', $filters['tipe_pendaftaran'])->pluck('nama_tipe_pendaftaran')->implode(', ') }}</span>
            </div>
        @endif
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th class="date-col">Tgl Daftar</th>
                <th class="nama-col">Nama Pemohon</th>
                <th class="alamat-col">Alamat Pemasangan</th>
                <th class="cabang-col">Cabang/Unit</th>
                <th class="area-col">Kec/Kel</th>
                <th class="tipe-col">Tipe Layanan</th>
                <th class="tipe-col">Jenis Daftar</th>
                <th class="biaya-col">Total Biaya</th>
                <th class="status-col">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pendaftarans as $index => $pendaftaran)
                <tr>
                    <td class="no-col">{{ $index + 1 }}</td>
                    <td class="date-col">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d/m/Y') }}
                    </td>
                    <td class="nama-col">{{ $pendaftaran->nama_pemohon ?? '-' }}</td>
                    <td class="alamat-col">
                        {{ \Illuminate\Support\Str::limit($pendaftaran->alamat_pemasangan ?? '-', 40) }}</td>
                    <td class="cabang-col">{{ $pendaftaran->cabang->nama_cabang ?? '-' }}</td>
                    <td class="area-col">
                        {{ $pendaftaran->kelurahan->kecamatan->nama_kecamatan ?? '-' }}<br>
                        <small>{{ $pendaftaran->kelurahan->nama_kelurahan ?? '-' }}</small>
                    </td>
                    <td class="tipe-col">{{ $pendaftaran->tipeLayanan->nama_tipe_layanan ?? '-' }}</td>
                    <td class="tipe-col">{{ $pendaftaran->jenisDaftar->nama_jenis_daftar ?? '-' }}</td>
                    <td class="biaya-col">
                        {{ $pendaftaran->total_biaya_pendaftaran ? 'Rp ' . number_format($pendaftaran->total_biaya_pendaftaran, 0, ',', '.') : '-' }}
                    </td>
                    <td class="status-col">
                        @if ($pendaftaran->id_pelanggan)
                            <span class="status-badge status-sudah">Sudah</span>
                        @else
                            <span class="status-badge status-belum">Belum</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px; color: #6b7280;">
                        Tidak ada data pendaftaran ditemukan pada periode dan filter yang dipilih
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div>
            <strong>Total: {{ $total_records }} pendaftaran</strong>
        </div>
        <div>
            Dicetak pada: {{ $generated_at->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>

</html>
