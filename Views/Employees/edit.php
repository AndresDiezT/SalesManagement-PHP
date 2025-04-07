<?php
include_once "Includes/alerts.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $_SESSION["errors"] ?? [];
unset($_SESSION["errors"]);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=employees">Gestionar Empleados</a> >
            <span>Editar Empleado</span>
        </nav>
        <h2>Editar Empleado</h2>

        <form method="post">
            <div class="input-field">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= $employee['nombre_empleado'] ?>" required>
                <?php if (isset($errors["nombre"])): ?>
                    <p class="error"><?= $errors["nombre"] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-field">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" value="<?= $employee['usuario'] ?>" required>
                <?php if (isset($errors["usuario"])): ?>
                    <p class="error"><?= $errors["usuario"] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-field">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" value="<?= $employee['correo'] ?>" required>
                <?php if (isset($errors["correo"])): ?>
                    <p class="error"><?= $errors["correo"] ?></p>
                <?php endif; ?>
            </div>

            <div class="form-buttons">
                <button type="submit" class="submit-button">Actualizar Empleado</button>
                <a href="index.php?route=employees" class="delete-button">Volver</a>
            </div>
        </form>
    </div>
</body>

</html>