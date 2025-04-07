<?php
require_once __DIR__ . "/../Models/Employee.php";
require_once __DIR__ . "/../Config/Connection.php";
require_once "Helpers/ValidationHelper.php";

class EmployeesController
{
    private $employee;
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->getConnection();
        $this->employee = new Employee($this->conn);
    }

    public function index()
    {
        $employees = $this->employee->getAllEmployees();

        include __DIR__ . '/../Views/Employees/index.php';
    }

    public function details($id)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employee = $this->employee->getOneEmployee($id);

        if (empty($employee))
        {
            $_SESSION["error_message"] = "Empleado no encontrado";
            header("Location: index.php?route=employees");
            exit;
        }

        include __DIR__ . '/../Views/Employees/details.php';
    }

    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION["errors"])) {
            unset($_SESSION["errors"]);
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "nombre" => isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "",
                "usuario" => isset($_POST["usuario"]) ? trim($_POST["usuario"]) : "",
                "correo" => isset($_POST["correo"]) ? trim($_POST["correo"]) : "",
                "empleado_password" => isset($_POST["empleado_password"]) ? trim($_POST["empleado_password"]) : "",
                "confirmar_contraseña" => isset($_POST["confirmar_contraseña"]) ? trim($_POST["confirmar_contraseña"]) : ""
            ];

            $rules = [
                "nombre" => ["required", "text", "min:6"],
                "usuario" => ["required", "min:3", "unique:empleados,usuario"],
                "correo" => ["required", "email", "unique:empleados,correo"],
                "empleado_password" => ["required", "min:6"],
                "confirmar_contraseña" => ["required", "match:empleado_password"]
            ];

            $errors = ValidationHelper::validate($data, $rules, $this->conn);

            if (!empty($errors)) {
                $_SESSION["errors"] = $errors;
                $_SESSION["old_data"] = $data;

                include __DIR__ . '/../Views/Employees/create.php';
                exit;
            } else {
                unset($_SESSION["errors"]);

                $data["empleado_password"] = password_hash($data["empleado_password"], PASSWORD_BCRYPT);
                unset($data["confirmar_contraseña"]);

                $createdEmployee = $this->employee->createEmployee($data);

                if ($createdEmployee) {
                    $_SESSION["success_message"] = "Empleado creado correctamente";
                    header("Location: index.php?route=employees");
                    exit;
                } else {
                    $_SESSION["error_message"] = "Error al registrar el empleado";
                }
            }
        }

        include __DIR__ . '/../Views/Employees/create.php';
    }

    public function update($id_employee)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employee = $this->employee->getOneEmployee($id_employee);

        if (empty($employee)) {
            $_SESSION["error_message"] = "Empleado no encontrado";
            header("Location: index.php?route=employees");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "id_empleado" => $id_employee,
                "nombre" => $_POST["nombre"],
                "usuario" => $_POST["usuario"],
                "correo" => $_POST["correo"],
                // "empleado_password" => $_POST["empleado_password"],
            ];

            $rules = [
                "nombre" => ["required", "text", "min:6"],
                "usuario" => ["required", "min:3", "unique:empleados, usuario, id_empleado, $id_employee"],
                "correo" => ["required", "email", "unique:empleados, correo, id_empleado, $id_employee"],
            ];

            $errors = ValidationHelper::validate($data, $rules, $this->conn);

            if (!empty($errors)) {
                $_SESSION["errors"] = $errors;

                include __DIR__ . '/../Views/Employees/edit.php';
                exit;
            } else {
                unset($_SESSION["errors"]);
                $updated = $this->employee->updateEmployee($id_employee, $data);

                if ($updated) {
                    $_SESSION["success_message"] = "Empleado actualizado correctamente.";
                    header("Location: index.php?route=employees");
                    exit;
                } else {
                    $_SESSION["error_message"] = "Error al actualizar el empleado.";
                }
            }
        }

        include __DIR__ . '/../Views/Employees/edit.php';
    }

    public function delete($id_employee)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $employee = $this->employee->getOneEmployee($id_employee);

        if (empty($employee)) {
            $_SESSION["error_message"] = "Empleado no encontrado";
            header("Location: index.php?route=employees");
            exit;
        }

        $deleted = $this->employee->deleteEmployee($id_employee);

        if ($deleted) {
            $_SESSION["success_message"] = "Empleado eliminado correctamente.";
        } else {
            $_SESSION["error_message"] = "Error al eliminar el empleado.";
        }

        header("Location: index.php?route=employees");
        exit;
    }


    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = isset($_POST["usuario"]) ? trim($_POST["usuario"]) : "";
            $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

            $employee = $this->employee->loginEmployee($usuario);

            if ($employee === "error_multiple") {
                $_SESSION["error_message"] = "Error: existen multiples usuarios con el mismo nombre";
                header("Location: index.php?route=login");
                exit;
            }

            if ($employee && password_verify($password, $employee["empleado_password"])) {
                $_SESSION["id_empleado"] = $employee["id_empleado"];

                if (isset($_POST["recordar"])) {
                    setcookie("id_empleado", $employee["id_empleado"], time() + (86400 * 30), "/");
                }

                header("Location: index.php");
                exit;
            } else {
                $_SESSION["error_message"] = "Usuario o Contraseña Incorrectos";
                header("Location: index.php?route=login");
                exit;
            }
        }

        include __DIR__ . '/../Views/login.php';
    }

    public function logout()
    {
        session_destroy();
        setcookie("id_empleado", "", time() - 3600, "/");
        header("Location: index.php?route=login");
        exit;
    }
}
?>