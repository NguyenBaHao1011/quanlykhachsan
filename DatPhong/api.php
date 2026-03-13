<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    try {
        $stmt = $conn->query("SELECT * FROM bookings ORDER BY id DESC");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch(PDOException $e) {
        echo json_encode([]);
    }
}

if ($method == 'POST') {
    $makh = $_POST['makh'] ?? '';
    $ten = $_POST['ten'] ?? '';
    $sdt = $_POST['sdt'] ?? '';
    $maphong = $_POST['maphong'] ?? '';
    $loaiphong = $_POST['loaiphong'] ?? '';
    $ngaynhan = $_POST['ngaynhan'] ?? '';
    $ngaytra = $_POST['ngaytra'] ?? '';
    $tong = $_POST['tong'] ?? 0;

    try {
        $conn->beginTransaction();

        $sql = "INSERT INTO bookings (makh, ten, sdt, maphong, loaiphong, ngaynhan, ngaytra, tong) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$makh, $ten, $sdt, $maphong, $loaiphong, $ngaynhan, $ngaytra, $tong]);

        $sql_update = "UPDATE phong SET tinhtrang = 'Đã đặt' WHERE maphong = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->execute([$maphong]);

        $conn->commit();
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
?>