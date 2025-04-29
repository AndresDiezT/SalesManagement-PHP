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
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;

        if ($search) {
            $products = $this->product->searchProducts($search);
        } else {
            $products = $this->product->getAllProducts();
        }

        include __DIR__ . '/../Views/Products/index.php';
    }

    public function details($cod_prod)
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
                "codigo" => ["required", "min:7", "max:7"],
                "nombre" => ["required", "min:6"],
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
                "nombre" => ["required", "min:6"],
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

    public function exportProducts()
    {
        $productos = $this->product->getAllProducts();

        header("Content-Type: text/csv; charset=ISO-8859-1");
        header("Content-Disposition: attachment; filename=productos.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        fputcsv($output, [
            "Codigo",
            "Nombre",
            "Descripcion",
            "Stock",
            "Impuesto",
            "Valor por Unidad",
            "Categoria",
            "Estado"
        ], ';');

        foreach ($productos as $prod) {
            fputcsv($output, [
                mb_convert_encoding($prod['cod_prod'], 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding($prod['nombre_prod'], 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding($prod['descripcion_prod'], 'ISO-8859-1', 'UTF-8'),
                $prod['stock_prod'],
                $prod['impuesto'] . '%',
                '$ ' . $prod['valor_unidad'],
                mb_convert_encoding($prod['nombre_categoria'], 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding($prod['nombre_estado'], 'ISO-8859-1', 'UTF-8')
            ], ';');
        }

        fclose($output);
    }
}
?>