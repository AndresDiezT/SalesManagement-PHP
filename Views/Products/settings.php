<?php include_once "Includes/alerts.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Productos</title>
    <link rel="stylesheet" href="./Css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./Js/script.js"></script>
    <script src="./Js/confirm.js"></script>
</head>

<body>
    <div class="container setting-container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="index.php?route=products">Gestionar Productos</a> >
            <span>Configuración Productos</span>
        </nav>
        <h2>Gestión de Productos</h2>
        <div class="setting-actions">
            <div>
                <button class="open-modal" onclick="openModal('modalCategory')">Agregar Categoria</button>
                <button class="open-modal" onclick="openModal('modalState')">Agregar Estado</button>
            </div>
        </div>
        <main class="settings-container">
            <table class="list">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre Categoría</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $category["id_categoria"] ?></td>
                                <td>
                                    <span id="category_name_<?= $category['id_categoria'] ?>">
                                        <?= $category['nombre_categoria'] ?>
                                    </span>
                                    <form id="category_form_<?= $category['id_categoria'] ?>"
                                        action="index.php?route=products/settings/categories/edit" method="POST"
                                        class="table-input" style="display: none;">
                                        <input type="hidden" name="id_category" value="<?= $category['id_categoria'] ?>">
                                        <input type="text" name="category_name" class="input-settings"
                                            id="input_categoria_<?= $category['id_categoria'] ?>"
                                            value="<?= $category['nombre_categoria'] ?>">
                                    </form>
                                </td>
                                <td>
                                    <a onclick="editCategory(<?= $category['id_categoria'] ?>)"
                                        id="category_edit_button_<?= $category['id_categoria'] ?>"
                                        class="edit-button">Editar</a>
                                    <a onclick="saveCategory(<?= $category['id_categoria'] ?>)"
                                        id="category_save_button_<?= $category['id_categoria'] ?>" style="display:none;"
                                        class="edit-button">Guardar</a>
                                    <a href="index.php?route=products/settings/categories/delete&id=<?= $category['id_categoria'] ?>"
                                        data-url="index.php?route=products/settings/categories/delete&id=<?= $category['id_categoria'] ?>"
                                        class="delete-button">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No hay categorias registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <table class="list">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre Estado</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($states)): ?>
                        <?php foreach ($states as $state): ?>
                            <tr>
                                <td><?= $state["id_estado"] ?></td>
                                <td>
                                    <span id="state_name_<?= $state['id_estado'] ?>">
                                        <?= $state['nombre_estado'] ?>
                                    </span>
                                    <form id="state_form_<?= $state['id_estado'] ?>"
                                        action="index.php?route=products/settings/states/edit" method="POST" class="table-input"
                                        style="display: none;">
                                        <input type="hidden" name="id_state" value="<?= $state['id_estado'] ?>">
                                        <input type="text" name="state_name" class="input-settings"
                                            id="input_estado_<?= $state['id_estado'] ?>" value="<?= $state['nombre_estado'] ?>">
                                    </form>
                                </td>
                                <td>
                                    <a onclick="editState(<?= $state['id_estado'] ?>)"
                                        id="state_edit_button_<?= $state['id_estado'] ?>" class="edit-button">Editar</a>
                                    <a onclick="saveState(<?= $state['id_estado'] ?>)"
                                        id="state_save_button_<?= $state['id_estado'] ?>" style="display:none;"
                                        class="edit-button">Guardar</a>
                                    <a href="index.php?route=products/settings/states/delete&id=<?= $state['id_estado'] ?>"
                                        data-url="index.php?route=products/settings/states/delete&id=<?= $state['id_estado'] ?>"
                                        class="delete-button">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No hay estados registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
        <div id="modalState" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalState')">&times;</span>
                <h2>Crear Estado</h2>
                <form action="index.php?route=products/settings/states/create" method="POST">
                    <input type="text" name="state_name" placeholder="Nombre del estado" required>
                    <button type="submit">Guardar</button>
                </form>
            </div>
        </div>
        <div id="modalCategory" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalCategory')">&times;</span>
                <h2>Crear Categoría</h2>
                <form action="index.php?route=products/settings/categories/create" method="POST">
                    <input type="text" name="category_name" placeholder="Nombre de la categoría" required>
                    <button type="submit">Guardar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editCategory(id) {
            document.getElementById("category_name_" + id).style.display = "none";
            document.getElementById("category_form_" + id).style.display = "inline-block";

            document.getElementById("category_edit_button_" + id).style.display = "none";
            document.getElementById("category_save_button_" + id).style.display = "inline-block";
        }

        function saveCategory(id) {
            document.getElementById("category_form_" + id).submit();
        }

        function editState(id) {
            document.getElementById("state_name_" + id).style.display = "none";
            document.getElementById("state_form_" + id).style.display = "inline-block";

            document.getElementById("state_edit_button_" + id).style.display = "none";
            document.getElementById("state_save_button_" + id).style.display = "inline-block";
        }

        function saveState(id) {
            document.getElementById("state_form_" + id).submit();
        }
    </script>
</body>

</html>