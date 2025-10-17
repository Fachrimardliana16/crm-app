# Git Branch Structure - CRM TP

Branch structure yang telah dibuat sesuai dengan workflow `workflow_branch.md`:

## Main Branches

-   `main` - Production branch
-   `develop` - Development integration branch

## Master Modules (Data Master)

-   `feature/master-pendaftaran` - Master data pendaftaran
-   `feature/master-survey` - Master data survey
-   `feature/master-rab` - Master data RAB (Rencana Anggaran Biaya)
-   `feature/master-pelanggan` - Master data pelanggan
-   `feature/master-pengaduan` - Master data pengaduan
-   `feature/master-balik-nama` - Master data balik nama
-   `feature/master-penutupan` - Master data penutupan
-   `feature/master-tangki` - Master data pembelian tangki
-   `feature/master-baca-meter` - Master data baca meter
-   `feature/master-rekening` - Master data rekening
-   `feature/master-transaksi` - Master data transaksi

## Functional Modules (Business Logic)

-   `feature/pendaftaran` - Implementasi alur pendaftaran pelanggan
-   `feature/survey` - Implementasi alur survey pelanggan
-   `feature/rab` - Implementasi RAB & validasi
-   `feature/pemasangan` - Implementasi pemasangan dan validasi GIS
-   `feature/pembacaan` - Implementasi baca meter
-   `feature/rekening` - Implementasi penghitungan rekening
-   `feature/pembayaran` - Implementasi API pembayaran & validasi
-   `feature/penagihan` - Implementasi alur penagihan & loket
-   `feature/pengaduan` - Implementasi alur pengaduan terpusat
-   `feature/layanan-khusus` - Implementasi layanan khusus (balik nama, tangki, dll)

## Fix Modules (Bug Fixes)

-   `fix/pembacaan-validasi-meter` - Fix error validasi pembacaan meter
-   `fix/rekening-kalkulasi` - Fix kesalahan kalkulasi rekening
-   `fix/pengaduan-respons-time` - Optimalkan waktu respons pengaduan

## Release Branch

-   `release/v1.0` - Final testing & staging build

## Workflow Guidelines

### 1. Master Modules Development

```bash
git checkout develop
git checkout -b feature/master-[module-name]
# Develop master data module
git add .
git commit -m "create master [module-name]"
git push origin feature/master-[module-name]
# Create PR to develop
```

### 2. Functional Modules Development

```bash
git checkout develop
git checkout -b feature/[module-name]
# Develop business logic
git add .
git commit -m "implementasi [module description]"
git push origin feature/[module-name]
# Create PR to develop
```

### 3. Bug Fixes

```bash
git checkout develop
git checkout -b fix/[issue-description]
# Fix the issue
git add .
git commit -m "fix [issue description]"
git push origin fix/[issue-description]
# Create PR to develop
```

### 4. Release Preparation

```bash
git checkout develop
git checkout -b release/v[version]
# Final testing and preparation
git add .
git commit -m "final testing & staging build"
git push origin release/v[version]
# Create PR to main for production release
```

### 5. Production Release

```bash
git checkout main
git merge develop
git tag v[version]
git push origin main --tags
```

## Branch Protection Rules (Recommended)

### Main Branch

-   Require pull request reviews
-   Require status checks to pass
-   Restrict pushes to main branch
-   Include administrators

### Develop Branch

-   Require pull request reviews
-   Require status checks to pass
-   Allow merge commits

## Created: October 17, 2025

## Total Branches: 26 branches

All branches have been successfully created and pushed to GitHub repository.
