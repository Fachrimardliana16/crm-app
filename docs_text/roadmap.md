gantt
title Roadmap Pengembangan CRM PDAM (Kolaborasi 2 Developer)
dateFormat YYYY-MM-DD
axisFormat %d %b
excludes weekends

    %% ===============================
    section Phase 1 - Foundation & Master Data (v0.1)
    Dev A - Setup Environment & Auth                :a1, 2025-10-01, 5d
    Dev B - Database & Role Access Setup            :b1, 2025-10-01, 5d
    Dev A - Master Data Pendaftaran                 :a2, after a1, 5d
    Dev B - Master Data Survey                      :b2, after b1, 5d
    Dev A - Master RAB                              :a3, after a2, 5d
    Dev B - Master Pelanggan                        :b3, after b2, 5d
    Integration & Testing                           :a4, after a3, 3d
    Milestone v0.1 Alpha Release                    :milestone, m1, after a4, 1d

    %% ===============================
    section Phase 2 - Registration to Installation (v0.2)
    Dev A - Modul Pendaftaran                       :a5, 2025-10-22, 5d
    Dev B - Modul Survey Lapangan                   :b5, 2025-10-22, 5d
    Dev A - RAB & Persetujuan                       :a6, after a5, 5d
    Dev B - Instalasi Teknis                        :b6, after b5, 5d
    Dev A - Pengaktifan Pelanggan Baru              :a7, after a6, 3d
    Dev B - Validasi Data Pelanggan                 :b7, after b6, 3d
    Milestone v0.2 Beta Release                     :milestone, m2, after a7, 1d

    %% ===============================
    section Phase 3 - Meter Reading & Billing (v0.3)
    Dev A - Baca Meter & Validasi Anomali           :a8, 2025-11-19, 5d
    Dev B - Billing Calculation                     :b8, 2025-11-19, 5d
    Dev A - Integrasi Pembayaran                    :a9, after a8, 4d
    Dev B - Rekapitulasi Transaksi                  :b9, after b8, 4d
    Dev A - Audit & Pelaporan                       :a10, after a9, 3d
    Dev B - Dashboard Rekening                      :b10, after b9, 3d
    Milestone v0.3 Billing Release                  :milestone, m3, after a10, 1d

    %% ===============================
    section Phase 4 - Collection & Finance (v0.4)
    Dev A - Penagihan & LPP                         :a11, 2025-12-17, 5d
    Dev B - Kasir & Rekonsiliasi                    :b11, 2025-12-17, 5d
    Dev A - Laporan Harian & Bulanan                :a12, after a11, 5d
    Dev B - Dashboard Keuangan                      :b12, after b11, 5d
    Milestone v0.4 Finance Release                  :milestone, m4, after a12, 1d

    %% ===============================
    section Phase 5 - Layanan & Pengaduan (v0.5–v0.7)
    Dev A - Pengaduan Terpusat                      :a13, 2026-01-07, 7d
    Dev B - Balik Nama                              :b13, 2026-01-07, 7d
    Dev A - Penutupan & Reaktivasi Pelanggan        :a14, after a13, 5d
    Dev B - Pembelian Tangki & Pelacakan            :b14, after b13, 5d
    Dev A - KPI dan Notifikasi                      :a15, after a14, 3d
    Dev B - Dashboard Pelayanan                     :b15, after b14, 3d
    Milestone v0.7 Customer Service Release         :milestone, m5, after a15, 1d

    %% ===============================
    section Phase 6 - QA & Production (v1.0)
    Dev A - QA & Audit Sistem                       :a16, 2026-01-28, 5d
    Dev B - UAT & Review Tampilan                   :b16, 2026-01-28, 5d
    Dev A - Bug Fixing & Security Review            :a17, after a16, 5d
    Dev B - Dokumentasi & Pelatihan User            :b17, after b16, 5d
    Deployment to Production                        :a18, after a17, 3d
    Milestone v1.0 Production Release               :milestone, m6, after a18, 1d
