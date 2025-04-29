<?php
include_once "Includes/alerts.php";

$errors = $_SESSION["errors"] ?? [];
$old = $_SESSION["old_data"] ?? [];
unset($_SESSION["errors"], $_SESSION["old_data"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Venta</title>
    <link rel="stylesheet" href="./Css/style.css">
</head>

<body>
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=sales">Gestionar Ventas</a> >
            <span>Subir Venta</span>
        </nav>
        <h2>Subir Venta</h2>
        <form class="sale-form" action="" method="post">
            <div class="input-field">
                <label for="id_cliente">Cliente</label>
                <select name="id_cliente" id="id_cliente" class="form-select" required>
                    <option value="<?= $old["id_cliente"] ?? "" ?>">Selecciona el cliente</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client["id_cliente"] ?>" <?= ($old["id_cliente"] ?? "") == $client["id_cliente"] ? "selected" : "" ?>>
                            <?= $client["nombre_cliente"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-field">
                <label for="id_empleado">Empleado</label>
                <select name="id_empleado" id="id_empleado" class="form-select" required>
                    <option value="<?= $old["id_empleado"] ?? "" ?>">Selecciona el empleado</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= $employee["id_empleado"] ?>" <?= ($old["id_empleado"] ?? "") == $employee["id_empleado"] ? "selected" : "" ?>>
                            <?= $employee["nombre_empleado"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-field">
                <label for="id_tipo_venta">Tipo de Venta</label>
                <select name="id_tipo_venta" id="id_tipo_venta" class="form-select" required>
                    <option value="<?= $old["id_tipo_venta"] ?? "" ?>">Selecciona el tipo de venta</option>
                    <?php foreach ($saleTypes as $saleType): ?>
                        <option value="<?= $saleType["id_tipo_venta"] ?>" <?= ($old["id_tipo_venta"] ?? "") == $saleType["id_tipo_venta"] ? "selected" : "" ?>>
                            <?= $saleType["descripcion"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-field">
                <label for="products">Selecciona productos</label>
                <select name="products[]" id="products" class="form-select">
                    <option value="" disabled selected>Selecciona un producto</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product["cod_prod"] ?>" data-price="<?= $product["valor_unidad"] ?>"
                            data-name="<?= $product["nombre_prod"] ?>" data-tax="<?= $product["impuesto"] ?>">
                            <?= $product["nombre_prod"] ?> - $<?= $product["valor_unidad"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-buttons">
                    <button type="button" class="submit-button" onclick="addProductRow()">Agregar Producto</button>
                </div>
            </div>

            <table class="list" id="product-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Impuesto</th>
                        <th>Valor Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <div class="input-field">
                <label>Total de la Venta:</label>
                <strong id="grand-total">$0.00</strong>
            </div>

            <div class="form-buttons">
                <button type="submit" class="submit-button">Subir Venta</button>
                <a href="index.php?route=products" class="delete-button">Volver</a>
            </div>
        </form>
    </div>

    <script>
        function addProductRow() {
            const productSelect = document.getElementById("products");

            if (productSelect.value === "") {
                alert("Por favor, selecciona un producto.");
                return;
            }

            const table = document.getElementById("product-table").getElementsByTagName("tbody")[0];
            const selectedProduct = productSelect.options[productSelect.selectedIndex];

            const productName = selectedProduct.getAttribute("data-name");
            const productPrice = parseFloat(selectedProduct.getAttribute("data-price"));
            const productCode = selectedProduct.value;
            const productTax = parseFloat(selectedProduct.getAttribute("data-tax"));

            const rows = table.getElementsByTagName("tr");
            for (let i = 0; i < rows.length; i++) {
                const existingCode = rows[i].querySelector('input[name="products[]"]').value;
                if (existingCode === productCode) {
                    alert("Este producto ya ha sido seleccionado");
                    document.getElementById("products").value = "";
                    return;
                }
            }

            const taxValue = (productPrice * productTax / 100).toFixed(2);
            const totalValue = ((productPrice + (taxValue * 1))).toFixed(2);

            const newRow = table.insertRow(-1);
            newRow.dataset.price = productPrice;
            newRow.dataset.tax = productTax;

            newRow.innerHTML = `
                <td>
                    ${productName}
                    <input type="hidden" name="products[]" value="${productCode}">
                    <input type="hidden" name="taxes[]" value="${taxValue}">
                    <input type="hidden" name="totals[]" value="${totalValue}">
                </td>
                <td><input type="number" class="input-settings quantity" name="quantities[]" value="1" min="1" step="1" required></td>
                <td>$${productPrice.toFixed(2)}</td>
                <td>$<span class="tax">${taxValue}</span></td>
                <td>$<span class="total">${totalValue}</span></td>
                <td><button type="button" class="delete-button" onclick="removeProductRow(this)">Eliminar</button></td>
            `;

            productSelect.value = "";
            updateGrandTotal();
        }

        function removeProductRow(button) {
            const row = button.closest("tr");
            row.remove();
            updateGrandTotal();
        }

        document.addEventListener("input", function (event) {
            if (event.target.classList.contains("quantity")) {
                const row = event.target.closest("tr");
                const quantity = parseInt(event.target.value) || 1;
                const unitPrice = parseFloat(row.dataset.price);
                const taxRate = parseFloat(row.dataset.tax);

                const taxPerUnit = unitPrice * taxRate / 100;
                const total = (unitPrice + taxPerUnit) * quantity;

                row.querySelector(".tax").textContent = (taxPerUnit * quantity).toFixed(2);
                row.querySelector('input[name="taxes[]"]').value = (taxPerUnit * quantity).toFixed(2);
                row.querySelector('input[name="totals[]"]').value = (total.toFixed(2));
                row.querySelector(".total").textContent = (total.toFixed(2));

                updateGrandTotal();
            }
        });

        function updateGrandTotal() {
            const totals = document.querySelectorAll(".total");
            let grandTotal = 0;
            totals.forEach(span => {
                grandTotal += parseFloat(span.textContent) || 0;
            });

            document.getElementById("grand-total").textContent = "$" + grandTotal.toFixed(2);
        }


        document.querySelector("form").onsubmit = function (event) {
            const productRows = document.querySelectorAll("#product-table tbody tr");
            document.getElementById("products").value = "";
            if (productRows.length === 0) {
                alert("Debes agregar al menos un producto.");
                event.preventDefault();
                return false;
            }
        }


    </script>
</body>

</html>