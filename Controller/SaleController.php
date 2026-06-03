<?php

/**
 * Controller Sale — AnimaMarket
 *
 * Vista de ventas/pedidos para el admin. Muestra todas las órdenes con sus detalles.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Order.php';
require_once __DIR__ . '/../Model/User.php';

class SaleController extends Controller {

    private $orderModel;
    private $userModel;

    public function __construct(){
        parent::__construct();
        $this->orderModel = new Order($this->db);
        $this->userModel  = new User($this->db);
    }

    // ══════════════════════════════════════════════════
    // LISTADO DE TODAS LAS VENTAS
    // ══════════════════════════════════════════════════
    public function index(){
        $sales = $this->orderModel->getAll();
        $this->render('admin/sales/SaleIndexView', compact('sales'), 'admin');
    }

    // ══════════════════════════════════════════════════
    // DETALLE DE UNA VENTA
    // Muestra los productos de una orden específica
    // ══════════════════════════════════════════════════
    public function show(){

        $id    = $_GET['id'] ?? null;
        $order = $this->orderModel->find($id);

        if(!$order){
            $this->redirect('sale', 'index');
        }

        $items = $this->orderModel->getItemsByOrder($id);
        $user  = $this->userModel->find($order['user_id']);

        $this->render('admin/sales/SaleShowView', compact('order', 'items', 'user'), 'admin');
    }
}