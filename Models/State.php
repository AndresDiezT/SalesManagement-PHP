<?php
class State
{
    private $db;

    public function __construct($connection)
    {
        $this->db = $connection;
    }

    public function getAllStates()
    {
        $query = "SELECT * FROM estados";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getOneState($id_state)
    {
        $query = "SELECT * FROM estados WHERE id_estado = :id_estado";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":id_estado", $id_state);

        return $statement->execute();
    }

    public function createState($state_name)
    {
        $query = "INSERT INTO estados (nombre_estado) VALUES (:nombre_estado)";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":nombre_estado", $state_name);

        return $statement->execute();
    }

    public function updateState($id_state, $state_name)
    {
        $query = "UPDATE estados SET nombre_estado = :nombre_estado WHERE id_estado = :id_estado";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":id_estado", $id_state);
        $statement->bindParam(":nombre_estado", $state_name);

        return $statement->execute();
    }

    public function deleteState($id_state)
    {
        $query = "DELETE FROM estados WHERE id_estado = :id_estado";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":id_estado", $id_state);

        return $statement->execute();
    }
}
?>