<?php

/**
 * Controller Provider — AnimaMarket
 *
 * CRUD de proveedores — solo admin.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Provider.php';

class ProviderController extends Controller {

    private $model;

    public function __construct(){
        parent::__construct();
        $this->model = new Provider($this->db);
    }

    public function index(){
        $providers = $this->model->all();
        $this->render('admin/providers/ProviderIndexView', compact('providers'), 'admin');
    }

    public function create(){
        $this->render('admin/providers/ProviderCreateView', [], 'admin');
    }

    public function store(){
        if(empty($_POST['nombre'])){
            $this->redirect('provider', 'create', ['error' => 'campo_vacio']);
        }
        $this->model->nombre    = $_POST['nombre'];
        $this->model->telefono  = $_POST['telefono']  ?? '';
        $this->model->direccion = $_POST['direccion'] ?? '';
        $this->model->create();
        $this->redirect('provider', 'index');
    }

    public function edit(){
        $id       = $_GET['id'] ?? null;
        $provider = $this->model->find($id);

        if(!$provider){
            $this->redirect('provider', 'index');
        }

        $this->render('admin/providers/ProviderEditView', compact('provider'));
    }

    public function update(){
        $this->model->id        = $_POST['id'];
        $this->model->nombre    = $_POST['nombre'];
        $this->model->telefono  = $_POST['telefono']  ?? '';
        $this->model->direccion = $_POST['direccion'] ?? '';
        $this->model->update();
        $this->redirect('provider', 'index');
    }

    public function delete(){
        $id = $_GET['id'] ?? null;
        $this->model->delete($id);
        $this->redirect('provider', 'index');
    }
}