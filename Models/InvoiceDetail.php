<?php
require_once __DIR__ . "/../Config/Connection.php";

class invoiceDetail
{
    private $db;

    public function __construct($connection)
    {
        $this->db = $connection;
    }

    public function updateInvoiceDetails($nro_factura, $cod_prod, $data)
    {
        $query = "UPDATE detalle_factura SET
                cantidad = :cantidad,
                valor_prod = :valor_prod,
                impuesto_iva = :impuesto_iva,
                valor_impuesto = :valor_impuesto,
                valor_total = :valor_total
              WHERE nro_factura = :nro_factura AND cod_prod = :cod_prod";

        $statement = $this->db->prepare($query);

        $statement->bindValue(':cantidad', $data["cantidad"]);
        $statement->bindValue(':valor_prod', $data["valor_prod"]);
        $statement->bindValue(':impuesto_iva', $data["impuesto_iva"]);
        $statement->bindValue(':valor_impuesto', $data["valor_impuesto"]);
        $statement->bindValue(':valor_total', $data["valor_total"]);
        $statement->bindValue(':nro_factura', $nro_factura);
        $statement->bindValue(':cod_prod', $cod_prod);

        return $statement->execute();
    }


    public function createInvoiceDetails($data)
    {
        $query = "INSERT INTO detalles_factura 
                (nro_factura, cod_prod, cantidad, valor_prod, valor_impuesto, valor_total) 
              VALUES 
                (:nro_factura, :cod_prod, :cantidad, :valor_prod, :valor_impuesto, :valor_total)";

        $statement = $this->db->prepare($query);

        $statement->bindValue(':nro_factura', $data["nro_factura"]);
        $statement->bindValue(':cod_prod', $data["cod_prod"]);
        $statement->bindValue(':cantidad', $data["cantidad"]);
        $statement->bindValue(':valor_prod', $data["valor_prod"]);
        $statement->bindValue(':valor_impuesto', $data["valor_impuesto"]);
        $statement->bindValue(':valor_total', $data["valor_total"]);

        return $statement->execute();
    }

}
?>