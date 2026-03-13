<?php
include 'config.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM phong";
$result = $conn->query($sql);

$rooms = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}
echo json_encode($rooms);

$conn->close();
?>