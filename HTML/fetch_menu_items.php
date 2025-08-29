<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_burger_house";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);

$menu_items = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
}

$conn->close();
echo json_encode($menu_items);
?>
