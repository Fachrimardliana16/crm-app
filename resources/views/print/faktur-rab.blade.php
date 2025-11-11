<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ $rab->pendaftaran->nomor_registrasi }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 7pt;
            line-height: 1.1;
            color: #000;
            background: #fff;
        }

        .page {
            width: 210mm;
            height: 148.5mm;
            padding: 6mm;
            margin: 0 auto;
            background: white;
            position: relative;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 2px;
            margin-bottom: 3px;
        }

        .header h1 {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 1px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 1px;
        }

        .header p {
            font-size: 6pt;
            margin: 0;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            margin: 3px 0;
            padding: 2px;
            background: #f0f0f0;
            border: 2px solid #000;
        }

        .doc-title h3 {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Two Column Layout */
        .two-column {
            display: table;
            width: 100%;
            margin: 2px 0;
        }

        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 3px;
        }

        .column:first-child {
            padding-left: 0;
        }

        .column:last-child {
            padding-right: 0;
        }

        /* Info Boxes */
        .info-section {
            margin: 2px 0;
        }

        .info-title {
            font-weight: bold;
            font-size: 7pt;
            background: #e0e0e0;
            padding: 1px 3px;
            margin-bottom: 2px;
            border-left: 3px solid #000;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 40%;
            padding: 0px 2px;
            font-weight: 600;
            vertical-align: top;
            font-size: 6.5pt;
        }

        .info-separator {
            display: table-cell;
            width: 3%;
            padding: 0px;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            width: 57%;
            padding: 0px 2px;
            vertical-align: top;
            font-size: 6.5pt;
        }

        /* Table */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2px 0;
            font-size: 6pt;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: left;
        }

        .detail-table th {
            background: #d0d0d0;
            font-weight: bold;
            text-align: center;
        }

        .detail-table td.right {
            text-align: right;
        }

        .detail-table td.center {
            text-align: center;
        }

        /* Total Box */
        .total-box {
            margin: 2px 0;
            padding: 3px;
            border: 2px solid #000;
            background: #f9f9f9;
        }

        .total-grid {
            display: table;
            width: 100%;
        }

        .total-item {
            display: table-row;
        }

        .total-label {
            display: table-cell;
            width: 60%;
            padding: 1px;
            font-weight: bold;
            font-size: 6.5pt;
            text-align: right;
        }

        .total-value {
            display: table-cell;
            width: 40%;
            padding: 1px 4px;
            font-size: 6.5pt;
            text-align: right;
        }

        .grand-total {
            background: #000;
            color: #fff;
            font-size: 8pt;
            font-weight: bold;
            padding: 2px 4px;
            margin-top: 2px;
            text-align: right;
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-weight: bold;
            font-size: 6pt;
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

        .badge-secondary {
            background: #6c757d;
            color: white;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 4px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 2px;
            font-size: 6pt;
        }

        .signature-box p {
            margin: 0;
        }

        .signature-line {
            margin-top: 40px;
            padding-top: 1px;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 6mm;
            left: 6mm;
            right: 6mm;
            text-align: center;
            font-size: 5.5pt;
            border-top: 1px solid #000;
            padding-top: 2px;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                margin: 0;
                padding: 6mm;
                width: 210mm;
                height: 148.5mm;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A5 landscape;
                margin: 0;
            }
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12pt;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            z-index: 1000;
        }

        .print-button:hover {
            background: #0056b3;
        }

        .highlight {
            background: #fff3cd;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Cetak</button>

    <div class="page">
        <!-- Header -->
        <div class="header">
            <h1>PERUSAHAAN DAERAH AIR MINUM (PDAM)</h1>
            <h2>{{ $rab->pendaftaran->cabang->nama_cabang ?? 'PDAM' }}</h2>
            <p>{{ $rab->pendaftaran->cabang->alamat_cabang ?? '' }}</p>
        </div>

        <!-- Document Title -->
        <div class="doc-title">
            <h3>RENCANA ANGGARAN BIAYA (RAB) SAMBUNGAN BARU</h3>
        </div>

        <!-- Info RAB & Pelanggan dalam 2 kolom -->
        <div class="two-column">
            <div class="column">
                <div class="info-section">
                    <div class="info-title">INFORMASI RAB</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">No. Registrasi</div>
                            <div class="info-separator">:</div>
                            <div class="info-value"><strong>{{ $rab->pendaftaran->nomor_registrasi }}</strong></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tanggal Input</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">{{ $rab->tanggal_input?->format('d/m/Y') ?? now()->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Jenis Biaya</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">{{ ucfirst(str_replace('_', ' ', $rab->jenis_biaya_sambungan ?? 'standar')) }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status RAB</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">
                                @if($rab->status_rab === 'approved')
                                    <span class="badge badge-success">APPROVED</span>
                                @elseif($rab->status_rab === 'draft')
                                    <span class="badge badge-secondary">DRAFT</span>
                                @elseif($rab->status_rab === 'review')
                                    <span class="badge badge-warning">REVIEW</span>
                                @elseif($rab->status_rab === 'rejected')
                                    <span class="badge badge-danger">REJECTED</span>
                                @elseif($rab->status_rab === 'final')
                                    <span class="badge badge-info">FINAL</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="info-section">
                    <div class="info-title">DATA PELANGGAN</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Nama</div>
                            <div class="info-separator">:</div>
                            <div class="info-value"><strong>{{ $rab->nama_pelanggan }}</strong></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Alamat</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">{{ $rab->alamat_pelanggan }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Telepon</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">{{ $rab->telepon_pelanggan ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Golongan Tarif</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">{{ $rab->golongan_tarif ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rincian Biaya -->
        <div class="info-section">
            <div class="info-title">RINCIAN BIAYA</div>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="55%">Uraian</th>
                        <th width="40%">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center">1</td>
                        <td><strong>UANG MUKA</strong></td>
                        <td class="right"></td>
                    </tr>
                    <tr>
                        <td class="center"></td>
                        <td style="padding-left: 15px;">Perencanaan dll</td>
                        <td class="right">{{ number_format($rab->perencanaan ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="center"></td>
                        <td style="padding-left: 10px;"><strong>Jumlah Uang Muka</strong></td>
                        <td class="right"><strong>{{ number_format($rab->jumlah_uang_muka ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                    
                    <tr>
                        <td class="center">2</td>
                        <td><strong>BIAYA INSTALASI</strong></td>
                        <td class="right"></td>
                    </tr>
                    <tr>
                        <td class="center"></td>
                        <td style="padding-left: 15px;">Pengerjaan Tanah</td>
                        <td class="right">{{ number_format($rab->pengerjaan_tanah ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="center"></td>
                        <td style="padding-left: 15px;">Tenaga Kerja</td>
                        <td class="right">{{ number_format($rab->tenaga_kerja ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="center"></td>
                        <td style="padding-left: 15px;">Pipa dan Accessories</td>
                        <td class="right">{{ number_format($rab->pipa_accessories ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="center"></td>
                        <td style="padding-left: 10px;"><strong>Jumlah Instalasi</strong></td>
                        <td class="right"><strong>{{ number_format($rab->jumlah_instalasi ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                    
                    <tr style="background: #f0f0f0;">
                        <td colspan="2" class="right" style="font-weight: bold;">TOTAL YANG HARUS DIBAYAR</td>
                        <td class="right" style="font-weight: bold;">{{ number_format((($rab->jumlah_uang_muka ?? 0) + ($rab->jumlah_instalasi ?? 0) + ($rab->pembulatan_piutang ?? 0)), 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Perhitungan Total -->
        <div class="two-column">
            <div class="column">
                <div class="info-section">
                    <div class="info-title">RINCIAN PEMBAYARAN</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Pembulatan</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">Rp {{ number_format($rab->pembulatan_piutang ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Uang Muka</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">Rp {{ number_format($rab->uang_muka ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Piutang NA</div>
                            <div class="info-separator">:</div>
                            <div class="info-value"><strong>Rp {{ number_format($rab->piutang_na ?? 0, 0, ',', '.') }}</strong></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Pajak</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">{{ $rab->pajak_piutang ?? 0 }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="info-section">
                    <div class="info-title">METODE PEMBAYARAN</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Tipe Pembayaran</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">
                                @if($rab->tipe_pembayaran === 'lunas')
                                    <span class="badge badge-success">LUNAS/CASH</span>
                                @else
                                    <span class="badge badge-warning">CICILAN</span>
                                @endif
                            </div>
                        </div>
                        @if($rab->tipe_pembayaran === 'cicilan')
                        <div class="info-row">
                            <div class="info-label">Mode Cicilan</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">
                                {{ $rab->mode_cicilan === 'auto' ? 'Auto (Nominal Sama)' : 'Custom (Nominal Berbeda)' }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Jumlah Cicilan</div>
                            <div class="info-separator">:</div>
                            <div class="info-value">{{ $rab->jumlah_cicilan ?? 0 }} bulan</div>
                        </div>
                        @if($rab->mode_cicilan === 'auto')
                        <div class="info-row">
                            <div class="info-label">Per Cicilan</div>
                            <div class="info-separator">:</div>
                            <div class="info-value"><strong>Rp {{ number_format($rab->nominal_per_cicilan ?? 0, 0, ',', '.') }}</strong></div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Biaya -->
        <div class="grand-total">
            TOTAL BIAYA SAMBUNGAN BARU: Rp {{ number_format($rab->total_biaya_sambungan_baru ?? 0, 0, ',', '.') }}
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p><strong>Kepala Cabang</strong></p>
                <div class="signature-line">
                    {{ $rab->pendaftaran->cabang->nama_cabang ?? '' }}
                </div>
            </div>
            <div class="signature-box">
                <p>Disetujui,</p>
                <p><strong>Bagian Keuangan</strong></p>
                <div class="signature-line">
                    (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                </div>
            </div>
            <div class="signature-box">
                <p>Pelanggan,</p>
                <p><strong>&nbsp;</strong></p>
                <div class="signature-line">
                    {{ $rab->nama_pelanggan }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | © {{ now()->year }} PDAM {{ $rab->pendaftaran->cabang->nama_cabang ?? '' }} - Faktur RAB</p>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
