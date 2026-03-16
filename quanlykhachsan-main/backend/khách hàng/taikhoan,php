<?php
/**
 * CUSTOMER - USER SERVICE
 * Actor: Customer
 * Service: Profile Management (View/Update personal info)
 */

namespace App\Controllers\Customer\User;

use App\Models\User;
use App\Core\Logger;

class CustomerUserController {

    public function getProfile() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu ID người dùng"]);
            return;
        }
        Logger::info("[Customer][User] GET profile for user: $id");
        $userModel = new User();
        $user = $userModel->getById($id);
        if ($user) {
            unset($user['password']); // Extra safety
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Không tìm thấy người dùng"]);
        }
    }

    public function updateProfile() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu ID người dùng"]);
            return;
        }
        Logger::info("[Customer][User] Updating profile for user: " . $data['id']);
        $userModel = new User();
        
        // Prevent role escalation - customer can only stay customer
        $existing = $userModel->getById($data['id']);
        if (!$existing) {
             http_response_code(404);
             echo json_encode(["message" => "Không tìm thấy người dùng"]);
             return;
        }
        $data['role'] = $existing['role']; 
        if (isset($data['username'])) $data['username'] = $existing['username']; // Prevent username change

        if ($userModel->update($data['id'], $data)) {
            // Fetch updated user to return
            $updated = $userModel->getById($data['id']);
            unset($updated['password']);
            echo json_encode(["message" => "Cập nhật thành công", "user" => $updated]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Cập nhật thất bại"]);
        }
    }
}
