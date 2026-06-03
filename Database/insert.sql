USE ecommerce_online_grocery_store;

-- ================== ROLES ==================
INSERT INTO roles (id, nombre) VALUES
(1, 'cliente'),
(2, 'admin');

-- ================== USERS ==================
INSERT INTO users (nombre, email, telefono, password, genero, direccion, barrio, ciudad, rol_id) VALUES
('Juan Perez', 'juan@email.com', '3001111111', '$2y$10$VKHu7bGovSmDFI8XAvMGzuwgKEvuUjSNiWfAr.4Cff8IOAfnRZjO6', 'Masculino', 'Calle 1', 'Centro', 'Popayan', 1),
('Maria Lopez', 'maria@email.com', '3002222222', '$2y$10$VKHu7bGovSmDFI8XAvMGzuwgKEvuUjSNiWfAr.4Cff8IOAfnRZjO6', 'Femenino', 'Calle 2', 'Norte', 'Popayan', 1),
('Carlos Ruiz', 'carlos@email.com', '3003333333', '$2y$10$VKHu7bGovSmDFI8XAvMGzuwgKEvuUjSNiWfAr.4Cff8IOAfnRZjO6', 'Masculino', 'Calle 3', 'Sur', 'Popayan', 1),
('Laura Gomez', 'laura@email.com', '3004444444', '$2y$10$VKHu7bGovSmDFI8XAvMGzuwgKEvuUjSNiWfAr.4Cff8IOAfnRZjO6', 'Femenino', 'Calle 4', 'Centro', 'Popayan', 1),
('Admin User', 'admin@email.com', '3005555555', '$2y$10$VKHu7bGovSmDFI8XAvMGzuwgKEvuUjSNiWfAr.4Cff8IOAfnRZjO6', 'Otro', 'Oficina', 'Centro', 'Popayan', 2);

-- ================== ADDRESSES ==================
INSERT INTO addresses (user_id, direccion, ciudad, barrio, referencia) VALUES
(1, 'Cra 1 #10-20', 'Popayan', 'Centro', 'Frente al parque'),
(2, 'Cra 2 #20-30', 'Popayan', 'Norte', 'Casa azul'),
(3, 'Cra 3 #30-40', 'Popayan', 'Sur', 'Tienda esquina'),
(4, 'Cra 4 #40-50', 'Popayan', 'Centro', 'Apto 201'),
(1, 'Cra 5 #50-60', 'Popayan', 'Centro', 'Puerta roja');

-- ================== CATEGORIES ==================
INSERT INTO categories (nombre) VALUES
('Café'),
('Arroz'),
('Legumbres'),
('Cereales'),
('Granos Premium');

-- ================== BRANDS ==================
INSERT INTO brands (nombre) VALUES
('Juan Valdez'),
('Diana'),
('Roa'),
('Florhuila'),
('La Muñeca');

-- ================== PROVIDERS ==================
INSERT INTO providers (nombre, telefono, direccion) VALUES
('Proveedor 1', '3100000001', 'Calle A'),
('Proveedor 2', '3100000002', 'Calle B'),
('Proveedor 3', '3100000003', 'Calle C'),
('Proveedor 4', '3100000004', 'Calle D'),
('Proveedor 5', '3100000005', 'Calle E');

-- ================== PRODUCTS (GRANOS) ==================
INSERT INTO products (nombre, descripcion, categoria_id, marca_id, proveedor_id, precio_costo, precio, stock) VALUES
('Café Molido Premium 500g', 'Café colombiano tostado medio', 1, 1, 1, 12000, 18000, 40),
('Café en Grano 1kg', 'Grano entero para moler', 1, 1, 2, 20000, 28000, 25),
('Arroz Blanco 1kg', 'Arroz tipo exportación', 2, 3, 3, 2500, 4000, 100),
('Arroz Integral 1kg', 'Arroz integral saludable', 2, 4, 3, 3000, 4500, 80),
('Lentejas 500g', 'Lentejas seleccionadas', 3, 2, 4, 1800, 3000, 70),
('Frijol Rojo 500g', 'Frijol cargamanto', 3, 2, 4, 2000, 3500, 60),
('Avena en Hojuelas 400g', 'Avena natural', 4, 5, 5, 1500, 2800, 90),
('Quinua 500g', 'Grano andino premium', 5, 5, 2, 6000, 9000, 30),
('Maíz Pira 500g', 'Para crispetas', 4, 3, 1, 1200, 2500, 85),
('Garbanzos 500g', 'Alta calidad', 3, 2, 4, 2200, 3600, 65);

-- ================== PRODUCT IMAGES ==================
INSERT INTO product_images (product_id, url) VALUES
(1, 'img/products/cafe_molido.jpg'),
(2, 'img/products/cafe_grano.jpg'),
(3, 'img/products/arroz_blanco.jpg'),
(4, 'img/products/arroz_integral.jpg'),
(5, 'img/products/lentejas.jpg'),
(6, 'img/products/frijol.jpg'),
(7, 'img/products/avena.jpg'),
(8, 'img/products/quinua.jpg'),
(9, 'img/products/maiz.jpg'),
(10, 'img/products/garbanzos.jpg');

-- ================== INVENTORY ==================
INSERT INTO inventory (product_id, stock_actual, stock_minimo) VALUES
(1, 40, 10),
(2, 25, 5),
(3, 100, 20),
(4, 80, 20),
(5, 70, 15),
(6, 60, 15),
(7, 90, 20),
(8, 30, 5),
(9, 85, 20),
(10, 65, 15);

-- ================== CARTS ==================
INSERT INTO carts (user_id) VALUES
(1),(2),(3),(4),(1);

-- ================== CART ITEMS ==================
INSERT INTO cart_items (cart_id, product_id, cantidad) VALUES
(1, 1, 2),
(1, 3, 1),
(2, 2, 1),
(3, 5, 3),
(4, 8, 1);

-- ================== ORDERS ==================
INSERT INTO orders (user_id, total, estado) VALUES
(1, 36000, 'pendiente'),
(2, 28000, 'pagado'),
(3, 9000, 'enviado'),
(4, 2500, 'entregado'),
(1, 45000, 'pendiente');

-- ================== ORDER ITEMS ==================
INSERT INTO order_items (order_id, product_id, cantidad, precio) VALUES
(1, 1, 2, 18000),
(2, 2, 1, 28000),
(3, 5, 3, 3000),
(4, 9, 1, 2500),
(5, 3, 5, 4000);

-- ================== PAYMENTS ==================
INSERT INTO payments (order_id, metodo, estado) VALUES
(1, 'efectivo', 'pendiente'),
(2, 'tarjeta', 'pagado'),
(3, 'nequi', 'pagado'),
(4, 'efectivo', 'pagado'),
(5, 'tarjeta', 'pendiente');

-- ================== REVIEWS ==================
INSERT INTO reviews (user_id, product_id, rating, comentario) VALUES
(1, 1, 5, 'Excelente café'),
(2, 2, 4, 'Muy buen grano'),
(3, 3, 3, 'Normal'),
(4, 4, 5, 'Muy saludable'),
(1, 5, 4, 'Buena calidad');