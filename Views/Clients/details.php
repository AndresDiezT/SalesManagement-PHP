<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Cliente</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=clients">Gestionar Clientes</a> >
            <span>Detalles Cliente</span>
        </nav>

        <div class="details-container">
            <h2>Detalles del Producto</h2>

            <p><strong>ID:</strong> <?= $client["id_cliente"] ?></p>
            <p><strong>Nombre Cliente:</strong> <?= $client["nombre_cliente"] ?></p>
            <p><strong>Numero Identidad:</strong> <?= $client["nro_identidad"] ?></p>
            <p><strong>Correo:</strong> <?= $client["correo_cliente"] ?></p>
            <p><strong>Dirección:</strong> <?= $client["direccion_cliente"] ?></p>
            <div class="details-buttons">
                <a href="index.php?route=clients/edit&id_client=<?= $client['id_cliente'] ?>"
                    class="edit-button">Editar</a>
                <a href="index.php?route=clients" class="delete-button">Volver</a>
            </div>
        </div>
    </div>
</body>

</html>