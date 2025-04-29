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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Cliente</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=clients">Gestionar Clientes</a> >
            <span>Subir Cliente</span>
        </nav>
        <h2>Subir Cliente</h2>
        <form method="post">
            <div class="input-field">
                <label for="identidad">Numero Ciudadanía</label>
                <input type="text" id="identidad" name="identidad" value="<?= $old['identidad'] ?? '' ?>" required>
                <?php if (isset($errors["identidad"])): ?>
                    <p class="error"><?= $errors["identidad"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="nombre">Nombre y Apellidos</label>
                <input type="text" id="nombre" name="nombre" value="<?= $old['nombre'] ?? '' ?>" required>
                <?php if (isset($errors["nombre"])): ?>
                    <p class="error"><?= $errors["nombre"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" value="<?= $old['correo'] ?? '' ?>" required>
                <?php if (isset($errors["correo"])): ?>
                    <p class="error"><?= $errors["correo"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="direccion">Direccion</label>
                <input type="text" id="direccion" name="direccion" value="<?= $old['direccion'] ?? '' ?>" required>
                <?php if (isset($errors["direccion"])): ?>
                    <p class="error"><?= $errors["direccion"] ?></p>
                <?php endif; ?>
            </div>

            <div class="form-buttons">
                <button type="submit" class="submit-button">Subir Cliente</button>
                <a href="index.php?route=clients" class="delete-button">Volver</a>
            </div>
        </form>
    </div>
</body>

</html>