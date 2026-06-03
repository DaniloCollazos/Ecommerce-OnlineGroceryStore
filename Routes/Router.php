<?php

/**
 * Router — AnimaMarket
 *
 * Registra todas las rutas de la aplicación y despacha cada petición al controller y método correcto.
 * Separa la lógica de enrutamiento del front controller (index.php), manteniendo index.php limpio y 
 * delegando aquí toda la responsabilidad de "quién atiende qué URL".
 *
 * Flujo completo de una petición: Navegador → public/index.php → Router → Controller → Model → View
 */

class Router {

    /**
     * Tabla de rutas registradas.
     * @var array
     */
    private array $routes = [];

    /**
     * Registra todas las rutas al instanciarse el Router. Cada ruta mapea un par controller+action a una clase y método.
     */
    public function __construct(){
        $this->registerRoutes();
    }

    // ══════════════════════════════════════════════════
    // 1. REGISTRO DE RUTAS
    // Aquí se declara TODA la tabla de rutas del proyecto. Si se agrega un nuevo controller o método, se registra aquí.
    //
    // auth  → true: el usuario debe estar logueado
    // admin → true: el usuario debe ser administrador (rol_id = 2)
    // ══════════════════════════════════════════════════
    private function registerRoutes(){

        // ── AUTENTICACIÓN ──────────────────────────────
        $this->add('auth:login',        'AuthController', 'login');
        $this->add('auth:loginPost',    'AuthController', 'loginPost');
        $this->add('auth:register',     'AuthController', 'register');
        $this->add('auth:registerPost', 'AuthController', 'registerPost');
        $this->add('auth:logout',       'AuthController', 'logout',       auth: true);

        // ── HOME ───────────────────────────────────────
        $this->add('home:index',        'HomeController',    'index');

        // ── PRODUCTOS (cliente) ────────────────────────
        $this->add('product:index',     'ProductController', 'index');
        $this->add('product:show',      'ProductController', 'show');

        // ── PRODUCTOS (admin) ──────────────────────────
        $this->add('product:indexAdmin','ProductController', 'indexAdmin', admin: true);
        $this->add('product:create',    'ProductController', 'create',     admin: true);
        $this->add('product:store',     'ProductController', 'store',      admin: true);
        $this->add('product:edit',      'ProductController', 'edit',       admin: true);
        $this->add('product:update',    'ProductController', 'update',     admin: true);
        $this->add('product:delete',    'ProductController', 'delete',     admin: true);

        // ── CATEGORÍAS ─────────────────────────────────
        $this->add('category:index',    'CategoryController', 'index',     admin: true);
        $this->add('category:create',   'CategoryController', 'create',    admin: true);
        $this->add('category:store',    'CategoryController', 'store',     admin: true);
        $this->add('category:edit',     'CategoryController', 'edit',      admin: true);
        $this->add('category:update',   'CategoryController', 'update',    admin: true);
        $this->add('category:delete',   'CategoryController', 'delete',    admin: true);
        $this->add('category:show',     'CategoryController', 'show');

        // ── MARCAS ─────────────────────────────────────
        $this->add('brand:index',       'BrandController', 'index',        admin: true);
        $this->add('brand:create',      'BrandController', 'create',       admin: true);
        $this->add('brand:store',       'BrandController', 'store',        admin: true);
        $this->add('brand:edit',        'BrandController', 'edit',         admin: true);
        $this->add('brand:update',      'BrandController', 'update',       admin: true);
        $this->add('brand:delete',      'BrandController', 'delete',       admin: true);
        $this->add('brand:show',        'BrandController', 'show');
        $this->add('brand:shop',        'BrandController', 'shop');

        // ── PROVEEDORES ────────────────────────────────
        $this->add('provider:index',    'ProviderController', 'index',     admin: true);
        $this->add('provider:create',   'ProviderController', 'create',    admin: true);
        $this->add('provider:store',    'ProviderController', 'store',     admin: true);
        $this->add('provider:edit',     'ProviderController', 'edit',      admin: true);
        $this->add('provider:update',   'ProviderController', 'update',    admin: true);
        $this->add('provider:delete',   'ProviderController', 'delete',    admin: true);

        // ── USUARIOS ───────────────────────────────────
        $this->add('user:index',        'UserController', 'index',         admin: true);
        $this->add('user:create',       'UserController', 'create',        admin: true);
        $this->add('user:store',        'UserController', 'store',         admin: true);
        $this->add('user:edit',         'UserController', 'edit',          admin: true);
        $this->add('user:update',       'UserController', 'update',        admin: true);
        $this->add('user:delete',       'UserController', 'delete',        admin: true);
        $this->add('user:profile',      'UserController', 'profile',       auth: true);

        // ── ROLES ──────────────────────────────────────
        $this->add('role:index',        'RoleController', 'index',         admin: true);
        $this->add('role:create',       'RoleController', 'create',        admin: true);
        $this->add('role:store',        'RoleController', 'store',         admin: true);
        $this->add('role:edit',         'RoleController', 'edit',          admin: true);
        $this->add('role:update',       'RoleController', 'update',        admin: true);
        $this->add('role:delete',       'RoleController', 'delete',        admin: true);

        // ── CARRITO ────────────────────────────────────
        $this->add('cart:index',        'CartController', 'index',         auth: true);
        $this->add('cart:add',          'CartController', 'add',           auth: true);
        $this->add('cart:delete',       'CartController', 'delete',        auth: true);

        // ── DIRECCIONES ────────────────────────────────
        $this->add('address:index',     'AddressController', 'index',      auth: true);
        $this->add('address:create',    'AddressController', 'create',     auth: true);
        $this->add('address:store',     'AddressController', 'store',      auth: true);
        $this->add('address:edit',      'AddressController', 'edit',       auth: true);
        $this->add('address:update',    'AddressController', 'update',     auth: true);
        $this->add('address:delete',    'AddressController', 'delete',     auth: true);

        // ── ÓRDENES ────────────────────────────────────
        $this->add('order:index',       'OrderController', 'index',        auth: true);
        $this->add('order:checkout',    'OrderController', 'checkout',     auth: true);

        // ── PAGOS ──────────────────────────────────────
        $this->add('payment:index',     'PaymentController', 'index',      admin: true);
        $this->add('payment:checkout',  'PaymentController', 'checkout',   auth: true);
        $this->add('payment:store',     'PaymentController', 'store',      auth: true);

        // ── INVENTARIO ─────────────────────────────────
        $this->add('inventory:index',   'InventoryController', 'index',    admin: true);
        $this->add('inventory:create',  'InventoryController', 'create',   admin: true);
        $this->add('inventory:store',   'InventoryController', 'store',    admin: true);
        $this->add('inventory:edit',    'InventoryController', 'edit',     admin: true);
        $this->add('inventory:update',  'InventoryController', 'update',   admin: true);
        $this->add('inventory:delete',  'InventoryController', 'delete',   admin: true);

        // ── BÚSQUEDA ───────────────────────────────────
        $this->add('search:index',      'SearchController', 'index');

        // ── VENTAS ─────────────────────────────────────
        $this->add('sale:index',        'SaleController', 'index',         admin: true);
        $this->add('sale:show',         'SaleController', 'show',          admin: true);
    }

