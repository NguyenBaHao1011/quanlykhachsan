<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Booking {
    private $conn;
    private $table_name = "bookings";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, room_id, check_in_date, check_out_date, total_price, status) 
                  VALUES (:user_id, :room_id, :check_in_date, :check_out_date, :total_price, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':room_id', $data['room_id']);
        $stmt->bindParam(':check_in_date', $data['check_in_date']);
        $stmt->bindParam(':check_out_date', $data['check_out_date']);
        $stmt->bindParam(':total_price', $data['total_price']);
        $stmt->bindParam(':status', $data['status']);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getAll($filters = []) {
        $query = "SELECT b.*, r.room_number, u.full_name as customer_name, rt.type_name 
                  FROM " . $this->table_name . " b
                  JOIN rooms r ON b.room_id = r.id
                  JOIN users u ON b.user_id = u.id
                  JOIN room_types rt ON r.room_type_id = rt.id";
        
        if (isset($filters['status'])) {
            $query .= " WHERE b.status = :status";
        }
        
        $stmt = $this->conn->prepare($query);
        if (isset($filters['status'])) {
            $stmt->bindParam(':status', $filters['status']);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT b.*, r.room_number, u.full_name as customer_name, rt.type_name 
                  FROM " . $this->table_name . " b
                  JOIN rooms r ON b.room_id = r.id
                  JOIN users u ON b.user_id = u.id
                  JOIN room_types rt ON r.room_type_id = rt.id
                  WHERE b.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET user_id = :user_id, room_id = :room_id, 
                      check_in_date = :check_in_date, check_out_date = :check_out_date, 
                      total_price = :total_price, status = :status
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':room_id', $data['room_id']);
        $stmt->bindParam(':check_in_date', $data['check_in_date']);
        $stmt->bindParam(':check_out_date', $data['check_out_date']);
        $stmt->bindParam(':total_price', $data['total_price']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
