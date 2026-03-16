<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Service {
    private $conn;
    private $table_name = "services";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (service_name, price, description) 
                  VALUES (:service_name, :price, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':service_name', $data['service_name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':description', $data['description']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET service_name = :service_name, price = :price, description = :description 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':service_name', $data['service_name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function addUsage($booking_id, $service_id, $quantity) {
        $query = "INSERT INTO service_usage (booking_id, service_id, quantity) 
                  VALUES (:booking_id, :service_id, :quantity)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':booking_id', $booking_id);
        $stmt->bindParam(':service_id', $service_id);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }
}
