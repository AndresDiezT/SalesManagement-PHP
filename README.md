📦 Proyecto: Gestor de Ventas
Aplicación web desarrollada en PHP para la gestión de productos, ventas, clientes y empleados. No utiliza frameworks, lo que permite mayor control sobre la lógica del proyecto.

🚀 Requisitos
PHP 8.x
MySQL/MariaDB
Servidor local como XAMPP, WAMP o similar
Navegador web

⚙️ Instalación
Clona el repositorio:

bash
git clone https://github.com/tuusuario/gestor-ventas.git
Importa la base de datos:

en la raíz de la carpeta esta el archivo sales.sql.

Importa ese archivo en tu servidor MySQL usando phpMyAdmin o consola.

Configura la conexión:

Edita los datos de conexión en el archivo Connection.php:

php
private $host = "localhost";
private $user = "root";
private $password = "tu_contraseña";
private $db = "2873797_adso";
Accede a la aplicación:

Abre el proyecto desde tu navegador: http://localhost/

👤 Usuario administrador automático
Al iniciar el sistema, se crea automáticamente un usuario administrador si no existe:

Correo: AdminGod

Contraseña: admin123

Esto se gestiona desde el archivo setup.php.

📁 Estructura principal
Controllers/
Helpers/
Views/
Models/
Connection.php
index.php
README.md
❗ Notas importantes
No se usan migraciones ni seeds automáticos. La base de datos debe configurarse manualmente desde el archivo SQL proporcionado.

Los controladores y rutas están definidos en index.php y funcionan por medio de parámetros GET.
