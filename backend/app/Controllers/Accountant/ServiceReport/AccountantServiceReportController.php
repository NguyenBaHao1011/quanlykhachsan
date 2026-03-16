<?php
/**
 * ACCOUNTANT - SERVICE REPORTING SERVICE
 * Actor: Accountant
 * Service: Service Revenue (View service usage and revenue earned per service)
 */

namespace App\Controllers\Accountant\ServiceReport;

use App\Core\Database;
use App\Core\Logger;
use PDO;

class AccountantServiceReportController {

    public function getSummary() {
        header('Content-Type: application/json');
        Logger::info("[Accountant][ServiceReport] GET service revenue summary");
        try {
            $db = new Database();
            $conn = $db->getConnection();

            $stmt = $conn->query(
                "SELECT s.service_name, s.price, 
                        COUNT(su.id) AS total_usage, 
                        SUM(su.quantity) AS total_quantity,
                        SUM(su.quantity * s.price) AS total_revenue
                 FROM services s
                 LEFT JOIN service_usage su ON s.id = su.service_id
                 GROUP BY s.id, s.service_name, s.price
                 ORDER BY total_revenue DESC"
            );

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (\Exception $e) {
            Logger::error("[Accountant][ServiceReport] Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["message" => "Lỗi tải báo cáo dịch vụ"]);
        }
    }
}
