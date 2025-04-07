<?php
require_once __DIR__ . "/../Models/Product.php";
require_once __DIR__ . "/../Models/Category.php";
require_once __DIR__ . "/../Models/State.php";

class ProductsController
{
    private $product;
    private $category;
    private $state;
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->getConnection();
        $this->product = new Product($this->conn);
        $this->category = new Category($this->conn);
        $this->state = new State($this->conn);
    }

    public function index()
    {
        $products = $this->product->getAllProducts();

        include __DIR__ . '/../Views/Products/index.php';
    }

    public function details($cod_prod)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $product = $this->product->getOneProduct($cod_prod);

        if (empty($product))
        {
            $_SESSION["error_message"] = "Producto no encontrado";
            header("Location: index.php?route=products");
            exit;
        }

        include __DIR__ . '/../Views/Products/details.php';
    }

    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $categories = $this->category->getAllCategories();
        $states = $this->state->getAllStates();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "codigo" => isset($_POST["codigo"]) ? trim($_POST["codigo"]) : "",
                "nombre" => isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "",
                "descripcion" => isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : "",
                "stock" => isset($_POST["stock"]) ? trim($_POST["stock"]) : "",
                "precio" => isset($_POST["precio"]) ? trim($_POST["precio"]) : "",
                "impuesto" => isset($_POST["impuesto"]) ? trim($_POST["impuesto"]) : "",
                "id_categoria" => $_POST["id_categoria"],
                "id_estado" => $_POST["id_estado"]
            ];

            $rules = [
                "codigo" => ["required", "min:7", "max:7", "unique:productos, cod_prod"],
                "nombre" => ["required", "min:6", "unique:productos, nombre_prod"],
                "descripcion" => ["required", "min:12", "max:300"],
                "stock" => ["required", "numeric"],
                "precio" => ["required", "numeric"],
                "impuesto" => ["required", "numeric"],
                "id_categoria" => ["required"],
                "id_estado" => ["required"],
            ];

            $errors = ValidationHelper::validate($data, $rules, $this->conn);

            if (!empty($errors)) {
                $_SESSION["errors"] = $errors;
                $_SESSION["old_data"] = $data;

                include __DIR__ . '/../Views/Products/create.php';
                exit;
            } else {
                unset($_SESSION["errors"]);

                $productCreated = $this->product->CreateProduct($data);

                if ($productCreated) {
                    $_SESSION["success_message"] = "Producto creado correctamente";
                    header("Location: index.php?route=products");
                    exit;
                } else {
                    $_SESSION["error_message"] = "Error al crear el producto";
                }
            }
        }

        include __DIR__ . '/../Views/Products/create.php';
    }

    public function update($cod_prod)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $categories = $this->category->getAllCategories();
        $states = $this->state->getAllStates();
        $product = $this->product->getOneProduct($cod_prod);

        if (empty($product)) {
            $_SESSION["error_message"] = "Producto no encontrado";
            header("Location: index.php?route=products");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "nombre" => isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "",
                "descripcion" => isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : "",
                "stock" => isset($_POST["stock"]) ? trim($_POST["stock"]) : "",
                "precio" => isset($_POST["precio"]) ? trim($_POST["precio"]) : "",
                "impuesto" => isset($_POST["impuesto"]) ? trim($_POST["impuesto"]) : "",
                "id_categoria" => $_POST["id_categoria"],
                "id_estado" => $_POST["id_estado"]
            ];

            $rules = [
                "nombre" => ["required", "min:6", "unique:productos, nombre_prod, cod_prod, $cod_prod"],
                "descripcion" => ["required", "min:12", "max:300"],
                "stock" => ["required", "numeric"],
                "precio" => ["required", "numeric"],
                "impuesto" => ["required", "numeric"],
                "id_categoria" => ["required"],
                "id_estado" => ["required"],
            ];

            $errors = ValidationHelper::validate($data, $rules, $this->conn);

            if (!empty($errors)) {
                $_SESSION["errors"] = $errors;

                include __DIR__ . '/../Views/Products/edit.php';
                exit;
            } else {
                unset($_SESSION["errors"]);

                $updated = $this->product->updateProduct($cod_prod, $data);

                if ($updated) {
                    $_SESSION["success_message"] = "Producto actualizado correctamente";
                    header("Location: index.php?route=products");
                    exit;
                } else {
                    $_SESSION["error_message"] = "Error al actualizar el producto";
                }
            }
        }

        include __DIR__ . '/../Views/Products/edit.php';
    }

    public function delete($cod_prod)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $product = $this->product->getOneProduct($cod_prod);

        if (empty($product)) {
            $_SESSION["error_message"] = "Producto no encontrado";
            header("Location: index.php?route=products");
            exit;
        }

        $deleted = $this->product->deleteProduct($cod_prod);

        if ($deleted) {
            $_SESSION["success_message"] = "Producto eliminado correctamente";

        } else {
            $_SESSION["error_message"] = "Error al eliminar el producto";
        }

        header("Location: index.php?route=products");
        exit;
    }
}
?>