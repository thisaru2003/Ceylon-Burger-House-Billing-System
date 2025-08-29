<?php


include('db.php'); 

$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);

$menuItems = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($menuItems);

$conn->close();
?>
