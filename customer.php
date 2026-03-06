<?php

error_reporting(E_ALL);

require '../db.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);