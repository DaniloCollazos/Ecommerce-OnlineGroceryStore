<?php

/**
 * Controller Brand — AnimaMarket
 *
 * Gestión de marcas (admin) y filtrado de productos por marca (cliente).
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Brand.php';
require_once __DIR__ . '/../Model/Product.php';
require_once __DIR__ . '/../Model/ProductImage.php';

class BrandController extends Controller {

    private $model;

    public function __construct(){
        parent::__construct();
        $this->model = new Brand($this->db);
    }

    // ── ADMIN ──────────────────────────────────────────

    public function index(){
        $brands = $this->model->all();
        $this->render('admin/brand/BrandIndexView', compact('brands'), 'admin');
    }

    public function create(){
        $this->render('admin/brand/BrandCreateView', [], 'admin');
    }

    public function store(){
        if(empty($_POST['nombre'])){
            $this->redirect('brand', 'create', ['error' => 'campo_vacio']);
        }
        $this->model->nombre = $_POST['nombre'];
        $this->model->create();
        $this->redirect('brand', 'index');
    }

    public function edit(){
        $id    = $_GET['id'] ?? null;
        $brand = $this->model->find($id);

        if(!$brand){
            $this->redirect('brand', 'index');
        }

        $this->render('admin/brand/BrandEditView', compact('brand'), 'admin');
    }

    public function update(){
        if(empty($_POST['id']) || empty($_POST['nombre'])){
            $this->redirect('brand', 'index');
        }
        $this->model->id     = $_POST['id'];
        $this->model->nombre = $_POST['nombre'];
        $this->model->update();
        $this->redirect('brand', 'index');
    }

    public function delete(){
        $id = $_GET['id'] ?? null;
        $this->model->delete($id);
        $this->redirect('brand', 'index');
    }

    // ── CLIENTE ────────────────────────────────────────

    /**
     * Muestra productos filtrados por marca.
     * Ruta: index.php?controller=brand&action=show&id=1
     */
    public function show(){
        $id           = $_GET['id'] ?? null;
        $brand        = $this->model->find($id);
        $productModel = new Product($this->db);
        $imageModel   = new ProductImage($this->db);
        $productos    = $productModel->getByBrand($brand['nombre'] ?? '');

        // Adjuntar imágenes a cada producto
        foreach($productos as &$p){
            $imagenes    = $imageModel->getByProduct($p['id']);
            $p['imagenes'] = $imagenes;
        }

        $marcas = $productModel->getMarcas();
        $this->render('products/index', compact('productos', 'marcas'), 'main');
    }

    /**
     * Lista todas las marcas para la vista del cliente.
     * Ruta: index.php?controller=brand&action=shop
     */
    public function shop(){
        $brands     = $this->model->all();
        $marcas = (new Product($this->db))->getMarcas();
        $this->render('brands/index', compact('brands', 'marcas'), 'main');
    }
}