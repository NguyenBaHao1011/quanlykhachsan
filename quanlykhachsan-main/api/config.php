<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quanlykhachsan";

// tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// kiểm tra lỗi
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// tránh lỗi tiếng Việt
$conn->set_charset("utf8");

// nếu dùng API
header("Content-Type: application/json; charset=UTF-8");

?>