<?php

/**
 * Controller Base — AnimaMarket
 * Provee métodos utilitarios compartidos que todos los controllers heredan.
 */

require_once __DIR__ . '/../Config/database.php';

class Controller {

    /**
     * Conexión PDO compartida por todos los controllers.
     * Se declara protected para que las clases hijas puedan usarla con $this->db
     */
    protected $db;

    // ══════════════════════════════════════════════════
    // CONSTRUCTOR
    // Establece la conexión a BD una sola vez.
    // Cada controller hijo la hereda automáticamente sin necesidad de repetir new Database()->connect()
    // ══════════════════════════════════════════════════
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    // ══════════════════════════════════════════════════
    // REDIRIGIR
    // Centraliza los header('Location: ...')
    // ══════════════════════════════════════════════════
    protected function redirect($controller, $action = 'index', $params = []){

        // Construir URL base de la redirección
        $url = BASE_URL . 'index.php?controller=' . $controller . '&action=' . $action;

        // Agregar parámetros adicionales si los hay
        foreach($params as $key => $value){
            $url .= '&' . $key . '=' . urlencode($value);
        }

        header('Location: ' . $url);
        exit;
    }

    // ══════════════════════════════════════════════════
    // RENDERIZAR VISTA
    // Carga un archivo .phtml pasándole variables.
    // Las claves del array se convierten en variables disponibles dentro del archivo .phtml: $productos, $categorias
    // ══════════════════════════════════════════════════
    protected function render($view, $data = [], $layout = null){

        // Convertir el array en variables individuales
        // ['productos' => [...]] → $productos = [...]
        extract($data);

        // Cargar datos compartidos para el layout 'main' (categorías y marcas)
        // Solo si no fueron provistos por el controlador.
        if($layout === 'main'){
            if(!isset($categorias) || !is_array($categorias)){
                require_once __DIR__ . '/../Model/Category.php';
                $catModel = new Category($this->db);
                $categorias = $catModel->all();
            }

            if(!isset($marcas) || !is_array($marcas)){
                require_once __DIR__ . '/../Model/Brand.php';
                $brandModel = new Brand($this->db);
                $marcas = $brandModel->all();
            }
        }

        // Construir ruta absoluta al archivo de vista
        $viewPath = __DIR__ . '/../View/' . $view . '.phtml';
        echo '<script>console.log(' . json_encode("[Controller.php] Cargando vista: {$viewPath}") . ');</script>';

        // Verificar que la vista exista antes de cargarla
        if(!file_exists($viewPath)){
            die("❌ Vista <strong>{$view}.phtml</strong> no encontrada.");
        }

        // Si hay layout, capturar el contenido de la vista en un buffer
        if($layout){
            ob_start();
            require_once $viewPath;
            $content = ob_get_clean();

            // Cargar el layout y pasarle el contenido capturado
            $layoutPath = __DIR__ . '/../View/layouts/' . $layout . '.phtml';
            echo '<script>console.log(' . json_encode("[Controller.php] Cargando layout: {$layoutPath}") . ');</script>';

            if(!file_exists($layoutPath)){
                die("❌ Layout <strong>layouts/{$layout}.phtml</strong> no encontrado.");
            }

            require_once $layoutPath;
        } else {
            // Render directo sin layout
            require_once $viewPath;
        }
    }

    // ══════════════════════════════════════════════════
    // RESPUESTA JSON
    // Para peticiones AJAX (carrito, búsqueda live). Envía el header correcto y codifica el array.
    // Uso desde un controller hijo: $this->json(['success' => true, 'total' => 3]);
    // ══════════════════════════════════════════════════
    protected function json($data, $statusCode = 200){
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
