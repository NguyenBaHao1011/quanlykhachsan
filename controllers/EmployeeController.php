<?php
class EmployeeController {

    public function index() {
        AuthMiddleware::adminOnly();
        echo "Chỉ ADMIN mới xem được danh sách nhân viên";
    }
}
