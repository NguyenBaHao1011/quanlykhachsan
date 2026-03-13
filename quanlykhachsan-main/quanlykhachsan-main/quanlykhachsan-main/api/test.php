<?php
include 'config.php';

if ($conn->connect_error) {
    echo "Kết nối thất bại: " . $conn->connect_error;
} else {
    echo "Kết nối thành công đến database quanlykhachsan!";
}

$conn->close();
?>