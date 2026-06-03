<?php

/**
 * Controller Auth — AnimaMarket
 *
 * Maneja autenticación de usuarios. Login, registro y logout.
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Model/User.php';

class AuthController extends Controller {

    private $model;

    public function __construct(){
        // Hereda la conexión $this->db del Controller base
        parent::__construct();
        $this->model = new User($this->db);
    }

    // ══════════════════════════════════════════════════
    // MOSTRAR LOGIN
    // ══════════════════════════════════════════════════
    public function login(){

        // Si ya hay sesión activa, redirigir al inicio
        if(isLoggedIn()){
            $this->redirect('product', 'index');
        }

        $this->render('auth/login');
    }

    // ══════════════════════════════════════════════════
    // PROCESAR LOGIN
    // Verifica email y contraseña. Redirige según el rol del usuario.
    // ══════════════════════════════════════════════════
    public function loginPost(){

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validación básica antes de consultar la BD
        if(empty($email) || empty($password)){
            $this->redirect('Auth', 'login', ['error' => 'campos_vacios']);
        }

        $user = $this->model->getByEmail($email);

        if($user && password_verify($password, $user['password'])){

            // Regenerar ID de sesión al autenticarse
            // (previene Session Fixation — definido en Config/session.php)
            regenerateSession();

            // Guardar datos del usuario en sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre']  = $user['nombre'];
            $_SESSION['rol_id']  = $user['rol_id'];

            // Redirigir según rol
            if($user['rol_id'] == 2){
                // Administrador → panel admin
                $this->redirect('product', 'indexAdmin');
            } else {
                // Cliente → tienda
                $this->redirect('product', 'index');
            }

        } else {
            // Credenciales incorrectas
            $this->redirect('Auth', 'login', ['error' => 'credenciales']);
        }
    }

    // ══════════════════════════════════════════════════
    // MOSTRAR REGISTRO
    // ══════════════════════════════════════════════════
    public function register(){

        if(isLoggedIn()){
            $this->redirect('product', 'index');
        }

        $this->render('auth/register');
    }

    // ══════════════════════════════════════════════════
    // PROCESAR REGISTRO
    // Valida que el email no exista y crea el usuario.
    // ══════════════════════════════════════════════════
    public function registerPost(){

        $nombre   = trim($_POST['nombre']   ?? '');
        $email    = trim($_POST['email']    ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validar campos obligatorios
        if(empty($nombre) || empty($email) || empty($password)){
            $this->redirect('Auth', 'register', ['error' => 'campos_vacios']);
        }

        // Verificar que el email no esté registrado
        if($this->model->emailExists($email)){
            $this->redirect('Auth', 'login', ['error' => 'email_existe']);
        }

        // Asignar valores al modelo
        $this->model->nombre   = $nombre;
        $this->model->email    = $email;
        $this->model->telefono = $telefono;
        $this->model->password = password_hash($password, PASSWORD_DEFAULT);
        $this->model->rol_id   = 1; // Cliente por defecto

        if($this->model->create()){
            $this->redirect('Auth', 'login', ['registro' => 'exitoso']);
        } else {
            $this->redirect('Auth', 'register', ['error' => 'error_registro']);
        }
    }

    // ══════════════════════════════════════════════════
    // LOGOUT
    // Destruye la sesión completamente usando destroySession() de Config/session.php
    // ══════════════════════════════════════════════════
    public function logout(){
        destroySession();
        $this->redirect('home', 'index');
    }
}