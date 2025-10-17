---
config:
    layout: elk
---

flowchart TD
subgraph subGraph0["Pendaftaran dan Pemasangan"]
A0(["Start: Pelanggan Mendaftar"])
A1_L{"Saluran Pendaftaran?"}
B1["Loket Pusat (KASUBAG Pelayanan Langganan) Menerima & Seleksi"]
B1_C["Loket Cabang/Unit (KASI Umum) Menerima & Seleksi"]
C1{"Loket Cek Kelengkapan Dokumen & Pilih Golongan [HUKUM: Perda Tarif Air]"}
AUDIT_C1["Log Audit: Validasi Dokumen & Compliance"]
SLA_C1["Monitor SLA: Validasi Dokumen (Target: 1 Jam)"]
D1("Transisi ke Seksi Teknik")
E1_1["Notifikasi Melengkapi (SLA: 2 Jam)"]
E1_2["Proses Pengembalian Dana (SLA: 3 Hari Kerja)"]
F1{"Kepala Seksi Teknik Validasi Dokumen (SLA: 1 Hari) [KPI: Waktu Proses Validasi Dokumen]"}
SLA_F1["Monitor SLA: Validasi Teknis & Update Status"]
AUDIT_F1["Log Audit: Keputusan Validasi Teknis"]
G1["Cek Kapasitas Jaringan [OUTPUT: Laporan Cek Kapasitas]"]
H1["Keluarkan SPK Survey & Input Hasil Survey (SLA: 3 Hari) [OUTPUT: SPK Survey, KPI: Akurasi Survey >95%]"]
I1["Staff Survey Input Detail Teknis (Titik GPS, Jarak, Jenis Meter, SPAM, Elevasi)"]
GIS_I1["Update Data GIS & Validasi Koordinat"]
J1["Staff Survey Buat RAB [OUTPUT: Draft RAB]"]
K1{"Kepala Cabang Memeriksa Survey & RAB [BUKTI: Persetujuan Elektronik/Tanda Tangan]"}
AUDIT_K1["Log Audit: Persetujuan RAB & Digital Signature"]
SLA_K1["Monitor SLA: Persetujuan RAB (Target: 2 Hari)"]
L1["Kepala Cabang Notifikasi Pelanggan & Minta Pembayaran RAB (SLA: 7 Hari) [OUTPUT: Surat Tagihan RAB]"]
NOTIF_L1["Template Notifikasi: Tagihan RAB via SMS/Email/WA"]
M0{"Pilihan Pembayaran RAB?"}
M1["Kepala Seksi Teknik Tugas Staff Teknis + SPK Pemasangan (SLA: 7 Hari)"]
M1_C["Kepala Cabang/Keuangan Approve Cicilan, Buat Jadwal Cicilan [OUTPUT: PKS Cicilan]"]
ANGSURAN_M1C["Setup Rincian Angsuran & Jadwal Auto-Tagihan"]
O1["Staff Teknis Ajukan Permintaan Barang ke Gudang"]
N1["Notifikasi Penolakan ke Pelanggan [OUTPUT: Surat Penolakan Resmi]"]
ARCHIVE_N1["Arsip Data Penolakan untuk Historis"]
P1("Transisi ke Alur Gudang")
end
subgraph Gudang["Gudang"]
O2["Staff Teknis Input SPK ke Sistem Gudang (SLA: 1 Jam)"]
SLA_O2["Monitor SLA: Input SPK Gudang"]
P2{"Kepala Sub Bagian Gudang Validasi SPK"}
AUDIT_P2["Log Audit: Validasi SPK Gudang"]
Q2["Kepala Sub Bagian Gudang Approve (SLA: 4 Jam) [KPI: Lead Time Gudang]"]
R2["Notifikasi Revisi ke Staff Teknis"]
S2["Tugaskan Staff Gudang + SPK Pengambilan [OUTPUT: SPK Pengambilan Barang]"]
T2["Eskalasi Jika >2 Iterasi"]
ESCALATION_T2["Log Eskalasi & Alert Kepala Bagian"]
U2{"Staff Gudang Cek Stok & Kualitas Barang (SLA: 1 Hari) [KPI: Zero Defect Material]"}
V2["Notifikasi Pengadaan Darurat (SLA: 2 Hari)"]
W2["Notifikasi Penggantian"]
X2["Staff Gudang Input Data Pengeluaran"]
JURNAL_X2["Buat Jurnal Pengeluaran Gudang (SAKEP 2025)"]
Y2["Buat Berita Acara Pengeluaran [BUKTI: BA Pengeluaran/BAPB]"]
Z2{"Kepala Sub Bagian Gudang Pengecekan Akhir"}
AUDIT_Z2["Log Audit: Pengecekan Akhir Gudang"]
AA2["Notifikasi ke Staff Teknis: Barang Siap (SLA: 2 Jam)"]
BB2{"Staff Teknis Checklist Pengambilan [BUKTI: Checklist Penerimaan]"}
CC2["Permintaan Closed, Stok Terpotong [OUTPUT: Laporan Bulanan Gudang]"]
DD2["Eskalasi ke Kepala Sub Bagian Gudang"]
EE2("Transisi ke Pemasangan Lanjutan")
end
subgraph subGraph2["Pemasangan Lanjutan"]
FF2["Staff Teknis Pemasangan & Uji Kualitas [KPI: Waktu Pemasangan Sesuai SLA 7 Hari]"]
SLA_FF2["Monitor SLA: Waktu Pemasangan & Update Status"]
GG2["Staff Teknis Input Data & GPS (Berita Acara) [BUKTI: BA Pemasangan, Foto Pemasangan Ber-GPS]"]
GIS_GG2["Update Koordinat GIS Final & Validasi Lokasi"]
HH2["Kepala Seksi Teknik Validasi Input di GIS"]
AUDIT_HH2["Log Audit: Validasi Data GIS & Teknis"]
II2{"Kepala Seksi Teknik Pengecekan Akhir [OUTPUT: Laporan Final Pemasangan]"}
JJ2{"Kepala Sub Bagian Pemasaran Cek Data (Inkl. GIS) [KPI: Akurasi Data GIS 100%]"}
GIS_JJ2["Validasi Final Akurasi GIS & Status"]
KK2["Keluarkan Laporan Pemasangan"]
LL2["Revisi ke Staff Teknis (Lengkapi GIS)"]
MM2["Pelanggan Aktif & Surat Pemberitahuan [OUTPUT: Surat Perjanjian Langganan]"]
NOTIF_MM2["Template Notifikasi: Aktivasi Layanan via Multi-Channel"]
ACTIVE_MM2["Update Status Pelanggan: Aktif & Setup Meter"]
end
subgraph subGraph3["Pembacaan Rekening dan Pembayaran Alur Utama"]
NN2["Cek Rutin Kualitas, Kuantitas, Kontinuitas Air [OUTPUT: Laporan Kualitas Air Bulanan]"]
QUALITY_NN2["Log Laporan Kualitas Air & Compliance"]
OO2["Periode Pembacaan Dimulai"]
SLA_OO2["Setup SLA Pembacaan Periode & Monitoring"]
PP2["Opsi Pembacaan Mandiri (Foto Meter)"]
QQ2["Staff Pembaca Meter Pembacaan & Validasi [KPI: Akurasi Baca Meter >99%]"]
RR2{"Validasi Anomali (Cek Historis & Fisik)"}
ANOMALI_RR2["Log Anomali Pembacaan & Tindakan"]
SS2["Buat SPK Pemeriksaan Lapangan Ulang (SLA: 24 Jam)"]
TT2["Berita Acara Pembacaan + Laporan Kondisi Meteran [BUKTI: BA Pembacaan]"]
UU2{"Kepala Bagian Hubungan Langganan Pengecekan"}
AUDIT_UU2["Log Audit: Pengecekan Pembacaan"]
VV2["SPK Penghitungan Rekening"]
WW2["Tutup Buku"]
CLOSING_WW2["Closing Periode & Backup Data Historis"]
XX2["Kepala Sub Bagian Pelayanan Langganan Penghitungan"]
YY2["Berita Acara + Laporan Ikhtisar [OUTPUT: Laporan Ikhtisar Tagihan]"]
ZZ2{"Kepala Bagian Hubungan Langganan Pengecekan & Persetujuan"}
AUDIT_ZZ2["Log Audit: Persetujuan Tagihan & Digital Sign"]
AAA2["Aktifkan API Pembayaran"]
API_SETUP["Setup API Gateway & Log Monitoring"]
API_LOG["Log API Request/Response & Status Real-time"]
BBB2["Pelanggan Terima Rekening & Bayar [OUTPUT: Rekening Tagihan]"]
NOTIF_BBB2["Multi-Channel Notification: Tagihan Ready"]
CCC2{"Validasi Pembayaran (SLA: 24 Jam) [KPI: Zero Error Validasi]"}
API_VALIDATE["API Validation & Security Check"]
JURNAL_CCC2["Buat Jurnal Pembayaran (SAKEP 2025)"]
AUDIT_PAY["Log Audit: Transaksi Pembayaran & Compliance"]
SLA_CCC2["Monitor SLA: Validasi Pembayaran"]
DDD2(["Selesai: Pelanggan Aktif - Layanan Normal [CSI Pembayaran Cepat]"])
EEE2["Peringatan Tunggakan Otomatis (7 Hari Grace)"]
NOTIF_EEE2["Auto-Notification: Peringatan Tunggakan"]
FFF2_1["Keluarkan SP Penutupan Sementara"]
FFF2_2["Staff Teknis Melaksanakan Pemutusan [BUKTI: Foto Meter & Segel Pemutusan]"]
GIS_FFF2["Update Status GIS: Pemutusan & Lokasi"]
GGG2["Pelanggan Minta Sambung Kembali"]
HHH2_1["Cek Pembayaran Tunggakan + Denda/Biaya Sambung Ulang"]
CALC_HHH2["Kalkulasi Denda & Biaya Sambung"]
HHH2_2["Layanan Diaktifkan (SLA: 24 Jam)"]
GIS_HHH2["Update Status GIS: Aktif Kembali"]
III2["Pemutusan Tetap (Setelah Masa Tenggang) [HUKUM: BA Pembongkaran Aset]"]
ARCHIVE_III2["Arsip Data Historis Pelanggan & Asset"]
end
subgraph subGraph4["Penagihan dan Loket Alur Paralel"]
JJJ2["Pelanggan Bayar (Loket Cabang/Mitra)"]
KKK2{"Validasi Pembayaran (Sinkronisasi Sistem Tunggal)"}
API_KKK2["API Sync & Real-time Validation"]
LLL2["Peringatan Tunggakan Otomatis"]
NOTIF_LLL2["Auto-Alert Tunggakan Multi-Channel"]
MMM2["Staff Penagihan Input Laporan & Setor Uang [OUTPUT: Laporan Tagihan Tunggakan]"]
JURNAL_MMM2["Jurnal Setoran & Rekonsiliasi (SAKEP 2025)"]
NNN2["Laporan Harian Penagihan"]
LPP_NNN2["Generate LPP Harian Otomatis"]
OOO2{"Kepala Seksi Umum/Kepala Unit Verifikasi"}
AUDIT_OOO2["Log Audit: Verifikasi Penagihan"]
PPP2["SPK Penutupan"]
QQQ2["Staff Teknis Verifikasi"]
RRR2["Penutupan Sambungan"]
GIS_RRR2["Update GIS: Status Penutupan"]
SSS2["Input Data Penutupan"]
TTT2["Permintaan Sambung Kembali"]
UUU2{"Loket Cabang/Mitra Validasi"}
API_UUU2["API Check Status & Validation"]
VVV2["Staff Teknis Terima SPK"]
WWW2["Penyambungan Kembali"]
GIS_WWW2["Update GIS: Status Aktif"]
XXX2["Input Data & Aktifkan"]
YYY2["Staff Loket Terima Pembayaran [BUKTI: Struk/Kwitansi Pembayaran]"]
ZZZ2{"Validasi Data (Sinkronisasi)"}
API_ZZZ2["API Real-time Sync & Validation"]
AAAA2["Pencatatan & Sinkronisasi Sistem"]
JURNAL_AAAA2["Jurnal Kas Loket (SAKEP 2025)"]
BBBB2["Notifikasi Koreksi"]
CCCC2["Rekap Harian Loket"]
LPP_CCCC2["Generate LPP Loket Otomatis"]
DDDD1{"Selisih?"}
AUDIT_DDDD1["Log Audit: Deteksi Selisih Kas"]
EEEE2["Kepala Seksi Umum Verifikasi"]
FFFF1["Staff Loket/Keuangan Lakukan Investigasi (SLA: 1 Hari)"]
SLA_FFFF1["Monitor SLA: Investigasi Selisih"]
GGGG1{"Penyebab Selisih Teridentifikasi?"}
AUDIT_GGGG1["Log Audit: Hasil Investigasi"]
HHHH1["Buat Berita Acara Koreksi & Setoran Susulan/Tarik Dana"]
JURNAL_HHHH1["Jurnal Koreksi (SAKEP 2025)"]
IIII1["Eskalasi ke Tim IT & Akuntansi Pusat"]
ESCALATION_IIII1["Log Eskalasi Sistem & Alert"]
JGGG2["Laporan ke Pusat"]
HHHH2{"Kepala Seksi Umum & Kepala Cabang Validasi [KPI: Akurasi Laporan Keuangan]"}
AUDIT_HHHH2["Log Audit: Validasi Kepala Cabang"]
IIII2["Kepala Sub Bagian Verifikasi Pembukuan Cek & Setuju"]
JJJJ2["Kepala Bagian Keuangan Buat Jurnal [BUKTI: Jurnal Keuangan Harian]"]
JURNAL_JJJJ2["Generate Jurnal Harian (SAKEP 2025)"]
KKKK2["Kepala Bagian Hubungan Langganan Integrasi"]
end
subgraph LayananKhusus["Layanan Khusus"]
SK_A(["Start: Pelanggan Ajukan Permintaan Layanan Khusus (Loket/CS)"])
SK_B{"Jenis Layanan?"}
SLA_SKB["Monitor SLA: Jenis Layanan & Setup Timer"]
SK_C["Loket Verifikasi Dokumen Kepemilikan (HUKUM: Sertifikat/AJB)"]
AUDIT_SKC["Log Audit: Verifikasi Dokumen Legal"]
SK_D["Loket Cek Tagihan & SPK Survey Lokasi Baru"]
SK_E["CS/Loket Verifikasi Lokasi & Cek Stok Unit Tangki"]
SK_F["Loket Hitung Biaya Administrasi Balik Nama"]
CALC_SKF["Kalkulasi Biaya & Generate Tagihan"]
SK_G["Staff Survey Cek Jaringan Lokasi Baru & RAB Pindah"]
GIS_SKG["Survey GIS Lokasi Baru & Validasi"]
SK_H["Kepala Seksi Teknik Approve RAB Pindah"]
AUDIT_SKH["Log Audit: Approve RAB Pindah"]
SK_I["Pelanggan Bayar Administrasi"]
API_SKI["API Payment Gateway & Log Transaction"]
JURNAL_SKI["Jurnal Pembayaran Layanan (SAKEP 2025)"]
SK_J["Loket Buat SPK Pembaruan Data/Pelaksanaan Teknis"]
SK_K["CS Hitung Biaya & Notifikasi Pelanggan"]
NOTIF_SKK["Template Notification: Biaya Air Tangki"]
SK_L["Pelanggan Bayar Biaya Air Tangki"]
API_SKL["API Payment & Validation"]
SK_M["SPK Pengiriman Tangki"]
TRACKING_SKM["Tracking Pengiriman & Update Status"]
SK_N(["Selesai: Layanan Tangki Diberikan"])
FEEDBACK_SKN["Collect Customer Satisfaction"]
end
subgraph PengaduanTerpusat["Pengaduan Terpusat"]
AD_A(["Start: Pelanggan Ajukan Pengaduan (Loket, Web, Telepon CS)"])
AD_A1{"Saluran Pengaduan?"}
TICKET_ADA1["Generate Ticket Number & SLA Timer"]
AD_B["Loket Pengaduan Pusat (KASUBAG Pelayanan Langganan) Menerima & Input"]
AD_B_C["Loket Pengaduan Cabang/Unit (KASI Umum) Menerima & Input"]
AD_C{"CS/Loket Sorting dan Memilah Aduan"}
AI_ADC["AI Classification & Priority Setting"]
SLA_ADC["Setup SLA Berdasarkan Jenis Pengaduan"]
AD_D["Kepala Sub Bagian Pelayanan Langganan Cek Histori Tagihan (SLA: 1 Hari)"]
AUDIT_ADD["Log Audit: Cek Histori & Analisis"]
AD_E["Kepala Seksi Teknik/Unit Lapangan Buat SPK Cek Lokasi (SLA: 1 Jam)"]
GIS_ADE["Check GIS Location & Route Optimization"]
AD_F["Eskalasi ke Kepala Cabang/Unit Terkait"]
ESCALATION_ADF["Log Eskalasi & Alert Manajemen"]
AD_G{"Perlu Koreksi Rekening?"}
AD_H["Buat Berita Acara Koreksi Tagihan [BUKTI: BA Koreksi]"]
JURNAL_ADH["Jurnal Koreksi Tagihan (SAKEP 2025)"]
AUDIT_ADH["Log Audit: Koreksi Tagihan"]
AD_I["Berikan Penjelasan ke Pelanggan"]
NOTIF_ADI["Multi-Channel Response ke Pelanggan"]
AD_J["Staff Teknis Lapangan Investigasi & Tindakan Korektif [BUKTI: Foto Lokasi & BA Perbaikan]"]
GIS_ADJ["Update GIS: Lokasi Perbaikan & Status"]
PHOTO_ADJ["Photo Documentation & GPS Tagging"]
AD_K["Unit Terkait Menangani & Berikan Solusi (SLA Fleksibel)"]
AD_L["Verifikasi Solusi (Internal)"]
AUDIT_ADL["Log Audit: Verifikasi Solusi Internal"]
AD_M(["Selesai: Notifikasi Pelanggan & Aduan Ditutup [KPI: Aduan Tertutup dalam SLA]"])
SURVEY_ADM["Customer Satisfaction Survey"]
REPORT_ADM["Generate Report & Analytics"]
end
A0 --> A1_L
A1_L -- Loket Pusat --> B1
A1_L -- Loket Cabang/Unit --> B1_C
B1 --> C1
B1_C --> C1
C1 --> AUDIT_C1
AUDIT_C1 --> SLA_C1
SLA_C1 -- Dokumen Lengkap & Golongan Sesuai --> D1
C1 -- Dokumen Tidak Lengkap --> E1_1
E1_1 --> E1_2
E1_2 --> ARCHIVE_N1
ARCHIVE_N1 --> A0
D1 --> F1
F1 --> SLA_F1
SLA_F1 --> AUDIT_F1
AUDIT_F1 -- Valid --> G1
F1 -- Tidak Valid --> E1_1
G1 --> H1
H1 --> I1
I1 --> GIS_I1
GIS_I1 --> J1
J1 --> K1
K1 --> AUDIT_K1
AUDIT_K1 --> SLA_K1
SLA_K1 -- Approve --> L1
K1 -- Tidak Approve --> H1
L1 --> NOTIF_L1
NOTIF_L1 --> M0
M0 -- Cash Lunas --> M1
M0 -- Cicilan (Max 10x) --> M1_C
M1_C --> ANGSURAN_M1C
ANGSURAN_M1C --> M1
M1 --> O1
L1 -- Batal/Tidak Bayar --> N1
N1 --> ARCHIVE_N1
O1 --> P1
P1 --> O2
O2 --> SLA_O2
SLA_O2 --> P2
P2 --> AUDIT_P2
AUDIT_P2 -- Valid --> Q2
P2 -- Tidak Valid --> R2
R2 --> O2
Q2 -- Approve --> S2
Q2 -- Tolak/Revisi --> T2
T2 --> ESCALATION_T2
ESCALATION_T2 --> O2
S2 --> U2
U2 -- Stok Kosong/Kurang --> V2
U2 -- Barang Rusak --> W2
W2 --> U2
U2 -- Stok Tersedia & Sesuai --> X2
X2 --> JURNAL_X2
JURNAL_X2 --> Y2
Y2 --> Z2
Z2 --> AUDIT_Z2
AUDIT_Z2 -- Setuju --> AA2
Z2 -- Tidak Setuju --> X2
AA2 --> BB2
BB2 -- Sesuai --> CC2
BB2 -- Tidak Sesuai --> DD2
DD2 --> Z2
CC2 --> EE2
EE2 --> FF2
FF2 --> SLA_FF2
SLA_FF2 --> GG2
GG2 --> GIS_GG2
GIS_GG2 --> HH2
HH2 --> AUDIT_HH2
AUDIT_HH2 --> II2
II2 -- Tidak Approve --> FF2
II2 -- Approve --> JJ2
JJ2 --> GIS_JJ2
GIS_JJ2 -- Setuju --> KK2
JJ2 -- Tidak Setuju --> LL2
LL2 --> GG2
KK2 --> MM2
MM2 --> NOTIF_MM2
NOTIF_MM2 --> ACTIVE_MM2
ACTIVE_MM2 --> NN2
NN2 --> QUALITY_NN2
QUALITY_NN2 --> OO2
OO2 --> SLA_OO2
SLA_OO2 --> PP2
PP2 --> QQ2
QQ2 --> RR2
RR2 --> ANOMALI_RR2
ANOMALI_RR2 -- Anomali Terkonfirmasi --> SS2
SS2 --> RR2
RR2 -- Valid --> TT2
TT2 --> UU2
UU2 --> AUDIT_UU2
AUDIT_UU2 -- Belum Setuju --> QQ2
UU2 -- Setuju --> VV2
VV2 --> WW2
WW2 --> CLOSING_WW2
CLOSING_WW2 --> XX2
XX2 --> YY2
YY2 --> ZZ2
ZZ2 --> AUDIT_ZZ2
AUDIT_ZZ2 -- Belum Setuju --> XX2
ZZ2 -- Setuju --> AAA2
AAA2 --> API_SETUP
API_SETUP --> API_LOG
API_LOG --> BBB2 & JJJ2
BBB2 --> NOTIF_BBB2
NOTIF_BBB2 --> CCC2
CCC2 --> API_VALIDATE
API_VALIDATE --> JURNAL_CCC2
JURNAL_CCC2 --> AUDIT_PAY
AUDIT_PAY --> SLA_CCC2
SLA_CCC2 -- Sukses --> DDD2
CCC2 -- Gagal --> EEE2
EEE2 --> NOTIF_EEE2
NOTIF_EEE2 --> FFF2_1
FFF2_1 --> FFF2_2
FFF2_2 --> GIS_FFF2
GIS_FFF2 --> GGG2 & III2
GGG2 --> HHH2_1
HHH2_1 --> CALC_HHH2
CALC_HHH2 --> HHH2_2
HHH2_2 --> GIS_HHH2
GIS_HHH2 --> DDD2
III2 --> ARCHIVE_III2
JJJ2 --> KKK2 & YYY2
KKK2 --> API_KKK2
API_KKK2 -- Sukses --> DDD2
KKK2 -- Gagal --> LLL2
LLL2 --> NOTIF_LLL2
NOTIF_LLL2 --> MMM2
MMM2 --> JURNAL_MMM2
JURNAL_MMM2 --> NNN2
NNN2 --> LPP_NNN2
LPP_NNN2 --> OOO2
OOO2 --> AUDIT_OOO2
AUDIT_OOO2 --> PPP2
PPP2 --> QQQ2
QQQ2 --> RRR2
RRR2 --> GIS_RRR2
GIS_RRR2 --> SSS2
SSS2 --> TTT2
TTT2 --> UUU2
UUU2 --> API_UUU2
API_UUU2 --> VVV2
VVV2 --> WWW2
WWW2 --> GIS_WWW2
GIS_WWW2 --> XXX2
XXX2 --> DDD2
YYY2 --> ZZZ2
ZZZ2 --> API_ZZZ2
API_ZZZ2 -- Valid --> AAAA2
ZZZ2 -- Tidak Valid --> BBBB2
BBBB2 --> YYY2
AAAA2 --> JURNAL_AAAA2
JURNAL_AAAA2 --> CCCC2
CCCC2 --> LPP_CCCC2
LPP_CCCC2 --> DDDD1
DDDD1 --> AUDIT_DDDD1
AUDIT_DDDD1 -- Tidak Ada --> EEEE2
DDDD1 -- Ada --> FFFF1
FFFF1 --> SLA_FFFF1
SLA_FFFF1 --> GGGG1
GGGG1 --> AUDIT_GGGG1
AUDIT_GGGG1 -- Ya (Kesalahan Loket/Bank) --> HHHH1
GGGG1 -- Tidak (Masalah Sistem) --> IIII1
HHHH1 --> JURNAL_HHHH1
JURNAL_HHHH1 --> EEEE2
IIII1 --> ESCALATION_IIII1
ESCALATION_IIII1 --> EEEE2
EEEE2 --> JGGG2
JGGG2 --> HHHH2
HHHH2 --> AUDIT_HHHH2
AUDIT_HHHH2 --> IIII2
IIII2 --> JJJJ2
JJJJ2 --> JURNAL_JJJJ2
JURNAL_JJJJ2 --> KKKK2
SK_A --> SK_B
SK_B --> SLA_SKB
SLA_SKB -- Balik Nama --> SK_C
SK_B -- Pindah Meter --> SK_D
SK_B -- Penyambungan Kembali --> GGG2
SK_B -- Permintaan Air Tangki --> SK_E
SK_C --> AUDIT_SKC
AUDIT_SKC --> SK_F
SK_D --> SK_G
SK_G --> GIS_SKG
GIS_SKG --> SK_H
SK_F --> CALC_SKF
CALC_SKF --> SK_I
SK_H --> AUDIT_SKH
AUDIT_SKH --> SK_I
SK_I --> API_SKI
API_SKI --> JURNAL_SKI
JURNAL_SKI --> SK_J
SK_J --> O1
SK_E --> SK_K
SK_K --> NOTIF_SKK
NOTIF_SKK --> SK_L
SK_L --> API_SKL
API_SKL --> SK_M
SK_M --> TRACKING_SKM
TRACKING_SKM --> SK_N
SK_N --> FEEDBACK_SKN
AD_A --> AD_A1
AD_A1 --> TICKET_ADA1
TICKET_ADA1 -- Loket Pusat/Web/Telp --> AD_B
AD_A1 -- Loket Cabang/Unit --> AD_B_C
AD_B --> AD_C
AD_B_C --> AD_C
AD_C --> AI_ADC
AI_ADC --> SLA_ADC
SLA_ADC -- Rekening/Tagihan --> AD_D
AD_C -- Kebocoran/Kualitas/Aliran --> AD_E
AD_C -- Lainnya --> AD_F
AD_D --> AUDIT_ADD
AUDIT_ADD --> AD_G
AD_G -- Ya --> AD_H
AD_G -- Tidak --> AD_I
AD_H --> JURNAL_ADH
JURNAL_ADH --> AUDIT_ADH
AUDIT_ADH --> AD_I
AD_E --> GIS_ADE
GIS_ADE --> AD_J
AD_F --> ESCALATION_ADF
ESCALATION_ADF --> AD_K
AD_J --> GIS_ADJ
GIS_ADJ --> PHOTO_ADJ
PHOTO_ADJ --> AD_L
AD_K --> AD_L
AD_I --> NOTIF_ADI
NOTIF_ADI --> AD_L
AD_L --> AUDIT_ADL
AUDIT_ADL --> AD_M
AD_M --> SURVEY_ADM
SURVEY_ADM --> REPORT_ADM
