<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_burger_house";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "DELETE FROM menu_items WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    header('Location: ManageItem.php');
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
