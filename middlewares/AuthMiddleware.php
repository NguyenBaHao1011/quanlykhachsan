<?php
class AuthMiddleware {
    public static function check() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?route=login");
            exit;
        }
    }

    public static function adminOnly() {
        self::check();
        if ($_SESSION['user']['role'] !== 'admin') {
            die("Bạn không có quyền truy cập chức năng này");
        }
    }
}
