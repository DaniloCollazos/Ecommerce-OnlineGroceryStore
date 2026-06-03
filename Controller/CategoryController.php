<?php

/**
 * Controller Category — AnimaMarket
 *
 * Gestión de categorías (admin) y filtrado de productos por categoría (cliente).
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Category.php';
require_once __DIR__ . '/../Model/Product.php';
require_once __DIR__ . '/../Model/ProductImage.php';

class CategoryController extends Controller {

    private $model;

    public function __construct(){
        parent::__construct();
        $this->model = new Category($this->db);
    }

    // ── ADMIN ──────────────────────────────────────────

    public function index(){
        $categories = $this->model->all();
        $this->render('admin/category/CategoryIndexView', compact('categories'), 'admin');
    }

    public function create(){
        $this->render('admin/category/CategoryCreateView', [], 'admin');
    }

    public function store(){
        if(empty($_POST['nombre'])){
            $this->redirect('category', 'create', ['error' => 'campo_vacio']);
        }
        $this->model->nombre = $_POST['nombre'];
        $this->model->create();
        $this->redirect('category', 'index');
    }

    public function edit(){
        $id       = $_GET['id'] ?? null;
        $category = $this->model->find($id);

        if(!$category){
            $this->redirect('category', 'index');
        }

        $this->render('admin/category/CategoryEditView', compact('category'), 'admin');
    }

    public function update(){
        if(empty($_POST['id']) || empty($_POST['nombre'])){
            $this->redirect('category', 'index');
        }
        $this->model->id     = $_POST['id'];
        $this->model->nombre = $_POST['nombre'];
        $this->model->update();
        $this->redirect('category', 'index');
    }

    public function delete(){
        $id = $_POST['id'] ?? null;

        // Evitar borrar si tiene productos asociados
        if($this->model->hasProducts($id)){
            $this->redirect('category', 'index', ['error' => 'tiene_productos']);
        }

        $this->model->delete($id);
        $this->redirect('category', 'index');
    }

    // ── CLIENTE ────────────────────────────────────────

    /**
     * Muestra productos filtrados por categoría.
     * Ruta: index.php?controller=category&action=show&id=1
     */
    public function show(){
        $id           = $_GET['id'] ?? null;
        $category     = $this->model->find($id);
        $productModel = new Product($this->db);
        $imageModel   = new ProductImage($this->db);
        $productos    = $productModel->getByCategory($id);

        // Adjuntar imágenes a cada producto
        foreach($productos as &$p){
            $imagenes    = $imageModel->getByProduct($p['id']);
            $p['imagenes'] = $imagenes;
        }

        $categorias = $productModel->getCategorias();
        $this->render('products/index', compact('productos', 'categorias'), 'main');
    }
}