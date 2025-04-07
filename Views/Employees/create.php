<?php
include_once "Includes/alerts.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = $_SESSION["errors"] ?? [];
$old = $_SESSION["old_data"] ?? [];
unset($_SESSION["errors"], $_SESSION["old_data"]);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Empleado</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=employees">Gestionar Empleados</a> >
            <span>Subir Empleado</span>
        </nav>
        <h2>Subir Empleado</h2>

        <form method="POST">
            <div class="combined-input-field">
                <div class="input-field">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="<?= $old['nombre'] ?? '' ?>" required>
                    <?php if (isset($errors["nombre"])): ?>
                        <p class="error"><?= $errors["nombre"] ?></p>
                    <?php endif; ?>
                </div>

                <div class="input-field">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" value="<?= $old['usuario'] ?? '' ?>" required>
                    <?php if (isset($errors["usuario"])): ?>
                        <p class="error"><?= $errors["usuario"] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-field">
                <label for="correo">Correo Electrónico:</label>
                <input type="correo" id="correo" name="correo" value="<?= $old['correo'] ?? '' ?>" required>
                <?php if (isset($errors["correo"])): ?>
                    <p class="error"><?= $errors["correo"] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-field">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="empleado_password"
                    value="<?= $old['empleado_password'] ?? '' ?>" required>
                <?php if (isset($errors["empleado_password"])): ?>
                    <p class="error"><?= $errors["empleado_password"] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-field">
                <label for="confirm_password">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirmar_contraseña"
                    value="<?= $old['confirmar_contraseña'] ?? '' ?>" required>
                <?php if (isset($errors["confirmar_contraseña"])): ?>
                    <p class="error"><?= $errors["confirmar_contraseña"] ?></p>
                <?php endif; ?>
            </div>
            <div class="form-buttons">
                <button type="submit" class="submit-button">Subir Empleado</button>
                <a href="index.php?route=employees" class="delete-button">Volver</a>
            </div>
        </form>
    </div>
</body>

</html>