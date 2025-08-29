<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ceylon_burger_house');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data from the request
$data = json_decode(file_get_contents("php://input"), true);

$admin_id = $data['id'];
$is_verified = $data['is_verified'];

// Update the admin status
$sql = "UPDATE admin SET is_verified = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $is_verified, $admin_id);

$response = array();
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>
