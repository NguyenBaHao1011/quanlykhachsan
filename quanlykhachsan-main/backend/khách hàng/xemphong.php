<?php
/**
 * CUSTOMER - ROOM BROWSING SERVICE
 * Actor: Customer
 * Service: Room Browsing (View available rooms, room details, filter by type)
 */

namespace App\Controllers\Customer\Room;

use App\Models\Room;
use App\Core\Logger;

class CustomerRoomController {

    public function index() {
        header('Content-Type: application/json');
        Logger::info("[Customer][Room] GET available rooms");
        $roomModel = new Room();
        $rooms = $roomModel->getAll();
        // Customers only see available rooms
        $available = array_filter($rooms, fn($r) => $r['status'] === 'available');
        echo json_encode(array_values($available));
    }

    public function show() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? 0;
        Logger::info("[Customer][Room] GET room detail: $id");
        $roomModel = new Room();
        $room = $roomModel->getById($id);
        if ($room && $room['status'] === 'available') {
            echo json_encode($room);
        } elseif (!$room) {
            http_response_code(404);
            echo json_encode(["message" => "Phòng không tồn tại"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Phòng này hiện không trống"]);
        }
    }

    public function showAll() {
        header('Content-Type: application/json');
        Logger::info("[Customer][Room] GET all rooms (public)");
        $roomModel = new Room();
        echo json_encode($roomModel->getAll());
    }
}
