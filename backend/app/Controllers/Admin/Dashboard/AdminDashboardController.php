<?php
/**
 * ADMIN - DASHBOARD SERVICE
 * Actor: Admin
 * Service: Dashboard & Statistics (Summary KPIs)
 */

namespace App\Controllers\Admin\Dashboard;

use App\Core\Database;
use App\Core\Logger;
use PDO;

class AdminDashboardController {

    public function getSummary() {
        header('Content-Type: application/json');
        Logger::info("[Admin][Dashboard] GET summary stats");
        try {
            $db = new Database();
            $conn = $db->getConnection();

            $stats = [];

            $stats['total_rooms'] = $conn->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
            $stats['booked_rooms'] = $conn->query("SELECT COUNT(*) FROM rooms WHERE status = 'occupied'")->fetchColumn();
            $stats['guests_today'] = $conn->query(
                "SELECT COUNT(*) FROM bookings WHERE check_in_date = CURDATE() AND status IN ('confirmed','checked_in')"
            )->fetchColumn();
            $stats['revenue'] = $conn->query(
                "SELECT COALESCE(SUM(total_amount), 0) FROM invoices WHERE payment_status = 'paid'"
            )->fetchColumn();
            $stats['available_rooms'] = $conn->query("SELECT COUNT(*) FROM rooms WHERE status = 'available'")->fetchColumn();
            $stats['pending_bookings'] = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
            $stats['total_users'] = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
            $stats['total_invoices'] = $conn->query("SELECT COUNT(*) FROM invoices")->fetchColumn();

            echo json_encode($stats);
        } catch (\Exception $e) {
            Logger::error("[Admin][Dashboard] Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["message" => "Lỗi tải thống kê"]);
        }
    }
}
