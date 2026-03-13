<?php
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thêm đặt phòng
    $makh = $_POST['makh'];
    $ten = $_POST['ten'];
    $sdt = $_POST['sdt'];
    $maphong = $_POST['maphong'];
    $loaiphong = $_POST['loaiphong'];
    $ngaynhan = $_POST['ngaynhan'];
    $ngaytra = $_POST['ngaytra'];
    $tong = $_POST['tong'];

    $sql = "INSERT INTO datphong (makh, ten, sdt, maphong, loaiphong, ngaynhan, ngaytra, tongtien) VALUES ('$makh', '$ten', '$sdt', '$maphong', '$loaiphong', '$ngaynhan', '$ngaytra', '$tong')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Lấy danh sách đặt phòng
    $sql = "SELECT * FROM datphong";
    $result = $conn->query($sql);

    $bookings = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    echo json_encode($bookings);
}

$conn->close();
?>