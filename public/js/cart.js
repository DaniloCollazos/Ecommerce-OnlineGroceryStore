/**
 * cart.js — AnimaMarket
 */

document.addEventListener('DOMContentLoaded', () => {

    // ══════════════════════════════════════════════
    // 1. CONFIRMACIÓN AL ELIMINAR ITEM DEL CARRITO
    // Intercepta los formularios de eliminar y pide
    // confirmación antes de enviar
    // ══════════════════════════════════════════════
    const deleteForms = document.querySelectorAll('.cart-item form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const confirmed = confirm('¿Quitar este producto del carrito?');
            if(!confirmed){
                e.preventDefault();
            }
        });
    });

    // ══════════════════════════════════════════════
    // 2. RESALTAR FILA AL HOVER EN CARRITO
    // Efecto visual de elevación en cada item
    // ══════════════════════════════════════════════
    const cartItems = document.querySelectorAll('.cart-item');

    cartItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            item.style.transform  = 'translateX(4px)';
            item.style.transition = 'transform 0.2s ease';
        });
        item.addEventListener('mouseleave', () => {
            item.style.transform = 'translateX(0)';
        });
    });

    // ══════════════════════════════════════════════
    // 3. TOTAL ANIMADO
    // Si hay un elemento #cartTotal, anima el número
    // al cargar la página del carrito
    // ══════════════════════════════════════════════
    const cartTotal = document.getElementById('cartTotal');

    if(cartTotal){
        const finalValue = parseFloat(
            cartTotal.dataset.value || cartTotal.textContent.replace(/[^0-9.]/g, '')
        );

        let current  = 0;
        const step   = finalValue / 40; // 40 frames

        const animate = () => {
            current += step;
            if(current < finalValue){
                cartTotal.textContent = '$' + Math.floor(current).toLocaleString('es-CO');
                requestAnimationFrame(animate);
            } else {
                cartTotal.textContent = '$' + finalValue.toLocaleString('es-CO');
            }
        };

        animate();
    }

});