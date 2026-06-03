/**
 * admin.js — AnimaMarket
 * Scripts exclusivos del panel de administración.
 *
 * Responsabilidades:
 * - Confirmar eliminaciones de registros
 * - Preview de imágenes antes de subir
 * - Resaltar fila activa en tablas
 * - Sidebar activo según URL
 *
 * Se carga SOLO en: View/layouts/admin.phtml
 */

document.addEventListener('DOMContentLoaded', () => {

    // ══════════════════════════════════════════════
    // 1. PREVIEW DE IMÁGENES ANTES DE SUBIR
    // Muestra miniaturas de las imágenes seleccionadas antes de guardar el formulario de producto
    // ══════════════════════════════════════════════
    const imageInput = document.querySelector('input[name="imagenes[]"]');

    if(imageInput){

        // Crear contenedor de previews si no existe
        let previewContainer = document.getElementById('imagePreviewContainer');

        if(!previewContainer){
            previewContainer = document.createElement('div');
            previewContainer.id        = 'imagePreviewContainer';
            previewContainer.className = 'd-flex flex-wrap gap-2 mt-2';
            imageInput.parentNode.insertBefore(previewContainer, imageInput.nextSibling);
        }

        imageInput.addEventListener('change', () => {

            // Limpiar previews anteriores
            previewContainer.innerHTML = '';

            const files = Array.from(imageInput.files).slice(0, 5);

            files.forEach(file => {

                if(!file.type.startsWith('image/')) return;

                const reader = new FileReader();

                reader.onload = (e) => {
                    const img      = document.createElement('img');
                    img.src        = e.target.result;
                    img.className  = 'rounded border';
                    img.style.cssText = 'width:80px;height:80px;object-fit:cover;';

                    const wrapper = document.createElement('div');
                    wrapper.appendChild(img);

                    // Nombre del archivo debajo de la miniatura
                    const name       = document.createElement('small');
                    name.className   = 'd-block text-muted text-truncate';
                    name.style.width = '80px';
                    name.textContent = file.name;
                    wrapper.appendChild(name);

                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            });
        });
    }

    // ══════════════════════════════════════════════
    // 2. CONFIRMAR ELIMINACIONES
    // Intercepta todos los formularios de delete en el panel admin para pedir confirmación
    // ══════════════════════════════════════════════
    const deleteForms = document.querySelectorAll('form[onsubmit]');

    deleteForms.forEach(form => {
        // El confirm ya está en el onsubmit del HTML
        // Este bloque es un fallback para los que no lo tienen
        const hasConfirm = form.getAttribute('onsubmit') &&
                           form.getAttribute('onsubmit').includes('confirm');

        if(!hasConfirm){
            form.addEventListener('submit', (e) => {
                const confirmed = confirm('¿Estás seguro? Esta acción no se puede deshacer.');
                if(!confirmed) e.preventDefault();
            });
        }
    });

    // ══════════════════════════════════════════════
    // 3. RESALTAR FILA AL HACER CLICK EN TABLA
    // Feedback visual al seleccionar un registro
    // ══════════════════════════════════════════════
    const tableRows = document.querySelectorAll('.admin-table tbody tr');

    tableRows.forEach(row => {
        row.addEventListener('click', (e) => {
            // No activar si el click fue en un botón o enlace
            if(e.target.closest('button, a, form')) return;

            // Quitar selección anterior
            tableRows.forEach(r => r.classList.remove('table-active'));

            // Marcar fila actual
            row.classList.add('table-active');
        });
    });

    // ══════════════════════════════════════════════
    // 4. AUTO-DISMISS DE ALERTAS
    // Las alertas de éxito/error desaparecen automáticamente después de 4 segundos
    // ══════════════════════════════════════════════
    const alerts = document.querySelectorAll('.alert');

    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity    = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

});