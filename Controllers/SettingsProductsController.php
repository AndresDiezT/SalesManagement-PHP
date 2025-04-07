<?php
require_once __DIR__ . "/../Models/Product.php";
require_once __DIR__ . "/../Models/Category.php";
require_once __DIR__ . "/../Models/State.php";

class SettingsProductsController
{
    private $category;
    private $state;

    public function __construct()
    {
        $db = new Connection();
        $conn = $db->getConnection();
        $this->category = new Category($conn);
        $this->state = new State($conn);
    }

    public function settings()
    {
        $categories = $this->category->getAllCategories();
        $states = $this->state->getAllStates();

        include __DIR__ . '/../Views/Products/settings.php';
    }

    public function createState()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        };

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "nombre_estado" => $_POST["state_name"]
            ];

            $stateCreated = $this->state->createState($data["nombre_estado"]);

            if ($stateCreated) {
                $_SESSION["success_message"] = "Estado creado correctamente";
            } else {
                $_SESSION["error_message"] = "Error al crear el estado";
            }

            header("Location: index.php?route=products/settings");
            exit();
        }
    }

    public function updateState()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "id_estado" => $_POST["id_state"] ?? null,
                "nombre_estado" => $_POST["state_name"]
            ];

            $updatedState = $this->state->updateState($data["id_estado"], $data["nombre_estado"]);

            if ($updatedState) {
                $_SESSION["success_message"] = "Estado actualizada correctamente";
            } else {
                $_SESSION["error_message"] = "Error al actualizar el estado";
            }

            header("Location: index.php?route=products/settings");
            exit();
        }
    }

    public function deleteState($id_state)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $state = $this->state->getOneState($id_state);

        if (empty($state)) {
            $_SESSION["error_message"] = "Estado no encontrado";
            header("Location: index.php?route=products/settings");
            exit;
        }

        $deleted = $this->state->deleteState($id_state);

        if ($deleted) {
            $_SESSION["success_message"] = "Estado eliminado correctamente";
        } else {
            $_SESSION["error_message"] = "Error al eliminar el estado";
        }

        header("Location: index.php?route=products/settings");
        exit;
    }

    public function createCategory()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "nombre_categoria" => $_POST["category_name"]
            ];

            $categoryCreated = $this->category->createCategory($data['nombre_categoria']);

            if ($categoryCreated) {
                $_SESSION["success_message"] = "Categoría creada correctamente";
            } else {
                $_SESSION["error_message"] = "Error al crear la categoría";
            }

            header("Location: index.php?route=products/settings");
            exit();
        }
    }

    public function updateCategory()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "id_categoria" => $_POST["id_category"] ?? null,
                "nombre_categoria" => $_POST["category_name"]
            ];

            $updatedCategory = $this->category->updateCategory($data["id_categoria"], $data["nombre_categoria"]);

            if ($updatedCategory) {
                $_SESSION["success_message"] = "Categoría actualizada correctamente";
            } else {
                $_SESSION["error_message"] = "Error al actualizar la categoría";
            }

            header("Location: index.php?route=products/settings");
            exit();
        }
    }

    public function deleteCategory($id_category)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $category = $this->category->getOneCategory($id_category);

        if (empty($category)) {
            $_SESSION["error_message"] = "Categoría no encontrada";
            header("Location: index.php?route=products/settings");
            exit;
        }

        $deleted = $this->category->deleteCategory($id_category);

        if ($deleted) {
            $_SESSION["success_message"] = "Categoría eliminada correctamente";
        } else {
            $_SESSION["error_message"] = "Error al eliminar la categoría";
        }

        header("Location: index.php?route=products/settings");
        exit;
    }
}
?>