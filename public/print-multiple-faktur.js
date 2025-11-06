/**
 * Function untuk print multiple faktur
 * Optimized untuk ukuran 1/2 kertas A4
 * @param {string} routeUrl - URL endpoint untuk print multiple
 * @param {string} csrfToken - CSRF token untuk keamanan
 * @param {array} ids - Array ID pendaftaran untuk dicetak
 */
window.printMultipleFaktur = function (routeUrl, csrfToken, ids) {
    try {
        // Remove existing form jika ada
        const existingForm = document.getElementById('multiplePrintForm');
        if (existingForm) {
            existingForm.remove();
        }

        // Create form untuk POST request
        const form = document.createElement('form');
        form.id = 'multiplePrintForm';
        form.method = 'POST';
        form.action = routeUrl;
        form.target = '_blank'; // Buka di tab baru

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        // Add IDs sebagai array
        ids.forEach(function (id) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

        // Append form ke body dan submit
        document.body.appendChild(form);
        form.submit();

        // Optional: Remove form setelah submit
        setTimeout(() => {
            if (document.getElementById('multiplePrintForm')) {
                document.getElementById('multiplePrintForm').remove();
            }
        }, 1000);

    } catch (error) {
        console.error('Error printing multiple faktur:', error);
        alert('Terjadi kesalahan saat membuka faktur. Silakan coba lagi.');
    }
};
