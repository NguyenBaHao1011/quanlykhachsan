<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Room {
    private $conn;
    private $table_name = "rooms";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT r.*, rt.type_name, rt.price_per_night, rt.capacity,
                  GROUP_CONCAT(DISTINCT a.name ORDER BY a.name SEPARATOR '||') AS amenity_names,
                  GROUP_CONCAT(DISTINCT a.icon ORDER BY a.name SEPARATOR '||') AS amenity_icons
                  FROM rooms r
                  JOIN room_types rt ON r.room_type_id = rt.id
                  LEFT JOIN room_amenities ra ON r.id = ra.room_id
                  LEFT JOIN amenities a ON ra.amenity_id = a.id
                  GROUP BY r.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Transform amenity strings into arrays
        foreach ($rooms as &$room) {
            $room['amenities'] = $room['amenity_names'] ? array_map(function($name, $icon) {
                return ['name' => $name, 'icon' => $icon];
            }, explode('||', $room['amenity_names']), explode('||', $room['amenity_icons'])) : [];
            unset($room['amenity_names'], $room['amenity_icons']);
        }
        return $rooms;
    }

    public function getById($id) {
        $query = "SELECT r.*, rt.type_name, rt.price_per_night, rt.capacity, rt.description AS type_description,
                  GROUP_CONCAT(DISTINCT CONCAT(a.name,'::',a.icon,'::',a.category) ORDER BY a.category, a.name SEPARATOR '||') AS amenities_raw
                  FROM rooms r
                  JOIN room_types rt ON r.room_type_id = rt.id
                  LEFT JOIN room_amenities ra ON r.id = ra.room_id
                  LEFT JOIN amenities a ON ra.amenity_id = a.id
                  WHERE r.id = :id GROUP BY r.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($room && $room['amenities_raw']) {
            $room['amenities'] = array_map(function($item) {
                [$name, $icon, $cat] = explode('::', $item);
                return ['name' => $name, 'icon' => $icon, 'category' => $cat];
            }, explode('||', $room['amenities_raw']));
        } else {
            $room['amenities'] = [];
        }
        unset($room['amenities_raw']);

        // Get images
        $imgQuery = "SELECT image_url, caption, sort_order FROM room_images WHERE room_id = :id ORDER BY sort_order";
        $imgStmt = $this->conn->prepare($imgQuery);
        $imgStmt->bindParam(':id', $id);
        $imgStmt->execute();
        $room['images'] = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

        return $room;
    }

    public function create($data) {
        $query = "INSERT INTO rooms (room_number, room_type_id, status, description, max_guests, image_url) 
                  VALUES (:room_number, :room_type_id, :status, :description, :max_guests, :image_url)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_number', $data['room_number']);
        $stmt->bindParam(':room_type_id', $data['room_type_id']);
        $status = $data['status'] ?? 'available';
        $stmt->bindParam(':status', $status);
        $description = $data['description'] ?? '';
        $stmt->bindParam(':description', $description);
        $maxGuests = $data['max_guests'] ?? 2;
        $stmt->bindParam(':max_guests', $maxGuests);
        $imageUrl = $data['image_url'] ?? '';
        $stmt->bindParam(':image_url', $imageUrl);
        if ($stmt->execute()) {
            $roomId = $this->conn->lastInsertId();
            if (!empty($data['amenity_ids'])) {
                $this->syncAmenities($roomId, $data['amenity_ids']);
            }
            return $roomId;
        }
        return false;
    }

    public function update($id, $data) {
        $query = "UPDATE rooms SET room_number = :room_number, room_type_id = :room_type_id, 
                  status = :status, description = :description, max_guests = :max_guests";
        if (!empty($data['image_url'])) {
            $query .= ", image_url = :image_url";
        }
        $query .= " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_number', $data['room_number']);
        $stmt->bindParam(':room_type_id', $data['room_type_id']);
        $stmt->bindParam(':status', $data['status']);
        $description = $data['description'] ?? '';
        $stmt->bindParam(':description', $description);
        $maxGuests = $data['max_guests'] ?? 2;
        $stmt->bindParam(':max_guests', $maxGuests);
        if (!empty($data['image_url'])) {
            $stmt->bindParam(':image_url', $data['image_url']);
        }
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            if (isset($data['amenity_ids'])) {
                $this->syncAmenities($id, $data['amenity_ids']);
            }
            return true;
        }
        return false;
    }

    private function syncAmenities($roomId, $amenityIds) {
        $del = $this->conn->prepare("DELETE FROM room_amenities WHERE room_id = :rid");
        $del->bindParam(':rid', $roomId);
        $del->execute();
        if (empty($amenityIds)) return;
        $ins = $this->conn->prepare("INSERT INTO room_amenities (room_id, amenity_id) VALUES (:rid, :aid)");
        foreach ($amenityIds as $aid) {
            $ins->bindValue(':rid', $roomId);
            $ins->bindValue(':aid', $aid);
            $ins->execute();
        }
    }

    public function addImage($roomId, $imageUrl, $caption = '') {
        $stmt = $this->conn->prepare("INSERT INTO room_images (room_id, image_url, caption) VALUES (:rid, :url, :cap)");
        $stmt->bindParam(':rid', $roomId);
        $stmt->bindParam(':url', $imageUrl);
        $stmt->bindParam(':cap', $caption);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM rooms WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAllAmenities() {
        $stmt = $this->conn->prepare("SELECT * FROM amenities ORDER BY category, name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
