# GitHub Copilot Instructions

## 🎯 Context

Proyek ini adalah sistem manajemen pelanggan **PDAM**, mencakup seluruh siklus layanan pelanggan:  
pendaftaran, survey, RAB & persetujuan, gudang, pemasangan, pembacaan meter, pembayaran, penagihan, layanan khusus, dan pengaduan.

Copilot harus membantu menjaga **konsistensi antar modul**:

-   Workflow operasional (sesuai SLA dan KPI)
-   Audit trail
-   Notifikasi multi-channel
-   Struktur form, tab, widget, dan halaman report
-   Integrasi sistem eksternal (GIS, SAKEP 2025, API Gateway)

---

## 🧩 Struktur Modul Utama

1. **Pendaftaran**
2. **Survey**
3. **RAB & Persetujuan**
4. **Gudang**
5. **Pemasangan**
6. **Pembacaan Rekening**
7. **Pembayaran**
8. **Penagihan & Loket Paralel**
9. **Layanan Khusus** (Balik Nama, Penyambungan Kembali, Air Tangki)
10. **Pengaduan Terpusat**
11. **Elemen Pendukung Umum**  
    (Audit Trail, SLA Monitoring, KPI, Integrasi Sistem, Notifikasi Multi-Channel)

---

## ⚙️ Aturan Umum Copilot

> Copilot harus memastikan setiap modul non-master memiliki **struktur dan perilaku seragam.**

### Untuk Semua Resource Non-Master

Setiap `Filament Resource` seperti `Pendaftaran`, `Survey`, `RAB`, `Pemasangan`, dll harus memiliki:

1. **Widget Statistik (`StatOverviewWidget`)**

    - Menampilkan jumlah total, status, SLA, dan KPI terkait.
    - Diletakkan di `getHeaderWidgets()` atau halaman dashboard resource.

2. **Tab Status (`getTabs()`)**

    - Minimal tiga tab default: `Semua`, `Menunggu`, `Selesai`.
    - Query disesuaikan dengan kolom `status`.

3. **Halaman Report**

    - Setiap resource memiliki `ReportPage` untuk ekspor data (Excel/PDF).
    - Harus menyertakan filter tanggal dan status.
    - Gunakan error handling standar dan notifikasi sukses/gagal.

4. **Notifikasi**

    - Semua aksi penting (create, update, approve, reject) mengirim notifikasi otomatis.
    - Format pesan:
        ```
        [Judul Notifikasi]
        Tanggal: <tanggal>
        Oleh: <nama user>
        Lihat detail data <resource>.
        ```
    - Gunakan `Notification::make()` Filament dan `sendToDatabase()`.

5. **Form Grouping**

    - Gunakan `Section` dan `Grid` untuk mengelompokkan input.
    - Setiap grup form harus **collapsible**.
    - Input penting diberi validasi (`->required()`).

6. **Error Handling**

    - Semua aksi (form save, edit, delete, custom action) wajib dibungkus `try-catch`.
    - Jika gagal, tampilkan `Notification::make()->danger()->body($e->getMessage())`.

7. **Event Driven Workflow**

    - Transisi antar modul dilakukan melalui event, misalnya:
        - `PendaftaranCreated` → `TriggerSurveyCreated`
        - `SurveyApproved` → `GenerateRAB`
        - `RABPaid` → `TriggerSPKPemasangan`
    - Gunakan nama event dan listener yang eksplisit dan mudah dipahami.

8. **Audit Trail**

    - Setiap perubahan penting harus tercatat:
        - User yang melakukan
        - Waktu tindakan
        - Data sebelum/sesudah

9. **SLA Monitoring**

    - Gunakan timer otomatis untuk memantau batas waktu tiap tahap (misal: validasi 1 jam, pemasangan 7 hari).
    - Jika melebihi SLA, kirim notifikasi ke kepala seksi terkait.

10. **KPI Tracking**

    - Copilot harus membantu membangun relasi KPI:
        - Validasi dokumen
        - Lead time gudang
        - Akurasi pembacaan meter
        - Kepuasan pelanggan

11. **Integrasi Sistem**
    - GIS: sinkronisasi titik GPS dan status pelanggan.
    - SAKEP 2025: jurnal otomatis (pengeluaran/pemasukan kas & barang).
    - API Gateway: sinkronisasi pembayaran dan notifikasi real-time.

---

## 🧱 Struktur Folder Standar

app/
├── Filament/
│ ├── Resources/
│ │ ├── PendaftaranResource.php
│ │ ├── SurveyResource.php
│ │ ├── RABResource.php
│ │ └── ...
│ ├── Pages/
│ │ └── Report/
│ │ ├── PendaftaranReport.php
│ │ ├── SurveyReport.php
│ │ └── ...
│ └── Widgets/
│ └── Stat/
│ ├── PendaftaranStatOverview.php
│ ├── SurveyStatOverview.php
│ └── ...
└── Notifications/
├── PendaftaranNotification.php
├── SurveyNotification.php
└── ...

---

## 🧠 Naming Convention

Copilot harus mengikuti penamaan fungsi dan variabel sesuai proses bisnis PDAM:

| Tujuan                | Nama Fungsi yang Disarankan   |
| --------------------- | ----------------------------- |
| Validasi dokumen      | `validateDokumen()`           |
| Membuat SPK Survey    | `generateSPKSurvey()`         |
| Notifikasi pembayaran | `notifikasiPembayaran()`      |
| Proses RAB            | `prosesRAB()`                 |
| Aktivasi pelanggan    | `activatePelanggan()`         |
| Audit trail           | `recordAuditTrail()`          |
| SLA timer             | `startSLA()` dan `checkSLA()` |

---

## ✅ Tujuan Akhir Copilot

-   Menjaga kesesuaian kode dengan **alur operasional PDAM**.
-   Membantu developer menghasilkan modul baru dengan struktur, notifikasi, dan report yang konsisten.
-   Menegakkan standar SLA, KPI, dan audit trail di seluruh modul.
-   Menghindari pembuatan kode yang tidak sesuai dengan **workflow PDAM**.
-   Menghasilkan kode yang siap produksi, dengan validasi, error handling, dan integrasi yang jelas.

---

## 💬 Catatan Tambahan

-   Semua kode Laravel/Filament harus **rapi, terstruktur, dan mudah dibaca**.
-   Setiap proses penting wajib memiliki **komentar deskriptif** agar Copilot memahami konteks bisnis.
-   Copilot harus **mengasumsikan sistem ini bersifat enterprise**, dengan multi-user, multi-cabang, dan logging detail.

---

> 🧩 **Ringkasan:**  
> Copilot wajib memperlakukan setiap resource non-master sebagai _modul PDAM lengkap_ dengan widget statistik, tab status, halaman report, notifikasi otomatis, form collapsible, dan error handling terstandar.
> Copilot harus memastikan alur kerja antar modul mengikuti event-driven workflow yang konsisten, dengan audit trail, SLA monitoring, KPI tracking, dan integrasi sistem eksternal.
> Penamaan fungsi dan variabel harus mencerminkan proses bisnis PDAM untuk memudahkan pemeliharaan dan pengembangan di masa depan.

# Filament Admin Panel Provider Customization
