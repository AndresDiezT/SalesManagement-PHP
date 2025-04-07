<?php
session_start();

class AuthMiddleware {
    public static function checkAuth() {
        if (!isset($_SESSION["id_empleado"]) && !isset($_COOKIE["id_empleado"])) {
            header("Location: index.php?route=login");
            exit;
        }
    }
}
?>