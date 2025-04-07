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
    <title>Editar Producto</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=products">Gestionar Productos</a> >
            <span>Editar Producto</span>
        </nav>
        <h2>Editar Producto</h2>
        <form action="" method="post">
            <div class="input-field">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= $product['nombre_prod'] ?>" required>
                <?php if (isset($errors["nombre"])): ?>
                    <p class="error"><?= $errors["nombre"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="descripcion">Descripcion</label>
                <textarea name="descripcion" id="descripcion" rows="4"
                    required><?= $product['descripcion_prod'] ?></textarea>
                <?php if (isset($errors["descripcion"])): ?>
                    <p class="error"><?= $errors["descripcion"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" value="<?= $product['stock_prod'] ?>" min="0" required>
                <?php if (isset($errors["stock"])): ?>
                    <p class="error"><?= $errors["stock"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" min="0" step="0.01"
                    value="<?= $product['valor_unidad'] ?>" required>
                <?php if (isset($errors["precio"])): ?>
                    <p class="error"><?= $errors["precio"] ?></p>
                <?php endif; ?>
            </div>
            <div class="input-field">
                <label for="impuesto">Impuesto</label>
                <input type="number" id="impuesto" name="impuesto" min="0" step="0.01"
                    value="<?= $product['impuesto'] ?>" required>
                <?php if (isset($errors["impuesto"])): ?>
                    <p class="error"><?= $errors["impuesto"] ?></p>
                <?php endif; ?>
            </div>
            <div class="combined-input-field">
                <div class="input-field">
                    <label for="id_category">Categoría</label>
                    <select name="id_categoria" id="id_category" class="form-select" required>
                        <option value="" disabled>Selecciona una categoría</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id_categoria'] ?>"
                                <?= $category['id_categoria'] == $product['id_categoria'] ? 'selected' : '' ?>>
                                <?= $category['nombre_categoria'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-field">
                    <label for="id_state">Estado Producto</label>
                    <select name="id_estado" id="id_state" class="form-select" required>
                        <option value="" disabled>Selecciona un estado</option>
                        <?php foreach ($states as $state): ?>
                            <option value="<?= $state['id_estado'] ?>" <?= $state['id_estado'] == $product['id_estado'] ? 'selected' : '' ?>>
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
                <button type="submit" class="submit-button">Actualizar Producto</button>
                <a href="index.php?route=products" class="delete-button">Volver</a>
            </div>
        </form>
    </div>
</body>

</html>