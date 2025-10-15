<x-filament-panels::page>
    <div class="w-full max-w-7xl mx-auto space-y-6 px-4">
        <!-- Form Content -->
        <div class="overflow-hidden">
            <div class="p-6">
                {{ $this->form }}
            </div>
        </div>
    </div>

    <style>
        /* ===== CONTAINER & LAYOUT ===== */
        .fi-main {
            @apply max-w-none w-full px-4;
        }

        /* ===== SECTION STYLING ===== */
        .fi-section {
            @apply border-0 shadow-none bg-transparent mb-6;
        }

        .fi-section-header {
            @apply pb-4 mb-6 border-b-2 border-gray-200;
        }

        .fi-section-header-heading {
            @apply text-xl font-bold text-gray-800 flex items-center gap-3;
        }

        .fi-section-content {
            @apply pt-0 bg-transparent;
        }

        /* Remove all white backgrounds */
        .fi-main-content {
            @apply bg-transparent;
        }

        .fi-fo-section {
            @apply bg-transparent;
        }

        /* ===== FORM GRID & LAYOUT ===== */
        .fi-fo-grid {
            @apply w-full gap-6;
        }

        .fi-fo-grid-col {
            @apply space-y-4;
        }

        /* ===== INPUT STYLING ===== */
        .fi-fo-text-input input,
        .fi-fo-textarea textarea,
        .fi-fo-select select {
            @apply text-base border-2 border-gray-300 rounded-lg px-4 py-3 transition-all duration-200;
        }

        .fi-fo-text-input input:focus,
        .fi-fo-textarea textarea:focus,
        .fi-fo-select select:focus {
            @apply ring-2 ring-blue-500 border-blue-500 outline-none;
        }

        /* ===== SEARCH SECTION LAYOUT ===== */
        .fi-section:has(.fi-fo-text-input input[placeholder*="Ketik nomor"]) {
            @apply bg-transparent border-0 rounded-xl p-6 mb-6;
        }

        .fi-section:has(.fi-fo-text-input input[placeholder*="Ketik nomor"]) .fi-section-header {
            @apply border-gray-300 mb-4;
        }

        .fi-section:has(.fi-fo-text-input input[placeholder*="Ketik nomor"]) .fi-section-header-heading {
            @apply text-gray-800;
        }

        /* ===== SEARCH INPUT SPECIAL STYLING ===== */
        .fi-fo-text-input:has(input[placeholder*="Ketik nomor"]) {
            @apply w-full;
        }

        .fi-fo-text-input:has(input[placeholder*="Ketik nomor"]) input {
            @apply text-lg py-4 font-semibold w-full border-2 border-blue-400 bg-white rounded-xl shadow-sm;
        }

        .fi-fo-text-input:has(input[placeholder*="Ketik nomor"]) input:focus {
            @apply ring-4 ring-blue-200 border-blue-600 bg-white shadow-md;
        }

        /* Suffix action styling */
        .fi-fo-text-input:has(input[placeholder*="Ketik nomor"]) .fi-input-wrp-suffix {
            @apply mr-2;
        }

        .fi-fo-text-input:has(input[placeholder*="Ketik nomor"]) .fi-input-wrp-suffix button {
            @apply bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-2 transition-colors duration-200;
        }

        /* ===== SEARCH BUTTONS LAYOUT ===== */
        /* (Styling moved to BUTTON STYLING section) */

        /* ===== SEARCH BUTTONS LAYOUT ===== */
        .fi-ac-actions {
            @apply flex gap-3 mt-4;
        }

        .fi-ac-action {
            @apply transition-all duration-300 rounded-xl px-6 py-3 font-semibold shadow-md border-0;
        }

        .fi-ac-action:hover {
            @apply transform scale-105 shadow-lg;
        }

        /* Search buttons styling */
        .fi-ac-action[data-action="cariPelanggan"] {
            @apply bg-blue-600 text-white hover:bg-blue-700 flex-1;
        }

        .fi-ac-action[wire\\:click*="cariPelanggan"] {
            @apply bg-blue-600 text-white hover:bg-blue-700 flex-1;
        }

        /* Modal button styling */
        .fi-ac-action[data-action*="cari"] {
            @apply bg-gray-600 text-white hover:bg-gray-700 flex-1;
        }

        /* ===== RADIO BUTTON STYLING ===== */
        .fi-fo-radio {
            @apply space-y-3;
        }

        .fi-fo-radio .fi-fo-radio-option {
            @apply p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 shadow-sm;
        }

        .fi-fo-radio .fi-fo-radio-option:has(input:checked) {
            @apply border-blue-600 bg-blue-100 ring-2 ring-blue-300 shadow-md;
        }

        .fi-fo-radio .fi-fo-radio-option-label {
            @apply font-semibold text-base text-gray-700;
        }

        /* ===== PAYMENT METHOD COLORS ===== */
        .fi-fo-radio:has(input[value="cash"]) .fi-fo-radio-option:has(input:checked) {
            @apply border-green-600 bg-green-100 ring-green-300;
        }

        .fi-fo-radio:has(input[value="qris"]) .fi-fo-radio-option:has(input:checked) {
            @apply border-purple-600 bg-purple-100 ring-purple-300;
        }

        .fi-fo-radio:has(input[value="debit"]) .fi-fo-radio-option:has(input:checked) {
            @apply border-yellow-600 bg-yellow-100 ring-yellow-300;
        }

        .fi-fo-radio:has(input[value="credit"]) .fi-fo-radio-option:has(input:checked) {
            @apply border-indigo-600 bg-indigo-100 ring-indigo-300;
        }

        /* ===== GENERAL BUTTON STYLING ===== */
        .fi-ac-action:hover {
            @apply transform scale-105 shadow-lg;
        }

        /* Search button styling */
        .fi-ac-action[data-action="cariManual"] {
            @apply bg-blue-600 text-white hover:bg-blue-700;
        }

        .fi-ac-action[wire\\:click*="cariManual"] {
            @apply bg-blue-600 text-white hover:bg-blue-700;
        }

        /* Payment process button */
        .fi-ac-action[data-action="prosesTransaksi"] {
            @apply bg-gradient-to-r from-green-600 to-green-700 text-white font-bold text-lg py-4 px-8 rounded-xl shadow-xl hover:shadow-2xl hover:from-green-700 hover:to-green-800 w-full mt-6;
        }

        /* ===== PLACEHOLDER CONTENT ===== */
        .fi-fo-placeholder-content {
            @apply text-sm leading-relaxed text-gray-600 bg-gray-50 p-4 rounded-lg border border-gray-200;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .fi-main {
                @apply px-2;
            }

            .fi-fo-grid {
                @apply gap-4;
            }

            .fi-fo-text-input:has(input[placeholder*="Ketik nomor"]) input {
                @apply text-base py-3;
            }

            .fi-fo-radio .fi-fo-radio-option {
                @apply p-3;
            }

            .fi-fo-radio .fi-fo-radio-option-label {
                @apply text-sm;
            }

            .fi-ac-action[data-action="prosesTransaksi"] {
                @apply text-base py-3 px-6;
            }

            .fi-ac-action[data-action="prosesTransaksi"] {
                @apply text-base py-3 px-6;
            }
        }

        /* ===== SPACING & LAYOUT FIXES ===== */
        .fi-section:first-child {
            @apply mt-0;
        }

        .fi-section:last-child {
            @apply mb-0;
        }

        .fi-fo-field-wrp {
            @apply mb-4;
        }

        .fi-fo-field-wrp-label {
            @apply mb-2 font-semibold text-gray-700;
        }

        /* ===== ERROR STATE STYLING ===== */
        .fi-fo-field-wrp:has(.fi-fo-field-wrp-error-message) input,
        .fi-fo-field-wrp:has(.fi-fo-field-wrp-error-message) select {
            @apply border-red-500 ring-red-200;
        }

        .fi-fo-field-wrp-error-message {
            @apply text-red-600 text-sm mt-1;
        }

        /* Loading state */
        .fi-loading {
            @apply opacity-50 pointer-events-none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Error handling for network issues
            window.addEventListener('online', function() {
                console.log('Connection restored');
            });

            window.addEventListener('offline', function() {
                alert('Koneksi internet terputus. Pastikan koneksi stabil sebelum melakukan transaksi.');
            });

            // Form validation helpers
            function validateForm() {
                const errors = [];

                // Check if customer data is loaded
                const customerInfo = document.querySelector('[data-customer-info]');
                if (!customerInfo || !customerInfo.textContent.includes('No. Pelanggan')) {
                    errors.push('Silakan pilih pelanggan terlebih dahulu');
                }

                // Check payment method
                const paymentMethod = document.querySelector('input[name="metode_pembayaran"]:checked');
                if (!paymentMethod) {
                    errors.push('Silakan pilih metode pembayaran');
                }

                return errors;
            }

            // Add validation before form submission
            document.addEventListener('submit', function(e) {
                const errors = validateForm();
                if (errors.length > 0) {
                    e.preventDefault();
                    alert('Error:\n' + errors.join('\n'));
                }
            });

            // Auto-save form data to localStorage (untuk recovery)
            const formInputs = document.querySelectorAll('input, select');
            formInputs.forEach(input => {
                input.addEventListener('change', function() {
                    try {
                        localStorage.setItem('pembayaran_form_' + this.name, this.value);
                    } catch (e) {
                        console.warn('LocalStorage tidak tersedia');
                    }
                });
            });

            // Restore form data on page load
            formInputs.forEach(input => {
                try {
                    const savedValue = localStorage.getItem('pembayaran_form_' + input.name);
                    if (savedValue && !input.value) {
                        input.value = savedValue;
                    }
                } catch (e) {
                    console.warn('Gagal memuat data tersimpan');
                }
            });

            // Clear saved data after successful transaction
            window.addEventListener('transaction-success', function() {
                try {
                    Object.keys(localStorage).forEach(key => {
                        if (key.startsWith('pembayaran_form_')) {
                            localStorage.removeItem(key);
                        }
                    });
                } catch (e) {
                    console.warn('Gagal membersihkan data tersimpan');
                }
            });
        });
    </script>
</x-filament-panels::page>
