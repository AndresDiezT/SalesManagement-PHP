<?php
require_once __DIR__ . "/../Models/Client.php";

class ClientsController
{
    private $client;
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->getConnection();
        $this->client = new Client($this->conn);
    }

    public function index()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;

        if ($search) {
            $clients = $this->client->searchClients($search);
        } else {
            $clients = $this->client->getAllClients();
        }

        include __DIR__ . '/../Views/Clients/index.php';
    }

    public function details($id_client)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $client = $this->client->getOneClient($id_client);

        if (empty($client)) {
            $_SESSION["error_message"] = "Cliente no encontrado";
            header("Location: index.php?route=clients");
            exit;
        }

        include __DIR__ . '/../Views/Clients/details.php';
    }

    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "identidad" => isset($_POST["identidad"]) ? trim($_POST["identidad"]) : "",
                "nombre" => isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "",
                "direccion" => isset($_POST["direccion"]) ? trim($_POST["direccion"]) : "",
                "correo" => isset($_POST["correo"]) ? trim($_POST["correo"]) : "",
            ];

            $rules = [
                "identidad" => ["required", "numeric", "max:10"],
                "nombre" => ["required", "text", "min:6", "max:50"],
                "direccion" => ["required", "min:10"],
                "correo" => ["required", "email"],
            ];

            $errors = ValidationHelper::validate($data, $rules, $this->conn);

            if (!empty($errors)) {
                $_SESSION["errors"] = $errors;
                $_SESSION["old_data"] = $data;

                include __DIR__ . '/../Views/Clients/create.php';
                exit;
            } else {
                unset($_SESSION["errors"]);

                $clientCreated = $this->client->createClient($data);

                if ($clientCreated) {
                    $_SESSION["success_message"] = "Cliente creado correctamente";
                    header("Location: index.php?route=clients");
                    exit;
                } else {
                    $_SESSION["error_message"] = "Error al subir el cliente";
                }
            }
        }

        include __DIR__ . '/../Views/clients/create.php';
    }

    public function update($id_client)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $client = $this->client->getOneClient($id_client);

        if (empty($client)) {
            $_SESSION["error_message"] = "Cliente no encontrado";
            header("Location: index.php?route=clients");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "identidad" => isset($_POST["identidad"]) ? trim($_POST["identidad"]) : "",
                "nombre" => isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "",
                "direccion" => isset($_POST["direccion"]) ? trim($_POST["direccion"]) : "",
                "correo" => isset($_POST["correo"]) ? trim($_POST["correo"]) : ""
            ];

            $rules = [
                "identidad" => ["required", "numeric", "max:10"],
                "nombre" => ["required", "text", "min:6", "max:50"],
                "direccion" => ["required", "min:10"],
                "correo" => ["required", "email"]
            ];

            $errors = ValidationHelper::validate($data, $rules, $this->conn);

            if (!empty($errors)) {
                $_SESSION["errors"] = $errors;

                include __DIR__ . '/../Views/Clients/edit.php';
                exit;
            } else {
                unset($_SESSION["errors"]);

                $updatedClient = $this->client->updateClient($id_client, $data);

                if ($updatedClient) {
                    $_SESSION["success_message"] = "Cliente actualizado correctamente";
                    header("Location: index.php?route=clients");
                    exit;
                } else {
                    $_SESSION["error_message"] = "Error al actualizar el cliente";
                }
            }
        }

        include __DIR__ . '/../Views/clients/edit.php';
    }

    public function delete($id_client)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $client = $this->client->getOneClient($id_client);

        if (empty($client)) {
            $_SESSION["error_message"] = "Cliente no encontrado";
            header("Location: index.php?route=clients");
            exit;
        }

        $deleted = $this->client->deleteClient($id_client);

        if ($deleted) {
            $_SESSION["success_message"] = "Cliente eliminado correctamente";
        } else {
            $_SESSION["error_message"] = "Error al eliminar el cliente";
        }

        header("Location: index.php?route=clients");
        exit;
    }

    public function exportClients()
    {
        $clientes = $this->client->getAllClients();

        header("Content-Type: text/csv; charset=ISO-8859-1");
        header("Content-Disposition: attachment; filename=clientes.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        fputcsv($output, ["ID", "Nro Identidad", "Nombre", "Direccion"], ';');

        foreach ($clientes as $cliente) {
            fputcsv($output, [
                mb_convert_encoding($cliente['id_cliente'], 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding($cliente['nro_identidad'], 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding($cliente['nombre_cliente'], 'ISO-8859-1', 'UTF-8'),
                mb_convert_encoding($cliente['direccion_cliente'], 'ISO-8859-1', 'UTF-8')
            ], ';');
        }

        fclose($output);
    }
}
?>