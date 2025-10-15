<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header dengan sambutan --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Selamat Datang, {{ auth()->user()->name }}!
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Dashboard CRM PDAM - Sistem Manajemen Pelanggan
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ now()->format('H:i') }} WIB
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Widget statistik akan dirender di sini --}}
        <div class="grid gap-6">
            {{-- Widgets akan di-render oleh Filament secara otomatis --}}
        </div>

        {{-- Quick Actions --}}
        <div
            class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header flex items-center gap-x-3 overflow-hidden px-6 py-4">
                <div class="grid flex-1">
                    <h3
                        class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Aksi Cepat
                    </h3>
                    <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                        Shortcut untuk aktivitas umum
                    </p>
                </div>
            </div>
            <div class="fi-section-content p-6 pt-0">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <a href="{{ route('filament.admin.resources.pendaftarans.create') }}"
                        class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-600 dark:bg-gray-800">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400">
                            <x-heroicon-o-document-plus class="h-5 w-5" />
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Pendaftaran Baru</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tambah pelanggan</p>
                        </div>
                    </a>

                    <a href="{{ route('filament.admin.resources.surveis.create') }}"
                        class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-600 dark:bg-gray-800">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-400">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Survei Baru</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Input survei</p>
                        </div>
                    </a>

                    <a href="{{ route('filament.admin.resources.pengaduans.index') }}"
                        class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-600 dark:bg-gray-800">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-400">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5" />
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Kelola Pengaduan</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Lihat pengaduan</p>
                        </div>
                    </a>

                    <a href="{{ route('filament.admin.resources.tagihan-bulanans.index') }}"
                        class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-600 dark:bg-gray-800">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-400">
                            <x-heroicon-o-banknotes class="h-5 w-5" />
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Kelola Tagihan</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Lihat tagihan</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
