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
            <a href="index.php">Inicio</a> > Gestionar Ventas
        </nav>
        <h2>Gestión de Ventas</h2>
        <div class="actions">
            <input type="search" id="search-input" class="search-input" placeholder="Buscar por codigo, nombre...">
            <div>
                <a href="index.php?route=sales/create" class="create-button">Agregar Nueva Venta</a>
                <a href="index.php?route=sales/settings" class="config-button">Configuración</a>
            </div>
        </div>
        <table class="list">
            <thead>
                <tr>
                    <th>Factura</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Empleado</th>
                    <th>Tipo de Venta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="body-table">
                <?php if (!empty($sales)): ?>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td><?= $sale["nro_factura"] ?></td>
                            <td><?= $sale["fecha_venta"] ?></td>
                            <td><?= $sale["nombre_cliente"] ?></td>
                            <td><?= $sale["nombre_empleado"] ?></td>
                            <td><?= $sale["descripcion"] ?></td>
                            <td class="table-actions">
                                <a href="index.php?route=sales/details&nro_invoice=<?= $sale["nro_factura"] ?>"
                                    class="view-button">Ver</a>
                                <a href="index.php?route=sales/edit&nro_invoice=<?= $sale["nro_factura"] ?>"
                                    class="edit-button">Editar</a>
                                <a href="index.php?route=sales/delete&nro_invoice=<?= $sale["nro_factura"] ?>"
                                    data-url="index.php?route=sales/delete&nro_invoice=<?= $sale["nro_factura"] ?>"
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