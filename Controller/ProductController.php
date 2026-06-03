<?php

/**
 * Controller Product — AnimaMarket
 *
 * Gestiona productos tanto para la vista del cliente como para el panel admin.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Product.php';
require_once __DIR__ . '/../Model/ProductImage.php';

class ProductController extends Controller {

    private $model;
    private $imageModel;

    public function __construct(){
        parent::__construct();
        $this->model      = new Product($this->db);
        $this->imageModel = new ProductImage($this->db);
    }

    // ══════════════════════════════════════════════════
    // CLIENTE — CATÁLOGO PRINCIPAL
    // ══════════════════════════════════════════════════
    public function index(){
        $productos  = $this->model->all();
        $categorias = $this->model->getCategorias();

        // Adjuntar imágenes a cada producto para las tarjetas
        $this->attachImages($productos);

        $this->render('products/index', compact('productos', 'categorias'), 'main');
    }

    // ══════════════════════════════════════════════════
    // CLIENTE — DETALLE DEL PRODUCTO
    // Muestra información completa + animación de granos
    // ══════════════════════════════════════════════════
    public function show(){

        $id      = $_GET['id'] ?? null;
        $product = $this->model->find($id);

        if(!$product){
            $this->redirect('product', 'index');
        }

        // Cargar imágenes del producto para el carrusel
        $imagenes = $this->imageModel->getByProduct($id);
        $product['fotos'] = array_column($imagenes, 'url');

        $this->render('products/detail', compact('product'), 'main');
    }

    // ══════════════════════════════════════════════════
    // ADMIN — LISTADO CRUD
    // ══════════════════════════════════════════════════
    public function indexAdmin(){
        $productos = $this->model->all();
        $this->attachImages($productos);
        $this->render('admin/product/ProductIndexView', compact('productos'), 'admin');
    }

    // ══════════════════════════════════════════════════
    // ADMIN — FORMULARIO CREAR
    // ══════════════════════════════════════════════════
    public function create(){
        $categorias  = $this->model->getCategorias();
        $marcas      = $this->model->getMarcas();
        $proveedores = $this->model->getProveedores();
        $this->render('admin/product/ProductCreateView', compact('categorias', 'marcas', 'proveedores'), 'admin');
    }

    // ══════════════════════════════════════════════════
    // ADMIN — GUARDAR NUEVO PRODUCTO
    // Crea el producto y sube hasta 5 imágenes
    // ══════════════════════════════════════════════════
    public function store(){

        if(empty($_POST['nombre']) || empty($_POST['precio'])){
            $this->redirect('product', 'create', ['error' => 'campos_vacios']);
        }

        $this->model->nombre       = $_POST['nombre'];
        $this->model->descripcion  = $_POST['descripcion']  ?? '';
        $this->model->precio       = $_POST['precio'];
        $this->model->precio_costo = $_POST['precio_costo'] ?? 0;
        $this->model->stock        = $_POST['stock']        ?? 0;
        $this->model->categoria_id = $_POST['categoria_id'];
        $this->model->marca_id     = $_POST['marca_id'];
        $this->model->proveedor_id = $_POST['proveedor_id'] ?? null;

        if($this->model->create()){
            $product_id = $this->model->getLastInsertId();
            // Subir imágenes del nuevo producto
            $this->uploadImages($product_id);
            $this->redirect('product', 'indexAdmin');
        } else {
            $this->redirect('product', 'create', ['error' => 'error_crear']);
        }
    }

    // ══════════════════════════════════════════════════
    // ADMIN — FORMULARIO EDITAR
    // ══════════════════════════════════════════════════
    public function edit(){

        $id      = $_GET['id'] ?? null;
        $product = $this->model->find($id);

        if(!$product){
            $this->redirect('product', 'indexAdmin');
        }

        // Cargar imágenes existentes para mostrar en el formulario
        $imagenes = $this->imageModel->getByProduct($id);
        $product['fotos'] = array_column($imagenes, 'url');

        $categorias  = $this->model->getCategorias();
        $marcas      = $this->model->getMarcas();
        $proveedores = $this->model->getProveedores();

        $this->render('admin/product/ProductEditView', compact('product', 'categorias', 'marcas', 'proveedores'), 'admin');
    }

    // ══════════════════════════════════════════════════
    // ADMIN — GUARDAR CAMBIOS DEL PRODUCTO
    // Actualiza datos + gestiona imágenes (eliminar/agregar)
    // ══════════════════════════════════════════════════
    public function update(){

        $id = $_POST['id'] ?? null;

        $this->model->id           = $id;
        $this->model->nombre       = $_POST['nombre'];
        $this->model->descripcion  = $_POST['descripcion']  ?? '';
        $this->model->precio       = $_POST['precio'];
        $this->model->precio_costo = $_POST['precio_costo'] ?? 0;
        $this->model->stock        = $_POST['stock'];
        $this->model->update();

        // Eliminar imágenes marcadas para borrar
        if(!empty($_POST['delete_images'])){
            foreach($_POST['delete_images'] as $imgUrl){
                $filePath = __DIR__ . '/../../' . $imgUrl;
                if(file_exists($filePath)){
                    unlink($filePath);
                }
                $this->imageModel->deleteByUrl($imgUrl);
            }
        }

        // Subir nuevas imágenes si se enviaron
        if(!empty($_FILES['imagenes']['name'][0])){
            $this->uploadImages($id);
        }

        $this->redirect('product', 'indexAdmin');
    }

    // ══════════════════════════════════════════════════
    // ADMIN — ELIMINAR PRODUCTO
    // ══════════════════════════════════════════════════
    public function delete(){

        $id = $_POST['id'] ?? null;

        if(!$id){
            $this->redirect('product', 'indexAdmin');
        }

        $this->model->delete($id);
        $this->redirect('product', 'indexAdmin');
    }

    // ══════════════════════════════════════════════════
    // MÉTODOS PRIVADOS AUXILIARES
    // ══════════════════════════════════════════════════

    /**
     * Adjunta imágenes a cada producto del listado.
     * imagen → primera foto (portada de la tarjeta)
     * imagenes → todas las fotos (carrusel en detalle)
     */
    private function attachImages(&$productos){
        foreach($productos as &$p){
            $imagenes      = $this->imageModel->getByProduct($p['id']);
            $p['imagenes'] = $imagenes         ?? [];
            $p['imagen']   = $imagenes[0]['url'] ?? null;
        }
    }

    /**
     * Sube hasta 5 imágenes al servidor y las registra en BD.
     * Usado en store() y update().
     */
    private function uploadImages($product_id){

        if(empty($_FILES['imagenes']['name'][0])){
            return;
        }

        $uploadDir = __DIR__ . '/../../Storage/products/';

        // Crear carpeta si no existe
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        // Máximo 5 imágenes por producto
        $total = min(count($_FILES['imagenes']['name']), 5);

        for($i = 0; $i < $total; $i++){

            if($_FILES['imagenes']['error'][$i] !== 0){
                continue;
            }

            $tmpName = $_FILES['imagenes']['tmp_name'][$i];
            $ext     = pathinfo($_FILES['imagenes']['name'][$i], PATHINFO_EXTENSION);
            $name    = time() . '_' . uniqid() . '.' . $ext;
            $path    = $uploadDir . $name;

            if(move_uploaded_file($tmpName, $path)){
                $url = 'Storage/products/' . $name;
                $this->imageModel->save($product_id, $url);
            }
        }
    }
}