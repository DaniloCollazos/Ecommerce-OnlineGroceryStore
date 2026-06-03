<?php

/**
 * Controller Home — AnimaMarket
 * Muestra la página principal del cliente.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Product.php';
require_once __DIR__ . '/../Model/ProductImage.php';

class HomeController extends Controller {

    private $model;
    private $imageModel;

    public function __construct(){
        parent::__construct();
        $this->model      = new Product($this->db);
        $this->imageModel = new ProductImage($this->db);
    }

    public function index(){
        $productos = $this->model->all();
        $this->attachImages($productos);
        $this->render('home/home', compact('productos'), 'main');
    }

    private function attachImages(array &$productos){
        foreach($productos as &$producto){
            $imagenes = $this->imageModel->getByProduct($producto['id']);
            $producto['imagenes'] = $imagenes;
        }
    }
}
