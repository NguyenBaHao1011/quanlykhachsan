<?php
class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userModel->findByUsername($_POST['username']);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
                header("Location: index.php?route=rooms");
                exit;
            }

            $error = "Sai tài khoản hoặc mật khẩu";
        }
        require "views/auth/login.php";
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->create(
                $_POST['username'],
                $_POST['email'],
                $_POST['password'],
                $_POST['role_id']
            );
            header("Location: index.php?route=login");
            exit;
        }
        require "views/auth/register.php";
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?route=login");
    }
}
