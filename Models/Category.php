<?php
class Category
{
    private $db;

    public function __construct($connection)
    {
        $this->db = $connection;
    }

    public function getAllCategories()
    {
        $query = "SELECT * FROM categorias";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getOneCategory($id_category)
    {
        $query = "SELECT * FROM categorias WHERE id_categoria = :id_categoria";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":id_categoria", $id_category);

        return $statement->execute();
    }

    public function createCategory($category_name)
    {
        $query = "INSERT INTO categorias (nombre_categoria) VALUES (:nombre_categoria)";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":nombre_categoria", $category_name);

        return $statement->execute();
    }

    public function updateCategory($id_category, $category_name)
    {
        $query = "UPDATE categorias SET nombre_categoria = :nombre_categoria WHERE id_categoria = :id_categoria";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":id_categoria", $id_category);
        $statement->bindParam(":nombre_categoria", $category_name);

        return $statement->execute();
    }

    public function deleteCategory($id_category)
    {
        $query = "DELETE FROM categorias WHERE id_categoria = :id_categoria";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":id_categoria", $id_category);

        return $statement->execute();
    }
}
?>