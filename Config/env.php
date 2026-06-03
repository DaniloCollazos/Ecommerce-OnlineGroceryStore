<?php

/**
 * Carga manual del archivo .env
 */

function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception("Archivo .env no encontrado");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Ignorar comentarios
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        // Separar clave=valor
        [$key, $value] = explode('=', $line, 2);

        $key = trim($key);
        $value = trim($value);

        // Guardar en $_ENV y $_SERVER
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}