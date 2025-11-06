# Dokumentasi Fitur Print Faktur Survei

## 📋 Overview

Fitur print faktur survei memungkinkan pengguna untuk mencetak dan mengunduh hasil survei lapangan dalam format yang rapi dan profesional. Faktur ini dirancang khusus untuk format kertas A4 dan siap cetak.

## ✨ Fitur Utama

### 1. **Print Faktur Single**
   - Mencetak faktur hasil survei untuk satu data survei
   - Tampilan langsung di browser dengan tombol cetak
   - Format A4 portrait yang optimal untuk print

### 2. **Download PDF Single**
   - Mengunduh faktur survei dalam format PDF
   - Nama file otomatis: `Faktur_Survei_{nomor_registrasi}_{timestamp}.pdf`
   - Dapat langsung disimpan atau dikirim via email

### 3. **Print Multiple Faktur**
   - Mencetak beberapa faktur survei sekaligus
   - Setiap faktur dalam halaman terpisah
   - Cocok untuk batch printing

### 4. **Download Multiple PDF**
   - Mengunduh beberapa faktur survei dalam satu file PDF
   - Nama file: `Faktur_Survei_Multiple_{timestamp}.pdf`

## 🎨 Desain Faktur

### Struktur Faktur
Faktur survei terdiri dari beberapa bagian utama:

1. **Header**
   - Logo/Nama PDAM
   - Nama Cabang
   - Alamat dan kontak cabang

2. **Informasi Pendaftaran**
   - Nomor registrasi
   - Tanggal pendaftaran
   - Data pemohon (nama, NIK, HP)
   - Alamat pemasangan

3. **Informasi Pelanggan**
   - Nomor pelanggan
   - Nama pelanggan
   - Alamat pelanggan

4. **Informasi Survei**
   - Tanggal survei
   - NIP Surveyor
   - SPAM
   - Status survei (badge berwarna)

5. **Koordinat & Lokasi**
   - Latitude dan longitude terverifikasi
   - Elevasi (MDPL)
   - Jarak pemasangan

6. **Parameter Survei & Scoring**
   - Tabel 11 parameter survei lengkap dengan skor
   - Total skor otomatis

7. **Hasil & Rekomendasi**
   - Skor total dengan highlight
   - Kategori golongan
   - Rekomendasi sub golongan
   - Hasil survei (badge status)

8. **Catatan & Rekomendasi Teknis**
   - Catatan survei
   - Rekomendasi teknis

9. **Tanda Tangan**
   - Kepala Cabang
   - Surveyor

10. **Footer**
    - Timestamp cetak
    - Copyright

## 🚀 Cara Penggunaan

### Dari Table Survei Resource

#### Print/Download Single Faktur:
1. Klik tombol **"More"** (⋯) pada baris survei yang diinginkan
2. Pilih **"Print Faktur"** untuk membuka di browser dan mencetak
3. Atau pilih **"Download PDF"** untuk mengunduh langsung

#### Print/Download Multiple Faktur:
1. Centang checkbox pada beberapa survei yang diinginkan
2. Klik menu **Bulk Actions** di bagian atas tabel
3. Pilih **"Print Multiple Faktur"** atau **"Download Multiple PDF"**

### Dari Browser (Manual)

#### Print Single:
```
GET /survei/print-faktur/{id}
```

#### Download PDF Single:
```
GET /survei/download-pdf/{id}
```

#### Print Multiple:
```
POST /survei/print-multiple
Body: { ids: [1, 2, 3, ...] }
```

#### Download Multiple PDF:
```
POST /survei/download-multiple-pdf
Body: { ids: [1, 2, 3, ...] }
```

## 🛠️ Technical Details

### Controller
**File:** `app/Http/Controllers/SurveiPrintController.php`

**Methods:**
- `printFaktur($id)` - Render HTML view untuk print single
- `downloadPdf($id)` - Generate dan download PDF single
- `printMultiple(Request $request)` - Render HTML view untuk print multiple
- `downloadMultiplePdf(Request $request)` - Generate dan download PDF multiple

