<?php
/**
 * CUSTOMER - BOOKING SERVICE
 * Actor: Customer
 * Service: Booking (Create booking, View booking history)
 */

namespace App\Controllers\Customer\Booking;

use App\Models\Booking;
use App\Core\Logger;

class CustomerBookingController {

    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['room_id']) || empty($data['check_in_date']) || empty($data['check_out_date'])) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu thông tin đặt phòng"]);
            return;
        }
        Logger::info("[Customer][Booking] Creating booking for room: " . $data['room_id']);
        $bookingModel = new Booking();
        $id = $bookingModel->create($data);
        if ($id) {
            echo json_encode(["message" => "Đặt phòng thành công! Chúng tôi sẽ liên hệ xác nhận.", "booking_id" => $id]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Có lỗi xảy ra khi đặt phòng"]);
        }
    }

    public function history() {
        header('Content-Type: application/json');
        $userId = $_GET['user_id'] ?? null;
        if (!$userId) {
            http_response_code(400);
            echo json_encode(["message" => "Cần user_id để xem lịch sử"]);
            return;
        }
        Logger::info("[Customer][Booking] GET booking history for user: $userId");
        $bookingModel = new Booking();
        $all = $bookingModel->getAll([]);
        $history = array_filter($all, fn($b) => $b['user_id'] == $userId);
        echo json_encode(array_values($history));
    }

    public function cancel() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Customer][Booking] Cancel booking: " . ($data['id'] ?? 'N/A'));
        $bookingModel = new Booking();
        if ($bookingModel->updateStatus($data['id'], 'cancelled')) {
            echo json_encode(["message" => "Đã hủy đặt phòng thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Hủy đặt phòng thất bại"]);
        }
    }
}
