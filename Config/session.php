<?php

/**
 * Configuración de Sesión — AnimaMarket
 * 
 * Centraliza toda la configuración de seguridad de la sesión PHP antes de iniciarla.
 */

// ══════════════════════════════════════════════════
// 1. NOMBRE DE LA SESIÓN
// Viene del .env → SESSION_NAME=animamarket_session
// ══════════════════════════════════════════════════
session_name($_ENV['SESSION_NAME'] ?? 'animamarket_session');

// ══════════════════════════════════════════════════
// 2. PARÁMETROS DE LA COOKIE DE SESIÓN
// Controla cómo se comporta la cookie en el navegador.
// ══════════════════════════════════════════════════
$isProduction = ($_ENV['APP_ENV'] ?? 'development') === 'production';

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $isProduction,
    'httponly' => true,
    'samesite' => 'Strict',
]);

// ══════════════════════════════════════════════════
// 3. TIEMPO DE VIDA DE LA SESIÓN EN SERVIDOR
// Define cuánto tiempo el servidor mantiene los datos de sesión activos sin actividad del usuario.
// ══════════════════════════════════════════════════
if($isProduction){
    // 30 minutos en producción
    ini_set('session.gc_maxlifetime', 1800);
} else {
    // 2 horas en desarrollo
    ini_set('session.gc_maxlifetime', 7200);
}

// ══════════════════════════════════════════════════
// 4. REGENERAR ID DE SESIÓN AL HACER LOGIN
// Esta función se llama desde AuthController justo después de verificar credenciales correctas,
// ANTES de guardar datos en $_SESSION.
//
// Previene el ataque "Session Fixation": el atacante no puede usar un ID de sesión
// robado antes del login porque cambia al autenticarse.
// ══════════════════════════════════════════════════
function regenerateSession(){

    // Genera un nuevo ID de sesión: true → elimina el archivo de sesión anterior del servidor
    session_regenerate_id(true);
}

// ══════════════════════════════════════════════════
// 5. DESTRUIR SESIÓN COMPLETAMENTE
// Se llama desde AuthController::logout().
// Limpia $_SESSION, destruye el archivo en servidor y expira la cookie en el navegador del cliente.
// ══════════════════════════════════════════════════
function destroySession(){

    // Vaciar el array de sesión
    $_SESSION = [];

    // Expirar la cookie en el navegador enviando fecha pasada
    if(ini_get('session.use_cookies')){
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // Destruir los datos de sesión en el servidor
    session_destroy();
}