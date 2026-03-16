<?php
/**
 * RECEPTIONIST - BOOKING SERVICE
 * Actor: Receptionist
 * Service: Booking Management (Create/Confirm/Check-in/Check-out/View bookings)
 */

namespace App\Controllers\Receptionist\Booking;

use App\Models\Booking;
use App\Core\Logger;

class ReceptionistBookingController {

    public function index() {
        header('Content-Type: application/json');
        Logger::info("[Receptionist][Booking] GET all bookings");
        $bookingModel = new Booking();
        $status = $_GET['status'] ?? null;
        echo json_encode($bookingModel->getAll($status ? ['status' => $status] : []));
    }

    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Receptionist][Booking] Creating new booking for guest");
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
        Logger::info("[Receptionist][Booking] Updating booking #{$data['id']}");
        $bookingModel = new Booking();
        if ($bookingModel->update($data['id'], $data)) {
            echo json_encode(["message" => "Cập nhật thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Cập nhật thất bại"]);
        }
    }


    public function checkIn() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Receptionist][Booking] Check-in booking #{$data['id']}");
        $bookingModel = new Booking();
        if ($bookingModel->updateStatus($data['id'], 'checked_in')) {
            echo json_encode(["message" => "Check-in thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Check-in thất bại"]);
        }
    }

    public function checkOut() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Receptionist][Booking] Check-out booking #{$data['id']}");
        $bookingModel = new Booking();
        if ($bookingModel->updateStatus($data['id'], 'checked_out')) {
            echo json_encode(["message" => "Check-out thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Check-out thất bại"]);
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
        Logger::info("[Receptionist][Booking] Update booking #{$data['id']} to {$data['status']}");
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
        Logger::info("[Receptionist][Booking] Cancel booking: " . ($data['id'] ?? 'N/A'));
        $bookingModel = new Booking();
        if ($bookingModel->delete($data['id'])) {
            echo json_encode(["message" => "Xóa đặt phòng thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Xóa thất bại"]);
        }
    }
}
