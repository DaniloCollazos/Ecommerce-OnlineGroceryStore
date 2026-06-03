<?php

/**
 * Modelo Payment — AnimaMarket
 *
 * Operaciones sobre la tabla `payments`.
 * Hereda de Model: find(), delete()
 */

require_once __DIR__ . '/Model.php';

class Payment extends Model {

    protected $table = 'payments';

    public $id;
    public $order_id;
    public $metodo;
    public $estado;

    // ══════════════════════════════════════════════════
    // REGISTRAR PAGO
    // Usado en PaymentController::store() después de confirmar el checkout
    // ══════════════════════════════════════════════════
    public function create(){
        $query = "INSERT INTO payments (order_id, metodo, estado)
                  VALUES (:order_id, :metodo, :estado)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $this->order_id);
        $stmt->bindParam(':metodo',   $this->metodo);
        $stmt->bindParam(':estado',   $this->estado);

        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // LISTAR TODOS LOS PAGOS
    // Usado en PaymentController::index() — panel admin
    // ══════════════════════════════════════════════════
    public function getAll(){
        $sql = "SELECT p.*, o.total AS total_orden
                FROM payments p
                JOIN orders o ON p.order_id = o.id
                ORDER BY p.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}