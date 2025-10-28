# Perbaikan Precision Latitude & Longitude di SurveiResource

## Masalah
Field latitude dan longitude di Survei Resource tidak dapat menerima input precision tinggi seperti `-7,390130990122515`. Database hanya mendukung 8 digit di belakang koma berdasarkan struktur `decimal(10, 8)` untuk latitude dan `decimal(11, 8)` untuk longitude.

## Solusi yang Diterapkan

### 1. Form Field Improvements (SurveiResource.php)

#### Map Picker (LeafletMapPicker)
- **Perubahan**: Menambahkan `round((float) $state['lat'], 8)` dan `round((float) $state['lng'], 8)` di `afterStateUpdated`
- **Tujuan**: Memastikan koordinat dari map picker diformat dengan presisi 8 digit di belakang koma

#### Input Field Latitude & Longitude
- **Validasi Tambahan**: 
  - Custom validation untuk memastikan presisi maksimal 8 digit di belakang koma
  - Validasi range yang benar: latitude (-90 sampai 90), longitude (-180 sampai 180)
- **Helper Text**: Ditambahkan keterangan "maksimal 8 digit di belakang koma"
- **Auto-formatting**: Input otomatis di-format dengan `round()` setelah user mengetik

### 2. Model Mutators (Survei.php)

```php
public function setLatitudeTerverifikasiAttribute($value)
{
    $this->attributes['latitude_terverifikasi'] = $value !== null ? round((float) $value, 8) : null;
}

public function setLongitudeTerverifikasiAttribute($value)
{
    $this->attributes['longitude_terverifikasi'] = $value !== null ? round((float) $value, 8) : null;
}
```

- **Tujuan**: Memastikan data selalu tersimpan dengan presisi yang tepat di level model
- **Benefit**: Konsistensi data meskipun ada input dari source lain (API, import, dll)

### 3. Database Casting (sudah ada)

```php
protected $casts = [
    'latitude_terverifikasi' => 'decimal:8',
    'longitude_terverifikasi' => 'decimal:8',
    // ...
];
```

## Struktur Database

```sql
-- Dari migration 2025_10_09_145521_create_operational_workflow_tables.php
$table->decimal('latitude_terverifikasi', 10, 8)->nullable();   -- Total 10 digit, 8 di belakang koma
$table->decimal('longitude_terverifikasi', 11, 8)->nullable();  -- Total 11 digit, 8 di belakang koma
```

## Testing Results

### Input Test
```
Original input:
Lat: -7.390130990122515
Lng: 109.358398123456789

After processing:
Lat: -7.39013099      (8 digit di belakang koma)
Lng: 109.35839812     (8 digit di belakang koma)
```

## Benefits

1. **Data Consistency**: Semua koordinat disimpan dengan presisi yang sama
2. **Database Compatibility**: Input sesuai dengan constraint database
3. **User Experience**: User mendapat feedback jelas tentang format yang didukung
4. **Error Prevention**: Validasi mencegah input yang tidak valid
5. **Performance**: Presisi 8 digit sudah sangat akurat untuk mapping (akurasi ~1.1 meter)

## Precision Context

- **8 decimal places** = akurasi sekitar **1.1 meter**
- **6 decimal places** = akurasi sekitar **111 meter** 
- **10 decimal places** = akurasi sekitar **11 sentimeter**

Untuk aplikasi PDAM, presisi 8 digit (1.1 meter) sudah sangat memadai untuk keperluan mapping dan navigasi.