-- ================== CREAR BASE DE DATOS ==================
-- Esto lo hace Render automáticamente, no necesitas ejecutarlo
-- CREATE DATABASE ecommerce_online_grocery_store;


-- ================== ROLES ==================
CREATE TABLE roles (
    id     SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);


-- ================== USUARIOS ==================
CREATE TABLE users (
    id               SERIAL PRIMARY KEY,
    nombre           VARCHAR(100),
    email            VARCHAR(100) UNIQUE,
    telefono         VARCHAR(20),
    password         VARCHAR(255),
    genero           VARCHAR(20),
    direccion        VARCHAR(150),
    barrio           VARCHAR(100),
    ciudad           VARCHAR(100),
    lat              DECIMAL(10,8),
    lng              DECIMAL(11,8),
    estado           VARCHAR(20)  DEFAULT 'Activo',
    fecha_registro   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    rol_id           INT,

    FOREIGN KEY (rol_id) REFERENCES roles(id)
);


-- ================== DIRECCIONES ==================
CREATE TABLE addresses (
    id        SERIAL PRIMARY KEY,
    user_id   INT,
    direccion VARCHAR(200),
    ciudad    VARCHAR(100),
    barrio    VARCHAR(100),
    referencia VARCHAR(150),

    FOREIGN KEY (user_id) REFERENCES users(id)
);


-- ================== CATEGORIAS ==================
CREATE TABLE categories (
    id     SERIAL PRIMARY KEY,
    nombre VARCHAR(100)
);


-- ================== MARCAS ==================
CREATE TABLE brands (
    id     SERIAL PRIMARY KEY,
    nombre VARCHAR(100)
);


-- ================== PROVEEDORES ==================
CREATE TABLE providers (
    id        SERIAL PRIMARY KEY,
    nombre    VARCHAR(100),
    telefono  VARCHAR(20),
    direccion VARCHAR(150)
);


-- ================== PRODUCTOS ==================
CREATE TABLE products (
    id            SERIAL PRIMARY KEY,
    nombre        VARCHAR(200),
    descripcion   TEXT,
    categoria_id  INT,
    marca_id      INT,
    proveedor_id  INT,
    precio_costo  DECIMAL(10,2),
    precio        DECIMAL(10,2),
    stock         INT,
    fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (categoria_id) REFERENCES categories(id),
    FOREIGN KEY (marca_id)     REFERENCES brands(id),
    FOREIGN KEY (proveedor_id) REFERENCES providers(id)
);


-- ================== IMAGENES DE PRODUCTOS ==================
CREATE TABLE product_images (
    id         SERIAL PRIMARY KEY,
    product_id INT,
    url        VARCHAR(255),

    FOREIGN KEY (product_id) REFERENCES products(id)
);


-- ================== INVENTARIO ==================
CREATE TABLE inventory (
    id           SERIAL PRIMARY KEY,
    product_id   INT,
    stock_actual INT,
    stock_minimo INT,

    FOREIGN KEY (product_id) REFERENCES products(id)
);


-- ================== CARRITO ==================
CREATE TABLE carts (
    id      SERIAL PRIMARY KEY,
    user_id INT,

    FOREIGN KEY (user_id) REFERENCES users(id)
);


-- ================== ITEMS DEL CARRITO ==================
CREATE TABLE cart_items (
    id         SERIAL PRIMARY KEY,
    cart_id    INT,
    product_id INT,
    cantidad   INT,

    FOREIGN KEY (cart_id)    REFERENCES carts(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);


-- ================== ORDENES ==================
CREATE TABLE orders (
    id      SERIAL PRIMARY KEY,
    user_id INT,
    total   DECIMAL(10,2),
    estado  VARCHAR(50),
    fecha   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
);


-- ================== DETALLE DE ORDENES ==================
CREATE TABLE order_items (
    id         SERIAL PRIMARY KEY,
    order_id   INT,
    product_id INT,
    cantidad   INT,
    precio     DECIMAL(10,2),

    FOREIGN KEY (order_id)   REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);


-- ================== PAGOS ==================
CREATE TABLE payments (
    id       SERIAL PRIMARY KEY,
    order_id INT,
    metodo   VARCHAR(50),
    estado   VARCHAR(50),
    fecha    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id) REFERENCES orders(id)
);


-- ================== RESEÑAS ==================
CREATE TABLE reviews (
    id         SERIAL PRIMARY KEY,
    user_id    INT,
    product_id INT,
    rating     INT,
    comentario TEXT,
    fecha      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)    REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
