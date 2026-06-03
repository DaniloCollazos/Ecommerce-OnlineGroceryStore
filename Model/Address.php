<?php

/**
 * Modelo Address — AnimaMarket
 *
 * Operaciones sobre la tabla `addresses`.
 * Gestiona las direcciones de entrega de cada usuario.
 * Hereda de Model: find(), delete()
 */

require_once __DIR__ . '/Model.php';

class Address extends Model {

    protected $table = 'addresses';

    public $id;
    public $user_id;
    public $direccion;
    public $ciudad;
    public $barrio;
    public $referencia;

    // ══════════════════════════════════════════════════
    // OBTENER DIRECCIONES DE UN USUARIO
    // Usado en checkout para mostrar las direcciones guardadas del cliente logueado
    // ══════════════════════════════════════════════════
    public function getByUser($user_id){
        $stmt = $this->conn->prepare(
            "SELECT * FROM addresses WHERE user_id = :user_id"
        );
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // CREAR DIRECCIÓN
    // Usado en AddressController::store()
    // ══════════════════════════════════════════════════
    public function create(){
        $query = "INSERT INTO addresses (user_id, direccion, ciudad, barrio, referencia)
                  VALUES (:user_id, :direccion, :ciudad, :barrio, :referencia)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id',    $this->user_id);
        $stmt->bindParam(':direccion',  $this->direccion);
        $stmt->bindParam(':ciudad',     $this->ciudad);
        $stmt->bindParam(':barrio',     $this->barrio);
        $stmt->bindParam(':referencia', $this->referencia);

        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // ACTUALIZAR DIRECCIÓN
    // Usado en AddressController::update()
    // ══════════════════════════════════════════════════
    public function update(){
        $query = "UPDATE addresses
                  SET direccion  = :direccion,
                      ciudad     = :ciudad,
                      barrio     = :barrio,
                      referencia = :referencia
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':direccion',  $this->direccion);
        $stmt->bindParam(':ciudad',     $this->ciudad);
        $stmt->bindParam(':barrio',     $this->barrio);
        $stmt->bindParam(':referencia', $this->referencia);
        $stmt->bindParam(':id',         $this->id);

        return $stmt->execute();
    }
}