<?php include_once "Includes/alerts.php"; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="./Css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./Js/script.js"></script>
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> > Gestionar Productos
        </nav>
        <h2>Gestión de Productos</h2>
        <div class="actions">
            <form method="GET" action="index.php" class="form-search">
                <input type="hidden" name="route" value="products">
                <input type="search" name="search" class="search-input"
                    placeholder="Buscar por código o nombre..."
                    value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                <button type="submit" class="search-button">Buscar</button>
            </form>
            <div>
                <a href="index.php?route=products/create" class="create-button">Agregar Nuevo Producto</a>
                <a href="index.php?route=products/export" class="edit-button">Exportar</a>
                <a href="index.php?route=products/settings" class="config-button">Configuración</a>
            </div>
        </div>
        <table class="list">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Impuesto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="body-table">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product["cod_prod"] ?></td>
                            <td><?= $product["nombre_prod"] ?></td>
                            <td><?= number_format($product["valor_unidad"], 2, ',', '.') ?></td>
                            <td><?= $product["stock_prod"] ?></td>
                            <td><?= $product["nombre_categoria"] ?></td>
                            <td><?= $product["nombre_estado"] ?></td>
                            <td><?= $product["impuesto"] ?></td>
                            <td class="table-actions">
                                <a href="index.php?route=products/details&cod=<?= $product["cod_prod"] ?>"
                                    class="view-button">Ver</a>
                                <a href="index.php?route=products/edit&cod=<?= $product['cod_prod'] ?>"
                                    class="edit-button">Editar</a>
                                <a href="index.php?route=products/delete&cod=<?= $product['cod_prod'] ?>"
                                    data-url="index.php?route=products/delete&cod=<?= $product['cod_prod'] ?>"
                                    class="delete-button">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay productos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>