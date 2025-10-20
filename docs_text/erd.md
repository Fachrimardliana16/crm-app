erDiagram
%% =======================
%% RELASI ENTITAS UTAMA
%% =======================
PELANGGAN ||--o{ PENDAFTARAN : memiliki
PELANGGAN ||--o{ PEMBAYARAN : membayar
PELANGGAN ||--o{ PENGADUAN : mengadu
PELANGGAN ||--o{ PEMBACAAN_METER : dicatat_meter
PELANGGAN ||--o{ PENUTUPAN_SAMBUNGAN : penutupan
PELANGGAN ||--o{ SAMBUNG_ULANG : sambung_ulang
PELANGGAN ||--o{ KOREKSI_TAGIHAN : koreksi
PELANGGAN ||--o{ LAYANAN_KHUSUS : permintaan_khusus
PELANGGAN ||--o{ GIS_LOG : histori_gis

PENDAFTARAN ||--o{ SURVEI : disurvei
PENDAFTARAN ||--o{ RAB : rab
PENDAFTARAN ||--o{ LOG_PENDAFTARAN : audit_pendaftaran

RAB ||--o{ TAGIHAN_RAB : tagihan
TAGIHAN_RAB ||--o{ PEMBAYARAN : pembayaran
PEMBAYARAN ||--o{ LOG_PEMBAYARAN : audit_pembayaran

SPAM ||--o{ PELANGGAN : asal_spam
SPAM ||--o{ SURVEI : asal_spam_survei

%% ========== KEUANGAN, API, SLA, AUDIT ==========
PEMBAYARAN ||--o{ JURNAL_KEUANGAN : jurnal_pembayaran
PENGELUARAN_GUDANG ||--o{ JURNAL_KEUANGAN : jurnal_gudang
JURNAL_KEUANGAN ||--o{ LOG_AUDIT : audit_jurnal
PEMBAYARAN ||--o{ API_PAYMENT_LOG : log_api_pembayaran
PENDAFTARAN ||--o{ API_PAYMENT_LOG : log_api_daftar
LAYANAN_KHUSUS ||--o{ API_PAYMENT_LOG : log_api_layanan
SLA ||--o{ SLA_HISTORY : tracking_sla
SLA_HISTORY ||--o{ LOG_AUDIT : audit_sla

%% ========== USER, ROLE, JABATAN, UNIT ==========
USER ||--o{ LOG_USER_AKSI : aksi_user
USER ||--o{ LOG_USER_LOGIN : login_user
USER ||--o{ LOG_USER_LOGOUT : logout_user
USER ||--o{ USER_ROLE : role_user
USER_ROLE ||--|| ROLE : mapping
USER ||--o{ USER_JABATAN : jabatan_user
USER ||--o{ USER_UNIT : unit_user
ROLE ||--o{ ROLE_PERMISSION : role_permission
PERMISSION ||--o{ ROLE_PERMISSION : permission_role

%% ========== MASTER & DATA REFERENSI ==========
GOLONGAN_PELANGGAN ||--o{ PELANGGAN : golongan
TARIF_AIR ||--o{ GOLONGAN_PELANGGAN : tarif_golongan
SEGMENT_PELANGGAN ||--o{ PELANGGAN : segmen
AREA ||--o{ PELANGGAN : area
JABATAN ||--o{ USER_JABATAN : jabatan
UNIT ||--o{ USER_UNIT : unit
METODE_BAYAR ||--o{ PEMBAYARAN : metode
TIPE_LAYANAN ||--o{ PENDAFTARAN : tipe_layanan
STATUS_PELANGGAN ||--o{ PELANGGAN : status
STATUS_PENDAFTARAN ||--o{ PENDAFTARAN : status
STATUS_RAB ||--o{ RAB : status
STATUS_TAGIHAN ||--o{ TAGIHAN_RAB : status
STATUS_PEMBAYARAN ||--o{ PEMBAYARAN : status

%% ========== TABEL UTAMA & GIS ==========
PELANGGAN {
String id_pelanggan PK
String nomor_pelanggan
String nama_pelanggan
String nik
String jenis_identitas
String nomor_identitas
String tempat_lahir
Date tanggal_lahir
String alamat
String rt_rw
String kelurahan
String kecamatan
String kode_pos
String nomor_hp
String nomor_telepon
String email
String status_pelanggan
String golongan
String tipe_pelanggan
String segment
String id_area FK
String id_spam FK
String latitude
String longitude
String elevasi
String kode_gis
String status_gis "ENUM: belum_divalidasi, valid, tidak_valid, revisi"
String tgl_validasi_gis
String validasi_gis_oleh
String keterangan_gis
String keterangan
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
String status_historis "ENUM: aktif, nonaktif, arsip"
Date tanggal_nonaktif
Date tanggal_arsip
}

SPAM {
String id_spam PK
String nama_spam
String wilayah
String deskripsi
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

GIS_LOG {
String id_gis_log PK
String id_pelanggan FK
String aksi_gis "ENUM: insert, update, delete, validasi, revisi"
Date waktu_aksi
String user_aksi
String latitude_lama
String longitude_lama
String elevasi_lama
String latitude_baru
String longitude_baru
String elevasi_baru
String kode_gis_lama
String kode_gis_baru
String status_gis_lama
String status_gis_baru
String keterangan
}

PENDAFTARAN {
String id_pendaftaran PK
String id_pelanggan FK
String status_pendaftaran
String cabang_pendaftaran
String kelurahan_pemasangan
String tipe_layanan
String pekerjaan_pemohon
String jenis_identitas
String nomor_identitas
Date tanggal_daftar
String nik_pemohon
String nama_pemohon
String alamat_pemasangan
Decimal latitude_awal
Decimal longitude_awal
Decimal elevasi_awal_mdpl
String keterangan_arah_lokasi
String scan_identitas_utama
String scan_dokumen_mou
Decimal dana_pengembalian
String ada_toren "ENUM: ya, tidak"
String ada_sumur "ENUM: ya, tidak"
String jenis_daftar "ENUM: standar, non_standar"
String tipe_daftar "ENUM: standar, kilat"
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

DOKUMEN_PENDAFTARAN {
String id_dokumen_pendaftaran PK
String id_pendaftaran FK
String nama_dokumen
String nomor_dokumen
Date tanggal_berlaku
String dokumen_diunggah
String status_kelengkapan "ENUM: lengkap, tidak_lengkap, revisi"
String catatan_validasi
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

LOG_PENDAFTARAN {
String id_log_pendaftaran PK
String id_pendaftaran FK
String id_user FK
Date waktu_log
String jenis_aksi "ENUM: submit, review, tolak, setujui, revisi"
String catatan
}

SURVEI {
String id_survei PK
String id_pendaftaran FK
String id_pelanggan FK
String id_spam FK
String nip_surveyor
Date tanggal_survei
String status_survei "ENUM: draft, disetujui, ditolak"
String subrayon
Decimal latitude_terverifikasi
Decimal longitude_terverifikasi
Decimal elevasi_terverifikasi_mdpl
Decimal jarak_pemasangan
String catatan_teknis
Integer nilai_survei
String golongan_survei
String kelas_survei_input
String foto_peta_lokasi
String foto_tanah_bangunan
String foto_dinding
String foto_lantai
String foto_atap
String foto_pagar
String foto_jalan
String foto_meteran_listrik
String rekomendasi_teknis
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

LOG_SURVEI {
String id_log_survei PK
String id_survei FK
String id_user FK
Date waktu_log
String jenis_aksi "ENUM: submit, revisi, tolak, setujui"
String catatan
}

RAB {
String id_rab PK
String id_pendaftaran FK
String id_pelanggan FK
Date tanggal_rab_dibuat
String status_rab "ENUM: draft, proses, disetujui, ditolak"
Decimal total_biaya_konstruksi
Decimal total_biaya_administrasi
Decimal sub_total_awal
Decimal nilai_pajak
Decimal total_rab_bruto
Decimal pembulatan
Decimal total_final_rab
Decimal uang_muka
Decimal biaya_sb
Decimal piutang_non_adir
Integer jumlah_angsuran
String status_pembayaran "ENUM: lunas, sebagian, belum"
String catatan_rab
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

TAGIHAN_RAB {
String id_tagihan PK
String id_rab FK
String id_pelanggan FK
String nomor_tagihan
Date tanggal_terbit
Date jatuh_tempo
Decimal total_tertagih
String status_pembayaran "ENUM: lunas, sebagian, belum"
String catatan_tagihan
String dibuat_oleh
Date dibuat_pada
}

PEMBAYARAN {
String id_pembayaran PK
String id_tagihan FK
String id_pelanggan FK
String nomor_pembayaran
Date tanggal_bayar
Decimal jumlah_bayar
Decimal biaya_admin
String bukti_bayar
String metode_bayar
String nip_petugas_loket
String status_verifikasi "ENUM: valid, tidak_valid, pending"
String catatan_pembayaran
String dibuat_oleh
Date dibuat_pada
}

LOG_PEMBAYARAN {
String id_log_pembayaran PK
String id_pembayaran FK
String id_user FK
Date waktu_log
String aksi "ENUM: input, verifikasi, gagal, sukses, batal"
String catatan
}

PENGADUAN {
String id_pengaduan PK
String id_pelanggan FK
String nomor_pengaduan
Date tanggal_aduan
String saluran_aduan "ENUM: loket, telepon, email, web, mobile"
String jenis_aduan "ENUM: kualitas, kuantitas, tagihan, pelayanan, teknis"
String status_aduan "ENUM: proses, selesai, ditolak, pending"
String keterangan
String tindakan
String berita_acara
String foto_aduan
String ditutup_oleh
Date ditutup_pada
String solusi_diberikan
String tingkat_kepuasan
}

PEMBACAAN_METER {
String id_pembacaan PK
String id_pelanggan FK
String periode_baca
Decimal nilai_meter_sebelum
Decimal nilai_meter_sekarang
Decimal pemakaian
String foto_meter
Date tanggal_baca
String status_validasi "ENUM: valid, anomali, revisi"
String petugas_baca
String catatan
String jenis_pembacaan "ENUM: rutin, khusus, koreksi"
}

ANOMALI_PEMBACAAN {
String id_anomali PK
String id_pembacaan FK
String jenis_anomali "ENUM: meter_rusak, tidak_terbaca, pemakaian_tinggi, pemakaian_nol"
String tindakan
String status_anomali "ENUM: selesai, proses, eskalasi"
String foto_anomali
Date tanggal
String dibuat_oleh
String keterangan_anomali
}

PENUTUPAN_SAMBUNGAN {
String id_penutupan PK
String id_pelanggan FK
String nomor_penutupan
String jenis_penutupan "ENUM: sementara, tetap"
Date tanggal_penutupan
String alasan
String status_penutupan "ENUM: proses, selesai"
String berita_acara
String foto_penutupan
String petugas_penutupan
String dibuat_oleh
}

SAMBUNG_ULANG {
String id_sambung_ulang PK
String id_pelanggan FK
String nomor_sambung_ulang
Date tanggal_sambung
String status_sambung "ENUM: proses, selesai"
String berita_acara
String foto_sambung
String petugas_sambung
String dibuat_oleh
Decimal biaya_sambung_ulang
}

KOREKSI_TAGIHAN {
String id_koreksi PK
String id_tagihan FK
String id_pelanggan FK
String nomor_koreksi
String alasan
Decimal nominal_sebelum
Decimal nominal_koreksi
Decimal nominal_sesudah
Date tanggal_koreksi
String status_koreksi "ENUM: proses, disetujui, ditolak"
String berita_acara
String dibuat_oleh
String disetujui_oleh
Date disetujui_pada
}

LAYANAN_KHUSUS {
String id_layanan_khusus PK
String id_pelanggan FK
String nomor_layanan
String jenis_layanan "ENUM: balik_nama, pindah_meter, penyambungan_ulang, air_tangki"
Date tanggal_permohonan
String status_layanan "ENUM: proses, selesai, ditolak"
Decimal biaya
String berita_acara
String keterangan
String dibuat_oleh
Date dibuat_pada
String diselesaikan_oleh
Date diselesaikan_pada
}

%% ========== KEUANGAN & AKUNTANSI (SAKEP 2025) ==========
JURNAL_KEUANGAN {
String id_jurnal PK
String nomor_jurnal
Date tanggal
String kode_transaksi
String jenis_jurnal "ENUM: penerimaan, pengeluaran"
String sumber "ENUM: pembayaran, gudang, lainnya"
String id_pembayaran FK
String id_gudang FK
String deskripsi
Decimal debet
Decimal kredit
Decimal saldo
String akun_debet
String akun_kredit
String dibuat_oleh
Date dibuat_pada
String status "ENUM: draft, posting, validasi"
String diverifikasi_oleh
Date waktu_verifikasi
String periode_akuntansi
}

PENGELUARAN_GUDANG {
String id_gudang PK
String nomor_bukti
Date tanggal_pengeluaran
String uraian
Decimal nilai_pengeluaran
String kategori_pengeluaran
String status "ENUM: draft, disetujui, dibayar"
String dibuat_oleh
Date dibuat_pada
String disetujui_oleh
Date disetujui_pada
}

RINCIAN_ANGSURAN {
String id_angsuran PK
String id_tagihan FK
Integer nomor_angsuran
Decimal jumlah_angsuran
Date tanggal_jatuh_tempo
String status_bayar "ENUM: belum, lunas, terlambat"
Date tanggal_bayar
Decimal denda
String keterangan
}

PENYESUAIAN_TAGIHAN {
String id_penyesuaian PK
String id_tagihan FK
String jenis_penyesuaian "ENUM: diskon, denda, pembebasan"
Decimal nilai_penyesuaian
String alasan
String status "ENUM: proses, disetujui, ditolak"
String dibuat_oleh
Date dibuat_pada
String disetujui_oleh
Date disetujui_pada
}

%% ========== API PEMBAYARAN & MONITOR ==========
API_PAYMENT_LOG {
String id_api_log PK
String id_pelanggan FK
String id_pembayaran FK
String id_pendaftaran FK
String id_layanan_khusus FK
String endpoint
String method "ENUM: GET, POST, PUT, DELETE"
String request_payload
String response_payload
String status_code
String status "ENUM: sukses, gagal, pending"
Date waktu_request
Date waktu_response
Integer durasi_ms
String ip_address
String user_agent
String keterangan
}

%% ========== SLA & TRACKING ==========
SLA {
String id_sla PK
String nama_sla
String proses
Integer batas_waktu_menit
String unit
String kriteria_sukses
String kriteria_gagal
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

SLA_HISTORY {
String id_sla_history PK
String id_sla FK
String id_pelanggan FK
String proses
Date waktu_mulai
Date waktu_selesai
Integer durasi_menit
String hasil "ENUM: sukses, gagal, pending"
String keterangan
String diverifikasi_oleh
Date waktu_verifikasi
String tindakan_perbaikan
}

%% ========== LOG AUDIT & COMPLIANCE ==========
LOG_AUDIT {
String id_log_audit PK
String tipe_log "ENUM: keuangan, operasional, SLA, user, data"
String id_referensi
String tabel_referensi
String aksi "ENUM: create, update, delete, read, login, logout"
String nilai_lama
String nilai_baru
String user_aksi
Date waktu_aksi
String ip_address
String user_agent
String keterangan
String sumber_audit "ENUM: internal, SPI, BPKP, Inspektorat"
String hasil_audit
Date waktu_audit
String catatan_audit
}

LOG_ERROR {
String id_log_error PK
String tipe_error "ENUM: system, database, api, user"
String error_message
String stack_trace
String user_id
String ip_address
String url
String method
Date waktu_error
String status "ENUM: baru, ditangani, selesai"
String ditangani_oleh
Date ditangani_pada
}

LOG_CONFIG {
String id_log_config PK
String parameter_name
String nilai_lama
String nilai_baru
String user_aksi
Date waktu_aksi
String alasan_perubahan
String ip_address
}

LOG_FILE_AKSES {
String id_log_file PK
String nama_file
String path_file
String aksi "ENUM: upload, download, view, delete"
String user_aksi
Date waktu_aksi
String ip_address
String user_agent
String ukuran_file
String keterangan
}

%% ========== USER & SECURITY ==========
USER {
String id_user PK
String username
String nama
String email
String password
String id_cabang FK
String id_jabatan FK
String id_role FK
String status "ENUM: aktif, nonaktif, suspend"
Date last_login
String last_ip
Integer failed_login_attempts
Date password_changed_at
String foto_profil
String nomor_hp
Date dibuat_pada
Date diperbarui_pada
String dibuat_oleh
String diperbarui_oleh
}

LOG_USER_AKSI {
String id_log_user_aksi PK
String id_user FK
Date waktu_aksi
String jenis_aksi "ENUM: create, read, update, delete, login, logout, export, import"
String tabel_target
String id_record_target
String ip_address
String user_agent
String keterangan
String hasil "ENUM: sukses, gagal"
}

LOG_USER_LOGIN {
String id_log_login PK
String id_user FK
Date waktu_login
String ip_address
String user_agent
String browser
String device
String status_login "ENUM: sukses, gagal"
String alasan_gagal
}

LOG_USER_LOGOUT {
String id_log_logout PK
String id_user FK
Date waktu_logout
String ip_address
String user_agent
Integer durasi_session_menit
}

%% ========== RBAC (ROLE & PERMISSION) ==========
ROLE {
String id_role PK
String nama_role
String deskripsi
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

PERMISSION {
String id_permission PK
String nama_permission
String deskripsi
String modul
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

ROLE_PERMISSION {
String id_role FK
String id_permission FK
String dibuat_oleh
Date dibuat_pada
}

USER_ROLE {
String id_user FK
String id_role FK
String dibuat_oleh
Date dibuat_pada
}

USER_JABATAN {
String id_user FK
String id_jabatan FK
String dibuat_oleh
Date dibuat_pada
}

USER_UNIT {
String id_user FK
String id_unit FK
String dibuat_oleh
Date dibuat_pada
}

%% ========== MASTER DATA LENGKAP ==========
GOLONGAN_PELANGGAN {
String id_golongan PK
String kode_golongan
String nama_golongan
String deskripsi
Decimal tarif_minimum
Decimal tarif_per_m3
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

TARIF_AIR {
String id_tarif PK
String id_golongan FK
String kode_tarif
Decimal tarif_per_m3
Date berlaku_mulai
Date berlaku_sampai
String keterangan
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

SEGMENT_PELANGGAN {
String id_segment PK
String kode_segment
String nama_segment
String deskripsi
String kriteria
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

AREA {
String id_area PK
String kode_area
String nama_area
String deskripsi
String koordinat_batas
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

JABATAN {
String id_jabatan PK
String kode_jabatan
String nama_jabatan
String deskripsi
String level_jabatan
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

UNIT {
String id_unit PK
String kode_unit
String nama_unit
String alamat_unit
String telepon_unit
String email_unit
String kepala_unit
String deskripsi
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

METODE_BAYAR {
String id_metode PK
String kode_metode
String nama_metode
String deskripsi
Decimal biaya_admin
String provider
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

TIPE_LAYANAN {
String id_tipe_layanan PK
String kode_tipe
String nama_tipe
String deskripsi
Decimal biaya_layanan
Integer sla_hari
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

STATUS_PELANGGAN {
String id_status_pelanggan PK
String kode_status
String nama_status
String deskripsi
String warna_status
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

STATUS_PENDAFTARAN {
String id_status_pendaftaran PK
String kode_status
String nama_status
String deskripsi
String warna_status
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

STATUS_RAB {
String id_status_rab PK
String kode_status
String nama_status
String deskripsi
String warna_status
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

STATUS_TAGIHAN {
String id_status_tagihan PK
String kode_status
String nama_status
String deskripsi
String warna_status
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

STATUS_PEMBAYARAN {
String id_status_pembayaran PK
String kode_status
String nama_status
String deskripsi
String warna_status
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

%% ========== PELAPORAN HARIAN DAN BULANAN ==========
LAPORAN_HARIAN_LOKET {
String id_laporan_harian PK
String id_unit FK
Date tanggal
Decimal total_pembayaran
Integer jumlah_transaksi
Integer jumlah_pembatalan
Decimal total_pengembalian
Decimal saldo_awal
Decimal saldo_akhir
String status_verifikasi "ENUM: draft, diverifikasi, valid"
String diverifikasi_oleh
Date diverifikasi_pada
String keterangan
String file_laporan
}

LAPORAN_PENAGIHAN {
String id_laporan_penagihan PK
Date periode
String id_unit FK
Integer jumlah_tagihan
Decimal total_tagihan
Integer jumlah_pembayaran
Decimal total_pembayaran
Integer jumlah_tunggakan
Decimal total_tunggakan
Decimal persentase_kolektabilitas
String keterangan
String file_laporan
}

REKAP_MUTASI_PELANGGAN {
String id_rekap PK
Date periode
String id_unit FK
Integer jumlah_pelanggan_awal
Integer jumlah_pelanggan_baru
Integer jumlah_pelanggan_aktif
Integer jumlah_pelanggan_nonaktif
Integer jumlah_pelanggan_putus
Integer jumlah_pelanggan_akhir
String keterangan
String file_laporan
}

REKAP_PEMASANGAN {
String id_rekap_pemasangan PK
Date periode
String id_unit FK
Integer jumlah_pendaftaran
Integer jumlah_survei
Integer jumlah_pemasangan_baru
Integer jumlah_penggantian_meter
Integer jumlah_penutupan
Integer jumlah_sambungan_ulang
Decimal total_investasi
String keterangan
String file_laporan
}

REKAP_SALDO {
String id_rekap_saldo PK
Date periode
String id_unit FK
Decimal saldo_awal
Decimal pemasukan_pembayaran
Decimal pemasukan_denda
Decimal pemasukan_lainnya
Decimal total_pemasukan
Decimal pengeluaran_operasional
Decimal pengeluaran_investasi
Decimal pengeluaran_lainnya
Decimal total_pengeluaran
Decimal saldo_akhir
String keterangan
String file_laporan
}

%% ========== TEMPLATE & PARAMETER ==========
TEMPLATE_NOTIFIKASI {
String id_template PK
String kode_template
String nama_template
String jenis_notifikasi "ENUM: tagihan, pembayaran, pengaduan, pemasangan, penutupan"
String subjek
String isi_template
String channel "ENUM: email, sms, wa, push_notification"
String variabel_tersedia
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

PARAMETER_GLOBAL {
String id_parameter PK
String kode_parameter
String nama_parameter
String nilai
String deskripsi
String tipe "ENUM: string, integer, decimal, date, boolean, json"
String kategori
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
String diperbarui_oleh
Date diperbarui_pada
}

REFERENSI_GLOBAL {
String id_referensi PK
String kode_referensi
String nama_referensi
String nilai
String parent_referensi
Integer urutan
String deskripsi
String status "ENUM: aktif, nonaktif"
String dibuat_oleh
Date dibuat_pada
}

NOTIFIKASI {
String id_notifikasi PK
String id_pelanggan FK
String id_template FK
String judul
String isi
String channel
String status_kirim "ENUM: pending, terkirim, gagal"
Date dikirim_pada
Date dibaca_pada
String status_notifikasi "ENUM: baru, dibaca, selesai"
Integer percobaan_kirim
String keterangan_error
}

LOG_NOTIFIKASI {
String id_log_notifikasi PK
String id_notifikasi FK
String id_user FK
Date waktu_log
String aksi "ENUM: dikirim, dibaca, gagal, retry"
String catatan
String error_message
}

%% KOLOM SENSITIF (WAJIB DIENKRIPSI): nik, nomor*identitas, nomor_hp, nomor_telepon, email, password, dokumen_diunggah, scan_identitas_utama, scan_dokumen_mou, bukti_bayar, berita_acara, foto*\*, file_laporan, request_payload, response_payload
%% KOLOM GIS: latitude, longitude, elevasi, kode_gis, status_gis, tgl_validasi_gis, validasi_gis_oleh, keterangan_gis di tabel PELANGGAN dan SURVEI
%% KOLOM HISTORIS: status_historis, tanggal_nonaktif, tanggal_arsip di tabel PELANGGAN untuk penanganan data historis
%% LOG AUDIT: mendukung audit otomatis untuk SPI, BPKP, Inspektorat dengan tipe_log, sumber_audit, hasil_audit
%% SLA TRACKING: SLA dan SLA_HISTORY untuk monitoring dan pelaporan SLA otomatis
%% API PAYMENT: API_PAYMENT_LOG untuk seluruh aktivitas pembayaran via API
%% KEUANGAN SAKEP 2025: JURNAL_KEUANGAN dengan sumber pembayaran dan gudang, RINCIAN_ANGSURAN, PENYESUAIAN_TAGIHAN
