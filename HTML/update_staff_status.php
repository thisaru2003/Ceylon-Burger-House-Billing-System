<?php

$conn = new mysqli('localhost', 'root', '', 'ceylon_burger_house');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$staff_id = $data['id'];
$is_verified = $data['is_verified'];

$sql = "UPDATE staff SET is_verified = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $is_verified, $staff_id);

$response = array();
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>
