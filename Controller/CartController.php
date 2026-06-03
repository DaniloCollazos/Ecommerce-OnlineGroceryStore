<?php

/**
 * Controller Cart — AnimaMarket
 *
 * Gestión del carrito de compras.
 * El carrito vive en $_SESSION['cart'] como array: $_SESSION['cart'][$product_id] = ['cantidad' => N]
 * Se usa sesión en lugar de BD para que funcione también para usuarios no registrados.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Product.php';

class CartController extends Controller {

    private $productModel;

    public function __construct(){
        parent::__construct();
        $this->productModel = new Product($this->db);
    }

    // ══════════════════════════════════════════════════
    // VER CARRITO
    // Recorre $_SESSION['cart'], carga datos de cada producto desde BD y calcula el subtotal
    // ══════════════════════════════════════════════════
    public function index(){

        $cart             = $_SESSION['cart'] ?? [];
        $productosCarrito = [];
        $subtotal         = 0;

        foreach($cart as $product_id => $data){

            $producto = $this->productModel->find($product_id);

            if($producto){
                $producto['cantidad'] = $data['cantidad'];
                $producto['subtotal'] = $producto['precio'] * $data['cantidad'];
                $subtotal            += $producto['subtotal'];
                $productosCarrito[]   = $producto;
            }
        }

        $this->render('cart/index', compact('productosCarrito', 'subtotal'), 'main');
    }

    // ══════════════════════════════════════════════════
    // AGREGAR PRODUCTO
    // Si el producto ya está en el carrito, suma cantidad.
    // Si no, lo agrega con la cantidad indicada.
    // ══════════════════════════════════════════════════
    public function add(){

        $product_id = $_POST['product_id'] ?? null;

        if(!$product_id){
            $this->redirect('product', 'index');
        }

        // Cantidad mínima 1
        $cantidad = max(1, (int)($_POST['cantidad'] ?? 1));

        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }

        if(isset($_SESSION['cart'][$product_id])){
            // Ya existe — sumar cantidad
            $_SESSION['cart'][$product_id]['cantidad'] += $cantidad;
        } else {
            // Nuevo item en el carrito
            $_SESSION['cart'][$product_id] = ['cantidad' => $cantidad];
        }

        $this->redirect('cart', 'index');
    }

    // ══════════════════════════════════════════════════
    // ELIMINAR PRODUCTO DEL CARRITO
    // ══════════════════════════════════════════════════
    public function delete(){

        $product_id = $_POST['producto_id'] ?? null;

        if($product_id && isset($_SESSION['cart'][$product_id])){
            unset($_SESSION['cart'][$product_id]);
        }

        $this->redirect('cart', 'index');
    }
}