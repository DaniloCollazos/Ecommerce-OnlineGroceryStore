<?php

/**
 * Modelo Inventory — AnimaMarket
 *
 * Operaciones sobre la tabla `inventory`.
 * Controla el stock actual y mínimo de cada producto.
 * Hereda de Model: find(), delete()
 */

require_once __DIR__ . '/Model.php';

class Inventory extends Model {

    protected $table = 'inventory';

    public $id;
    public $product_id;
    public $stock_actual;
    public $stock_minimo;

    // ══════════════════════════════════════════════════
    // LISTAR INVENTARIO COMPLETO
    // Trae nombre del producto junto a su stock para mostrar en la vista del panel admin
    // ══════════════════════════════════════════════════
    public function all(){
        $sql = "SELECT i.*, p.nombre AS producto
                FROM inventory i
                JOIN products p ON i.product_id = p.id
                ORDER BY p.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // CREAR REGISTRO DE INVENTARIO
    // Usado en InventoryController::store()
    // ══════════════════════════════════════════════════
    public function create(){
        $query = "INSERT INTO inventory (product_id, stock_actual, stock_minimo)
                  VALUES (:product_id, :stock_actual, :stock_minimo)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id',   $this->product_id);
        $stmt->bindParam(':stock_actual', $this->stock_actual);
        $stmt->bindParam(':stock_minimo', $this->stock_minimo);

        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // ACTUALIZAR STOCK
    // Usado en InventoryController::update()
    // ══════════════════════════════════════════════════
    public function update(){
        $query = "UPDATE inventory
                  SET stock_actual = :stock_actual,
                      stock_minimo = :stock_minimo
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':stock_actual', $this->stock_actual);
        $stmt->bindParam(':stock_minimo', $this->stock_minimo);
        $stmt->bindParam(':id',           $this->id);

        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // PRODUCTOS CON STOCK BAJO
    // Retorna productos cuyo stock_actual está por debajo del stock_minimo definido.
    // Útil para alertas en el dashboard del admin.
    // ══════════════════════════════════════════════════
    public function getLowStock(){
        $sql = "SELECT i.*, p.nombre AS producto
                FROM inventory i
                JOIN products p ON i.product_id = p.id
                WHERE i.stock_actual <= i.stock_minimo
                ORDER BY i.stock_actual ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}