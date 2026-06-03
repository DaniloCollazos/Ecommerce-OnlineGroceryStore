<?php

/**
 * Controller Search — AnimaMarket
 *
 * Búsqueda de productos por nombre o descripción desde la barra del navbar.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Product.php';
require_once __DIR__ . '/../Model/ProductImage.php';

class SearchController extends Controller {

    private $model;
    private $imageModel;

    public function __construct(){
        parent::__construct();
        $this->model      = new Product($this->db);
        $this->imageModel = new ProductImage($this->db);
    }

    // ══════════════════════════════════════════════════
    // BUSCAR PRODUCTOS
    // Lee ?q= de la URL, busca en BD y adjunta imágenes
    // ══════════════════════════════════════════════════
    public function index(){

        $q = trim($_GET['q'] ?? '');

        // Si no hay término, mostrar todo el catálogo
        $productos  = !empty($q) ? $this->model->search($q) : $this->model->all();
        $categorias = $this->model->getCategorias();

        // Adjuntar imágenes a cada resultado
        foreach($productos as &$p){
            $imagenes    = $this->imageModel->getByProduct($p['id']);
            $p['imagenes'] = $imagenes;
        }

        unset($p);

        $this->render('products/index', compact('productos', 'categorias'), 'main');
    }
}