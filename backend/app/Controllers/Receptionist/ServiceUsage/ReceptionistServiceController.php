<?php
/**
 * RECEPTIONIST - SERVICE USAGE SERVICE
 * Actor: Receptionist
 * Service: Hotel Services (View services, Log service usage per booking)
 */

namespace App\Controllers\Receptionist\ServiceUsage;

use App\Models\Service;
use App\Core\Logger;

class ReceptionistServiceController {

    public function index() {
        header('Content-Type: application/json');
        Logger::info("[Receptionist][ServiceUsage] GET all services");
        $serviceModel = new Service();
        echo json_encode($serviceModel->getAll());
    }

    public function addUsage() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['booking_id']) || empty($data['service_id'])) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu booking_id hoặc service_id"]);
            return;
        }
        Logger::info("[Receptionist][ServiceUsage] Adding service {$data['service_id']} to booking {$data['booking_id']}");
        $serviceModel = new Service();
        if ($serviceModel->addUsage($data['booking_id'], $data['service_id'], $data['quantity'] ?? 1)) {
            echo json_encode(["message" => "Thêm dịch vụ thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Thêm dịch vụ thất bại"]);
        }
    }
}
