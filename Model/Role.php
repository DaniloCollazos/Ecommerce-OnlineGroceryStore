<?php

/**
 * Modelo Role — AnimaMarket
 *
 * Operaciones sobre la tabla `roles`.
 * Hereda de Model: all(), find(), delete()
 */

require_once __DIR__ . '/Model.php';

class Role extends Model {

    protected $table = 'roles';

    public $id;
    public $nombre;

    public function create(){
        $stmt = $this->conn->prepare(
            "INSERT INTO roles (nombre) VALUES (:nombre)"
        );
        $stmt->bindParam(':nombre', $this->nombre);
        return $stmt->execute();
    }

    public function update(){
        $stmt = $this->conn->prepare(
            "UPDATE roles SET nombre = :nombre WHERE id = :id"
        );
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':id',     $this->id);
        return $stmt->execute();
    }
}