<?php

class Database {

    private $host = "localhost";
    private $db = "discover_rangsit";
    private $user = "root";
    private $pass = "";

    public function connect() {

        $pdo = new PDO(
            "mysql:host=".$this->host.";dbname=".$this->db.";charset=utf8mb4",
            $this->user,
            $this->pass
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

}