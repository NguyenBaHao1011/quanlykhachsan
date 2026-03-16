<?php
/**
 * ADMIN - BOOKING MANAGEMENT SERVICE
 * Actor: Admin
 * Service: Booking Management (View all bookings, Cancel bookings)
 */

namespace App\Controllers\Admin\BookingManagement;

use App\Models\Booking;
use App\Core\Logger;

class AdminBookingController {

    public function index() {
        header('Content-Type: application/json');
        Logger::info("[Admin][BookingManagement] GET all bookings");
        $bookingModel = new Booking();
        $status = $_GET['status'] ?? null;
        echo json_encode($bookingModel->getAll($status ? ['status' => $status] : []));
    }

    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][BookingManagement] Creating new booking by admin");
        $bookingModel = new Booking();
        $bookingId = $bookingModel->create($data);
        if ($bookingId) {
            echo json_encode(["message" => "Dặt phòng thành công", "id" => $bookingId]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Lỗi khi tạo đặt phòng"]);
        }
    }

    public function update() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu ID đặt phòng"]);
            return;
        }
        Logger::info("[Admin][BookingManagement] Updating booking #{$data['id']}");
        $bookingModel = new Booking();
        if ($bookingModel->update($data['id'], $data)) {
            echo json_encode(["message" => "Cập nhật thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Cập nhật thất bại"]);
        }
    }

    public function updateStatus() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['id']) || empty($data['status'])) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu thông tin"]);
            return;
        }
        Logger::info("[Admin][BookingManagement] Update booking #{$data['id']} to {$data['status']}");
        $bookingModel = new Booking();
        if ($bookingModel->updateStatus($data['id'], $data['status'])) {
            echo json_encode(["message" => "Cập nhật trạng thái thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Cập nhật thất bại"]);
        }
    }

    public function destroy() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][BookingManagement] Deleting booking: " . ($data['id'] ?? 'N/A'));
        $bookingModel = new Booking();
        if ($bookingModel->delete($data['id'])) {
            echo json_encode(["message" => "Xóa đặt phòng thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Xóa thất bại"]);
        }
    }
}
