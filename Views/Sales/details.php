<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Venta</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=sales">Gestionar Ventas</a> >
            <span>Detalles Venta</span>
        </nav>

        <div class="details-container">
            <h2>Detalles de la Venta</h2>

            <p><strong>Numero de Factura:</strong> <?= $sale["nro_factura"] ?></p>
            <p><strong>Fecha:</strong> <?= date("d/m/Y", strtotime($sale["fecha_venta"])) ?></p>
            <p><strong>Correo Cliente:</strong> <?= $sale["correo_cliente"] ?></p>
            <p><strong>Cliente:</strong> <?= $sale["nombre_cliente"] ?></p>
            <p><strong>Empleado:</strong> <?= $sale["nombre_empleado"] ?></p>
            <p><strong>Tipo de Venta:</strong> <?= $sale["tipo_venta"] ?></p>

            <div class="sale-details-head">
                <h3>Productos Comprados</h3>
                <div>
                    <a href="index.php?route=sales/send-invoice&nro_invoice=<?= $nro_invoice ?>"
                        class="send-invoice-button">Enviar Factura al Cliente</a>
                    <a href="index.php?route=sales/generate-pdf&nro_invoice=<?= $nro_invoice ?>"
                        target="_blank">Imprimir Factura</a>
                </div>
            </div>
            <table class="list">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Tasa de Impuesto</th>
                        <th>Precio Unitario</th>
                        <th>Valor Impuesto</th>
                        <th>Total Producto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($saleDetails as $detail): ?>
                        <tr>
                            <td><?= $detail["nombre_prod"] ?></td>
                            <td><?= $detail["cantidad"] ?></td>
                            <td><?= $detail["impuesto"] ?>%</td>
                            <td>$<?= number_format($detail["valor_prod"], 2) ?></td>
                            <td>$<?= number_format($detail["valor_impuesto"], 2) ?></td>
                            <td>$<?= number_format($detail["valor_total"], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-summary">
                <p><strong>Total de la Venta:</strong>
                    $<?= number_format(array_sum(array_column($saleDetails, "valor_total")), 2) ?></p>
            </div>

            <div class="details-buttons">
                <a href="index.php?route=sales/edit&nro_invoice=<?= $sale["nro_factura"] ?>"
                    class="edit-button">Editar</a>
                <a href="index.php?route=sales" class="delete-button">Volver</a>
            </div>
        </div>
    </div>
</body>

</html>