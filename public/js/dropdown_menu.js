document.addEventListener('DOMContentLoaded', () => {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    // Si no hay dropdowns, salir
    if (dropdowns.length === 0) return;

    // Aplicar event listeners a CADA dropdown
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        
        if (!toggle) return;

        // Click en el botón toggle para abrir/cerrar
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();

            const isOpen = dropdown.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen);

            // Cerrar otros dropdowns abiertos
            dropdowns.forEach(other => {
                if (other !== dropdown) {
                    other.classList.remove('open');
                    const otherToggle = other.querySelector('.dropdown-toggle');
                    if (otherToggle) {
                        otherToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        });
    });

    // Cerrar todos los dropdowns si haces click fuera
    document.addEventListener('click', (e) => {
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                const toggle = dropdown.querySelector('.dropdown-toggle');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    });

});