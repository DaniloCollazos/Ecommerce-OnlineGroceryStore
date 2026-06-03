<?php

class Database {

    private string $host;
    private string $port;
    private string $db;
    private string $user;
    private string $pass;
    
    // Singleton: una sola conexión por request
    private static ?PDO $instance = null;

    public function __construct() {
        $this->loadEnv();

        $this->host = $_ENV['DB_HOST']     ?? getenv('DB_HOST')     ?: 'localhost';
        $this->port = $_ENV['DB_PORT']     ?? getenv('DB_PORT')     ?: '5432';
        $this->db   = $_ENV['DB_NAME']     ?? getenv('DB_NAME')     ?: 'ecommerce_online_grocery_store';
        $this->user = $_ENV['DB_USER']     ?? getenv('DB_USER')     ?: 'postgres';

        // ✅ Fix: usa ?? consistentemente, sin mezclar ?:
        $rawPass    = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD')
                   ?: $_ENV['DB_PASS']    ?? getenv('DB_PASS')
                   ?? '';
        $this->pass = $rawPass;
    }

    private function loadEnv(): void {
        $envFile = __DIR__ . '/../.env';

        if (!file_exists($envFile)) return;

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) continue;
            if (!str_contains($line, '=')) continue;

            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim(trim($value), '"\'');

            if (!array_key_exists($key, $_ENV) && getenv($key) === false) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }

    public function connect(): PDO {
        // Singleton: reutiliza la conexión si ya existe
        if (self::$instance !== null) {
            return self::$instance;
        }

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db}";

            $conn = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT         => false, // true solo si usas PgBouncer
            ]);

            self::$instance = $conn;
            return $conn;

        } catch (PDOException $e) {
            $env = $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: 'development';

            if ($env === 'development') {
                // Muestra error sin exponer contraseña
                $safe = str_replace($this->pass, '***', $e->getMessage());
                die('❌ Error de conexión: ' . htmlspecialchars($safe));
            }

            error_log('DB connection failed: ' . $e->getMessage()); // queda en logs de Render
            die('❌ Error de conexión. Contacta al administrador.');
        }
    }
    
    // Útil para tests o scripts de migración
    public static function reset(): void {
        self::$instance = null;
    }
}