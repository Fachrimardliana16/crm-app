<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Pembayaran Multiple</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            line-height: 1.1;
            color: #000;
            background: white;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 8px;
        }

        .faktur-item {
            page-break-after: always;
            margin-bottom: 15px;
        }

        .faktur-item:last-child {
            page-break-after: auto;
        }

        /* Header KOP */
        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .header h1 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .header .company {
            font-size: 9px;
            margin-bottom: 1px;
        }

        .separator {
            text-align: center;
            margin: 5px 0;
            font-size: 8px;
        }

        /* Info Layout - 2 Kolom */
        .info-container {
            display: flex;
            margin-bottom: 8px;
            gap: 10px;
        }

        .info-left,
        .info-right {
            flex: 1;
            font-size: 8px;
        }

        .info-row {
            display: flex;
            margin-bottom: 1px;
        }

        .info-label {
            width: 60px;
            font-weight: normal;
        }

        .info-value {
            flex: 1;
            font-weight: bold;
        }

        /* Tabel Rincian Biaya */
        .table-container {
            margin: 8px 0;
        }

        .table-title {
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 3px;
            text-decoration: underline;
        }

        .table-simple {
            width: 100%;
            font-size: 8px;
        }

        .table-row {
            display: flex;
            border-bottom: 1px dotted #000;
            padding: 1px 0;
        }

        .table-row.total {
            border-bottom: 2px solid #000;
            font-weight: bold;
            margin-top: 2px;
        }

        .col-desc {
            flex: 1;
        }

        .col-amount {
            width: 80px;
            text-align: right;
        }

        /* Footer */
        .footer {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 8px;
        }

        .signature {
            text-align: center;
            width: 120px;
        }

        .signature-line {
            margin-top: 25px;
            border-top: 1px solid #000;
            padding-top: 2px;
        }

        .print-date {
            text-align: right;
            font-size: 7px;
            margin-top: 8px;
        }

        /* Print optimizations */
        @media print {
            body {
                font-size: 9px;
            }

            .container {
                padding: 5px;
                max-width: none;
                width: 100%;
            }

            .no-print {
                display: none;
            }

            * {
                color: black !important;
                background: white !important;
            }

            @page {
                size: A5;
                margin: 0.5cm;
            }
        }

        .print-button {
            background: #000;
            color: white;
            border: 1px solid #000;
            padding: 5px 10px;
            font-size: 10px;
            cursor: pointer;
            margin-bottom: 10px;
            font-family: 'Courier New', monospace;
        }

        .print-button:hover {
            background: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="no-print">
            <button class="print-button" onclick="window.print()">PRINT {{ count($pendaftarans) }} FAKTUR</button>
        </div>

        @foreach ($pendaftarans as $index => $pendaftaran)
            <div class="faktur-item">
                <!-- KOP -->
                <div class="header">
                    <div class="company">PERUSAHAAN DAERAH AIR MINUM (PDAM)</div>
                    <h1>{{ strtoupper($pendaftaran->cabang->nama_cabang ?? 'CABANG') }}</h1>
                    <div class="company">{{ strtoupper($pendaftaran->kelurahan->kecamatan->nama_kecamatan ?? '') }}
                    </div>
                    <div class="company">Telp: (0123) 456789</div>
                </div>

                <div class="separator">----------------------------------------------------</div>

                <!-- Info 2 Kolom -->
                <div class="info-container">
                    <div class="info-left">
                        <div class="info-row">
                            <span class="info-label">No.Reg</span>
                            <span class="info-value">: {{ $pendaftaran->nomor_registrasi }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tanggal</span>
                            <span class="info-value">:
                                {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d-m-Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nama</span>
                            <span class="info-value">:
                                {{ strtoupper(\Str::limit($pendaftaran->nama_pemohon, 15)) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">No.HP</span>
                            <span class="info-value">: {{ $pendaftaran->no_hp_pemohon }}</span>
                        </div>
                    </div>

                    <div class="info-right">
                        <div class="info-row">
                            <span class="info-label">Alamat</span>
                            <span class="info-value">: {{ \Str::limit($pendaftaran->alamat_pemasangan, 20) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kelurahan</span>
                            <span class="info-value">:
                                {{ \Str::limit($pendaftaran->kelurahan->nama_kelurahan ?? '-', 15) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tipe</span>
                            <span class="info-value">:
                                {{ \Str::limit($pendaftaran->tipeLayanan->nama_tipe_layanan ?? '-', 15) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Cabang</span>
                            <span class="info-value">:
                                {{ \Str::limit($pendaftaran->cabang->nama_cabang ?? '-', 15) }}</span>
                        </div>
                    </div>
                </div>

                <div class="separator">----------------------------------------------------</div>

                <!-- Rincian Biaya -->
                <div class="table-container">
                    <div class="table-title">RINCIAN BIAYA</div>

                    <div class="table-simple">
                        @if ($pendaftaran->biaya_tipe_layanan > 0)
                            <div class="table-row">
                                <div class="col-desc">
                                    {{ \Str::limit($pendaftaran->tipeLayanan->nama_tipe_layanan ?? 'Tipe Layanan', 25) }}
                                </div>
                                <div class="col-amount">
                                    {{ number_format($pendaftaran->biaya_tipe_layanan, 0, ',', '.') }}</div>
                            </div>
                        @endif

                        @if ($pendaftaran->biaya_jenis_daftar > 0)
                            <div class="table-row">
                                <div class="col-desc">
                                    {{ \Str::limit($pendaftaran->jenisDaftar->nama_jenis_daftar ?? 'Jenis Daftar', 25) }}
                                </div>
                                <div class="col-amount">
                                    {{ number_format($pendaftaran->biaya_jenis_daftar, 0, ',', '.') }}</div>
                            </div>
                        @endif

                        @if ($pendaftaran->biaya_tipe_pendaftaran > 0)
                            <div class="table-row">
                                <div class="col-desc">
                                    {{ \Str::limit($pendaftaran->tipePendaftaran->nama_tipe_pendaftaran ?? 'Tipe Pendaftaran', 25) }}
                                </div>
                                <div class="col-amount">
                                    {{ number_format($pendaftaran->biaya_tipe_pendaftaran, 0, ',', '.') }}</div>
                            </div>
                        @endif

                        @if ($pendaftaran->biaya_tambahan > 0)
                            <div class="table-row">
                                <div class="col-desc">Biaya Tambahan</div>
                                <div class="col-amount">{{ number_format($pendaftaran->biaya_tambahan, 0, ',', '.') }}
                                </div>
                            </div>
                        @endif

                        @if ($pendaftaran->nilai_pajak > 0)
                            <div class="table-row">
                                <div class="col-desc">{{ $pendaftaran->pajak->nama_pajak ?? 'Pajak' }}</div>
                                <div class="col-amount">{{ number_format($pendaftaran->nilai_pajak, 0, ',', '.') }}
                                </div>
                            </div>
                        @endif

                        <div class="table-row total">
                            <div class="col-desc">TOTAL BAYAR</div>
                            <div class="col-amount">Rp
                                {{ number_format($pendaftaran->total_biaya_pendaftaran, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Tanda Tangan -->
                <div class="footer">
                    <div class="signature">
                        Pemohon<br><br><br>
                        <div class="signature-line">{{ strtoupper(\Str::limit($pendaftaran->nama_pemohon, 15)) }}</div>
                    </div>

                    <div class="signature">
                        Petugas<br><br><br>
                        <div class="signature-line">{{ strtoupper(\Str::limit($pendaftaran->dibuat_oleh, 15)) }}</div>
                    </div>
                </div>

                <div class="print-date">
                    Print: {{ now()->format('d-m-Y H:i') }}
                </div>
            </div>
        @endforeach
    </div>

    <script src="/print-multiple-faktur.js"></script>
    <script>
        window.addEventListener('load', function() {
            // Optional: Auto print when page loads
            // window.print();
        });
    </script>
</body>

</html>
