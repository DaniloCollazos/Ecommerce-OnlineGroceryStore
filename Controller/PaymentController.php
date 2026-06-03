<?php

/**
 * Controller Payment — AnimaMarket
 *
 * Vista de checkout con resumen de la orden y registro del pago.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Payment.php';
require_once __DIR__ . '/../Model/Order.php';
require_once __DIR__ . '/../Model/User.php';

class PaymentController extends Controller {

    private $model;
    private $orderModel;

    public function __construct(){
        parent::__construct();
        $this->model      = new Payment($this->db);
        $this->orderModel = new Order($this->db);
    }

    // ══════════════════════════════════════════════════
    // ADMIN — LISTADO DE PAGOS
    // ══════════════════════════════════════════════════
    public function index(){
        $payments = $this->model->getAll();
        $this->render('admin/payments/PaymentIndexView', compact('payments'), 'admin');
    }

    // ══════════════════════════════════════════════════
    // CLIENTE — VISTA DE CHECKOUT
    // Muestra resumen de la última orden para confirmar pago
    // ══════════════════════════════════════════════════
    public function checkout(){

        $user_id   = $_SESSION['user_id'];
        $userModel = new User($this->db);
        $user      = $userModel->find($user_id);

        // Obtener la última orden del usuario
        $orders = $this->orderModel->getByUser($user_id);
        $order  = $orders[0] ?? null;

        if(!$order){
            $this->redirect('product', 'index');
        }

        // Cargar los items de esa orden
        $cartItems = $this->orderModel->getItemsByOrder($order['id']);

        $this->render('orders/checkout', compact('user', 'order', 'cartItems'));
    }

    // ══════════════════════════════════════════════════
    // CLIENTE — REGISTRAR PAGO
    // Guarda el método de pago elegido en la orden
    // ══════════════════════════════════════════════════
    public function store(){

        $order_id = $_POST['order_id'] ?? null;
        $metodo   = $_POST['metodo']   ?? 'efectivo';

        if(!$order_id){
            $this->redirect('product', 'index');
        }

        $this->model->order_id = $order_id;
        $this->model->metodo   = $metodo;
        $this->model->estado   = 'pagado';
        $this->model->create();

        $this->redirect('order', 'index');
    }
}