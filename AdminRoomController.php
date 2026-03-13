<?php
/**
 * ADMIN - ROOM MANAGEMENT SERVICE
 * Actor: Admin
 * Service: Room Management (CRUD Rooms, Images, Amenities)
 */

namespace App\Controllers\Admin\RoomManagement;

use App\Models\Room;
use App\Core\Logger;

class AdminRoomController {

    public function index() {
        header('Content-Type: application/json');
        Logger::info("[Admin][RoomManagement] GET all rooms");
        $roomModel = new Room();
        echo json_encode($roomModel->getAll());
    }

    public function show() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? 0;
        Logger::info("[Admin][RoomManagement] GET room detail: $id");
        $roomModel = new Room();
        $room = $roomModel->getById($id);
        if ($room) {
            echo json_encode($room);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Phòng không tồn tại"]);
        }
    }

    public function amenities() {
        header('Content-Type: application/json');
        Logger::info("[Admin][RoomManagement] GET all amenities");
        $roomModel = new Room();
        echo json_encode($roomModel->getAllAmenities());
    }

    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][RoomManagement] Creating room: " . ($data['room_number'] ?? 'N/A'));
        $roomModel = new Room();
        $roomId = $roomModel->create($data);
        if ($roomId) {
            echo json_encode(["message" => "Phòng đã được tạo", "id" => $roomId]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Lỗi khi tạo phòng"]);
        }
    }

    public function upload() {
        header('Content-Type: application/json');
        if (!isset($_FILES['image'])) {
            http_response_code(400);
            echo json_encode(["message" => "Không có file nào được gửi lên"]);
            return;
        }
        $uploadDir = __DIR__ . '/../../../../public/uploads/rooms/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $file = $_FILES['image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowed)) {
            http_response_code(400);
            echo json_encode(["message" => "Chỉ cho phép định dạng JPG, PNG, WEBP, GIF"]);
            return;
        }
        $filename = 'room_' . time() . '_' . uniqid() . '.' . $ext;
        $filepath = $uploadDir . $filename;
        $url = '/uploads/rooms/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            Logger::info("[Admin][RoomManagement] Image uploaded: $filename");
            $roomId = $_POST['room_id'] ?? null;
            $caption = $_POST['caption'] ?? '';
            if ($roomId) {
                $roomModel = new Room();
                $roomModel->addImage($roomId, $url, $caption);
            }
            echo json_encode(["message" => "Tải lên thành công", "url" => $url]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Lỗi khi lưu file"]);
        }
    }

    public function update() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][RoomManagement] Updating room: " . ($data['id'] ?? 'N/A'));
        $roomModel = new Room();
        if ($roomModel->update($data['id'], $data)) {
            echo json_encode(["message" => "Cập nhật phòng thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Cập nhật thất bại"]);
        }
    }

    public function destroy() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Admin][RoomManagement] Deleting room: " . ($data['id'] ?? 'N/A'));
        $roomModel = new Room();
        if ($roomModel->delete($data['id'])) {
            echo json_encode(["message" => "Xóa phòng thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Xóa thất bại"]);
        }
    }
}
