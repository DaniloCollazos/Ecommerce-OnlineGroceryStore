<?php

/**
 * Modelo Provider — AnimaMarket
 *
 * Operaciones sobre la tabla `providers`.
 * Hereda de Model: all(), find(), delete()
 */

require_once __DIR__ . '/Model.php';

class Provider extends Model {

    protected $table = 'providers';

    public $id;
    public $nombre;
    public $telefono;
    public $direccion;

    public function create(){
        $query = "INSERT INTO providers (nombre, telefono, direccion)
                  VALUES (:nombre, :telefono, :direccion)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre',    $this->nombre);
        $stmt->bindParam(':telefono',  $this->telefono);
        $stmt->bindParam(':direccion', $this->direccion);

        return $stmt->execute();
    }

    public function update(){
        $query = "UPDATE providers
                  SET nombre = :nombre, telefono = :telefono, direccion = :direccion
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre',    $this->nombre);
        $stmt->bindParam(':telefono',  $this->telefono);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':id',        $this->id);

        return $stmt->execute();
    }
}