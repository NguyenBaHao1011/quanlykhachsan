<?php
/**
 * SHARED - AUTHENTICATION SERVICE (All Actors)
 * Actor: Admin, Receptionist, Accountant, Customer
 * Service: Authentication (Login, Logout, Register)
 */

namespace App\Controllers\Auth;

use App\Models\User;
use App\Core\Logger;

class AuthController {

    public function login() {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $role     = $data['role'] ?? '';

        Logger::info("[Auth] Login attempt - Role: $role, Username: $username");

        if (empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu tên đăng nhập hoặc mật khẩu"]);
            return;
        }

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user) {
            Logger::warn("[Auth] Login failed: User '$username' not found");
            Logger::warn("[Auth] Failed login attempt for username: $username");
            http_response_code(401);
            echo json_encode(["message" => "Sai tên đăng nhập hoặc mật khẩu"]);
            return;
        }

        if (!empty($role) && $user['role'] !== $role) {
            Logger::warn("[Auth] Login failed: Role mismatch for '$username'");
            http_response_code(401);
            echo json_encode(["message" => "Vai trò không hợp lệ"]);
            return;
        }

        if (!password_verify($password, $user['password'])) {
            Logger::warn("[Auth] Failed login attempt for username: $username");
            http_response_code(401);
            echo json_encode(["message" => "Sai tên đăng nhập hoặc mật khẩu"]);
            return;
        }

        Logger::info("[Auth] User logged in: $username ({$user['role']})");
        echo json_encode([
            "message"   => "Đăng nhập thành công",
            "user"      => [
                "id"        => $user['id'],
                "username"  => $user['username'],
                "full_name" => $user['full_name'],
                "role"      => $user['role']
            ]
        ]);
    }
}
