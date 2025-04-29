<?php
require_once __DIR__ . "/../Models/SaleEmployee.php";
require_once __DIR__ . "/../Models/SaleType.php";
require_once __DIR__ . "/../Models/Client.php";
require_once __DIR__ . "/../Models/Employee.php";
require_once __DIR__ . "/../Models/Product.php";
require_once __DIR__ . "/../Models/InvoiceDetail.php";

require_once __DIR__ . '/../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;

        if ($search) {
            $sales = $this->saleEmployee->searchSales($search);
        } else {
            $sales = $this->saleEmployee->getAllSales();
        }

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

    public function exportSales()
    {
        $ventas = $this->saleEmployee->getAllSales();

        header("Content-Type: text/csv; charset=ISO-8859-1");
        header("Content-Disposition: attachment; filename=ventas.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        fputcsv($output, [
            "Nro Factura",
            "Fecha Venta",
            "Cliente",
            "Empleado",
            "Tipo de Venta",
            "ID Producto",
            "Nombre Producto",
            "Cantidad",
            "Impuesto",
            "Valor Producto",
            "Valor Impuesto",
            "Valor Total"
        ], ';');

        foreach ($ventas as $venta) {
            $detalles = $this->saleEmployee->getOneSale($venta['nro_factura']);

            foreach ($detalles as $detalle) {
                $cod_prod = isset($detalle['cod_prod']) ? $detalle['cod_prod'] : 'N/A';
                $nombre_prod = isset($detalle['nombre_prod']) ? $detalle['nombre_prod'] : 'N/A';
                $cantidad = isset($detalle['cantidad']) ? $detalle['cantidad'] : '0';
                $impuesto = isset($detalle['impuesto']) ? $detalle['impuesto'] : '0';
                $valor_prod = isset($detalle['valor_prod']) ? $detalle['valor_prod'] : '0';
                $valor_impuesto = isset($detalle['valor_impuesto']) ? $detalle['valor_impuesto'] : '0';
                $valor_total = isset($detalle['valor_total']) ? $detalle['valor_total'] : '0';

                fputcsv($output, [
                    mb_convert_encoding($venta['nro_factura'], 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($venta['fecha_venta'], 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($venta['nombre_cliente'], 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($venta['nombre_empleado'], 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($venta['descripcion'], 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($cod_prod, 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($nombre_prod, 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($cantidad, 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($impuesto, 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($valor_prod, 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($valor_impuesto, 'ISO-8859-1', 'UTF-8'),
                    mb_convert_encoding($valor_total, 'ISO-8859-1', 'UTF-8')
                ], ';');
            }
        }

        fclose($output);
    }

    public function generatePdf($nro_invoice)
    {
        $saleDetails = $this->saleEmployee->getOneSale($nro_invoice);
        if (empty($saleDetails)) {
            echo "No se encontró la venta.";
            return;
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        $cssFile = __DIR__ . '/../Css/facturaPDF.css';
        $css = file_get_contents($cssFile);

        $html = "<html>
                <head>
                    <style>
                        $css
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Factura No. {$nro_invoice}</h1>
                            <p>Fecha: {$saleDetails[0]['fecha_venta']}</p>
                            <p><strong>Cliente:</strong> {$saleDetails[0]['nombre_cliente']}</p>
                            <p><strong>Empleado:</strong> {$saleDetails[0]['nombre_empleado']}</p>
                            <p><strong>Tipo de Venta:</strong> {$saleDetails[0]['tipo_venta']}</p>
                        </div>

                        <div class='invoice-info'>
                            <p><strong>Datos de la Venta:</strong></p>
                            <p><strong>Factura:</strong> {$nro_invoice}</p>
                            <p><strong>Fecha de Venta:</strong> {$saleDetails[0]['fecha_venta']}</p>
                        </div>

                        <div class='table-container'>
                            <h3>Productos Comprados</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Valor Unitario</th>
                                        <th>Valor Total</th>
                                    </tr>
                                </thead>
                                <tbody>";

        foreach ($saleDetails as $detail) {
            $html .= "<tr>
                    <td>{$detail['nombre_prod']}</td>
                    <td>{$detail['cantidad']}</td>
                    <td>$ {$detail['valor_prod']}</td>
                    <td>$ {$detail['valor_total']}</td>
                  </tr>";
        }

        $html .= "</tbody>
                </table>
              </div>";

        $totalAmount = array_sum(array_column($saleDetails, 'valor_total'));
        $html .= "<div class='total'>
                <p>Total Factura: $ " . number_format($totalAmount, 0, ',', '.') . " COP</p>
              </div>";

        $html .= "<div class='footer'>
                <p>Gracias por tu compra</p>
                <p>Este documento es válido como recibo de venta</p>
                <p>TIENDA DE TODOS S.A.S</p>
              </div>";

        $html .= "</div>
            </body>
          </html>";

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream("factura_{$nro_invoice}.pdf", array("Attachment" => 0));
    }

    public function sendInvoiceByEmail($nro_invoice)
    {
    }

}
?>