document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('[data-image-input]');
    const preview = document.querySelector('[data-image-preview]');
    if (!input || !preview) return;
    input.addEventListener('change', () => {
        const file = input.files && input.files[0];
        if (!file) { preview.src = ''; preview.classList.add('d-none'); return; }
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    });
});
