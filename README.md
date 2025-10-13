# PDAM CRM System

Sistem Customer Relationship Management (CRM) khusus untuk Perusahaan Daerah Air Minum (PDAM) yang dibangun dengan Laravel dan Filament untuk mengelola pendaftaran pelanggan, pembayaran, dan operasional harian PDAM.

## 🚀 Fitur Utama

### 📋 Manajemen Pelanggan

-   **Registrasi Pelanggan** - Pendaftaran pelanggan baru dengan workflow lengkap
-   **Data Pelanggan** - Pengelolaan informasi pelanggan dan riwayat layanan
-   **Survei & Instalasi** - Manajemen proses survei dan pemasangan sambungan
-   **Status Tracking** - Pelacakan status pendaftaran real-time

### 💰 Sistem Pembayaran

-   **Faktur Digital** - Generate faktur pembayaran otomatis
-   **Print Dot Matrix** - Faktur optimized untuk printer dot matrix
-   **Riwayat Pembayaran** - Tracking pembayaran dan tagihan bulanan
-   **RAB & Angsuran** - Manajemen Rencana Anggaran Biaya dan cicilan

### 🏢 Manajemen Operasional

-   **Master Data** - Cabang, area, kecamatan, kelurahan, dan wilayah layanan
-   **Bacaan Meter** - Input dan monitoring penggunaan air pelanggan
-   **Pengaduan** - Sistem ticketing untuk keluhan pelanggan
-   **Reporting** - Dashboard dan laporan operasional

### 🔐 Sistem Administrasi

-   **Multi-Role Access** - Admin, operator, dan supervisor dengan hak akses berbeda
-   **Activity Logging** - Pencatatan semua aktivitas pengguna
-   **Authentication** - Sistem login yang aman dengan session management
-   **Modal Notifications** - Popup notifications untuk user experience yang lebih baik

## 🛠️ Tech Stack

### Backend

-   **Laravel v12** - PHP Framework
-   **PHP ^8.2** - Programming Language
-   **SQLite/MySQL** - Database
-   **Laravel Sanctum** - API Authentication

### Frontend & UI

-   **Filament v3.3** - Admin Panel Framework
-   **TailwindCSS** - CSS Framework
-   **Alpine.js** - JavaScript Framework
-   **Vite** - Build Tool

### PDAM CRM Modules

-   **Filament Resources** - Customer, Registration, Payment, Billing
-   **Filament Shield** - Role & Permission Management
-   **Activity Log** - User activity tracking
-   **Dot Matrix Printing** - Optimized faktur for dot matrix printers
-   **Modal System** - Enhanced user notifications

### Additional Libraries

-   **Laravel Authentication Log** - Login activity tracking
-   **Spatie Activity Log** - Comprehensive activity logging
-   **Filament Breezy** - Enhanced authentication UI

## 📋 Requirements

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   MySQL/SQLite Database
-   Web Server (Apache/Nginx/Built-in PHP Server)

## ⚡ Quick Start

### 1. Clone Repository

```bash
git clone https://github.com/Fachrimardliana16/crm-tp.git
cd crm-tp
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup

```bash
# Edit .env file untuk konfigurasi database
php artisan migrate
php artisan db:seed
```

### 5. Build Assets & Run

```bash
npm run build
php artisan serve
```

Aplikasi akan berjalan di http://localhost:8000

## 🔐 Default Access

Setelah menjalankan seeder, gunakan akun berikut:

**Admin Access:**

-   URL: http://localhost:8000/admin
-   Email: admin@pdam.com
-   Password: password

## 🧪 Testing

Menjalankan test suite:

```bash
php artisan test
```

## 📁 Struktur Aplikasi PDAM CRM

```
├── app/
│   ├── Filament/
│   │   ├── Resources/          # PDAM CRM Resources
│   │   │   ├── PelangganResource.php      # Manajemen Pelanggan
│   │   │   ├── PendaftaranResource.php    # Registrasi Pelanggan
│   │   │   ├── PembayaranResource.php     # Sistem Pembayaran
│   │   │   ├── TagihanBulananResource.php # Tagihan Bulanan
│   │   │   ├── BacaanMeterResource.php    # Bacaan Meter Air
│   │   │   ├── PengaduanResource.php      # Keluhan Pelanggan
│   │   │   └── Master Data/               # Area, Cabang, dll
│   │   └── Pages/              # Custom pages & dashboard
│   ├── Http/Controllers/
│   │   └── FakturController.php     # Controller print faktur
│   ├── Models/                 # Eloquent models PDAM
│   │   ├── Pelanggan.php       # Model Pelanggan
│   │   ├── Pendaftaran.php     # Model Pendaftaran
│   │   ├── Pembayaran.php      # Model Pembayaran
│   │   └── ...                 # Models lainnya
│   └── Providers/
│       └── AdminPanelProvider.php   # Filament panel config
├── database/
│   ├── migrations/             # Database schema PDAM CRM
│   └── seeders/               # Data master PDAM (Cabang, Kecamatan, dll)
├── resources/
│   └── views/
│       └── faktur/            # Template faktur pembayaran
│           ├── pembayaran.blade.php    # Faktur dot matrix optimized
│           └── multiple.blade.php      # Multiple faktur printing
└── routes/
    └── web.php                # Routes untuk faktur dan API
