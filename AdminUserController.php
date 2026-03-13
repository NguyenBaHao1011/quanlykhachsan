<?php
/**
 * ADMIN - USER MANAGEMENT SERVICE
 * Actor: Admin
 * Service: User Management (CRUD Users, Staff, Customers)
 */

namespace App\Controllers\Admin\UserManagement;

use App\Models\User;
use App\Core\Logger;

class AdminUserController {

    public function index() {
        header('Content-Type: application/json');
        Logger::info("[Admin][UserManagement] GET all users");
        $userModel = new User();
        echo json_encode($userModel->getAll());
    }

    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][UserManagement] Creating user: " . ($data['username'] ?? 'N/A'));
        $userModel = new User();
        if ($userModel->create($data)) {
            echo json_encode(["message" => "Người dùng đã được tạo"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Lỗi khi tạo người dùng"]);
        }
    }

    public function update() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][UserManagement] Updating user: " . ($data['id'] ?? 'N/A'));
        $userModel = new User();
        if ($userModel->update($data['id'], $data)) {
            echo json_encode(["message" => "Cập nhật người dùng thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Cập nhật thất bại"]);
        }
    }

    public function destroy() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][UserManagement] Deleting user: " . ($data['id'] ?? 'N/A'));
        $userModel = new User();
        if ($userModel->delete($data['id'])) {
            echo json_encode(["message" => "Xóa người dùng thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Xóa thất bại"]);
        }
    }
}
