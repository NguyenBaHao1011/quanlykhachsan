<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Invoice {
    private $conn;
    private $table_name = "invoices";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function generate($booking_id) {
        // Calculate total: Room Price * Nights + Services
        // This is a simplified version
        $query = "SELECT b.*, r.id as room_id, rt.price_per_night 
                  FROM bookings b 
                  JOIN rooms r ON b.room_id = r.id 
                  JOIN room_types rt ON r.room_type_id = rt.id 
                  WHERE b.id = :booking_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':booking_id', $booking_id);
        $stmt->execute();
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$booking) return false;

        // Calculate service costs
        $sQuery = "SELECT SUM(su.quantity * s.price) as service_total 
                   FROM service_usage su 
                   JOIN services s ON su.service_id = s.id 
                   WHERE su.booking_id = :booking_id";
        $sStmt = $this->conn->prepare($sQuery);
        $sStmt->bindParam(':booking_id', $booking_id);
        $sStmt->execute();
        $sTotal = $sStmt->fetch(PDO::FETCH_ASSOC)['service_total'] ?? 0;

        $totalAmount = $booking['total_price'] + $sTotal;

        $iQuery = "INSERT INTO " . $this->table_name . " (booking_id, total_amount, payment_status) 
                   VALUES (:booking_id, :total_amount, 'unpaid')";
        $iStmt = $this->conn->prepare($iQuery);
        $iStmt->bindParam(':booking_id', $booking_id);
        $iStmt->bindParam(':total_amount', $totalAmount);
        
        if($iStmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getAll() {
        $query = "SELECT i.*, u.full_name, b.check_in_date, b.check_out_date 
                  FROM " . $this->table_name . " i 
                  JOIN bookings b ON i.booking_id = b.id 
                  JOIN users u ON b.user_id = u.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET payment_status = :status WHERE id = :id";
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
