<?php

$conn = new mysqli('localhost', 'root', '', 'gallery_cafe');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM admin";
$result = $conn->query($sql);

$admins = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
}


header('Content-Type: application/json');
echo json_encode($admins);

$conn->close();
?>
