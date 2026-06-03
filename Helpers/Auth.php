<?php

/**
 * Helper de Autenticación — AnimaMarket
 *
 * Centraliza todas las funciones de verificación de sesión y control de acceso por roles.
 * Se carga una sola vez desde public/index.php y sus funciones quedan disponibles en todos los controllers.
 * Roles del sistema (definidos en tabla `roles`): rol_id = 1 → Cliente, rol_id = 2 → Administrador
 */

// ══════════════════════════════════════════════════
// 1. VERIFICAR ADMINISTRADOR
// Detiene la ejecución si el usuario no está autenticado como admin (rol_id = 2).
// Se usa al inicio de cada método del AdminController y controllers de gestión (product, category, etc.)
// ══════════════════════════════════════════════════
function checkAdmin(){

    // Si no hay sesión activa o el rol no es admin, redirigir al login
    if(!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 2){
        header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . 'index.php?controller=Auth&action=login');
        exit;
    }
}

// ══════════════════════════════════════════════════
// 2. VERIFICAR USUARIO AUTENTICADO
// Detiene la ejecución si no hay ningún usuario logueado, sin importar su rol.
// Se usa en rutas que requieren login pero no admin: carrito, perfil, checkout, historial de pedidos.
// ══════════════════════════════════════════════════
function requireUser(){

    // Si no hay user_id en sesión, redirigir al login
    if(!isset($_SESSION['user_id'])){
        header('Location: ' . (defined('BASE_URL') ? BASE_URL : '/') . 'index.php?controller=Auth&action=login');
        exit;
    }
}

// ══════════════════════════════════════════════════
// 3. VERIFICAR SI HAY SESIÓN ACTIVA
// Retorna true o false sin redirigir.
// Útil en vistas para mostrar/ocultar elementos como el botón de "Mi cuenta" o el contador del carrito.
// ══════════════════════════════════════════════════
function isLoggedIn(){
    return isset($_SESSION['user_id']);
}

// ══════════════════════════════════════════════════
// 4. VERIFICAR SI EL USUARIO ACTUAL ES ADMIN
// Retorna true o false sin redirigir.
// Útil en vistas para mostrar/ocultar secciones del navbar o botones de gestión.
// ══════════════════════════════════════════════════
function isAdmin(){
    return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 2;
}

// ══════════════════════════════════════════════════
// 5. OBTENER USUARIO EN SESIÓN
// Retorna un array con los datos básicos del usuario activo, o null si no hay sesión.
// Útil en partials (header, navbar) para mostrar el nombre del usuario logueado.
// ══════════════════════════════════════════════════
function currentUser(){

    if(!isLoggedIn()){
        return null;
    }

    // Retorna solo los datos que las vistas necesitan
    return [
        'id'     => $_SESSION['user_id'],
        'nombre' => $_SESSION['nombre']  ?? 'Usuario',
        'rol_id' => $_SESSION['rol_id']  ?? 1,
    ];
}