<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Pembayaran - {{ $pendaftaran->nomor_registrasi }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 13px; /* Diperbesar untuk screen preview */
            line-height: 1.4;
            color: #000;
            background: #f5f5f5;
            margin: 10px;
        }

        .container {
            width: 100%;
            max-width: 600px; /* Diperbesar lagi untuk preview yang lebih baik */
            margin: 0 auto;
            padding: 15px; /* Diperbesar padding */
            border: 1px solid #ddd;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            min-height: 400px; /* Minimum height untuk preview */
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

        /* Print optimizations untuk 1/2 kertas A4 - Font Lebih Besar, Gap Dikurangi */
        @media print {
            body {
                font-size: 14px; /* Diperbesar dari 12px */
                line-height: 1.2; /* Dikurangi dari 1.3 untuk mengurangi gap */
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: none;
                width: 20.6cm; /* Lebar A4 minus margin */
                height: 14.5cm; /* Exact height setengah A4 */
                max-width: 20.6cm;
                max-height: 14.5cm;
                padding: 0.5cm; /* Dikurangi sedikit dari 0.6cm */
                margin: 0.2cm auto; /* Dikurangi dari 0.3cm */
                border: 1px solid #333;
                page-break-inside: avoid;
                box-sizing: border-box;
                overflow: hidden;
            }

            .header {
                margin-bottom: 0.3cm; /* Dikurangi dari 0.5cm */
            }

            .header h1 {
                font-size: 16px; /* Diperbesar dari 14px */
                margin-bottom: 0.2cm; /* Dikurangi dari 0.3cm */
                font-weight: bold;
                text-align: center;
            }

            .header .company {
                font-size: 13px; /* Diperbesar dari 11px */
                text-align: center;
                margin-bottom: 0.1cm; /* Dikurangi dari 0.2cm */
            }

            .separator {
                margin: 0.2cm 0; /* Dikurangi dari 0.4cm */
                text-align: center;
                font-size: 10px; /* Diperbesar dari 9px */
            }

            .info-container {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.3cm; /* Dikurangi dari 0.5cm */
                gap: 0.4cm; /* Dikurangi dari 0.5cm */
            }

            .info-left,
            .info-right {
                flex: 1;
                font-size: 13px; /* Diperbesar dari 11px */
            }

            .info-row {
                display: flex;
                margin-bottom: 0.15cm; /* Dikurangi dari 0.3cm */
                line-height: 1.2; /* Dikurangi dari 1.4 */
            }

            .info-label {
                width: 75px; /* Diperbesar dari 70px */
                font-size: 13px; /* Diperbesar dari 11px */
                display: inline-block;
            }

            .info-value {
                flex: 1;
                font-size: 13px; /* Diperbesar dari 11px */
                font-weight: bold;
            }

            .table-container {
                margin: 0.3cm 0; /* Dikurangi dari 0.5cm */
            }

            .table-title {
                text-align: center;
                font-size: 14px; /* Diperbesar dari 12px */
                margin-bottom: 0.2cm; /* Dikurangi dari 0.3cm */
                font-weight: bold;
            }

            .table-simple {
                width: 100%;
                font-size: 13px; /* Diperbesar dari 11px */
            }

            .table-row {
                display: flex;
                justify-content: space-between;
                padding: 0.1cm 0; /* Dikurangi dari 0.2cm */
                border-bottom: 1px solid #ddd;
                line-height: 1.2; /* Dikurangi dari 1.4 */
            }

            .table-row.total {
                margin-top: 0.2cm; /* Dikurangi dari 0.3cm */
                font-weight: bold;
                font-size: 14px; /* Diperbesar dari 12px */
                border-top: 2px solid #000;
                border-bottom: 2px solid #000;
            }

            .col-desc {
                flex: 1;
                font-size: 13px; /* Diperbesar dari 11px */
            }

            .col-amount {
                width: 100px; /* Diperbesar dari 90px */
                text-align: right;
                font-size: 13px; /* Diperbesar dari 11px */
            }

            .footer {
                display: flex;
                justify-content: space-between;
                margin-top: 0.4cm; /* Dikurangi dari 0.6cm */
                font-size: 12px; /* Diperbesar dari 10px */
            }

            .signature {
                width: 130px; /* Diperbesar dari 120px */
                text-align: center;
            }

            .signature-line {
                margin-top: 0.8cm; /* Dikurangi dari 1cm */
                padding-top: 0.2cm; /* Dikurangi dari 0.3cm */
                font-size: 11px; /* Diperbesar dari 9px */
                border-top: 1px solid #000;
            }

            .print-date {
                text-align: right;
                font-size: 9px; /* Diperbesar dari 8px */
                margin-top: 0.2cm; /* Dikurangi dari 0.3cm */
            }

            .no-print {
                display: none;
            }

            * {
                color: black !important;
                background: white !important;
            }

            /* Pengaturan untuk 1/2 A4 portrait */
            @page {
                size: A4 portrait;
                margin: 0.2cm; /* Dikurangi dari 0.3cm untuk lebih banyak ruang */
            }
        }

        .print-button {
            background: #000;
            color: white;
            border: 1px solid #000;
            padding: 8px 15px;
            font-size: 12px;
            cursor: pointer;
            margin-bottom: 15px;
            font-family: 'Courier New', monospace;
            border-radius: 3px;
        }

        .print-button:hover {
            background: #333;
        }

        /* Responsive preview untuk development */
        @media screen and (max-width: 480px) {
            .container {
                max-width: 100%;
                padding: 10px;
            }
            
            .info-container {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="no-print">
            <button class="print-button" onclick="window.print()">PRINT FAKTUR</button>
        </div>

        <div class="header">
            <div class="company">PERUSAHAAN UMUM DAERAH AIR MINUM TIRTA PERWIRA</div>
            <div class="company">Kabupaten Purbalingga</div>
            <p style="font-size:6px; margin-top: 2px;">
                Jl. Letjen S. Parman No.23 Purbalingga 53311 Telp. (0281) 891234 Fax. (0281) 892347
            </p>
            <div style="font-size: 9px; font-weight: bold; margin-top: 3px; text-decoration: underline;">
                FAKTUR PEMBAYARAN PENDAFTARAN
            </div>
        </div>

        <div class="separator">----------------------------------------------------</div>

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
                    {{-- DIHILANGKAN PEMOTONGAN --}}
                    <span class="info-value">: {{ strtoupper($pendaftaran->nama_pemohon) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No.HP</span>
                    <span class="info-value">: {{ $pendaftaran->no_hp_pemohon }}</span>
                </div>
            </div>

            <div class="info-right">
                <div class="info-row">
                    <span class="info-label">Alamat</span>
                    {{-- DIHILANGKAN PEMOTONGAN --}}
                    <span class="info-value">: {{ $pendaftaran->alamat_pemasangan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kelurahan</span>
                    {{-- DIHILANGKAN PEMOTONGAN --}}
                    <span class="info-value">:
                        {{ $pendaftaran->kelurahan->nama_kelurahan ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipe</span>
                    {{-- DIHILANGKAN PEMOTONGAN --}}
                    <span class="info-value">:
                        {{ $pendaftaran->tipeLayanan->nama_tipe_layanan ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Cabang</span>
                    {{-- DIHILANGKAN PEMOTONGAN --}}
                    <span class="info-value">: {{ $pendaftaran->cabang->nama_cabang ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="separator">----------------------------------------------------</div>

        <div class="table-container">
            <div class="table-title">RINCIAN BIAYA</div>

            <div class="table-simple">
                @if ($pendaftaran->biaya_tipe_layanan > 0)
                    <div class="table-row">
                        <div class="col-desc">
                            {{-- DIHILANGKAN PEMOTONGAN --}}
                            {{ $pendaftaran->tipeLayanan->nama_tipe_layanan ?? 'Tipe Layanan' }}</div>
                        <div class="col-amount">{{ number_format($pendaftaran->biaya_tipe_layanan, 0, ',', '.') }}
                        </div>
                    </div>
                @endif

                @if ($pendaftaran->biaya_jenis_daftar > 0)
                    <div class="table-row">
                        <div class="col-desc">
                            {{-- DIHILANGKAN PEMOTONGAN --}}
                            {{ $pendaftaran->jenisDaftar->nama_jenis_daftar ?? 'Jenis Daftar' }}</div>
                        <div class="col-amount">{{ number_format($pendaftaran->biaya_jenis_daftar, 0, ',', '.') }}
                        </div>
                    </div>
                @endif

                @if ($pendaftaran->biaya_tipe_pendaftaran > 0)
                    <div class="table-row">
                        <div class="col-desc">
                            {{-- DIHILANGKAN PEMOTONGAN --}}
                            {{ $pendaftaran->tipePendaftaran->nama_tipe_pendaftaran ?? 'Tipe Pendaftaran' }}
                        </div>
                        <div class="col-amount">{{ number_format($pendaftaran->biaya_tipe_pendaftaran, 0, ',', '.') }}
                        </div>
                    </div>
                @endif

                @if ($pendaftaran->biaya_tambahan > 0)
                    <div class="table-row">
                        <div class="col-desc">Biaya Tambahan</div>
                        <div class="col-amount">{{ number_format($pendaftaran->biaya_tambahan, 0, ',', '.') }}</div>
                    </div>
                @endif

                @if ($pendaftaran->nilai_pajak > 0)
                    <div class="table-row">
                        <div class="col-desc">{{ $pendaftaran->pajak->nama_pajak ?? 'Pajak' }}</div>
                        <div class="col-amount">{{ number_format($pendaftaran->nilai_pajak, 0, ',', '.') }}</div>
                    </div>
                @endif

                <div class="table-row total">
                    <div class="col-desc">TOTAL BAYAR</div>
                    <div class="col-amount">Rp {{ number_format($pendaftaran->total_biaya_pendaftaran, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>



        <div class="footer">
            <div class="signature">
                Pemohon<br><br><br>
                {{-- DIHILANGKAN PEMOTONGAN --}}
                <div class="signature-line">{{ strtoupper($pendaftaran->nama_pemohon) }}</div>
            </div>

            <div class="signature">
                Petugas<br><br><br>
                {{-- DIHILANGKAN PEMOTONGAN --}}
                <div class="signature-line">{{ strtoupper($pendaftaran->dibuat_oleh) }}</div>
            </div>
        </div>

        <div class="print-date">
            Print: {{ now()->format('d-m-Y H:i') }} WIB | Format: 1/2 A4 Portrait (Setengah Tinggi)
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            // Auto print saat halaman dimuat
            window.print();
        });
    </script>

    <script>
        // Pastikan skrip ini ada di file layout utama Filament Anda (misal: app.blade.php)
        window.addEventListener('print-multiple-faktur', event => {
            const urls = event.detail.urls;

            if (urls && urls.length > 0) {
                urls.forEach((url, index) => {
                    // Beri jeda 200ms untuk setiap tab untuk membantu browser memprosesnya.
                    // Ini mengurangi risiko pemblokiran dibandingkan membuka semua sekaligus.
                    setTimeout(() => {
                        window.open(url, '_blank');
                    }, index * 200);
                });
            }
        });
    </script>
</body>

</html>
