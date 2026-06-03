<?php

/**
 * Modelo Order — AnimaMarket
 *
 * Responsabilidad: operaciones sobre las tablas `orders` y `order_items`.
 * Hereda de Model: find(), delete()
 */

require_once __DIR__ . '/Model.php';

class Order extends Model {

    protected $table = 'orders';

    public $id;
    public $user_id;
    public $total;
    public $estado;

    // ══════════════════════════════════════════════════
    // CREAR ORDEN
    // Usado en OrderController::checkout()
    // ══════════════════════════════════════════════════
    public function create(){
        $query = "INSERT INTO orders (user_id, total, estado)
                  VALUES (:user_id, :total, :estado)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':total',   $this->total);
        $stmt->bindParam(':estado',  $this->estado);

        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // OBTENER TODAS LAS ÓRDENES
    // Usado en el panel admin para ver todos los pedidos
    // ══════════════════════════════════════════════════
    public function getAll(){
        $sql = "SELECT o.*, u.nombre AS cliente
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // OBTENER ÓRDENES DE UN USUARIO
    // Usado en OrderController::index() para mostrar el historial de pedidos del cliente logueado
    // ══════════════════════════════════════════════════
    public function getByUser($user_id){
        $stmt = $this->conn->prepare(
            "SELECT * FROM orders WHERE user_id = :user_id ORDER BY fecha DESC"
        );
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // OBTENER ITEMS DE UNA ORDEN
    // Usado en SaleController::show() para ver el detalle de productos de un pedido específico
    // ══════════════════════════════════════════════════
    public function getItemsByOrder($order_id){
        $sql = "SELECT oi.*, p.nombre AS producto
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // GUARDAR ITEM DE ORDEN
    // Usado en OrderController::checkout() para guardar cada producto del carrito como item de la orden
    // ══════════════════════════════════════════════════
    public function addItem($order_id, $product_id, $cantidad, $precio){
        $query = "INSERT INTO order_items (order_id, product_id, cantidad, precio)
                  VALUES (:order_id, :product_id, :cantidad, :precio)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id',   $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':cantidad',   $cantidad);
        $stmt->bindParam(':precio',     $precio);

        return $stmt->execute();
    }
}