    // ══════════════════════════════════════════════════
    // 2. AGREGAR UNA RUTA: Registra una entrada en la tabla de rutas.
    // ══════════════════════════════════════════════════
    private function add(
        string $key,            // identificador único 'controller:action'
        string $class,          // nombre de la clase controller
        string $method,         // método a ejecutar
        bool $auth  = false,    // requiere usuario logueado
        bool $admin = false     // requiere rol administrador
    ){
        $this->routes[$key] = [
            'class'  => $class,
            'method' => $method,
            'auth'   => $auth,
            'admin'  => $admin,
        ];
    }

    // ══════════════════════════════════════════════════
    // 3. DESPACHAR LA PETICIÓN
    // Lee controller y action desde la URL, verifica permisos y ejecuta el método correspondiente.
    // ══════════════════════════════════════════════════
    public function dispatch(){

        // Leer y sanitizar parámetros de la URL
        $controller = isset($_GET['controller'])
            ? strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $_GET['controller']))
            : 'home';

        $action = isset($_GET['action'])
            ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['action'])
            : 'index';

        // Construir la clave para buscar en la tabla de rutas
        $key = $controller . ':' . $action;

        // ── RUTA NO REGISTRADA ─────────────────────────
        // Si la combinación controller:action no existe en la tabla, responder con 404
        if(!isset($this->routes[$key])){
            http_response_code(404);
            die("❌ Ruta <strong>{$key}</strong> no encontrada.");
        }

        $route = $this->routes[$key];

        // ── VERIFICAR PERMISOS ─────────────────────────
        // Primero admin (más restrictivo), luego auth
        if($route['admin']){
            // Llama a checkAdmin() de Helpers/Auth.php, redirige al login si no es admin
            checkAdmin();

        } elseif($route['auth']){
            // Llama a requireUser() de Helpers/Auth.php, redirige al login si no hay sesión
            requireUser();
        }

        // ── CARGAR Y EJECUTAR ──────────────────────────
        $class  = $route['class'];
        $method = $route['method'];

        // Verificar que la clase exista (el autoloader la busca automáticamente en Controller/ y Model/)
        if(!class_exists($class)){
            http_response_code(404);
            die("❌ Controlador <strong>{$class}</strong> no encontrado.");
        }

        $instance = new $class();

        // Verificar que el método exista en el controller
        if(!method_exists($instance, $method)){
            http_response_code(404);
            die("❌ Método <strong>{$method}</strong> no existe en <strong>{$class}</strong>.");
        }

        // Todo validado — ejecutar la acción
        $instance->$method();
    }
}