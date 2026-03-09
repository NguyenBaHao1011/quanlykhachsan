<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'db.php';

try {
    $stmt = $conn->query("SELECT maphong, loaiphong, giaphong FROM phong WHERE tinhtrang = 'Trống'");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch(PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>