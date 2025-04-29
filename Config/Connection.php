<?php
    class Connection
    {
        private $host = "gestorventas.infinityfreeapp.com";
        private $user = "if0_38839698";
        private $password = "57LJFEPSIPp";
        private $db = "if0_38839698_2873797_adso";
        private $connection;

        public function __construct()
        {
            $db_string = "mysql:host=".$this->host.";dbname=".$this->db.";charset=utf8";
            try {
                $this->connection = new PDO($db_string, $this->user, $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                echo ("Connection Error");
                die("Connection Error: " . $e->getMessage());
            }
        }

        public function getConnection()
        {
            return $this->connection;
        }
    }
?>