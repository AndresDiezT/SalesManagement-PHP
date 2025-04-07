<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=products">Gestionar Productos</a> >
            <span>Detalles Producto</span>
        </nav>

        <div class="details-container">
            <h2>Detalles del Producto</h2>

            <p><strong>Nombre:</strong> <?= $product["nombre_prod"] ?></p>
            <p><strong>Descripción:</strong> <?= $product["descripcion_prod"] ?></p>
            <p><strong>Stock Disponible:</strong> <?= $product["stock_prod"] ?></p>
            <p><strong>Precio por Unidad:</strong> $<?= number_format($product["valor_unidad"], 2) ?></p>
            <p><strong>Categoría:</strong> <?= $product["nombre_categoria"] ?></p>
            <p><strong>Estado:</strong> <?= $product["nombre_estado"] ?></p>
            <div class="details-buttons">
                <a href="index.php?route=products/edit&cod=<?= $product['cod_prod'] ?>" class="edit-button">Editar</a>
                <a href="index.php?route=products" class="delete-button">Volver</a>
            </div>
        </div>
    </div>
</body>

</html>