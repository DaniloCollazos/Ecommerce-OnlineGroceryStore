/**
 * Main — AnimaMarket
 * Script principal del cliente
 * Carga dinámicamente los scripts especializados según el contexto
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('AnimaMarket App inicializado');

    // ══════════════════════════════════════════════════
    // CARGAR SCRIPTS SEGÚN EL CONTEXTO DE LA PÁGINA
    // ══════════════════════════════════════════════════
    loadScriptsForContext();

    // ══════════════════════════════════════════════════
    // INICIALIZAR BOOTSTRAP
    // ══════════════════════════════════════════════════
    initBootstrap();

    // ══════════════════════════════════════════════════
    // EVENT LISTENERS GLOBALES
    // ══════════════════════════════════════════════════
    setupGlobalListeners();

    // ══════════════════════════════════════════════════
    // ANIMACIONES DE PÁGINA
    // ══════════════════════════════════════════════════
    setupPageAnimations();
});

// ══════════════════════════════════════════════════
// CARGAR SCRIPTS SEGÚN EL CONTEXTO DE LA PÁGINA
// ══════════════════════════════════════════════════
function loadScriptsForContext() {
    const currentPath = window.location.pathname;
    const querySelector = selector => document.querySelector(selector) !== null;
    const urlParams = new URLSearchParams(window.location.search);

    if(querySelector('#carouselHome') || currentPath.includes('products/index')) {
        loadScript('carousel.js');
    }

    if(querySelector('.cart-item') || currentPath.includes('cart')) {
        loadScript('cart.js');
    }

    if(querySelector('.dropdown-toggle') && querySelector('.dropdown')) {
        loadScript('dropdown_menu.js');
    }

    if(querySelector('#fallZone') || querySelector('.grain-animation-wrapper') || currentPath.includes('products/detail')) {
        loadScript('granos.js');
    }

    if(querySelector('.formulario_login') || querySelector('.contenedor_login-register')) {
        loadScript('login.js');
    }

    if(querySelector('input[name="q"]') || querySelector('.search-form')) {
        loadScript('search.js');
    }

    if(document.body.classList.contains('admin-body') || currentPath.includes('/admin') || querySelector('.admin-table')) {
        loadScript('admin.js');
    }

    if(querySelector('.page-enter') || querySelector('.container-carousel') || querySelector('.count-up') || urlParams.get('pedido') === 'confirmado') {
        loadScript('animations.js');
    }
}

// ══════════════════════════════════════════════════
// CARGAR UN SCRIPT DINÁMICAMENTE
// ══════════════════════════════════════════════════
function loadScript(filename) {
    const currentScript = document.querySelector('script[src$="main.js"]');
    const basePath = currentScript ? new URL('.', currentScript.src).href : '/js/';

    const script = document.createElement('script');
    script.src = `${basePath}${filename}?v=${Date.now()}`; // Cache buster
    script.async = false;
    script.onerror = () => console.warn(`⚠️ No se pudo cargar ${filename}`);
    document.body.appendChild(script);

    console.log(`✅ Cargando ${filename}`);
}

// ══════════════════════════════════════════════════
// INICIALIZAR BOOTSTRAP
// Activa tooltips, popovers y otros componentes
// ══════════════════════════════════════════════════
function initBootstrap() {
    // Tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        if(window.bootstrap) {
            new bootstrap.Tooltip(tooltip);
        }
    });

    // Popovers
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(popover => {
        if(window.bootstrap) {
            new bootstrap.Popover(popover);
        }
    });
}

// ══════════════════════════════════════════════════
// EVENT LISTENERS GLOBALES
// ══════════════════════════════════════════════════
function setupGlobalListeners() {
    
    // Confirmación antes de eliminar
    document.addEventListener('click', function(e) {
        if(e.target.classList.contains('btn-delete')) {
            if(!confirm('¿Estás seguro? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        }
    });

    // Mostrar loader en formularios
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if(submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.textContent;
                submitBtn.textContent = '⏳ Procesando...';
                submitBtn.dataset.originalText = originalText;
            }
        });
    });
}

// ══════════════════════════════════════════════════
// ANIMACIONES DE PÁGINA
// Fade-in al cargar
// ══════════════════════════════════════════════════
function setupPageAnimations() {
    const elements = document.querySelectorAll('.page-enter, .reveal');
    elements.forEach((el, index) => {
        el.style.animation = `fadeInUp 0.6s ease-in-out ${index * 0.1}s forwards`;
    });
}

// =========================================================
// PRODUCT DETAIL CONTROLS
// Mueve el comportamiento inline de la vista detail.phtml aquí
// =========================================================
function initProductDetailControls() {
    const input = document.getElementById('lbsInput');
    const hidden = document.getElementById('hiddenCantidad');
    const minus = document.getElementById('btnMinus');
    const plus = document.getElementById('btnPlus');

    if(!input || !hidden) return;

    input.addEventListener('input', () => {
        hidden.value = input.value;
    });

    if(minus) minus.addEventListener('click', () => {
        if(Number(input.value) > 1){
            input.value = Number(input.value) - 1;
            input.dispatchEvent(new Event('input'));
        }
    });

    if(plus) plus.addEventListener('click', () => {
        input.value = Number(input.value) + 1;
        input.dispatchEvent(new Event('input'));
    });
}

// Inicializar controles específicos después de DOMContentLoaded
document.addEventListener('DOMContentLoaded', initProductDetailControls);