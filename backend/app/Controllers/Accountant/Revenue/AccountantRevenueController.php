<?php
/**
 * ACCOUNTANT - REVENUE REPORTING SERVICE
 * Actor: Accountant
 * Service: Revenue Reports (Monthly revenue, Room earnings, Service earnings)
 */

namespace App\Controllers\Accountant\Revenue;

use App\Core\Database;
use App\Core\Logger;
use PDO;

class AccountantRevenueController {

    public function getSummary() {
        header('Content-Type: application/json');
        Logger::info("[Accountant][Revenue] GET revenue summary");
        try {
            $db = new Database();
            $conn = $db->getConnection();

            $summary = [];

            $summary['total_revenue'] = $conn->query(
                "SELECT COALESCE(SUM(total_amount), 0) FROM invoices WHERE payment_status = 'paid'"
            )->fetchColumn();

            $summary['monthly_revenue'] = $conn->query(
                "SELECT COALESCE(SUM(total_amount), 0) FROM invoices 
                 WHERE payment_status = 'paid' AND MONTH(invoice_date) = MONTH(NOW()) AND YEAR(invoice_date) = YEAR(NOW())"
            )->fetchColumn();

            $summary['unpaid_invoices'] = $conn->query(
                "SELECT COUNT(*) FROM invoices WHERE payment_status = 'unpaid'"
            )->fetchColumn();

            $summary['total_invoices'] = $conn->query("SELECT COUNT(*) FROM invoices")->fetchColumn();

            // Revenue by room type
            $stmt = $conn->query(
                "SELECT rt.type_name, COALESCE(SUM(i.total_amount), 0) as revenue
                 FROM invoices i
                 JOIN bookings b ON i.booking_id = b.id
                 JOIN rooms r ON b.room_id = r.id
                 JOIN room_types rt ON r.room_type_id = rt.id
                 WHERE i.payment_status = 'paid'
                 GROUP BY rt.id, rt.type_name"
            );
            $summary['revenue_by_type'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Recent 6 months timeline
            $stmt = $conn->query(
                "SELECT DATE_FORMAT(invoice_date, '%Y-%m') AS month, COALESCE(SUM(total_amount), 0) as revenue
                 FROM invoices WHERE payment_status = 'paid'
                 GROUP BY month ORDER BY month DESC LIMIT 6"
            );
            $summary['monthly_timeline'] = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

            echo json_encode($summary);
        } catch (\Exception $e) {
            Logger::error("[Accountant][Revenue] Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["message" => "Lỗi tải báo cáo doanh thu"]);
        }
    }
}
