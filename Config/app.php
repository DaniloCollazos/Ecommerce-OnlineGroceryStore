<?php

/**
 * Configuración global de la aplicación — AnimaMarket
 * 
 * Centraliza todas las constantes y configuraciones globales que cualquier parte del proyecto puede necesitar (vistas, controllers, helpers).
 * Este archivo se carga una sola vez desde public/index.php y sus constantes quedan disponibles en toda la aplicación.
 */

// ══════════════════════════════════════════════════
// 1. URL BASE
// Se usa en vistas para construir rutas de assets, imágenes y enlaces sin depender de rutas relativas.
// ══════════════════════════════════════════════════
define('BASE_URL', $_ENV['APP_URL'] ?? 'http://localhost:8000/');

// ══════════════════════════════════════════════════
// 2. NOMBRE DE LA APLICACIÓN
// Usado en títulos de página, navbar y footer.
// ══════════════════════════════════════════════════
define('APP_NAME', $_ENV['APP_NAME'] ?? 'AnimaMarket');

// ══════════════════════════════════════════════════
// 3. ENTORNO
// Controla comportamientos según el contexto:
// 'development' → errores visibles, logs detallados
// 'production'  → errores ocultos, mensajes genéricos
// ══════════════════════════════════════════════════
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');

// ══════════════════════════════════════════════════
// 4. RUTAS INTERNAS DEL PROYECTO
// Rutas absolutas al sistema de archivos. Útiles para require_once y manejo de uploads.
// ROOT_PATH → raíz del proyecto
// STORAGE_PATH → donde se guardan imágenes subidas
// ══════════════════════════════════════════════════

// Raíz del proyecto (un nivel arriba de public/)
define('ROOT_PATH', dirname(__DIR__));

// Carpeta de imágenes subidas por el admin
define('STORAGE_PATH', ROOT_PATH . '/public/img/products/');

// Ruta pública de imágenes (para usar en src de <img>)
define('STORAGE_URL', BASE_URL . 'img/products/');

// ══════════════════════════════════════════════════
// 5. CONFIGURACIÓN DE SESIÓN
// Nombre personalizado para la cookie de sesión.
// Evita conflictos si hay varios proyectos PHP corriendo en el mismo servidor local.
// ══════════════════════════════════════════════════
$sessionName = $_ENV['SESSION_NAME'] ?? 'animamarket_session';
session_name($sessionName);

// ══════════════════════════════════════════════════
// 6. ZONA HORARIA
// Importante para que TIMESTAMP y fechas en BD coincidan con la hora local del servidor.
// ══════════════════════════════════════════════════
date_default_timezone_set('America/Bogota');