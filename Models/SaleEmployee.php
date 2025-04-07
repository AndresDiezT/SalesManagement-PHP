<?php
class SaleEmployee
{
    private $db;

    public function __construct($connection)
    {
        $this->db = $connection;
    }

    public function getAllSales()
    {
        $query = "SELECT
            ventas_empleado.nro_factura, ventas_empleado.fecha_venta, clientes.nombre_cliente, empleados.nombre_empleado, tipos_venta.descripcion
            FROM ventas_empleado
            INNER JOIN clientes ON ventas_empleado.id_cliente = clientes.id_cliente
            INNER JOIN empleados ON ventas_empleado.id_empleado = empleados.id_empleado
            INNER JOIN tipos_venta ON ventas_empleado.id_tipo_venta = tipos_venta.id_tipo_venta";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getOneSale($nro_invoice)
    {
        $query = "SELECT v.nro_factura, v.fecha_venta,
                v.id_cliente AS venta_id_cliente, v.id_tipo_venta AS venta_id_tipo_venta, v.id_empleado AS venta_id_empleado,
                c.nombre_cliente, e.nombre_empleado, t.descripcion AS tipo_venta,
                p.nombre_prod, d.cantidad, p.impuesto, d.valor_prod, d.valor_impuesto, d.valor_total
                FROM ventas_empleado v
                JOIN clientes c ON v.id_cliente = c.id_cliente
                JOIN empleados e ON v.id_empleado = e.id_empleado
                JOIN tipos_venta t ON v.id_tipo_venta = t.id_tipo_venta
                LEFT JOIN detalles_factura d ON v.nro_factura = d.nro_factura
                LEFT JOIN productos p ON d.cod_prod = p.cod_prod
                WHERE v.nro_factura = :nro_factura";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":nro_factura", $nro_invoice);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function createSale($data)
    {
        $query = "INSERT INTO
            ventas_empleado (nro_factura, fecha_venta, id_cliente, id_tipo_venta, id_empleado)
            VALUES (:nro_factura, :fecha_venta, :id_cliente, :id_tipo_venta, :id_empleado)";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":nro_factura", $data["nro_factura"]);
        $statement->bindParam(":fecha_venta", $data["fecha"]);
        $statement->bindParam(":id_cliente", $data["id_cliente"]);
        $statement->bindParam(":id_tipo_venta", $data["id_tipo_venta"]);
        $statement->bindParam(":id_empleado", $data["id_empleado"]);

        return $statement->execute();
    }

    public function updateSale($nro_invoice, $data)
    {
        $query = "UPDATE ventas_empleado SET
            id_cliente = :id_cliente,
            id_empleado = :id_empleado,
            id_tipo_venta = :id_tipo_venta
            WHERE nro_factura = :nro_factura";

        $statement = $this->db->prepare($query);

        $statement->bindParam(":nro_factura", $nro_invoice);
        $statement->bindParam(":id_cliente", $data["id_cliente"]);
        $statement->bindParam(":id_empleado", $data["id_empleado"]);
        $statement->bindParam(":id_tipo_venta", $data["id_tipo_venta"]);

        return $statement->execute();
    }

    public function deleteSale($nro_invoice)
    {
        $queryDetails = "DELETE FROM detalles_factura WHERE nro_factura = :nro_factura";
        $statementDetails = $this->db->prepare($queryDetails);
        $statementDetails->bindParam(":nro_factura", $nro_invoice);
        $statementDetails->execute();

        $query = "DELETE FROM ventas_empleado WHERE nro_factura = :nro_factura";
        $statement = $this->db->prepare($query);
        $statement->bindParam(":nro_factura", $nro_invoice);
        return $statement->execute();
    }

    public function generateInvoiceNumber()
    {
        $prefix = "F";
        $year = date("y");
        $month = date("m");

        $query = "SELECT MAX(nro_factura) as last_invoice FROM ventas_empleado WHERE nro_factura LIKE ?";
        $statement = $this->db->prepare($query);
        $statement->execute(["{$prefix}{$year}{$month}%"]);
        $result = $statement->fetch();

        if ($result["last_invoice"]) {
            $lastNumber = intval(substr($result["last_invoice"], -4));
            $newNumber = str_pad($lastNumber + 1, 4, "0", STR_PAD_LEFT);
        } else {
            $newNumber = "0001";
        }

        return "{$prefix}{$year}{$month}{$newNumber}";
    }

}
?>