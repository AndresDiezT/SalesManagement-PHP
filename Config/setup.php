<?php
require_once __DIR__ . "/Connection.php";

function ensureAdminUser() {
    $conn = (new Connection())->getConnection();

    $query = "SELECT COUNT(*) as total FROM empleados WHERE correo = 'admin@sales.com'";
    $stmt = $conn->query($query);
    $result = $stmt->fetch();

    if ($result['total'] == 0) {
        $insert = $conn->prepare("INSERT INTO empleados (nombre_empleado, usuario, empleado_password, correo) VALUES (?, ?, ?, ?)");
        
        $hashedPassword = password_hash("admin123", PASSWORD_DEFAULT);
        
        $insert->execute(["God", "AdminGod", $hashedPassword, "admin@sales.com"]);
    }
}

ensureAdminUser();
?>
