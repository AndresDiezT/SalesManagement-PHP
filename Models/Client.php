<?php
require_once __DIR__ . "/../Config/Connection.php";

class Client
{
    private $db;

    public function __construct($connection)
    {
        $this->db = $connection;
    }

    public function getAllClients()
    {
        $query = "SELECT * FROM clientes";

        $statement = $this->db->prepare($query);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getOneClient($id_client)
    {
        $query = "SELECT * FROM clientes WHERE id_cliente = :id_cliente";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":id_cliente", $id_client);

        $statement->execute();

        return $statement->fetch();
    }

    public function createClient($data)
    {
        $query = "INSERT INTO clientes
            (nro_identidad, nombre_cliente, direccion_cliente) 
        VALUES
            (:nro_identidad, :nombre_cliente, :direccion_cliente)";

        $statement = $this->db->prepare($query);

        $statement->bindParam(':nro_identidad', $data["identidad"]);
        $statement->bindParam(':nombre_cliente', $data["nombre"]);
        $statement->bindParam(':direccion_cliente', $data["direccion"]);

        return $statement->execute();
    }


    public function updateClient($id_client, $data)
    {
        $query = "UPDATE clientes SET
                nro_identidad = :nro_identidad, 
                nombre_cliente = :nombre_cliente,
                direccion_cliente = :direccion_cliente
            WHERE id_cliente = :id_cliente";

        $statement = $this->db->prepare($query);

        $statement->bindParam(':nro_identidad', $data["identidad"]);
        $statement->bindParam(':nombre_cliente', $data["nombre"]);
        $statement->bindParam(':direccion_cliente', $data["direccion"]);
        $statement->bindParam(':id_cliente', $id_client);

        return $statement->execute();
    }


    public function deleteClient($id_client)
    {
        $query = "DELETE FROM clientes WHERE id_cliente = :id_cliente";

        $statement = $this->db->prepare($query);

        $statement->bindParam(':id_cliente', $id_client);

        return $statement->execute();
    }
}
?>