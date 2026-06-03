<?php

/**
 * Controller Inventory — AnimaMarket
 *
 * CRUD de inventario — solo admin.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Inventory.php';
require_once __DIR__ . '/../Model/Product.php';

class InventoryController extends Controller {

    private $model;

    public function __construct(){
        parent::__construct();
        $this->model = new Inventory($this->db);
    }

    public function index(){
        $inventory  = $this->model->all();
        $lowStock   = $this->model->getLowStock();
        $this->render('admin/inventory/InventoryIndexView',
            compact('inventory', 'lowStock'),
            'admin'
        );
    }

    public function create(){
        $productModel = new Product($this->db);
        $products     = $productModel->all();
        $this->render('admin/inventory/InventoryCreateView', compact('products'), 'admin');
    }

    public function store(){
        $this->model->product_id   = $_POST['product_id'];
        $this->model->stock_actual = $_POST['stock_actual'];
        $this->model->stock_minimo = $_POST['stock_minimo'];
        $this->model->create();
        $this->redirect('inventory', 'index');
    }

    public function edit(){
        $id        = $_GET['id'] ?? null;
        $inventory = $this->model->find($id);

        if(!$inventory){
            $this->redirect('inventory', 'index');
        }

        $this->render('admin/inventory/InventoryEditView', compact('inventory'), 'admin');
    }

    public function update(){
        $this->model->id           = $_POST['id'];
        $this->model->stock_actual = $_POST['stock_actual'];
        $this->model->stock_minimo = $_POST['stock_minimo'];
        $this->model->update();
        $this->redirect('inventory', 'index');
    }

    public function delete(){
        $id = $_GET['id'] ?? null;
        $this->model->delete($id);
        $this->redirect('inventory', 'index');
    }
}