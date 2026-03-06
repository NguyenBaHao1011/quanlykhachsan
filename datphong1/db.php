<?php
// File: db.php
$host = "localhost";
$db_name = "datphong";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode([
        "status" => "error",
        "message" => "Lỗi kết nối CSDL: " . $e->getMessage()
    ]));
}
?>