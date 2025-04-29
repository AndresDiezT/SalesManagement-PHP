<?php
require_once __DIR__ . "/../Config/Connection.php";

class Employee
{
    private $db;

    public function __construct($connection)
    {
        $this->db = $connection;
    }

    public function getAllEmployees()
    {
        $query = "SELECT id_empleado, nombre_empleado, usuario, correo FROM empleados";
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getOneEmployee($id_employee)
    {
        $query = "SELECT id_empleado, nombre_empleado, usuario, correo FROM empleados WHERE id_empleado = :id_empleado";
        $statement = $this->db->prepare($query);
        $statement->bindParam(":id_empleado", $id_employee);
        $statement->execute();

        return $statement->fetch();
    }

    public function searchEmployees($term)
    {
        $term = "%{$term}%";
        $query = "SELECT id_empleado, nombre_empleado, usuario, correo 
              FROM empleados
              WHERE id_empleado LIKE :term OR nombre_empleado LIKE :term OR usuario LIKE :term OR correo LIKE :term";

        $statement = $this->db->prepare($query);
        $statement->bindParam(':term', $term);
        $statement->execute();

        return $statement->fetchAll();
    }


    public function createEmployee($data)
    {
        $query = "INSERT INTO empleados (nombre_empleado, usuario, correo, empleado_password) 
                  VALUES (:nombre_empleado, :usuario, :correo, :empleado_password)";

        $statement = $this->db->prepare($query);
        $statement->bindParam(':nombre_empleado', $data["nombre"]);
        $statement->bindParam(':usuario', $data["usuario"]);
        $statement->bindParam(':correo', $data["correo"]);
        $statement->bindParam(':empleado_password', $data["empleado_password"]);

        return $statement->execute();
    }

    public function loginEmployee($usuario)
    {
        $query = "SELECT * FROM empleados WHERE usuario = :usuario";
        $statement = $this->db->prepare($query);

        $statement->bindParam(':usuario', $usuario);
        $statement->execute();

        $employees = $statement->fetchAll();

        if (count($employees) > 1) {
            return "error_multiple";
        }

        return $employees[0] ?? null;
    }

    public function updateEmployee($id_employee, $data)
    {
        $query = "UPDATE empleados SET 
                  nombre_empleado = :nombre_empleado, 
                  usuario = :usuario,
                  correo = :correo
                  WHERE id_empleado = :id_empleado";

        $statement = $this->db->prepare($query);
        $statement->bindParam(':nombre_empleado', $data["nombre"]);
        $statement->bindParam(':usuario', $data["usuario"]);
        $statement->bindParam(':correo', $data["correo"]);
        $statement->bindParam(':id_empleado', $id_employee);

        return $statement->execute();
    }

    public function deleteEmployee($id_employee)
    {
        $query = "DELETE FROM empleados WHERE id_empleado = :id_empleado";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':id_empleado', $id_employee);

        return $statement->execute();
    }
}
?>