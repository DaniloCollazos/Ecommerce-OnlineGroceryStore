<?php

/**
 * Modelo Category — AnimaMarket
 *
 * Operaciones sobre la tabla `categories`.
 * Hereda de Model: all(), find(), delete()
 */

require_once __DIR__ . '/Model.php';

class Category extends Model {

    protected $table = 'categories';

    public $id;
    public $nombre;

    public function create(){
        $stmt = $this->conn->prepare(
            "INSERT INTO categories (nombre) VALUES (:nombre)"
        );
        $stmt->bindParam(':nombre', $this->nombre);
        return $stmt->execute();
    }

    public function update(){
        $stmt = $this->conn->prepare(
            "UPDATE categories SET nombre = :nombre WHERE id = :id"
        );
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':id',     $this->id);
        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // VERIFICAR SI LA CATEGORÍA TIENE PRODUCTOS
    // Usado en CategoryController::delete() para evitar borrar una categoría con productos asociados
    // ══════════════════════════════════════════════════
    public function hasProducts($id){
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as total FROM products WHERE categoria_id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch()['total'] > 0;
    }
}