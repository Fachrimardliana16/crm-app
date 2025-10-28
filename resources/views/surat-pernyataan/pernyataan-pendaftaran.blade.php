<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan</title>
    <style>
        /* [Kode CSS yang kamu berikan tetap sama] */
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            color: #000;
            line-height: 1.5;
            font-size: 12pt;
        }

        .container {
            width: 8.5in;
            margin: 0 auto;
            padding: 1in;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
            padding-right: 10px;
        }

        .kop {
            text-align: center;
            flex-grow: 1;
            max-width: 85%;
        }

        .kop p {
            margin: 0;
            line-height: 1.2;
        }

        .kop .nama-instansi-utama {
            font-size: 14pt;
            font-weight: bold;
        }

        .kop .nama-instansi-kedua {
            font-size: 12pt;
            font-weight: bold;
        }

        .kop .alamat {
            font-size: 10pt;
        }

        .kop-line {
            border-bottom: 1px solid #000;
            margin: 10px 0 30px 0;
        }

        .title-container {
            text-align: center;
            margin: 0 0 30px 0;
        }

        .title {
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
        }

        .section {
            margin-bottom: 20px;
        }

        .content p {
            margin: 5px 0;
        }

        .data-input {
            margin-bottom: 20px;
        }

        .data-item {
            display: flex;
            margin: 3px 0;
        }

        .data-item .label {
            width: 150px;
            text-align: left;
        }

        .menyatakan-label {
            text-align: center;
            font-weight: bold;
            margin: 30px 0 10px 0;
        }

        .list {
            margin-left: 20px;
            padding-left: 0;
        }

        .list li {
            margin-bottom: 8px;
            text-align: justify;
        }

        .signature-section {
            margin-top: 50px;
            text-align: right;
        }

        .signature-block {
            display: inline-block;
            width: 50%;
            text-align: center;
            margin-top: 50px;
        }

        .signature-block p {
            margin: 0;
            line-height: 1.2;
        }

        .signature-date {
            text-align: right;
            margin-right: 15px;
        }

        .signature-line {
            margin-top: 80px;
            font-weight: bold;
        }

        .signature-line span {
            border-bottom: 1px solid #000;
            padding: 0 60px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="logo">
            <div class="kop">
                <p class="nama-instansi-utama">Pemerintah Kabupaten Purbalingga</p>
                <p class="nama-instansi-kedua">Perusahaan Umum Daerah Air Minum Tirta Perwira</p>
                <p>Kabupaten Purbalingga</p>
                <p class="alamat">Jl. Letjend S. Parman. No. 62. Purbalingga. Telp (0281) 891706 Fax (0281) 895534</p>
            </div>
        </div>
        <div class="kop-line"></div>

        <div class="title-container">
            <div class="title">SURAT PERNYATAAN</div>
        </div>

        <div class="section">
            <p>Yang bertanda tangan di bawah ini :</p>
            <div class="data-input">
                <div class="data-item">
                    <span class="label">Nama Lengkap</span>
                    <span>:
                        ....................................................................................................................</span>
                </div>
                <div class="data-item">
                    <span class="label">Alamat Lengkap</span>
                    <span>:
                        ....................................................................................................................</span>
                </div>
                <div class="data-item">
                    <span class="label">Nomor Telepon</span>
                    <span>:
                        ....................................................................................................................</span>
                </div>
                <div class="data-item">
                    <span class="label">Pekerjaan</span>
                    <span>: PNS / Wiraswasta / Swasta / TNI / POLRI / BUMD / BUMN /</span>
                </div>
                <div class="data-item">
                    <span class="label"></span>
                    <span style="margin-left: -50px;">Tani / Pensiunan /
                        ................................................................................................</span>
                </div>
            </div>
        </div>

        <div class="menyatakan-label">
            MENYATAKAN
        </div>

        <div class="section">
            <ol class="list">
                <li>Bersedia membayar rekening air dan non air tepat waktu, mulai tanggal 1 s/d 20 di loket PDAM Pusat
                    dan tanggal 5 s/d 20 di loket-loket PDAM yang ditunjuk.</li>
                <li>Tidak keberatan apabila menunggak 3 bulan, jaringan PDAM di tempat kami ditutup tanpa pemberitahuan
                    oleh petugas PDAM.</li>
                <li>Bertanggungjawab, apabila kelak di kemudian hari timbul sengketa hak pemilikan tanah dan bangunan
                    sehingga jaringan PDAM harus dibongkar.</li>
                <li>Bersedia saluran pipa dinas setelah dipasang menjadi aset PDAM, selanjutnya PDAM berhak untuk
                    memperluas maupun menyambung pemasangan baru.</li>
                <li>Bersedia memenuhi segala aturan dan ketentuan yang berlaku di PDAM Purbalingga baik yang tertulis
                    maupun yang sifatnya kebijakan.</li>
                <li>Bersedia untuk tidak menuntut pelayanan aliran air mengalir 24 jam, terkait dengan kondisi aliran di
                    wilayah tempat tinggal pelanggan. (SK.DIREKSI NO. 690/13/137.A/IV/2025) PASAL : 4</li>
            </ol>
        </div>

        <div class="signature-section">
            <div class="signature-date">
                <p>Purbalingga, ...........................................</p>
            </div>
            <div class="signature-block">
                <p class="signature-line">(<span class="underline">..........................................</span>)
                </p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