### Views
**File:** `resources/views/print/faktur-survei.blade.php` (Single)
**File:** `resources/views/print/faktur-survei-multiple.blade.php` (Multiple)

### Routes
**File:** `routes/web.php`

```php
Route::get('/survei/print-faktur/{id}', [SurveiPrintController::class, 'printFaktur'])
    ->name('survei.print-faktur');

Route::get('/survei/download-pdf/{id}', [SurveiPrintController::class, 'downloadPdf'])
    ->name('survei.download-pdf');

Route::post('/survei/print-multiple', [SurveiPrintController::class, 'printMultiple'])
    ->name('survei.print-multiple');

Route::post('/survei/download-multiple-pdf', [SurveiPrintController::class, 'downloadMultiplePdf'])
    ->name('survei.download-multiple-pdf');
```

### Resource Actions
**File:** `app/Filament/Resources/SurveiResource.php`

**Single Actions:**
- Print Faktur (icon: printer, color: info)
- Download PDF (icon: arrow-down-tray, color: success)

**Bulk Actions:**
- Print Multiple Faktur
- Download Multiple PDF

## 📊 Data yang Dimuat

Controller menggunakan **eager loading** untuk optimasi performa:

```php
$survei = Survei::with([
    'pendaftaran.cabang',
    'pelanggan',
    'spam',
    'rekomendasiSubGolongan.golonganPelanggan',
    'masterLuasTanah',
    'masterLuasBangunan',
    'masterLokasiBangunan',
    'masterDindingBangunan',
    'masterLantaiBangunan',
    'masterAtapBangunan',
    'masterPagarBangunan',
    'masterKondisiJalan',
    'masterDayaListrik',
    'masterFungsiRumah',
    'masterKepemilikanKendaraan'
])->findOrFail($id);
```

## 🎯 Styling & Print Optimization

### CSS Features:
- **Responsive untuk A4 (210mm x 297mm)**
- **Print-friendly styles** dengan `@media print`
- **Page break** untuk multiple print
- **Professional layout** dengan grid dan table
- **Color-coded badges** untuk status
- **Fixed header dan footer**
- **Tombol print** yang hilang saat cetak

### Print Button:
- Fixed position di pojok kanan atas
- Hidden saat print (class: `no-print`)
- One-click print dengan JavaScript

## ⚠️ Error Handling

Semua method controller dilengkapi dengan try-catch block:

```php
try {
    // Logic
} catch (\Exception $e) {
    return back()->with('error', 'Error message: ' . $e->getMessage());
}
```

## 📝 Best Practices

1. **Selalu load relasi yang diperlukan** untuk menghindari N+1 query problem
2. **Gunakan findOrFail()** untuk memastikan data ada
3. **Format tanggal dengan Carbon** untuk consistency
4. **Gunakan badge warna** untuk status visual
5. **Sediakan fallback value** dengan `??` operator
6. **Test print** sebelum deployment ke production

## 🔧 Customization

### Mengubah Layout:
Edit file blade: `resources/views/print/faktur-survei.blade.php`

### Mengubah Styling:
Modifikasi CSS di dalam tag `<style>` pada file blade

### Menambah Data:
1. Tambahkan relasi di eager loading controller
2. Tambahkan section baru di view blade
3. Sesuaikan styling jika perlu

## 📱 Compatibility

- ✅ Chrome/Edge (Recommended)
- ✅ Firefox
- ✅ Safari
- ✅ Semua browser modern yang support CSS Grid dan Flexbox

## 🎓 Tips Penggunaan

1. **Set printer ke A4** untuk hasil optimal
2. **Gunakan "Print to PDF"** jika ingin save digital copy
3. **Preview dulu** sebelum print massal
4. **Pastikan data survei lengkap** sebelum print
5. **Gunakan landscape mode** hanya jika perlu tabel lebih lebar

## 📞 Support

Jika ada masalah atau pertanyaan terkait fitur print faktur survei, silakan hubungi tim IT atau buat issue ticket.

---

**Version:** 1.0.0  
**Last Updated:** November 6, 2025  
**Developer:** CRM PDAM Development Team
