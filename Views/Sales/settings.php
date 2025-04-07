<?php include_once "Includes/alerts.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Ventas</title>
    <link rel="stylesheet" href="./Css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./Js/script.js" defer></script>
    <script src="./Js/confirm.js"></script>
</head>

<body>
    <div class="container setting-container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=sales">Gestionar Ventas</a> >
            <span>Configuración Ventas</span>
        </nav>
        <h2>Gestión de Ventas</h2>
        <div class="setting-actions">
            <div>
                <button class="open-modal" onclick="openModal('modalSaleType')">Agregar Tipo Venta</button>
            </div>
        </div>
        <main class="settings-container">

            <table class="list">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($saleTypes)): ?>
                        <?php foreach ($saleTypes as $saleType): ?>
                            <tr>
                                <td><?= $saleType["id_tipo_venta"] ?></td>
                                <td>
                                    <span id="sale_type_name_<?= $saleType['id_tipo_venta'] ?>">
                                        <?= $saleType['descripcion'] ?>
                                    </span>
                                    <form id="sale_type_form_<?= $saleType['id_tipo_venta'] ?>"
                                        action="index.php?route=sales/settings/sale-types/edit" method="POST"
                                        class="table-input" style="display: none;">
                                        <input type="hidden" name="id_tipo_venta" value="<?= $saleType['id_tipo_venta'] ?>">
                                        <input type="text" name="descripcion" class="input-settings"
                                            id="input_state_<?= $saleType['id_tipo_venta'] ?>"
                                            value="<?= $saleType['descripcion'] ?>">
                                    </form>
                                </td>
                                <td class="actions">
                                    <a onclick="editSaleType(<?= $saleType['id_tipo_venta'] ?>)"
                                        id="sale_type_edit_button_<?= $saleType['id_tipo_venta'] ?>"
                                        class="edit-button">Editar</a>
                                    <a onclick="saveSaleType(<?= $saleType['id_tipo_venta'] ?>)"
                                        id="sale_type_save_button_<?= $saleType['id_tipo_venta'] ?>" style="display:none;"
                                        class="edit-button">Guardar</a>
                                    <a href="index.php?route=sales/settings/sale-types/delete&id=<?= $saleType['id_tipo_venta'] ?>"
                                        data-url="index.php?route=sales/settings/sale-types/delete&id=<?= $saleType['id_tipo_venta'] ?>"
                                        class="delete-button">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No hay estados registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>

        <div id="modalTax" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTax')">&times;</span>
                <h2>Crear Estado</h2>
                <form action="index.php?route=sales/settings/taxes/create" method="POST">
                    <input type="text" name="descripcion" placeholder="Descripcion" required>
                    <input type="text" name="tasa" placeholder="Tasa de Impuestos" required>
                    <button type="submit">Guardar</button>
                </form>
            </div>
        </div>
        <div id="modalSaleType" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalSaleType')">&times;</span>
                <h2>Crear Categoría</h2>
                <form action="index.php?route=sales/settings/sale-types/create" method="POST">
                    <input type="text" name="descripcion" placeholder="Descripcion" required>
                    <button type="submit">Guardar</button>
                </form>
            </div>
        </div>

        <script>
            function editTax(id) {
                document.getElementById("tax_desc_" + id).style.display = "none";
                document.getElementById("tax_rate_" + id).style.display = "none";

                document.getElementById("input_tax_desc_" + id).style.display = "inline-block";
                document.getElementById("input_tax_rate_" + id).style.display = "inline-block";

                document.getElementById("tax_edit_button_" + id).style.display = "none";
                document.getElementById("tax_save_button_" + id).style.display = "inline-block";
            }


            function saveTax(id) {
                document.getElementById("tax_form_" + id).submit();
            }



            function editSaleType(id) {
                document.getElementById("sale_type_name_" + id).style.display = "none";
                document.getElementById("sale_type_form_" + id).style.display = "inline-block";

                document.getElementById("sale_type_edit_button_" + id).style.display = "none";
                document.getElementById("sale_type_save_button_" + id).style.display = "inline-block";
            }

            function saveSaleType(id) {
                document.getElementById("sale_type_form_" + id).submit();
            }
        </script>
    </div>
</body>

</html>