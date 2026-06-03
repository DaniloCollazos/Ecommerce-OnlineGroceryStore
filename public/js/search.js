/**
 * Search — AnimaMarket
 * Búsqueda con debounce para evitar peticiones excesivas mientras el usuario escribe.
 */

document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.querySelector('input[name="q"]');

    if(!searchInput) return;

    let debounceTimer = null;

    /**
     * Espera 400ms después del último keystroke
     * antes de enviar el formulario de búsqueda.
     * Evita una petición por cada tecla presionada.
     */
    searchInput.addEventListener('input', () => {

        clearTimeout(debounceTimer);

        const query = searchInput.value.trim();

        // No buscar si hay menos de 2 caracteres
        if(query.length < 2) return;

        debounceTimer = setTimeout(() => {
            // Enviar el formulario padre automáticamente
            const form = searchInput.closest('form');
            if(form) form.submit();
        }, 400);
    });

    // Limpiar búsqueda con Escape
    searchInput.addEventListener('keydown', (e) => {
        if(e.key === 'Escape'){
            searchInput.value = '';
            clearTimeout(debounceTimer);
        }
    });

});