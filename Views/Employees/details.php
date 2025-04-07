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
            <a href="index.php?route=employees">Gestionar Empleados</a> >
            <span>Detalles Empleado</span>
        </nav>

        <div class="details-container">
            <h2>Detalles del Producto</h2>

            <p><strong>ID:</strong> <?= $employee["id_empleado"] ?></p>
            <p><strong>Nombre:</strong> <?= $employee["nombre_empleado"] ?></p>
            <p><strong>Usuario:</strong> <?= $employee["usuario"] ?></p>
            <p><strong>Correo Electronico:</strong> <?= $employee["correo"] ?></p>
            <div class="details-buttons">
                <a href="index.php?route=employees/edit&id_employee=<?= $employee['id_empleado'] ?>"
                    class="edit-button">Editar</a>
                <a href="index.php?route=employees" class="delete-button">Volver</a>
            </div>
        </div>
    </div>
</body>

</html>