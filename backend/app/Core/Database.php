<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private $host = "db";
    private $db_name = "hotel_management";
    private $username = "user";
    private $password = "user_password";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            Logger::error("Database Connection Error: " . $exception->getMessage());
            // Don't echo HTML here, it breaks API responses
            return null;
        }
        return $this->conn;
    }
}
