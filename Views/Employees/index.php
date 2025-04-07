<?php include_once "Includes/alerts.php"; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="./Css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./Js/script.js"></script>
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> > Gestionar Empleados
        </nav>
        <h2>Gestión de Empleados</h2>

        <div class="actions">
            <input type="search" class="search-input" placeholder="Buscar por ID, nombre...">
            <div>
                <a href="index.php?route=employees/create" class="create-button">Agregar Nuevo Empleado</a>
                <span>Filtrar</span>
            </div>
        </div>

        <table class="list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php

                if (!empty($employees)):
                    foreach ($employees as $employee): ?>
                        <tr>
                            <td data-label="ID"><?= $employee["id_empleado"] ?></td>
                            <td data-label="Nombre"><?= $employee["nombre_empleado"] ?></td>
                            <td data-label="Correo"><?= $employee["correo"] ?></td>
                            <td data-label="ID"><?= $employee["usuario"] ?></td>
                            <td data-label="ID" class="table-actions">
                                <a href="index.php?route=employees/details&id_employee=<?= $employee["id_empleado"] ?>"
                                    class="view-button">Ver</a>
                                <a href="index.php?route=employees/edit&id_employee=<?= $employee['id_empleado'] ?>"
                                    class="edit-button">Editar</a>
                                <a href="index.php?route=employees/delete&id_employee=<?= $employee['id_empleado'] ?>"
                                    data-url="index.php?route=employees/delete&id_employee=<?= $employee['id_empleado'] ?>"
                                    class="delete-button">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay empleados registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>