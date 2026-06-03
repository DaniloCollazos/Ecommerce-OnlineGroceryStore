<?php

/**
 * Modelo Cart — AnimaMarket
 *
 * Operaciones sobre las tablas `carts` y `cart_items`.
 *
 * El carrito funciona en sesión ($_SESSION['cart']) para usuarios no logueados, 
 * y en BD para usuarios con sesión activa.
 * Hereda de Model: find(), delete()
 */

require_once __DIR__ . '/Model.php';

class Cart extends Model {

    protected $table = 'carts';

    // ══════════════════════════════════════════════════
    // OBTENER O CREAR CARRITO
    // Busca el carrito del usuario en BD.
    // Si no existe, lo crea automáticamente.
    // Método privado — solo lo usan los métodos de esta clase.
    // ══════════════════════════════════════════════════
    private function getCartId($user_id){
        $stmt = $this->conn->prepare(
            "SELECT id FROM carts WHERE user_id = ?"
        );
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch();

        if(!$cart){
            // No tiene carrito aún — crear uno nuevo
            $stmt = $this->conn->prepare(
                "INSERT INTO carts (user_id) VALUES (?)"
            );
            $stmt->execute([$user_id]);
            return $this->conn->lastInsertId();
        }

        return $cart['id'];
    }

    // ══════════════════════════════════════════════════
    // OBTENER CARRITO COMPLETO
    // Retorna todos los items con datos del producto (nombre, precio, stock) para mostrar en la vista
    // ══════════════════════════════════════════════════
    public function getCart($user_id){
        $cart_id = $this->getCartId($user_id);

        $sql = "SELECT ci.*, p.nombre, p.precio, p.stock
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.cart_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$cart_id]);
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // AGREGAR PRODUCTO AL CARRITO
    // Si el producto ya existe en el carrito, suma +1.
    // Si no existe, lo inserta con cantidad 1.
    // ══════════════════════════════════════════════════
    public function addProduct($user_id, $product_id){
        $cart_id = $this->getCartId($user_id);

        // Verificar si el producto ya está en el carrito
        $stmt = $this->conn->prepare(
            "SELECT id FROM cart_items
             WHERE cart_id = :cart_id AND product_id = :product_id"
        );
        $stmt->bindParam(':cart_id',    $cart_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            // Ya existe — incrementar cantidad
            $query = "UPDATE cart_items
                      SET cantidad = cantidad + 1
                      WHERE cart_id = :cart_id AND product_id = :product_id";
        } else {
            // No existe — insertar nuevo item
            $query = "INSERT INTO cart_items (cart_id, product_id, cantidad)
                      VALUES (:cart_id, :product_id, 1)";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cart_id',    $cart_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // ELIMINAR ITEM DEL CARRITO
    // Recibe el id del cart_item (no del producto)
    // ══════════════════════════════════════════════════
    public function removeItem($id){
        $stmt = $this->conn->prepare(
            "DELETE FROM cart_items WHERE id = :id"
        );
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // VACIAR CARRITO COMPLETO
    // Usado en OrderController::checkout() después de confirmar el pedido para limpiar el carrito
    // ══════════════════════════════════════════════════
    public function clear($user_id){
        $cart_id = $this->getCartId($user_id);
        $stmt = $this->conn->prepare(
            "DELETE FROM cart_items WHERE cart_id = :cart_id"
        );
        $stmt->bindParam(':cart_id', $cart_id);
        return $stmt->execute();
    }
}