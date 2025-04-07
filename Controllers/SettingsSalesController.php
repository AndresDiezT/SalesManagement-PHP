<?php
require_once __DIR__ . "/../Models/SaleType.php";

class SettingsSalesController
{
    private $saleType;
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->getConnection();
        $this->saleType = new SaleType($this->conn);
    }

    public function settings()
    {
        $saleTypes = $this->saleType->getAllSaleTypes();

        include __DIR__ . '/../Views/Sales/settings.php';
    }

    public function createSaleType()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "descripcion" => isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : ""
            ];

            $saleTypeCreated = $this->saleType->createSaleType($data['descripcion']);

            if ($saleTypeCreated) {
                $_SESSION["success_message"] = "Tipo de venta creada correctamente";
            } else {
                $_SESSION["error_message"] = "Error al crear el tipo de venta";
            }

            header("Location: index.php?route=sales/settings");
            exit();
        }
    }

    public function updateSaleType()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "id_tipo_venta" => $_POST["id_tipo_venta"] ?? null,
                "descripcion" => isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : ""
            ];

            $updatedSaleType = $this->saleType->updateSaleType($data["id_tipo_venta"], $data["descripcion"]);

            if ($updatedSaleType) {
                $_SESSION["success_message"] = "Tipo de venta actualizada correctamente";
            } else {
                $_SESSION["error_message"] = "Error al actualizar el tipo de venta";
            }

            header("Location: index.php?route=sales/settings");
            exit();
        }
    }

    public function deleteSaleType($id_sale_type)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $saleType = $this->saleType->getOneSaleType($id_sale_type);

        if (empty($saleType)) {
            $_SESSION["error_message"] = "Tipo de venta no encontrado";
            header("Location: index.php?route=sales/settings");
            exit;
        }

        $deleted = $this->saleType->deleteSaleType($id_sale_type);

        if ($deleted) {
            $_SESSION["success_message"] = "Tipo de venta eliminada correctamente";
        } else {
            $_SESSION["error_message"] = "Error al eliminar este tipo de venta";
        }

        header("Location: index.php?route=sales/settings");
        exit;
    }
}
?>