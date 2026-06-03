/**
 * Granos Animation — AnimaMarket
 */

// Self-initializing module: runs even if script is loaded after DOMContentLoaded
(function initGranos() {
    function run() {
        /* ── Scroll Reveal (antes faltaba el observer) ── */
        const revealEls = document.querySelectorAll('.reveal');
        if (revealEls.length) {
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('visible');
                        revealObserver.unobserve(e.target);
                    }
                });
            }, { threshold: 0.15 });

            revealEls.forEach(el => revealObserver.observe(el));
        }

        /* ── Referencias ── */
        const lbsInput   = document.getElementById('lbsInput');
        const fallZone   = document.getElementById('fallZone');
        const grainPile  = document.getElementById('grainPile');
        const bagFill    = document.getElementById('bagFill');
        const scaleBeam  = document.getElementById('scaleBeam');
        const weightText = document.getElementById('weightText');

        // Salida temprana si los elementos no existen
        if (!lbsInput || !fallZone || !bagFill || !weightText) return;

        // Ocultar grain-pile para que no compita con bag-fill
        if (grainPile) grainPile.style.display = 'none';

        /* ── Estado ── */
        let current = 0;
        let target = 1;
        let animationFrameId = null;
        let beanTimerId = null;
        const ANIMATION_DURATION = 2200;
        const BEAN_INTERVAL = 60;

        /* ── Crear un grano ── */
        function createBean() {
            const bean = document.createElement('div');
            bean.classList.add('bean');

            const zoneW = fallZone.offsetWidth || 260;
            bean.style.left = Math.random() * (zoneW - 10) + 'px';

            fallZone.appendChild(bean);
            setTimeout(() => bean.remove(), 900);
        }

        /* ── Actualizar visuales ── */
        function updateVisual() {
            const percent = Math.min((current / target) * 100, 100);

            // Relleno bolsa
            bagFill.style.height = percent + '%';

            // Balanza
            if (scaleBeam) {
                const rotation = (percent / 100) * 8;
                scaleBeam.style.transform = `rotate(${rotation}deg)`;
            }

            // Texto
            weightText.textContent = current.toFixed(1);
        }

        /* ── Iniciar animación ── */
        function startAnimation() {
            const startTime = performance.now();
            let lastBeanTime = 0;

            const tick = (now) => {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / ANIMATION_DURATION, 1);

                current = target * progress;
                updateVisual();

                if (elapsed - lastBeanTime >= BEAN_INTERVAL && progress < 1) {
                    createBean();
                    lastBeanTime = elapsed;
                }

                if (progress < 1) {
                    animationFrameId = requestAnimationFrame(tick);
                    return;
                }

                stopAnimation();
            };

            animationFrameId = requestAnimationFrame(tick);
        }

        /* ── Detener animación ── */
        function stopAnimation() {
            if (animationFrameId !== null) {
                cancelAnimationFrame(animationFrameId);
                animationFrameId = null;
            }

            if (beanTimerId !== null) {
                clearTimeout(beanTimerId);
                beanTimerId = null;
            }
        }

        /* ── Reset + arrancar ── */
        function resetAnimation() {
            stopAnimation();

            current = 0;
            target  = parseFloat(lbsInput.value);
            if (isNaN(target) || target < 1) target = 1;

            bagFill.style.height   = '0%';
            weightText.textContent = '0.0';
            fallZone.innerHTML     = '';

            if (scaleBeam) scaleBeam.style.transform = 'rotate(0deg)';

            startAnimation();
        }

        /* ── Eventos ── */
        lbsInput.addEventListener('input', resetAnimation);

        // Arranque inicial
        resetAnimation();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', run);
    } else {
        run();
    }
})();