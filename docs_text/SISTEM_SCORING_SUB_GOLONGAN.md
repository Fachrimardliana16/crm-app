# Sistem Scoring Sub Golongan Pelanggan

## Overview
Sistem scoring untuk menentukan sub golongan pelanggan berdasarkan parameter survei yang dilakukan di lapangan. Sistem ini membantu otomatisasi penentuan kategori pelanggan berdasarkan kondisi ekonomi dan bangunan.

## Parameter Scoring

### Parameter yang Dinilai:
1. **Luas Tanah** - Skor berdasarkan ukuran tanah
2. **Luas Bangunan** - Skor berdasarkan ukuran bangunan
3. **Lokasi Bangunan** - Skor berdasarkan posisi strategis
4. **Material Dinding** - Skor berdasarkan kualitas material
5. **Material Lantai** - Skor berdasarkan jenis lantai
6. **Material Atap** - Skor berdasarkan jenis atap
7. **Kondisi Pagar** - Skor berdasarkan ada/tidaknya pagar
8. **Kondisi Jalan** - Skor berdasarkan akses jalan
9. **Daya Listrik** - Skor berdasarkan kapasitas listrik
10. **Fungsi Rumah** - Skor berdasarkan penggunaan
11. **Kepemilikan Kendaraan** - Skor berdasarkan jenis kendaraan

### Range Skor Total: 0 - 150 poin

## Kategori Sub Golongan

### 1. SOSIAL
- **SOC-KH (Sosial Khusus)**: 0-40 poin
  - Rumah ibadah, panti asuhan, yayasan sosial
- **SOC-HU (Sosial Umum)**: 0-35 poin  
  - Sekolah, puskesmas, fasilitas umum

### 2. RUMAH TANGGA
- **RT-A**: 20-50 poin
  - Rumah sederhana, material sederhana, tanpa kendaraan bermotor
- **RT-B**: 51-80 poin
  - Rumah menengah, material semi permanen, ada sepeda motor
- **RT-C**: 81-120 poin
  - Rumah menengah ke atas, material permanen, ada mobil
- **RT-KH (Rumah Tangga Khusus)**: 121+ poin
  - Rumah mewah, material premium, kendaraan mewah

### 3. INSTANSI
- **INS-PEM**: 60+ poin
  - Kantor pemerintahan dengan fasilitas lengkap

### 4. TNI/POLRI  
- **TNI**: 30-70 poin
- **POLRI**: 30-70 poin

### 5. NIAGA
- **NGA-KC (Niaga Kecil)**: 40-90 poin
  - Usaha kecil-menengah
- **NGA-BS (Niaga Besar)**: 91+ poin
  - Mall, hotel, perkantoran besar

### 6. INDUSTRI
- **IND-KC (Industri Kecil)**: 60-110 poin
  - Industri rumahan, UKM
- **IND-BS (Industri Besar)**: 111+ poin
  - Pabrik, manufaktur besar

## Cara Penggunaan

### 1. Pengaturan Sub Golongan
1. Masuk ke menu **Master Data > Sub Golongan Pelanggan**
2. Edit sub golongan yang diinginkan
3. Pada section **Sistem Scoring Survei**:
   - Aktifkan **Gunakan Sistem Scoring Otomatis**
   - Set **Skor Minimum** dan **Skor Maksimum**
   - Atur **Prioritas Scoring** (untuk overlap range)
   - Isi **Kriteria Scoring** sebagai panduan

### 2. Proses Survei
1. Lengkapi form survei dengan parameter-parameter yang ada
2. Sistem akan otomatis menghitung skor berdasarkan master data
3. Gunakan action **"Tentukan Sub Golongan"** untuk mendapatkan rekomendasi

### 3. Method Programming

```php
// Hitung total skor survei
$skorTotal = $survei->hitungTotalSkor();

// Tentukan sub golongan berdasarkan skor
$subGolongan = SubGolonganPelanggan::rekomendasiSubGolongan($skorTotal, $golonganId);

// Update hasil survei
$survei->updateHasilSurvei($golonganId);

// Cek apakah skor masuk dalam range sub golongan tertentu
$isValid = $subGolongan->isScoreInRange($skorTotal);
```

## Konfigurasi Master Data Parameter

Setiap parameter survei memiliki master data dengan skor:
- **Luas Tanah**: 0-15 poin
- **Luas Bangunan**: 0-15 poin  
- **Lokasi/Material**: 0-10 poin masing-masing
- **Fasilitas**: 0-10 poin masing-masing

## Prioritas Scoring

Jika ada overlap range skor antar sub golongan, sistem akan memilih berdasarkan:
1. **Prioritas Scoring** (tertinggi diutamakan)
2. **Golongan Pelanggan** (jika ditentukan)
3. **Urutan** dalam sub golongan

## Validasi dan Review

- Skor **100+**: Kategori A (Otomatis direkomendasikan)
- Skor **75-99**: Kategori B (Direkomendasikan)  
- Skor **50-74**: Kategori C (Perlu review)
- Skor **<50**: Kategori D (Perlu review manual)

## Troubleshooting

### Tidak Ada Sub Golongan yang Cocok
1. Cek apakah ada sub golongan dengan status **aktif**
2. Pastikan range skor sudah dikonfigurasi dengan benar
3. Periksa parameter survei sudah lengkap
4. Review manual untuk kasus khusus

### Overlap Range Skor
1. Gunakan **Prioritas Scoring** untuk menentukan prioritas
2. Sesuaikan range skor agar tidak tumpang tindih
3. Set **Skor Maksimum** sebagai **null** untuk range terbuka

## Update dan Maintenance

- Review berkala range skor berdasarkan data lapangan
- Update master parameter scoring sesuai perkembangan
- Monitor akurasi rekomendasi sistem vs validasi manual
- Backup data sebelum perubahan konfigurasi besar