<?php
require_once __DIR__ . "/../Models/Product.php";
require_once __DIR__ . "/../Models/Category.php";
require_once __DIR__ . "/../Models/State.php";

class HomeController
{
    private $product;
    private $category;
    private $state;
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->getConnection();
    }

    public function index()
    {
        if (isset($_SESSION["id_empleado"])) {
            $idEmployee = $_SESSION["id_empleado"];
        } elseif (isset($_COOKIE["id_empleado"])) {
            $idEmployee = $_COOKIE["id_empleado"];
            $_SESSION["id_empleado"] = $idEmployee;
        } else {
            $idEmployee = null;
        }

        if ($idEmployee) {
            $stmt = $this->conn->prepare("SELECT nombre_empleado FROM empleados WHERE id_empleado = :id_empleado");
            $stmt->bindParam(":id_empleado", $idEmployee);
            $stmt->execute();
            $employee = $stmt->fetch();
            $employee_name = $employee ? $employee["nombre_empleado"] : "Usuario";
        } else {
            $employee_name = "Invitado";
        }

        $totalProducts = $this->conn->query("SELECT COUNT(*) AS total FROM productos")->fetch()["total"];
        $totalEmployees = $this->conn->query("SELECT COUNT(*) AS total FROM empleados")->fetch()["total"];
        $totalClients = $this->conn->query("SELECT COUNT(*) AS total FROM clientes")->fetch()["total"];
        $totalSales = $this->conn->query("SELECT COUNT(*) AS total FROM ventas_empleado")->fetch()["total"];

        include __DIR__ . '/../Views/home.php';
    }

    public function details($cod_prod)
    {
        $product = $this->product->getOneProduct($cod_prod);

        include __DIR__ . '/../Views/Products/details.php';
    }
}
?>