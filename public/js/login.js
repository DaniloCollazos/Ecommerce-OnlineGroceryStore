/**
 * Login — AnimaMarket
 * Alterna entre los formularios de login y registro con animación de slide horizontal.
 */

// ── REFERENCIAS AL DOM ──────────────────────────────
const btnLogin    = document.getElementById('btn_iniciar-sesion');
const btnRegister = document.getElementById('btn_registrarse');
const container   = document.querySelector('.contenedor_login-register');
const formLogin   = document.querySelector('.formulario_login');
const formRegister= document.querySelector('.formulario_register');
const cajaTrasera = document.querySelector('.caja_trasera');

// ── VALIDAR QUE EXISTEN LOS ELEMENTOS ──────────────
if(!btnLogin || !btnRegister || !container){
    console.warn('login.js: elementos del DOM no encontrados');
}

/**
 * Muestra el formulario de login y oculta el de registro.
 * Desliza el contenedor a la posición original (izquierda).
 */
function showLogin(){
    formLogin.style.display    = 'block';
    formRegister.style.display = 'none';

    // Posición original — izquierda
    container.style.left = '10px';

    // Responsive: en móvil no se mueve
    if(window.innerWidth <= 850){
        container.style.left = '-5px';
    }
}

/**
 * Muestra el formulario de registro y oculta el de login.
 * Desliza el contenedor a la derecha para alinearse
 * con el panel de "caja trasera" de registro.
 */
function showRegister(){
    formRegister.style.display = 'block';
    formLogin.style.display    = 'none';

    // Desplazar a la derecha (aprox. ancho de caja_trasera / 2)
    container.style.left = '420px';

    if(window.innerWidth <= 850){
        container.style.left = '-5px';
    }
}

// ── EVENTOS ─────────────────────────────────────────
if(btnLogin)    btnLogin.addEventListener('click',    showLogin);
if(btnRegister) btnRegister.addEventListener('click', showRegister);

/**
 * Ajuste responsive al redimensionar la ventana.
 * Recalcula la posición del contenedor según el
 * estado actual (login o registro visible).
 */
window.addEventListener('resize', () => {
    if(window.innerWidth > 850){

        // Restaurar visibilidad de paneles traseros
        const cajaLogin    = document.querySelector('.caja_trasera-login');
        const cajaRegister = document.querySelector('.caja_trasera-register');

        if(cajaLogin)    cajaLogin.style.display    = 'block';
        if(cajaRegister) cajaRegister.style.display = 'block';

        // Reposicionar según formulario activo
        if(formRegister && formRegister.style.display === 'block'){
            container.style.left = '420px';
        } else {
            container.style.left = '10px';
        }

    } else {

        // En móvil centrar siempre
        container.style.left = '-5px';
    }
});