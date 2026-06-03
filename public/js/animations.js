/**
 * animations.js — AnimaMarket
 * Animaciones avanzadas que requieren JS puro.
 */

document.addEventListener('DOMContentLoaded', () => {

    // ══════════════════════════════════════════════
    // 1. ENTRADA ESCALONADA DE TARJETAS
    // Cada tarjeta de producto entra con un delay
    // progresivo para dar sensación de cascada
    // ══════════════════════════════════════════════
    const cards = document.querySelectorAll('.card-product');

    cards.forEach((card, index) => {
        card.style.opacity    = '0';
        card.style.transform  = 'translateY(24px)';
        card.style.transition = `opacity 0.4s ease ${index * 0.06}s,
                                  transform 0.4s ease ${index * 0.06}s`;

        // Pequeño timeout para que el navegador registre
        // el estado inicial antes de animar
        setTimeout(() => {
            card.style.opacity   = '1';
            card.style.transform = 'translateY(0)';
        }, 50);
    });

    // ══════════════════════════════════════════════
    // 2. PARALLAX SUAVE EN CARRUSEL
    // El carrusel de portada se desplaza a la mitad
    // de la velocidad del scroll para efecto parallax
    // ══════════════════════════════════════════════
    const carousel = document.querySelector('.container-carousel');

    if(carousel){
        window.addEventListener('scroll', () => {
            const scrollY = window.pageYOffset;
            // Solo aplica parallax si el carrusel es visible
            if(scrollY < carousel.offsetHeight + 100){
                carousel.style.transform = `translateY(${scrollY * 0.3}px)`;
            }
        }, { passive: true });
    }

    // ══════════════════════════════════════════════
    // 3. CONFETI AL CONFIRMAR PEDIDO
    // Se activa si la URL tiene ?pedido=confirmado
    // Genera piezas de confeti con colores de la paleta
    // ══════════════════════════════════════════════
    const urlParams = new URLSearchParams(window.location.search);

    if(urlParams.get('pedido') === 'confirmado'){
        launchConfetti();
    }

    function launchConfetti(){

        // Colores de la paleta AnimaMarket
        const colors = [
            '#d85c32', // accent-orange
            '#d58d51', // warm-tan
            '#311b14', // primary-dark
            '#ffffff', // white
            '#2e7d32', // success
        ];

        const totalPieces = 80;

        for(let i = 0; i < totalPieces; i++){

            const piece = document.createElement('div');
            piece.classList.add('confetti-piece');

            // Posición horizontal aleatoria
            piece.style.left = Math.random() * 100 + 'vw';

            // Tamaño aleatorio
            const size = Math.random() * 10 + 6;
            piece.style.width  = size + 'px';
            piece.style.height = size + 'px';

            // Color aleatorio de la paleta
            piece.style.background = colors[Math.floor(Math.random() * colors.length)];

            // Forma aleatoria (cuadrado o círculo)
            piece.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';

            // Delay aleatorio para que no caigan todos a la vez
            piece.style.animationDelay    = Math.random() * 2 + 's';
            piece.style.animationDuration = (Math.random() * 2 + 2) + 's';

            document.body.appendChild(piece);

            // Eliminar del DOM al terminar la animación
            piece.addEventListener('animationend', () => {
                piece.remove();
            });
        }
    }

    // ══════════════════════════════════════════════
    // 4. CONTADOR ANIMADO DE NÚMEROS
    // Para futuro dashboard admin con estadísticas.
    // Uso: <span class="count-up" data-target="1234"></span>
    // ══════════════════════════════════════════════
    const counters = document.querySelectorAll('.count-up');

    counters.forEach(counter => {

        const target   = parseInt(counter.dataset.target) || 0;
        const duration = 1200; // ms
        const step     = target / (duration / 16); // ~60fps
        let current    = 0;

        const update = () => {
            current += step;
            if(current < target){
                counter.textContent = Math.floor(current).toLocaleString('es-CO');
                requestAnimationFrame(update);
            } else {
                counter.textContent = target.toLocaleString('es-CO');
            }
        };

        update();
    });

});