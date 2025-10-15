<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-blue-900">{{ static::$title }}</h1>
                    <p class="text-sm text-blue-700">Sistem pembayaran rekening air, pendaftaran, dan layanan lainnya</p>
                </div>
            </div>
        </div>

        {{ $this->form }}

        @if($showRekeningData && !empty($pelangganData))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Detail Rekening Card -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center space-x-2 mb-4">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Detail Rekening</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-gray-700 font-medium">Deskripsi</th>
                                <th class="px-3 py-2 text-right text-gray-700 font-medium">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-3 py-2 text-gray-600">Nama Pelanggan</td>
                                <td class="px-3 py-2 text-right font-medium">{{ $pelangganData['nama_pelanggan'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-600">Nomor Pelanggan</td>
                                <td class="px-3 py-2 text-right font-medium">{{ $pelangganData['nomor_pelanggan'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-600">Alamat</td>
                                <td class="px-3 py-2 text-right">{{ $pelangganData['alamat'] ?? '-' }}</td>
                            </tr>
                            @if(!empty($tagihanData))
                            <tr>
                                <td class="px-3 py-2 text-gray-600">Periode Tagihan</td>
                                <td class="px-3 py-2 text-right">{{ $tagihanData['periode_tagihan'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-600">Pemakaian Air</td>
                                <td class="px-3 py-2 text-right">{{ $tagihanData['pemakaian_air'] ?? 0 }} m³</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-3 py-2 font-semibold text-gray-800">Rincian Tagihan:</td>
                                <td class="px-3 py-2"></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-600">Biaya Pemakaian</td>
                                <td class="px-3 py-2 text-right">Rp {{ number_format($tagihanData['biaya_pemakaian'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-600">Biaya Administrasi</td>
                                <td class="px-3 py-2 text-right">Rp {{ number_format($tagihanData['biaya_administrasi'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-600">Biaya Beban</td>
                                <td class="px-3 py-2 text-right">Rp {{ number_format($tagihanData['biaya_beban'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @if(($tagihanData['biaya_denda'] ?? 0) > 0)
                            <tr>
                                <td class="px-3 py-2 text-red-600">Biaya Denda</td>
                                <td class="px-3 py-2 text-right text-red-600">Rp {{ number_format($tagihanData['biaya_denda'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr class="bg-green-50 border-t-2">
                                <td class="px-3 py-2 font-bold text-gray-800">Total Tagihan</td>
                                <td class="px-3 py-2 text-right font-bold text-green-600">Rp {{ number_format($tagihanData['total_tagihan'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Summary Card -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center space-x-2 mb-4">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Ringkasan Pembayaran</h3>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Metode Pembayaran:</span>
                        <span class="font-medium uppercase">{{ $data['metode_pembayaran'] ?? '-' }}</span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Jenis Pembayaran:</span>
                        <span class="font-medium">{{ $data['jenis_pembayaran_type'] === 'semua' ? 'Bayar Semua' : 'Bayar Sebagian' }}</span>
                    </div>

                    @php
                        $jumlahBayar = ($data['jenis_pembayaran_type'] ?? '') === 'semua' 
                            ? ($data['total_tagihan'] ?? 0)
                            : ($data['jumlah_bayar'] ?? 0);
                    @endphp

                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Jumlah Bayar:</span>
                        <span class="font-bold text-blue-600">Rp {{ number_format($jumlahBayar, 0, ',', '.') }}</span>
                    </div>

                    @if(($data['metode_pembayaran'] ?? '') === 'cash')
                        @php
                            $uangDiterima = $data['uang_diterima'] ?? 0;
                            $kembalian = max(0, $uangDiterima - $jumlahBayar);
                        @endphp
                        
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Uang Diterima:</span>
                            <span class="font-medium">Rp {{ number_format($uangDiterima, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Kembalian:</span>
                            <span class="font-bold {{ $kembalian >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($kembalian, 0, ',', '.') }}
                                @if($kembalian < 0) (Kurang) @endif
                            </span>
                        </div>
                    @endif

                    @if(($data['total_tagihan'] ?? 0) > $jumlahBayar)
                        @php
                            $sisaTagihan = ($data['total_tagihan'] ?? 0) - $jumlahBayar;
                        @endphp
                        <div class="flex justify-between py-2 border-b bg-yellow-50 px-3 rounded">
                            <span class="text-gray-600">Sisa Tagihan:</span>
                            <span class="font-bold text-yellow-600">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                @if(($data['metode_pembayaran'] ?? '') !== 'cash')
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Pembayaran {{ strtoupper($data['metode_pembayaran'] ?? '') }}</p>
                            <p class="text-xs text-blue-600">Hubungi petugas untuk melanjutkan transaksi</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <style>
        .fi-section-content-ctn {
            @apply space-y-6;
        }
        
        .fi-fo-placeholder .fi-fo-placeholder-label {
            @apply sr-only;
        }
        
        .fi-fo-placeholder .fi-fo-placeholder-content {
            @apply text-sm leading-relaxed;
        }

        /* Custom styling for radio buttons */
        .fi-fo-radio .fi-fo-radio-option {
            @apply p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors;
        }
        
        .fi-fo-radio .fi-fo-radio-option:has(input:checked) {
            @apply bg-blue-50 border-blue-300;
        }
    </style>
</x-filament-panels::page>
