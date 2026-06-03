<?php
/**
 * Front Controller — AnimaMarket
 */
// 1. CARGAR VARIABLES DE ENTORNO (ANTES DE TODO)
require_once __DIR__ . '/../Config/env.php';
loadEnv(__DIR__ . '/../.env');
// 2. CONFIGURACIONES BASE
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Config/app.php';
require_once __DIR__ . '/../Config/session.php';
// 3. INICIAR SESIÓN
session_start();
// 4. HELPERS
require_once __DIR__ . '/../Helpers/Auth.php';
// ══════════════════════════════════════════════════
// MANEJO DE ERRORES
// ══════════════════════════════════════════════════
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
// ══════════════════════════════════════════════════
// AUTOLOADER
// ══════════════════════════════════════════════════
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../Controller/' . $class . '.php',
        __DIR__ . '/../Model/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
// ══════════════════════════════════════════════════
// DESPACHO VÍA ROUTER
// ══════════════════════════════════════════════════
require_once __DIR__ . '/../Routes/Router.php';
(new Router())->dispatch();
