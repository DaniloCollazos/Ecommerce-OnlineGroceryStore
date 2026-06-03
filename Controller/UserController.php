<?php

/**
 * Controller User — AnimaMarket
 *
 * CRUD de usuarios — solo admin.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/User.php';
require_once __DIR__ . '/../Model/Role.php';

class UserController extends Controller {

    private $model;

    public function __construct(){
        parent::__construct();
        $this->model = new User($this->db);
    }

    public function index(){
        $users = $this->model->all();
        $this->render('admin/users/UsersIndexView', compact('users'), 'admin');
    }

    public function create(){
        $roleModel = new Role($this->db);
        $roles     = $roleModel->all();
        $this->render('admin/users/UsersCreateView', compact('roles'), 'admin');
    }

    public function store(){
        $this->model->nombre   = $_POST['nombre'];
        $this->model->email    = $_POST['email'];
        $this->model->telefono = $_POST['telefono'] ?? '';
        $this->model->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $this->model->rol_id   = $_POST['rol_id'] ?? 1;
        $this->model->create();
        $this->redirect('user', 'index');
    }

    public function edit(){
        $id   = $_GET['id'] ?? null;
        $user = $this->model->find($id);

        if(!$user){
            $this->redirect('user', 'index');
        }

        $this->render('admin/users/UsersEditView', compact('user'), 'admin');
    }

    public function update(){
        $this->model->id       = $_POST['id'];
        $this->model->nombre   = $_POST['nombre'];
        $this->model->email    = $_POST['email'];
        $this->model->telefono = $_POST['telefono'] ?? '';
        $this->model->update();
        $this->redirect('user', 'index');
    }

    public function delete(){
        $id = $_GET['id'] ?? null;
        $this->model->delete($id);
        $this->redirect('user', 'index');
    }

    public function profile(){
        $usuario = currentUser();
        
        if(!$usuario){
            $this->redirect('auth', 'login');
        }

        $user = $this->model->find($usuario['id']);
        $this->render('user/profile', compact('user'), 'main');
    }
}