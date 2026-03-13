<?php
include 'config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];



if ($method === 'GET') {

    $sql = "SELECT * FROM khachhang";
    $result = $conn->query($sql);

    $data = [];

    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }

    echo json_encode($data);
}



elseif ($method === 'POST') {

    $makh = $_POST['makh'];
    $ten = $_POST['ten'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $cccd = $_POST['cccd'];

    $sql = "INSERT INTO khachhang (makh, ten, sdt, email, cccd)
            VALUES ('$makh','$ten','$sdt','$email','$cccd')";

    if ($conn->query($sql) === TRUE) {

        echo json_encode([
            "success" => true,
            "message" => "Thêm khách hàng thành công"
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "error" => $conn->error
        ]);
    }
}



elseif ($method === 'PUT') {

    parse_str(file_get_contents("php://input"), $_PUT);

    $makh = $_PUT['makh'];
    $ten = $_PUT['ten'];
    $sdt = $_PUT['sdt'];
    $email = $_PUT['email'];
    $cccd = $_PUT['cccd'];

    $sql = "UPDATE khachhang SET
            ten='$ten',
            sdt='$sdt',
            email='$email',
            cccd='$cccd'
            WHERE makh='$makh'";

    if ($conn->query($sql)) {

        echo json_encode([
            "success" => true,
            "message" => "Cập nhật thành công"
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "error" => $conn->error
        ]);
    }
}



elseif ($method === 'DELETE') {

    parse_str(file_get_contents("php://input"), $_DELETE);

    $makh = $_DELETE['makh'];

    $sql = "DELETE FROM khachhang WHERE makh='$makh'";

    if ($conn->query($sql)) {

        echo json_encode([
            "success" => true,
            "message" => "Xóa thành công"
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "error" => $conn->error
        ]);
    }
}

$conn->close();
?>