<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include "db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch($method){



case 'GET':

$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
$data[] = $row;
}

echo json_encode($data);

break;




case 'POST':

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'];
$phone = $data['phone'];
$email = $data['email'];
$address = $data['address'];

$sql = "INSERT INTO customers(name,phone,email,address)
VALUES('$name','$phone','$email','$address')";

if($conn->query($sql)){
echo json_encode(["message"=>"Thêm thành công"]);
}else{
echo json_encode(["message"=>"Lỗi"]);
}

break;




case 'PUT':

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$name = $data['name'];
$phone = $data['phone'];
$email = $data['email'];
$address = $data['address'];

$sql = "UPDATE customers
SET name='$name',
phone='$phone',
email='$email',
address='$address'
WHERE id=$id";

if($conn->query($sql)){
echo json_encode(["message"=>"Cập nhật thành công"]);
}else{
echo json_encode(["message"=>"Lỗi"]);
}

break;




case 'DELETE':

$id = $_GET['id'];

$sql = "DELETE FROM customers WHERE id=$id";

if($conn->query($sql)){
echo json_encode(["message"=>"Xóa thành công"]);
}else{
echo json_encode(["message"=>"Lỗi"]);
}

break;

}

?>