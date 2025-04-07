<?php
include_once "Includes/alerts.php";

$errors = $_SESSION["errors"] ?? [];
unset($_SESSION["errors"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=sales">Gestionar Ventas</a> >
            <span>Editar Venta</span>
        </nav>
        <h2>Editar Venta</h2>
        <form action="" method="post">
            <div class="input-field">
                <label for="id_cliente">Cliente</label>
                <select name="id_cliente" id="id_cliente" class="form-select" required>
                    <option value="" disabled>Selecciona el Cliente</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client["id_cliente"] ?>" <?= $sale["venta_id_cliente"] == $client["id_cliente"] ? "selected" : "" ?>>
                            <?= $client["nombre_cliente"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-field">
                <label for="id_empleado">Empleado</label>
                <select name="id_empleado" id="id_empleado" class="form-select" required>
                    <option value="" disabled>Selecciona el empleado</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= $employee["id_empleado"] ?>"
                            <?= $employee["id_empleado"] == $sale["venta_id_empleado"] ? "selected" : "" ?>>
                            <?= $employee["nombre_empleado"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-field">
                <label for="id_tipo_venta">Tipo de Venta</label>
                <select name="id_tipo_venta" id="id_tipo_venta" class="form-select" required>
                    <option value="" disabled>Selecciona el tipo de venta</option>
                    <?php foreach ($saleTypes as $saleType): ?>
                        <option value="<?= $saleType["id_tipo_venta"] ?>"
                            <?= $saleType["id_tipo_venta"] == $sale["venta_id_empleado"] ? "selected" : "" ?>>
                            <?= $saleType["descripcion"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-buttons">
                <button type="submit" class="submit-button">Actualizar Venta</button>
                <a href="index.php?route=products" class="delete-button">Volver</a>
            </div>
        </form>
    </div>
</body>

</html>