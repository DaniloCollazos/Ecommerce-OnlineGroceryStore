<?php

/**
 * Modelo Brand — AnimaMarket
 *
 * Operaciones sobre la tabla `brands`.
 * Hereda de Model: all(), find(), delete()
 */

require_once __DIR__ . '/Model.php';

class Brand extends Model {

    protected $table = 'brands';

    public $id;
    public $nombre;

    public function create(){
        $stmt = $this->conn->prepare(
            "INSERT INTO brands (nombre) VALUES (:nombre)"
        );
        $stmt->bindParam(':nombre', $this->nombre);
        return $stmt->execute();
    }

    public function update(){
        $stmt = $this->conn->prepare(
            "UPDATE brands SET nombre = :nombre WHERE id = :id"
        );
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':id',     $this->id);
        return $stmt->execute();
    }
}