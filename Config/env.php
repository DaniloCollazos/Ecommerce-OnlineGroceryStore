<?php
function loadEnv($path)
{
    // Si no existe .env (producción), no hacer nada
    // Las variables ya están inyectadas por Render
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;

        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);

        // ✅ NO pisar variables que ya existen en el entorno (las de Render)
        if (!array_key_exists($key, $_ENV) && getenv($key) === false) {
            $_ENV[$key]    = $value;
            $_SERVER[$key] = $value;
            putenv("$key=$value");
        }
    }
}
