<?php

/**
 * Controller Role — AnimaMarket
 *
 * CRUD de roles — solo admin.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/Role.php';

class RoleController extends Controller {

    private $model;

    public function __construct(){
        parent::__construct();
        $this->model = new Role($this->db);
    }

    public function index(){
        $roles = $this->model->all();
        $this->render('admin/roles/RoleIndexView', compact('roles'), 'admin');
    }

    public function create(){
        $this->render('admin/roles/RoleCreateView', [], 'admin');
    }

    public function store(){
        if(empty($_POST['nombre'])){
            $this->redirect('role', 'create', ['error' => 'campo_vacio']);
        }
        $this->model->nombre = $_POST['nombre'];
        $this->model->create();
        $this->redirect('role', 'index');
    }

    public function edit(){
        $id   = $_GET['id'] ?? null;
        $role = $this->model->find($id);

        if(!$role){
            $this->redirect('role', 'index');
        }

        $this->render('admin/roles/RoleEditView', compact('role'), 'admin');
    }

    public function update(){
        $this->model->id     = $_POST['id'];
        $this->model->nombre = $_POST['nombre'];
        $this->model->update();
        $this->redirect('role', 'index');
    }

    public function delete(){
        $id = $_GET['id'] ?? null;
        $this->model->delete($id);
        $this->redirect('role', 'index');
    }
}