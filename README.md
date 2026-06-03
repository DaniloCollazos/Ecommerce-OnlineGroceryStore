# 🌾 AnimaMarket - Online Grocery Store 🌾

#### **Ecommerce-OnlineGroceryStore** · PHP · PHTML · CSS · JavaScript · Arquitectura MVC

AnimaMarket es una tienda de abarrotes en línea donde los clientes pueden comprar productos de la canasta básica desde cualquier dispositivo. Lo que nos diferencia no es solo vender arroz, fríjoles o azúcar por internet — es la forma en que se venden.

Cuando el usuario escoge una cantidad, la pantalla cobra vida: los granos caen desde un silo animado, una balanza sube su fiel en tiempo real y una bolsa se va llenando hasta sellarse. Cada tarjeta de producto, el login, el carrito y el checkout tienen animaciones propias pensadas para que comprar se sienta tan natural como ir a la tienda de la esquina.

---

## Animaciones destacadas

- **Selector de cantidad** — granos cayendo, balanza con física real y bolsa que se llena y sella sola.
- **Tarjetas de producto** — hover con elevación, zoom de imagen y revelado del botón de compra.
- **Login** — el formulario entra con una animación de puerta abriéndose; shake en campos vacíos.
- **Carrito** — micro-rebote en el icono cada vez que se agrega un producto.
- **Checkout** — stepper con slide entre pasos y confeti al confirmar el pedido.

---

## Estructura del proyecto

```
Ecommerce-OnlineGroceryStore/
│
├── Model/          → Capa de datos. Clase base Model.php y un modelo por cada tabla de la BD.
├── View/           → Capa de presentación. Layouts, partials y vistas por módulo (cliente y admin).
├── Controller/     → Capa de lógica. Clase base Controller.php y un controller por módulo.
│
├── Config/         → Conexión PDO (PHP Data Objects), constantes globales e inicio de sesión.
├── Routes/         → Motor de enrutamiento (Router.php) y mapa de rutas (web.php).
├── Helpers/        → Verificación de roles (Auth.php) y funciones utilitarias (Functions.php).
├── Database/       → schema.sql con las tablas y seeds.sql con datos de prueba del equipo.
├── Storage/        → Imágenes de productos subidas desde el panel admin.
│
└── public/         → Document root del servidor. Contiene index.php, .htaccess y los assets estáticos (css/, js/, img/).
```

**Archivos en la raíz:**

`.env.example` — plantilla de variables de entorno que cada desarrollador copia como `.env` local.
`.gitignore` — excluye `.env`, `Storage/` y cachés del repositorio.
`README.md` — este archivo.

---

## Instalación

1. Clonar el repo y apuntar el document root del servidor a `public/`.
2. Copiar `.env.example` → `.env` y completar las credenciales de la BD.
3. Importar `Database/schema.sql` y luego `Database/seeds.sql`.
4. Abrir `http://localhost/` — el front controller enruta todo automáticamente.

---

## Equipo

- Adrian Galindez
- Danilo Collazos
- Maira Garcia
- Verónica Pajoy