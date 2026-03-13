<?php

$host = "127.0.0.1";
$user = "root";
$password = "123456"; 
$database = "QLKS(hotel_management)";

$conn = mysqli_connect($host, $user, $password, $database, 3306);

if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

?>