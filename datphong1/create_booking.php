<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'db.php';

$data = json_decode(file_get_contents("php://input"));

if(
    empty($data->room_id) || 
    empty($data->customer_name) || 
    empty($data->check_in) || 
    empty($data->check_out)
) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Vui lòng điền đầy đủ thông tin!"]);
    exit();
}

$room_id = $data->room_id;
$customer_name = $data->customer_name;
$check_in = $data->check_in;
$check_out = $data->check_out;

if(strtotime($check_in) >= strtotime($check_out)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Ngày trả phòng phải sau ngày nhận phòng!"]);
    exit();
}

try {
    $sql_check = "SELECT id FROM bookings 
                  WHERE room_id = :room_id 
                  AND (check_in < :check_out AND check_out > :check_in)";
                  
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([
        ':room_id' => $room_id,
        ':check_in' => $check_in,
        ':check_out' => $check_out
    ]);

    if($stmt_check->rowCount() > 0) {
        http_response_code(409); 
        echo json_encode(["status" => "error", "message" => "Phòng này đã kín lịch trong thời gian bạn chọn!"]);
        exit();
    }

    $sql_insert = "INSERT INTO bookings (room_id, customer_name, check_in, check_out) 
                   VALUES (:room_id, :customer_name, :check_in, :check_out)";
                   
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->execute([
        ':room_id' => $room_id,
        ':customer_name' => $customer_name,
        ':check_in' => $check_in,
        ':check_out' => $check_out
    ]);

    http_response_code(201);
    echo json_encode([
        "status" => "success", 
        "message" => "Đặt phòng thành công!",
        "booking_id" => $conn->lastInsertId()
    ]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
}
?>