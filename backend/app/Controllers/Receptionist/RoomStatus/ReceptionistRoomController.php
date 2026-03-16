<?php
/**
 * RECEPTIONIST - ROOM STATUS SERVICE
 * Actor: Receptionist
 * Service: Room Status Management (View rooms, Update status: clean/dirty/maintenance)
 */

namespace App\Controllers\Receptionist\RoomStatus;

use App\Models\Room;
use App\Core\Logger;

class ReceptionistRoomController {

    public function index() {
        header('Content-Type: application/json');
        Logger::info("[Receptionist][RoomStatus] GET all rooms");
        $roomModel = new Room();
        echo json_encode($roomModel->getAll());
    }

    public function show() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? 0;
        Logger::info("[Receptionist][RoomStatus] GET room detail: $id");
        $roomModel = new Room();
        $room = $roomModel->getById($id);
        if ($room) {
            echo json_encode($room);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Phòng không tồn tại"]);
        }
    }

    public function updateStatus() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['id']) || empty($data['status'])) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu thông tin id hoặc status"]);
            return;
        }
        Logger::info("[Receptionist][RoomStatus] Update room #{$data['id']} status to {$data['status']}");
        $roomModel = new Room();
        if ($roomModel->update($data['id'], $data)) {
            echo json_encode(["message" => "Trạng thái phòng đã được cập nhật"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Cập nhật thất bại"]);
        }
    }
}
