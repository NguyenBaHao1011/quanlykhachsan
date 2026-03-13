<?php
/**
 * ADMIN - SERVICE MANAGEMENT SERVICE
 * Actor: Admin
 * Service: Hotel Service Catalog (CRUD services offered by the hotel)
 */

namespace App\Controllers\Admin\ServiceManagement;

use App\Models\Service;
use App\Core\Logger;

class AdminServiceController {

    public function index() {
        header('Content-Type: application/json');
        Logger::info("[Admin][ServiceManagement] GET all services");
        $serviceModel = new Service();
        echo json_encode($serviceModel->getAll());
    }

    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][ServiceManagement] Creating service: " . ($data['service_name'] ?? 'N/A'));
        $serviceModel = new Service();
        if ($serviceModel->create($data)) {
            echo json_encode(["message" => "Dịch vụ đã được tạo"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Lỗi khi tạo dịch vụ"]);
        }
    }

    public function update() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][ServiceManagement] Updating service: " . ($data['id'] ?? 'N/A'));
        $serviceModel = new Service();
        if ($serviceModel->update($data['id'], $data)) {
            echo json_encode(["message" => "Cập nhật dịch vụ thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Cập nhật thất bại"]);
        }
    }

    public function destroy() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][ServiceManagement] Deleting service: " . ($data['id'] ?? 'N/A'));
        $serviceModel = new Service();
        if ($serviceModel->delete($data['id'])) {
            echo json_encode(["message" => "Xóa dịch vụ thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Xóa thất bại"]);
        }
    }
}
