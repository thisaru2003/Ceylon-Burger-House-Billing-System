<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_burger_house";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$user_name = $_SESSION['username'];

$order_date = $_POST['order_date'] ?? '';
$order_time = $_POST['order_time'] ?? '';
$cart = json_decode($_POST['cart'], true); // Cart data from JS

// Start transaction
$conn->begin_transaction();

try {
    // Insert order details into the orders table
    $stmt = $conn->prepare("INSERT INTO orders (user_name, order_date, order_time) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind parameters (user_name, order_date, order_time)
    $stmt->bind_param("sss", $user_name, $order_date, $order_time);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Get the inserted order ID
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert each item from the cart into the order_items table
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, quantity, price) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    foreach ($cart as $item) {
        // Bind parameters for each item (order_id, item_name, quantity, price)
        $stmt->bind_param("isid", $order_id, $item['name'], $item['quantity'], $item['price']);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    }
    $stmt->close();

    // Clear the user's cart after successful checkout
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_name = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $user_name);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // Commit the transaction
    $conn->commit();

    // Return the order ID in the success response
    echo json_encode(['status' => 'success', 'order_id' => $order_id]);

} catch (Exception $e) {
    // Rollback the transaction in case of error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// Close connection
$conn->close();
?>
