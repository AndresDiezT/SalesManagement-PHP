<?php
require_once __DIR__ . "/../Models/SaleEmployee.php";
require_once __DIR__ . "/../Models/SaleType.php";
require_once __DIR__ . "/../Models/Client.php";
require_once __DIR__ . "/../Models/Employee.php";
require_once __DIR__ . "/../Models/Product.php";
require_once __DIR__ . "/../Models/InvoiceDetail.php";

class SalesController
{
    private $saleEmployee;
    private $clients;
    private $saleType;
    private $employee;
    private $product;
    private $invoiceDetails;
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->getConnection();
        $this->saleEmployee = new SaleEmployee($this->conn);
        $this->clients = new Client($this->conn);
        $this->saleType = new SaleType($this->conn);
        $this->employee = new Employee($this->conn);
        $this->product = new Product($this->conn);
        $this->invoiceDetails = new invoiceDetail($this->conn);
    }

    public function index()
    {
        $sales = $this->saleEmployee->getAllSales();

        include __DIR__ . '/../Views/Sales/index.php';
    }

    public function details($nro_invoice)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $saleDetails = $this->saleEmployee->getOneSale($nro_invoice);
        
        if (empty($saleDetails)) {
            $_SESSION["error_message"] = "Venta no encontrada";
            header("Location: index.php?route=sales");
            exit;
        }

        $sale = $saleDetails[0];

        include __DIR__ . '/../Views/Sales/details.php';
    }

    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $clients = $this->clients->getAllClients();
        $employees = $this->employee->getAllEmployees();
        $saleTypes = $this->saleType->getAllSaleTypes();
        $products = $this->product->getAllProducts();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "nro_factura" => $this->saleEmployee->generateInvoiceNumber(),
                "fecha" => date("Y-m-d H:i:s"),
                "id_cliente" => $_POST["id_cliente"],
                "id_tipo_venta" => $_POST["id_tipo_venta"],
                "id_empleado" => $_POST["id_empleado"],
            ];

            $rules = [
                "fecha" => ["required"],
                "id_cliente" => ["required", "numeric"],
                "id_tipo_venta" => ["required", "numeric"],
                "id_empleado" => ["required", "numeric"],
            ];

            $errors = ValidationHelper::validate($data, $rules, $this->conn);

            if (!empty($errors)) {
                $_SESSION["errors"] = $errors;
                $_SESSION["old_data"] = $data;
                include __DIR__ . "/../Views/Sales/create.php";
                exit;
            }

            try {

                $products = $_POST['products'];
                $quantities = $_POST['quantities'];
                $taxes = $_POST['taxes'];
                $totals = $_POST['totals'];

                if (
                    count($_POST["products"]) !== count($_POST["quantities"]) ||
                    count($_POST["products"]) !== count($_POST["taxes"]) ||
                    count($_POST["products"]) !== count($_POST["totals"])
                ) {
                    $_SESSION["error_message"] = "Error en los datos enviados";
                    header("Location: index.php?route=sales/create");
                    exit;
                }

                $saleCreated = $this->saleEmployee->createSale($data);


                if (!$saleCreated) {
                    $_SESSION["error_message"] = "Error al procesar la venta";
                    header("Location: index.php?route=sales/create");
                    exit;
                }


                foreach ($products as $index => $product_code) {
                    $quantity = (int) $quantities[$index];
                    $valor_tax = (float) $taxes[$index];
                    $valor_total = (float) $totals[$index];

                    $product = $this->product->getOneProduct($product_code);

                    if (empty($product)) {
                        $_SESSION["error_message"] = "Producto no encontrado";
                        header("Location: index.php?route=sales/create");
                        exit;
                    }

                    $invoiceData =
                        [
                            "nro_factura" => $data["nro_factura"],
                            "cod_prod" => $product_code,
                            "cantidad" => $quantity,
                            "valor_prod" => $product["valor_unidad"],
                            "valor_impuesto" => $valor_tax,
                            "valor_total" => $valor_total
                        ];

                    $invoiceDetailsResult = $this->invoiceDetails->createInvoiceDetails($invoiceData);

                    if (!$invoiceDetailsResult) {
                        $_SESSION["error_message"] = "Factura no creada correctamente";
                        header("Location: index.php?route=sales/create");
                        exit;
                    }
                }

                $_SESSION["success_message"] = "Venta subida correctamente";
                header("Location: index.php?route=sales");
                exit;

            } catch (Exception $e) {
                $_SESSION["error_message"] = "Error al procesar la venta: " . $e->getMessage();
                header("Location: index.php?route=sales");
                exit;
            }
        }

        include __DIR__ . '/../Views/Sales/create.php';
    }

    public function update($nro_invoice)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $clients = $this->clients->getAllClients();
        $employees = $this->employee->getAllEmployees();
        $saleTypes = $this->saleType->getAllSaleTypes();
        $saleDetails = $this->saleEmployee->getOneSale($nro_invoice);
        if (empty($saleDetails)) {
            $_SESSION["error_message"] = "Venta no encontrada.";
            header("Location: index.php?route=sales");
            exit;
        }

        $sale = $saleDetails[0];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "id_cliente" => $_POST["id_cliente"],
                "id_tipo_venta" => $_POST["id_tipo_venta"],
                "id_empleado" => $_POST["id_empleado"],
            ];

            $rules = [
                "id_cliente" => ["required", "numeric"],
                "id_tipo_venta" => ["required", "numeric"],
                "id_empleado" => ["required", "numeric"]
            ];

            $errors = ValidationHelper::validate($data, $rules, $this->conn);

            if (!empty($errors)) {
                $_SESSION["errors"] = $errors;

                include __DIR__ . '/../Views/Sales/edit.php';
                exit;
            } else {
                unset($_SESSION["errors"]);

                $saleUpdated = $this->saleEmployee->updateSale($nro_invoice, $data);

                if ($saleUpdated) {
                    $_SESSION["success_message"] = "Venta actualizada correctamente";
                    header("Location: index.php?route=sales");
                    exit;
                } else {
                    $_SESSION["error_message"] = "Error al actualizar la venta";
                    header("Location: index.php?route=sales/create");
                    exit;
                }
            }
        }

        include __DIR__ . '/../Views/Sales/edit.php';
    }

    public function delete($nro_invoice)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sale = $this->saleEmployee->getOneSale($nro_invoice);

        if (empty($sale)) {
            $_SESSION["error_message"] = "Venta no encontrada";
            header("Location: index.php?route=sales");
            exit;
        }

        $deleted = $this->saleEmployee->deleteSale($nro_invoice);

        if ($deleted) {
            $_SESSION["success_message"] = "Venta eliminada correctamente";
        } else {
            $_SESSION["error_message"] = "Error al eliminar la venta";
        }
        header("Location: index.php?route=sales");
        exit;
    }

    public function generateInvoiceNumber()
    {
        $prefix = "FAC";
        $year = date("Y");
        $query = "SELECT MAX(id) as last_id FROM ventas";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $nextId = $result["last_id"] ? $result["last_id"] + 1 : 1;
        $invoiceNumber = $prefix . $year . str_pad($nextId, 6, "0", STR_PAD_LEFT);

        return $invoiceNumber;
    }
}
?>