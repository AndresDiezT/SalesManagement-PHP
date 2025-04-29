<?php include_once "Includes/alerts.php"; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="./Css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./Js/script.js"></script>
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> > Gestionar Clientes
        </nav>
        <h2>Gestión de Clientes</h2>
        <div class="actions">
            <form method="GET" action="index.php" class="form-search">
                <input type="hidden" name="route" value="clients">
                <input type="search" name="search" class="search-input"
                    placeholder="Buscar por ID, nombre o numero identidad..."
                    value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                <button type="submit" class="search-button">Buscar</button>
            </form>
            <div>
                <a href="index.php?route=clients/create" class="create-button">Agregar Nuevo Cliente</a>
                <a href="index.php?route=clients/export" class="edit-button">Exportar</a>
            </div>
        </div>
        <table class="list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Numero Identidad</th>
                    <th>Correo</th>
                    <th>Direccion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clients)): ?>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= $client["id_cliente"] ?></td>
                            <td><?= $client["nombre_cliente"] ?></td>
                            <td><?= $client["nro_identidad"] ?></td>
                            <td><?= $client["correo_cliente"] ?></td>
                            <td><?= $client["direccion_cliente"] ?></td>
                            <td class="table-actions">
                                <a href="index.php?route=clients/details&id_client=<?= $client["id_cliente"] ?>"
                                    class="view-button">Ver</a>
                                <a href="index.php?route=clients/edit&id_client=<?= $client['id_cliente'] ?>"
                                    class="edit-button">Editar</a>
                                <a href="index.php?route=clients/delete&id_client=<?= $client['id_cliente'] ?>"
                                    data-url="index.php?route=clients/delete&id_client=<?= $client['id_cliente'] ?>"
                                    class="delete-button">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>