<?php
include_once "Includes/alerts.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = $_SESSION["errors"] ?? [];
unset($_SESSION["errors"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=clients">Gestionar Clientes</a> >
            <span>Editar Cliente</span>
        </nav>
        <h2>Editar Cliente</h2>
        <form action="" method="post">
            <div class="input-field">
                <label for="identidad">Numero Identidad</label>
                <input type="text" id="identidad" name="identidad" value="<?= $client['nro_identidad'] ?>" required>
                <?php if (isset($errors["identidad"])): ?>
                    <p class="error"><?= $errors["identidad"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="nombre">Nombre del Cliente</label>
                <input type="text" id="nombre" name="nombre" value="<?= $client['nombre_cliente'] ?>" min="0" required>
                <?php if (isset($errors["nombre"])): ?>
                    <p class="error"><?= $errors["nombre"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="direccion">Direccion del Cliente</label>
                <input type="text" id="direccion" name="direccion" value="<?= $client['direccion_cliente'] ?>" min="0"
                    required>
                <?php if (isset($errors["direccion"])): ?>
                    <p class="error"><?= $errors["direccion"] ?></p>
                <?php endif; ?>
            </div>
            <div class="form-buttons">
                <button type="submit" class="submit-button">Actualizar Empleado</button>
                <a href="index.php?route=clients" class="delete-button">Volver</a>
            </div>
        </form>
    </div>
</body>

</html>