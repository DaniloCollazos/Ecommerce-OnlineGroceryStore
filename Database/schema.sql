CREATE DATABASE ecommerce_online_grocery_store;

USE ecommerce_online_grocery_store;


-- //==================TABLA ROLES ================

CREATE TABLE roles(
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(50) NOT NULL
);


-- //==================TABLA USUARIOS ================
CREATE TABLE users(

 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100),
 email VARCHAR(100) UNIQUE,
 telefono VARCHAR(20),
 password VARCHAR(255),

 genero VARCHAR(20),
 direccion VARCHAR(150),
 barrio VARCHAR(100),
 ciudad VARCHAR(100),

 lat DECIMAL(10,8),
 lng DECIMAL(11,8),

 estado VARCHAR(20) DEFAULT 'Activo',
 fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

 rol_id INT,

 FOREIGN KEY (rol_id) REFERENCES roles(id)

);



-- //==================TABLA DIRECCIONES ================
CREATE TABLE addresses(

 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 direccion VARCHAR(200),
 ciudad VARCHAR(100),
 barrio VARCHAR(100),
 referencia VARCHAR(150),

 FOREIGN KEY (user_id) REFERENCES users(id)

);



-- //==================TABLA CATEGORIAS ================
CREATE TABLE categories(

 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100)

);



-- //==================TABLA MARCAS ================
CREATE TABLE brands(

 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100)

);



-- //====================TABLA PROVEEDORES============
CREATE TABLE providers(

 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100),
 telefono VARCHAR(20),
 direccion VARCHAR(150)

);


-- //====================TABLA PRODUCTOS============
CREATE TABLE products(

 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(200),
 descripcion TEXT,

 categoria_id INT,
 marca_id INT,
 proveedor_id INT,

 precio_costo DECIMAL(10,2),
 precio DECIMAL(10,2),

 stock INT,

 fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

 FOREIGN KEY (categoria_id) REFERENCES categories(id),
 FOREIGN KEY (marca_id) REFERENCES brands(id),
 FOREIGN KEY (proveedor_id) REFERENCES providers(id)

);


-- //===================TABLA IMAGENES DE PRODUCTOS ==========
CREATE TABLE product_images(

 id INT AUTO_INCREMENT PRIMARY KEY,
 product_id INT,
 url VARCHAR(255),

 FOREIGN KEY (product_id) REFERENCES products(id)

);



-- //====================TABLA INVENTARIO============
CREATE TABLE inventory(

 id INT AUTO_INCREMENT PRIMARY KEY,
 product_id INT,
 stock_actual INT,
 stock_minimo INT,

 FOREIGN KEY (product_id) REFERENCES products(id)

);


-- //====================TABLA CARRITO============
CREATE TABLE carts(

 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,

 FOREIGN KEY (user_id) REFERENCES users(id)

);


-- //=====================TABLA ITEMS DEL CARRITO ===========
CREATE TABLE cart_items(

 id INT AUTO_INCREMENT PRIMARY KEY,
 cart_id INT,
 product_id INT,
 cantidad INT,

 FOREIGN KEY (cart_id) REFERENCES carts(id),
 FOREIGN KEY (product_id) REFERENCES products(id)

);





-- //=======================TABLA ORDENES ================
CREATE TABLE orders(

 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 total DECIMAL(10,2),
 estado VARCHAR(50),
 fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

 FOREIGN KEY (user_id) REFERENCES users(id)

);



-- //=========================TABLA DETALLE DE ORDENES ================
CREATE TABLE order_items(

 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT,
 product_id INT,
 cantidad INT,
 precio DECIMAL(10,2),

 FOREIGN KEY (order_id) REFERENCES orders(id),
 FOREIGN KEY (product_id) REFERENCES products(id)

);


-- //=========================TABLA PAGOS ================
CREATE TABLE payments(

 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT,
 metodo VARCHAR(50),
 estado VARCHAR(50),
 fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

 FOREIGN KEY (order_id) REFERENCES orders(id)

);


-- //=========================TABLA RESEÑAS ================
CREATE TABLE reviews(

 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 product_id INT,
 rating INT,
 comentario TEXT,
 fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

 FOREIGN KEY (user_id) REFERENCES users(id),
 FOREIGN KEY (product_id) REFERENCES products(id)

);