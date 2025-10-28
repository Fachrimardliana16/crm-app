window.printMultipleFaktur = function(routeUrl, csrfToken, ids) {
    try {
        const existingForm = document.getElementById('multiplePrintForm');
        if (existingForm) {
            existingForm.remove();
        }
        const form = document.createElement('form');
        form.id = 'multiplePrintForm';
        form.method = 'POST';
        form.action = routeUrl;
        form.target = '_blank';
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        ids.forEach(function(id) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        document.body.appendChild(form);
        form.submit();
    } catch (error) {
        console.error('Error printing multiple faktur:', error);
        alert('Terjadi kesalahan saat membuka faktur. Silakan coba lagi.');
    }
};
