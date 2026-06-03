<?php

/**
 * Modelo Base — AnimaMarket
 *
 * Provee la conexión PDO y métodos genéricos de consulta que todos los modelos heredan.
 */

require_once __DIR__ . '/../Config/Database.php';

class Model {

    /**
     * Conexión PDO compartida por todos los modelos.
     * Se declara protected para que las clases hijas puedan usarla directamente con $this->conn
     */
    protected $conn;

    /**
     * Nombre de la tabla en BD.
     * Cada modelo hijo lo sobreescribe: protected $table = 'products';
     */
    protected $table = '';

    // ══════════════════════════════════════════════════
    // CONSTRUCTOR
    // Recibe la conexión PDO inyectada desde el controller, si no se pasa, crea una nueva automáticamente.
    // Esto permite flexibilidad: los controllers que ya tienen $db la pasan, los que no, se autoconectan.
    // ══════════════════════════════════════════════════
    public function __construct($db = null){
        if($db){
            // Usar la conexión inyectada desde el controller
            $this->conn = $db;
        } else {
            // Crear conexión propia si no se inyecta una
            $database = new Database();
            $this->conn = $database->connect();
        }
    }

    // ══════════════════════════════════════════════════
    // MÉTODOS GENÉRICOS HEREDABLES
    // Los modelos hijos pueden usar estos directamente o sobreescribirlos si necesitan lógica propia.
    // ══════════════════════════════════════════════════

    /**
     * Retorna todos los registros de la tabla. Uso: $this->all() desde cualquier modelo hijo.
     */
    public function all(){
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Busca un registro por su ID. Uso: $this->find($id)
     */
    public function find($id){
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Elimina un registro por su ID. Uso: $this->delete($id)
     */
    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Retorna el ID del último registro insertado.
     */
    public function getLastInsertId(){
        return $this->conn->lastInsertId();
    }

    /**
     * Cuenta el total de registros de la tabla.
     */
    public function count(){
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}