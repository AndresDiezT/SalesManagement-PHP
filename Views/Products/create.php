<?php
include_once "Includes/alerts.php";

$errors = $_SESSION["errors"] ?? [];
$old = $_SESSION["old_data"] ?? [];
unset($_SESSION["errors"], $_SESSION["old_data"]);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Producto</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=products">Gestionar Productos</a> >
            <span>Subir Producto</span>
        </nav>
        <h2>Subir Producto</h2>
        <form action="" method="post">
            <div class="input-field">
                <label for="codigo">Código</label>
                <input type="text" id="codigo" name="codigo" value="<?= $old['codigo'] ?? '' ?>" required>
                <?php if (isset($errors["codigo"])): ?>
                    <p class="error"><?= $errors["codigo"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= $old['nombre'] ?? '' ?>" required>
                <?php if (isset($errors["nombre"])): ?>
                    <p class="error"><?= $errors["nombre"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="descripcion">Descripcion</label>
                <textarea name="descripcion" id="descripcion" rows="4"
                    required><?= $old['descripcion'] ?? '' ?></textarea>
                <?php if (isset($errors["descripcion"])): ?>
                    <p class="error"><?= $errors["descripcion"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" min="0" value="<?= $old['stock'] ?? '' ?>" required>
                <?php if (isset($errors["stock"])): ?>
                    <p class="error"><?= $errors["stock"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" min="0" step="0.01" value="<?= $old['precio'] ?? '' ?>"
                    required>
                <?php if (isset($errors["precio"])): ?>
                    <p class="error"><?= $errors["precio"] ?></p>
                <?php endif; ?>
            </div>

            <div class="input-field">
                <label for="impuesto">Impuesto</label>
                <input type="number" id="impuesto" name="impuesto" min="0" step="0.01"
                    value="<?= $old['impuesto'] ?? '' ?>" required>
                <?php if (isset($errors["impuesto"])): ?>
                    <p class="error"><?= $errors["impuesto"] ?></p>
                <?php endif; ?>
            </div>

            <div class="combined-input-field">
                <div class="input-field">
                    <label for="id_category">Categoría</label>
                    <select name="id_categoria" id="id_category" class="form-select" required>
                        <option value="<?= $old['stock_prod'] ?? '' ?>">Selecciona una categoría</option>
                        <?php foreach ($categories as $categoria): ?>
                            <option value="<?= $categoria['id_categoria'] ?>">
                                <?= $categoria['nombre_categoria'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-field">
                    <label for="id_state">Estado Producto</label>
                    <select name="id_estado" id="id_state" class="form-select" required>
                        <option value="<?= $old['stock_prod'] ?? '' ?>">Selecciona un estado</option>
                        <?php foreach ($states as $state): ?>
                            <option value="<?= $state['id_estado'] ?>">
                                <?= $state['nombre_estado'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="input-field">
                <label for="code">Presentacion</label>

            </div>

            <div class="form-buttons">
                <button type="submit" class="submit-button">Subir Producto</button>
                <a href="index.php?route=products" class="delete-button">Volver</a>
            </div>
        </form>
    </div>
</body>

</html>