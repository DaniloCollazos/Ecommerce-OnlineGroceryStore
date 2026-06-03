<?php

/**
 * Controller Order — AnimaMarket
 *
 * Proceso de checkout y historial de pedidos del cliente.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Order.php';
require_once __DIR__ . '/../Model/Cart.php';
require_once __DIR__ . '/../Model/Product.php';

class OrderController extends Controller {

    private $model;
    private $cartModel;

    public function __construct(){
        parent::__construct();
        $this->model     = new Order($this->db);
        $this->cartModel = new Cart($this->db);
    }

    // ══════════════════════════════════════════════════
    // HISTORIAL DE PEDIDOS DEL CLIENTE
    // ══════════════════════════════════════════════════
    public function index(){
        $user_id = $_SESSION['user_id'];
        $orders  = $this->model->getByUser($user_id);
        $this->render('orders/history', compact('orders'), 'main');
    }

    // ══════════════════════════════════════════════════
    // PROCESAR CHECKOUT
    // 1. Lee el carrito de sesión
    // 2. Calcula el total
    // 3. Crea la orden en BD
    // 4. Guarda cada producto como order_item
    // 5. Vacía el carrito
    // ══════════════════════════════════════════════════
    public function checkout(){

        $user_id      = $_SESSION['user_id'];
        $cart         = $_SESSION['cart'] ?? [];
        $productModel = new Product($this->db);

        if(empty($cart)){
            $this->redirect('cart', 'index');
        }

        $total = 0;
        $items = [];

        // Calcular total y preparar items
        foreach($cart as $product_id => $data){
            $producto = $productModel->find($product_id);
            if($producto){
                $subtotal = $producto['precio'] * $data['cantidad'];
                $total   += $subtotal;
                $items[]  = [
                    'product_id' => $product_id,
                    'cantidad'   => $data['cantidad'],
                    'precio'     => $producto['precio'],
                ];
            }
        }

        // Crear la orden
        $this->model->user_id = $user_id;
        $this->model->total   = $total;
        $this->model->estado  = 'pendiente';
        $this->model->create();

        $order_id = $this->model->getLastInsertId();

        // Guardar cada producto como item de la orden
        foreach($items as $item){
            $this->model->addItem(
                $order_id,
                $item['product_id'],
                $item['cantidad'],
                $item['precio']
            );
        }

        // Vaciar el carrito de sesión
        $_SESSION['cart'] = [];

        $this->redirect('payment', 'checkout');
    }
}