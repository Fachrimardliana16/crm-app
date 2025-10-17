gitGraph
commit id: "init"
branch develop
checkout develop
commit id: "setup project base"

    %% === MASTER MODULES ===
    branch feature/master-pendaftaran
    commit id: "create master pendaftaran"
    checkout develop
    merge feature/master-pendaftaran id: "merge master pendaftaran"

    branch feature/master-survey
    commit id: "create master survey"
    checkout develop
    merge feature/master-survey id: "merge master survey"

    branch feature/master-rab
    commit id: "create master RAB"
    checkout develop
    merge feature/master-rab id: "merge master RAB"

    branch feature/master-pelanggan
    commit id: "create master pelanggan"
    checkout develop
    merge feature/master-pelanggan id: "merge master pelanggan"

    branch feature/master-pengaduan
    commit id: "create master pengaduan"
    checkout develop
    merge feature/master-pengaduan id: "merge master pengaduan"

    branch feature/master-balik-nama
    commit id: "create master balik nama"
    checkout develop
    merge feature/master-balik-nama id: "merge master balik nama"

    branch feature/master-penutupan
    commit id: "create master penutupan"
    checkout develop
    merge feature/master-penutupan id: "merge master penutupan"

    branch feature/master-tangki
    commit id: "create master pembelian tangki"
    checkout develop
    merge feature/master-tangki id: "merge master tangki"

    branch feature/master-baca-meter
    commit id: "create master baca meter"
    checkout develop
    merge feature/master-baca-meter id: "merge master baca meter"

    branch feature/master-rekening
    commit id: "create master rekening"
    checkout develop
    merge feature/master-rekening id: "merge master rekening"

    branch feature/master-transaksi
    commit id: "create master transaksi"
    checkout develop
    merge feature/master-transaksi id: "merge master transaksi"

    %% === FUNCTIONAL MODULES ===
    branch feature/pendaftaran
    commit id: "implementasi alur pendaftaran pelanggan"
    checkout develop
    merge feature/pendaftaran id: "merge pendaftaran"

    branch feature/survey
    commit id: "implementasi alur survey pelanggan"
    checkout develop
    merge feature/survey id: "merge survey"

    branch feature/rab
    commit id: "implementasi RAB & validasi"
    checkout develop
    merge feature/rab id: "merge RAB"

    branch feature/pemasangan
    commit id: "implementasi pemasangan dan validasi GIS"
    checkout develop
    merge feature/pemasangan id: "merge pemasangan"

    branch feature/pembacaan
    commit id: "implementasi baca meter"
    checkout develop
    merge feature/pembacaan id: "merge baca meter"

    branch feature/rekening
    commit id: "implementasi penghitungan rekening"
    checkout develop
    merge feature/rekening id: "merge rekening"

    branch feature/pembayaran
    commit id: "implementasi API pembayaran & validasi"
    checkout develop
    merge feature/pembayaran id: "merge pembayaran"

    branch feature/penagihan
    commit id: "implementasi alur penagihan & loket"
    checkout develop
    merge feature/penagihan id: "merge penagihan"

    branch feature/pengaduan
    commit id: "implementasi alur pengaduan terpusat"
    checkout develop
    merge feature/pengaduan id: "merge pengaduan"

    branch feature/layanan-khusus
    commit id: "implementasi layanan khusus (balik nama, tangki, dll)"
    checkout develop
    merge feature/layanan-khusus id: "merge layanan khusus"

    %% === FIX MODULES (PER MODUL) ===
    branch fix/pembacaan-validasi-meter
    commit id: "fix error validasi pembacaan meter"
    checkout develop
    merge fix/pembacaan-validasi-meter id: "merge fix pembacaan"

    branch fix/rekening-kalkulasi
    commit id: "fix kesalahan kalkulasi rekening"
    checkout develop
    merge fix/rekening-kalkulasi id: "merge fix rekening"

    branch fix/pengaduan-respons-time
    commit id: "optimalkan waktu respons pengaduan"
    checkout develop
    merge fix/pengaduan-respons-time id: "merge fix pengaduan"

    %% === RELEASE STAGING ===
    branch release/v1.0
    commit id: "final testing & staging build"
    checkout develop
    merge release/v1.0 id: "merge release preparation"

    %% === PRODUCTION RELEASE ===
    checkout main
    merge develop id: "release v1.0 production"
    commit id: "tag: v1.0.0"
