<?php
include_once "Includes/alerts.php";

if (isset($_SESSION["id_empleado"]) || isset($_COOKIE["id_empleado"])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>

    <div class="container">
        <h2>Iniciar Sesión</h2>

        <form method="POST">
            <div class="input-field">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="input-field">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="remember-field">
                <input type="checkbox" id="recordar" name="recordar">
                <label for="recordar">Recordarme</label>
            </div>

            <button type="submit" class="submit-button">Ingresar</button>
        </form>
    </div>

</body>

</html>