# Faktur Pembayaran - Compact Format untuk Setengah Kertas A4/F4

## Overview

Faktur pembayaran telah dioptimalkan untuk format compact yang cocok untuk:

-   Setengah kertas A4/F4
-   Printer dot matrix
-   Thermal printer
-   Kertas roll kecil

### ✅ Fitur Compact Design:

#### 1. **Layout 2 Kolom**

```
KOP PDAM
----------------------------------------------------
No.Reg : CKB/I/X/MMXXV  | Alamat    : Jl. Contoh 123
Tanggal: 11-10-2025      | Kelurahan : Purbalingga
Nama   : JOHN DOE        | Tipe      : Sambungan Baru
No.HP  : 081234567890    | Cabang    : Cabang Utama
----------------------------------------------------
```

#### 2. **Tabel Rincian Sederhana**

```
RINCIAN BIAYA
Sambungan Baru Domestik                    1.500.000
Biaya Admin                                  100.000
Pajak PPN 11%                                176.000
............................................
TOTAL BAYAR                          Rp 1.776.000

#### 4. **Signature Area Kecil**

```

Pemohon Petugas

---

JOHN DOE ADMIN PDAM

```

### 📏 **Dimensi & Ukuran:**

#### **Kertas:**

-   **Lebar**: Maksimal 400px (~ 10cm)
-   **Tinggi**: Fleksibel, sekitar 15cm untuk 1 faktur
-   **Page Size**: A5 dalam print setting
-   **Margin**: 0.5cm

#### **Font:**

-   **Typeface**: Courier New (monospace)
-   **Size Layar**: 10px
-   **Size Print**: 9px
-   **Line Height**: 1.1 (hemat space)

#### **Layout Spacing:**

-   **Header**: 8px margin
-   **Sections**: 8px margin between
-   **Info rows**: 1px margin
-   **Table rows**: 1px padding

### 🎯 **Struktur Layout:**

```

┌─────────────────────────────────────────────┐
│ PERUSAHAAN DAERAH │
│ PDAM CABANG │
│ PURBALINGGA │
│ Telp: (0123) 456789 │
├─────────────────────────────────────────────┤
│ No.Reg : ABC/I/X/MMXXV │ Alamat : Jl.123 │
│ Tanggal: 11-10-2025 │ Kel : Desa A │
│ Nama : JOHN DOE │ Tipe : Baru │
│ No.HP : 081234567890 │ Cabang : Utama │
├─────────────────────────────────────────────┤
│ RINCIAN BIAYA │
│ │
│ Sambungan Baru 1.500.000 │
│ Biaya Admin 100.000 │
│ ........................................ │
│ TOTAL BAYAR Rp 1.600.000 │
├─────────────────────────────────────────────┤
│ PEMBAYARAN: BRI: 1234-5678-9012 │
├─────────────────────────────────────────────┤
│ Pemohon Petugas │
│ │
│ ****\_\_\_\_**** ****\_\_\_\_**** │
│ JOHN DOE ADMIN PDAM │
└─────────────────────────────────────────────┘

````

### �️ **Print Settings:**

#### **Untuk Printer Dot Matrix:**

-   **Paper**: A5 atau Custom (10cm x 15cm)
-   **Quality**: Draft
-   **Font**: Courier New 9pt
-   **Margins**: 0.5cm semua sisi
-   **Orientation**: Portrait

#### **Browser Print Settings:**

-   **Scale**: 100%
-   **Margins**: Minimum
-   **Background graphics**: OFF
-   **Print to PDF**: A5 size

### 📊 **Optimizations:**

#### **Text Truncation:**

-   Nama: maksimal 15 karakter
-   Alamat: maksimal 20 karakter
-   Kelurahan: maksimal 15 karakter
-   Biaya items: maksimal 25 karakter

#### **Number Formatting:**

-   Format: `1.500.000` (tanpa "Rp" kecuali total)
-   Right-aligned dalam kolom 80px
-   Separator ribuan: titik (.)

#### **Responsive Elements:**

```css
@media print {
    font-size: 9px;
    @page {
        size: A5;
        margin: 0.5cm;
    }
}
````

### 🔧 **Customization:**

#### **Untuk Kertas Lebih Kecil (7cm width):**

```css
.container {
    max-width: 280px;
}
.info-label {
    width: 45px;
}
.col-amount {
    width: 60px;
}
```

#### **Untuk Printer Thermal:**

```css
body {
    font-size: 8px;
}
.separator {
    font-size: 6px;
}
```

### 📝 **Usage:**

#### **Single Print:**

```
GET /faktur/pembayaran/{id}
```

#### **Multiple Print:**

```
POST /faktur/multiple-print
Body: { ids: [1,2,3] }
```

### ⚠️ **Print Tips:**

1. **Test Print**: Selalu test dengan 1 faktur dulu
2. **Paper Alignment**: Pastikan kertas center-aligned
3. **Font Check**: Pastikan Courier New tersedia di printer
4. **Margin Setting**: Gunakan minimum margin untuk hemat kertas
5. **Page Break**: Setiap faktur otomatis page break

### 📞 **Troubleshooting:**

#### **Teks terpotong:**

-   Kurangi font size dari 10px ke 9px
-   Adjust margin dari 0.5cm ke 0.3cm

#### **Layout berantakan:**

-   Pastikan printer support monospace font
-   Check paper width setting

#### **Multiple faktur overlap:**

-   Pastikan page-break-after: always aktif
-   Check printer setting untuk auto form feed

**Format ini dirancang khusus untuk efisiensi kertas dan kecepatan printing sambil tetap mempertahankan semua informasi penting!** 🎯
