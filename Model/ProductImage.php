<?php

/**
 * Modelo ProductImage — AnimaMarket
 *
 * Operaciones sobre la tabla `product_images`.
 * Gestiona las imágenes asociadas a cada producto.
 * Hereda de Model: delete()
 */

require_once __DIR__ . '/Model.php';

class ProductImage extends Model {

    protected $table = 'product_images';

    // ══════════════════════════════════════════════════
    // OBTENER IMÁGENES DE UN PRODUCTO
    // Usado en ProductController::attachImages() para cargar todas las fotos de cada producto
    // ══════════════════════════════════════════════════
    public function getByProduct($product_id){
        $stmt = $this->conn->prepare(
            "SELECT * FROM product_images WHERE product_id = :product_id"
        );
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════════
    // GUARDAR IMAGEN
    // Usado en ProductController::store() y update() después de mover el archivo al servidor
    // ══════════════════════════════════════════════════
    public function save($product_id, $url){
        $stmt = $this->conn->prepare(
            "INSERT INTO product_images (product_id, url) VALUES (:product_id, :url)"
        );
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':url',        $url);
        return $stmt->execute();
    }

    // ══════════════════════════════════════════════════
    // ELIMINAR POR URL
    // Usado en ProductController::update() cuando el admin marca imágenes existentes para eliminar
    // ══════════════════════════════════════════════════
    public function deleteByUrl($url){
        $stmt = $this->conn->prepare(
            "DELETE FROM product_images WHERE url = :url"
        );
        $stmt->bindParam(':url', $url);
        return $stmt->execute();
    }
}