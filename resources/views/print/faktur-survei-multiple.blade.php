<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            margin: 0 auto;
            background: white;
            position: relative;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 10pt;
            margin: 2px 0;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background: #f0f0f0;
            border: 2px solid #000;
        }

        .doc-title h3 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Info Boxes */
        .info-section {
            margin: 15px 0;
        }

        .info-title {
            font-weight: bold;
            font-size: 12pt;
            background: #e0e0e0;
            padding: 5px 10px;
            margin-bottom: 10px;
            border-left: 4px solid #000;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 35%;
            padding: 4px 8px;
            font-weight: 600;
            vertical-align: top;
        }

        .info-separator {
            display: table-cell;
            width: 5%;
            padding: 4px;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            width: 60%;
            padding: 4px 8px;
            vertical-align: top;
        }

        /* Parameter Table */
        .parameter-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .parameter-table th,
        .parameter-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        .parameter-table th {
            background: #d0d0d0;
            font-weight: bold;
            text-align: center;
        }

        .parameter-table td.center {
            text-align: center;
        }

        .parameter-table td.right {
            text-align: right;
        }

        /* Score Box */
        .score-box {
            margin: 20px 0;
            padding: 15px;
            border: 2px solid #000;
            background: #f9f9f9;
        }

        .score-grid {
            display: table;
            width: 100%;
        }

        .score-item {
            display: table-row;
        }

        .score-label {
            display: table-cell;
            width: 40%;
            padding: 8px;
            font-weight: bold;
            font-size: 11pt;
        }

        .score-value {
            display: table-cell;
            width: 60%;
            padding: 8px;
            font-size: 11pt;
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10pt;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #000;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 10px;
        }

        .signature-box p {
            margin: 5px 0;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 15mm;
            left: 15mm;
            right: 15mm;
            text-align: center;
            font-size: 9pt;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                margin: 0;
                padding: 15mm;
                width: 210mm;
                min-height: 297mm;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14pt;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            z-index: 1000;
        }

        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Cetak Semua</button>

    @foreach($surveis as $survei)
    <div class="page">
        <!-- Header -->
        <div class="header">
            <h1>PERUSAHAAN DAERAH AIR MINUM (PDAM)</h1>
            <h2>{{ $survei->pendaftaran->cabang->nama_cabang ?? 'PDAM' }}</h2>
            <p>{{ $survei->pendaftaran->cabang->alamat_cabang ?? '' }}</p>
            <p>Telp: {{ $survei->pendaftaran->cabang->no_telepon ?? '-' }} | Email: {{ $survei->pendaftaran->cabang->email ?? '-' }}</p>
        </div>

        <!-- Document Title -->
        <div class="doc-title">
            <h3>LEMBAR HASIL SURVEI LAPANGAN</h3>
        </div>

        <!-- Info Pendaftaran -->
        <div class="info-section">
            <div class="info-title">INFORMASI PENDAFTARAN</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nomor Registrasi</div>
                    <div class="info-separator">:</div>
                    <div class="info-value"><strong>{{ $survei->pendaftaran->nomor_registrasi }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Pendaftaran</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $survei->pendaftaran->tanggal_daftar?->format('d F Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nama Pemohon</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $survei->pendaftaran->nama_pemohon }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat Pemasangan</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $survei->pendaftaran->alamat_pemasangan }}</div>
                </div>
            </div>
        </div>

        <!-- Info Survei -->
        <div class="info-section">
            <div class="info-title">INFORMASI SURVEI</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Tanggal Survei</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $survei->tanggal_survei?->format('d F Y') ?? 'Belum dijadwalkan' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">SPAM</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $survei->spam->nama_spam ?? 'Belum ditentukan' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status Survei</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">
                        @if($survei->status_survei === 'disetujui')
                            <span class="badge badge-success">DISETUJUI</span>
                        @elseif($survei->status_survei === 'draft')
                            <span class="badge badge-warning">DRAFT</span>
                        @elseif($survei->status_survei === 'ditolak')
                            <span class="badge badge-danger">DITOLAK</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Parameter Survei (Ringkas) -->
        <div class="info-section">
            <div class="info-title">PARAMETER SURVEI & SCORING</div>
            <table class="parameter-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="40%">Parameter</th>
                        <th width="40%">Nilai</th>
                        <th width="15%">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center">1</td>
                        <td>Luas Tanah</td>
                        <td>{{ $survei->masterLuasTanah->nama ?? '-' }}</td>
                        <td class="center">{{ $survei->masterLuasTanah->skor ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="center">2</td>
                        <td>Luas Bangunan</td>
                        <td>{{ $survei->masterLuasBangunan->nama ?? '-' }}</td>
                        <td class="center">{{ $survei->masterLuasBangunan->skor ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="center">3</td>
                        <td>Lokasi Bangunan</td>
                        <td>{{ $survei->masterLokasiBangunan->nama ?? '-' }}</td>
                        <td class="center">{{ $survei->masterLokasiBangunan->skor ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="center">4</td>
                        <td>Dinding Bangunan</td>
                        <td>{{ $survei->masterDindingBangunan->nama ?? '-' }}</td>
                        <td class="center">{{ $survei->masterDindingBangunan->skor ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="center">5</td>
                        <td>Lantai Bangunan</td>
                        <td>{{ $survei->masterLantaiBangunan->nama ?? '-' }}</td>
                        <td class="center">{{ $survei->masterLantaiBangunan->skor ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="center">6</td>
                        <td>Atap Bangunan</td>
                        <td>{{ $survei->masterAtapBangunan->nama ?? '-' }}</td>
                        <td class="center">{{ $survei->masterAtapBangunan->skor ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="center">7</td>
                        <td>Kondisi Jalan</td>
                        <td>{{ $survei->masterKondisiJalan->nama ?? '-' }}</td>
                        <td class="center">{{ $survei->masterKondisiJalan->skor ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td class="center">8</td>
                        <td>Daya Listrik</td>
                        <td>{{ $survei->masterDayaListrik->nama ?? '-' }}</td>
                        <td class="center">{{ $survei->masterDayaListrik->skor ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold; background: #e0e0e0;">TOTAL SKOR</td>
                        <td class="center" style="font-weight: bold; background: #e0e0e0; font-size: 12pt;">{{ $survei->skor_total ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Hasil & Rekomendasi -->
        <div class="score-box">
            <div class="score-grid">
                <div class="score-item">
                    <div class="score-label">Skor Total:</div>
                    <div class="score-value"><strong>{{ $survei->skor_total ?? 0 }} poin</strong></div>
                </div>
                <div class="score-item">
                    <div class="score-label">Kategori Golongan:</div>
                    <div class="score-value"><strong>{{ $survei->kategori_golongan ?? 'Belum ditentukan' }}</strong></div>
                </div>
                <div class="score-item">
                    <div class="score-label">Rekomendasi Sub Golongan:</div>
                    <div class="score-value">
                        @if($survei->rekomendasiSubGolongan)
                            <strong>{{ $survei->rekomendasiSubGolongan->nama_sub_golongan }}</strong>
                        @else
                            Belum ditentukan
                        @endif
                    </div>
                </div>
                <div class="score-item">
                    <div class="score-label">Hasil Survei:</div>
                    <div class="score-value">
                        @if($survei->hasil_survei === 'direkomendasikan')
                            <span class="badge badge-success">DIREKOMENDASIKAN</span>
                        @elseif($survei->hasil_survei === 'tidak_direkomendasikan')
                            <span class="badge badge-danger">TIDAK DIREKOMENDASIKAN</span>
                        @elseif($survei->hasil_survei === 'perlu_review')
                            <span class="badge badge-warning">PERLU REVIEW</span>
                        @else
                            <span class="badge badge-info">BELUM DITENTUKAN</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p><strong>Kepala Cabang</strong></p>
                <div class="signature-line">
                    {{ $survei->pendaftaran->cabang->nama_cabang ?? '' }}
                </div>
            </div>
            <div class="signature-box">
                <p>Surveyor,</p>
                <p><strong>&nbsp;</strong></p>
                <div class="signature-line">
                    {{ $survei->nip_surveyor ?? '' }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
            <p>© {{ now()->year }} PDAM {{ $survei->pendaftaran->cabang->nama_cabang ?? '' }} - Faktur Hasil Survei (Halaman {{ $loop->iteration }} dari {{ $surveis->count() }})</p>
        </div>
    </div>
    @endforeach

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
