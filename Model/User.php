<?php

/**
 * Modelo User — AnimaMarket
 *
 * Operaciones de BD relacionadas con la tabla `users`.
 * Hereda de Model: all(), find(), delete(), getLastInsertId()
 */

require_once __DIR__ . '/Model.php';

class User extends Model {

    protected $table = 'users';

    // Propiedades que mapean las columnas de la tabla
    public $id;
    public $nombre;
    public $email;
    public $telefono;
    public $password;
    public $rol_id;

    /**
     * Retorna usuarios incluyendo nombre del rol.
     */
    public function all(){
        $query = "SELECT u.*, r.nombre AS rol
                  FROM users u
                  LEFT JOIN roles r ON r.id = u.rol_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Busca un usuario por ID incluyendo nombre del rol.
     */
    public function find($id){
        $query = "SELECT u.*, r.nombre AS rol
                  FROM users u
                  LEFT JOIN roles r ON r.id = u.rol_id
                  WHERE u.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ══════════════════════════════════════════════════
    // CREAR USUARIO
    // Usado en AuthController::registerPost() y UserController::store()
    // ══════════════════════════════════════════════════
    public function create(){
        $query = "INSERT INTO users (nombre, email, telefono, password, rol_id)
                  VALUES (:nombre, :email, :telefono, :password, :rol_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre',   $this->nombre);
        $stmt->bindParam(':email',    $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':rol_id',   $this->rol_id);

        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // ACTUALIZAR USUARIO
    // Usado en UserController::update()
    // Solo actualiza campos editables — password se cambia con un método dedicado (updatePassword)
    // ══════════════════════════════════════════════════
    public function update(){
        $query = "UPDATE users
                  SET nombre = :nombre, email = :email, telefono = :telefono
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre',   $this->nombre);
        $stmt->bindParam(':email',    $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':id',       $this->id);

        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // BUSCAR POR EMAIL
    // Usado en AuthController::loginPost() para verificar credenciales del usuario
    // ══════════════════════════════════════════════════
    public function getByEmail($email){
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ══════════════════════════════════════════════════
    // VERIFICAR SI EMAIL YA EXISTE
    // Usado en AuthController::registerPost() para evitar registros duplicados
    // ══════════════════════════════════════════════════
    public function emailExists($email){
        $stmt = $this->conn->prepare(
            "SELECT id FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
}