```

## 🔧 Fitur Khusus PDAM

### 📄 Sistem Faktur Dot Matrix

-   **Compact Design**: Faktur dioptimalkan untuk kertas setengah A4/F4
-   **Dot Matrix Friendly**: Menggunakan font Courier New dan border dotted
-   **Print Multiple**: Bisa print beberapa faktur sekaligus dengan page break
-   **Modal Integration**: Popup modal untuk akses cepat print dan view faktur

### 📊 Master Data PDAM

-   **Cabang & Area Layanan**: Manajemen wilayah operasional PDAM
-   **Kecamatan & Kelurahan**: Data administrasi lengkap Purbalingga
-   **Jenis Layanan**: Tipe pendaftaran dan golongan pelanggan
-   **Tarif & Pajak**: Sistem perhitungan biaya yang fleksibel

### � Workflow Pendaftaran

1. **Pendaftaran Awal**: Input data calon pelanggan
2. **Survei Lapangan**: Penjadwalan dan hasil survei lokasi
3. **RAB & Persetujuan**: Rencana Anggaran Biaya pemasangan
4. **Instalasi**: Proses pemasangan sambungan air
5. **Pembayaran & Aktivasi**: Pembayaran dan aktivasi layanan

### 🏷️ Modal Notifications

-   **Success Modal**: Popup sukses dengan tombol aksi (Lihat/Print)
-   **Enhanced UX**: Menggantikan notification biasa dengan modal interaktif
-   **Quick Actions**: Akses langsung ke faktur dan detail pendaftaran

## 📝 Development Commands

### Laravel Commands

-   `php artisan migrate` - Jalankan database migration
-   `php artisan db:seed` - Jalankan database seeders
-   `php artisan serve` - Jalankan development server
-   `php artisan optimize` - Optimize aplikasi (cache routes, config, dll)
-   `php artisan optimize:clear` - Clear semua cache

### Filament Commands

-   `php artisan filament:make-user` - Buat user admin baru
-   `php artisan shield:generate` - Generate permissions untuk resources
-   `php artisan shield:super-admin` - Assign super admin role

### Asset Commands

-   `npm run dev` - Development mode untuk frontend assets
-   `npm run build` - Build production assets
-   `npm run watch` - Watch perubahan file dan rebuild otomatis

### Faktur & Printing

-   Route: `/faktur/pembayaran/{id}` - View single faktur
-   Route: `/faktur/multiple` - Print multiple faktur
-   Modal popup terintegrasi dengan create pendaftaran

## 🎯 Penggunaan Aplikasi

### 1. Setup Data Master

-   Login ke admin panel `/admin`
-   Setup data Cabang, Kecamatan, Kelurahan
-   Konfigurasi Jenis Layanan dan Tarif
-   Setup roles dan permissions user

### 2. Workflow Pendaftaran Pelanggan

-   Buat pendaftaran baru di menu "Pendaftaran"
-   Modal popup akan muncul setelah sukses create
-   Gunakan tombol "Print Faktur" untuk cetak dot matrix
-   Track status pendaftaran melalui dashboard

### 3. Manajemen Pembayaran

-   Input pembayaran melalui menu "Pembayaran"
-   Generate faktur otomatis setelah pembayaran
-   Print faktur dengan format compact untuk dot matrix
-   Monitor tagihan bulanan dan tunggakan

### 4. Monitoring & Reporting

-   Dashboard overview untuk semua aktivitas
-   Activity log untuk tracking user actions
-   Reports pembayaran dan pendaftaran per periode
-   Monitoring bacaan meter dan penggunaan air

## 📖 Documentation

### Framework Documentation

-   [Laravel Documentation](https://laravel.com/docs) - Laravel framework
-   [Filament Documentation](https://filamentphp.com/docs) - Admin panel framework
-   [TailwindCSS Documentation](https://tailwindcss.com/docs) - CSS framework

### PDAM CRM Guides

-   `ANALISIS_GAP_WORKFLOW.md` - Analisis gap dan workflow bisnis PDAM
-   `FAKTUR_DOT_MATRIX_GUIDE.md` - Guide design faktur untuk printer dot matrix
-   Database schema di folder `database/migrations/`
-   Seeders data master di folder `database/seeders/`

## 🤝 Contributing

1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/fitur-baru`)
3. Commit perubahan (`git commit -m 'Tambah fitur baru'`)
4. Push ke branch (`git push origin feature/fitur-baru`)
5. Buat Pull Request

## 📄 License

Project ini menggunakan [MIT License](LICENSE).

## 🏢 About PDAM CRM

Sistem CRM ini dirancang khusus untuk kebutuhan Perusahaan Daerah Air Minum (PDAM) dengan fitur-fitur yang disesuaikan dengan workflow operasional PDAM, mulai dari pendaftaran pelanggan baru, manajemen pembayaran, hingga monitoring operasional harian.

**Key Features:**

-   ✅ Modal popup notifications untuk UX yang lebih baik
-   ✅ Faktur dot matrix optimized untuk printer thermal/dot matrix
-   ✅ Compact design faktur untuk efisiensi kertas (setengah A4/F4)
-   ✅ Comprehensive workflow management dari pendaftaran hingga pembayaran
-   ✅ Multi-role access control untuk berbagai level user
-   ✅ Real-time activity logging dan monitoring

## ⚙️ Configuration

### Environment Variables Penting

```env
APP_NAME="PDAM CRM System"
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pdam_crm
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Printer Configuration

Untuk optimal printing di dot matrix printer:

-   Gunakan kertas continuous form atau A4 dipotong setengah
-   Set printer ke mode draft untuk kecepatan tinggi
-   Font Courier New sudah dioptimalkan untuk dot matrix

---

**Developed with ❤️ for PDAM Operations**
