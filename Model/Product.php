<?php

/**
 * Modelo Product — AnimaMarket
 *
 * Operaciones sobre la tabla `products`.
 * Es el modelo más completo del proyecto ya que los productos 
 * se relacionan con categorías, marcas, proveedores e imágenes.
 * Hereda de Model: find(), delete(), getLastInsertId()
 */

require_once __DIR__ . '/Model.php';

class Product extends Model {

    protected $table = 'products';

    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $precio_costo;
    public $stock;
    public $categoria_id;
    public $marca_id;
    public $proveedor_id;

    // ══════════════════════════════════════════════════
    // LISTAR TODOS CON JOINS
    // Trae nombre de categoría y marca junto al producto para evitar hacer consultas extra en los controllers
    // ══════════════════════════════════════════════════
    public function all(){
        $sql = "SELECT p.*,
                       c.nombre AS categoria,
                       b.nombre AS marca
                FROM products p
                LEFT JOIN categories c ON p.categoria_id = c.id
                LEFT JOIN brands b     ON p.marca_id     = b.id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Busca un producto por ID con su categoría y marca.
     * Sobreescribe el find() genérico del modelo base para incluir los JOINs necesarios.
     */
    public function find($id){
        $sql = "SELECT p.*,
                       c.nombre AS categoria,
                       b.nombre AS marca
                FROM products p
                LEFT JOIN categories c ON p.categoria_id = c.id
                LEFT JOIN brands b     ON p.marca_id     = b.id
                WHERE p.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ══════════════════════════════════════════════════
    // CREAR PRODUCTO
    // Usado en ProductController::store()
    // ══════════════════════════════════════════════════
    public function create(){
        $query = "INSERT INTO products
                    (nombre, descripcion, precio, precio_costo, stock, categoria_id, marca_id, proveedor_id)
                  VALUES
                    (:nombre, :descripcion, :precio, :precio_costo, :stock, :categoria_id, :marca_id, :proveedor_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre',       $this->nombre);
        $stmt->bindParam(':descripcion',  $this->descripcion);
        $stmt->bindParam(':precio',       $this->precio);
        $stmt->bindParam(':precio_costo', $this->precio_costo);
        $stmt->bindParam(':stock',        $this->stock);
        $stmt->bindParam(':categoria_id', $this->categoria_id);
        $stmt->bindParam(':marca_id',     $this->marca_id);
        $stmt->bindParam(':proveedor_id', $this->proveedor_id);

        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // ACTUALIZAR PRODUCTO
    // Usado en ProductController::update()
    // ══════════════════════════════════════════════════
    public function update(){
        $query = "UPDATE products
                  SET nombre       = :nombre,
                      descripcion  = :descripcion,
                      precio       = :precio,
                      precio_costo = :precio_costo,
                      stock        = :stock
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre',       $this->nombre);
        $stmt->bindParam(':descripcion',  $this->descripcion);
        $stmt->bindParam(':precio',       $this->precio);
        $stmt->bindParam(':precio_costo', $this->precio_costo);
        $stmt->bindParam(':stock',        $this->stock);
        $stmt->bindParam(':id',           $this->id);

        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // BÚSQUEDA DE PRODUCTOS
    // Usado en SearchController::index()
    // Busca por nombre o descripción con LIKE
    // ══════════════════════════════════════════════════
    public function search($q){
        $query = "SELECT * FROM products
                  WHERE nombre LIKE :q OR descripcion LIKE :q";

        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $q . '%';
        $stmt->bindParam(':q', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // FILTRAR POR CATEGORÍA
    // Usado en CategoryController::show()
    // ══════════════════════════════════════════════════
    public function getByCategory($id){
        $sql = "SELECT p.*,
                       c.nombre AS categoria,
                       b.nombre AS marca
                FROM products p
                LEFT JOIN categories c ON p.categoria_id = c.id
                LEFT JOIN brands b     ON p.marca_id     = b.id
                WHERE p.categoria_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // FILTRAR POR MARCA
    // Usado en BrandController::show()
    // ══════════════════════════════════════════════════
    public function getByBrand($nombre){
        $query = "SELECT p.*
                  FROM products p
                  INNER JOIN brands b ON p.marca_id = b.id
                  WHERE b.nombre = :nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // AUXILIARES PARA FORMULARIOS
    // Usados en ProductController::create() y edit() para poblar los <select> de categoría y marca
    // ══════════════════════════════════════════════════
    public function getCategorias(){
        return $this->conn->query("SELECT * FROM categories")->fetchAll();
    }

    public function getMarcas(){
        return $this->conn->query("SELECT * FROM brands")->fetchAll();
    }

    public function getProveedores(){
        return $this->conn->query("SELECT * FROM providers")->fetchAll();
    }
}