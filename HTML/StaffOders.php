<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>
    <link rel="icon" type="image" href="../Images/Icon.png">
    <link rel="stylesheet" href="../CSS/staff_orders.css">
</head>
<body>
    <?php include('Header.php'); ?>
    <div class="Mtext"><b>Manage Orders</b></div>
    <div class="container">
        <table id="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Ordered Items</th>
                    <th>Total Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Order Time</th>
                    <th>Dine/Takeaway</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection settings
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "gallery_cafe";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Query to fetch order details with grouped items
                $query = "
                    SELECT o.id AS order_id, o.user_name, 
                           GROUP_CONCAT(CONCAT(oi.item_name, ' (', oi.quantity, ')') ORDER BY oi.item_name SEPARATOR ', ') AS ordered_items,
                           SUM(oi.quantity) AS total_quantity,
                           SUM(oi.price * oi.quantity) AS total_price,
                           o.order_date, o.order_time, o.dine_takeaway, o.status
                    FROM orders o
                    JOIN order_items oi ON o.id = oi.order_id
                    GROUP BY o.id
                ";
                $result = $conn->query($query);

                // Display orders in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['order_id']}</td>";
                    echo "<td>{$row['user_name']}</td>";
                    echo "<td>{$row['ordered_items']}</td>";
                    echo "<td>{$row['total_quantity']}</td>";
                    echo "<td>{$row['total_price']}</td>";
                    echo "<td>{$row['order_date']}</td>";
                    echo "<td>{$row['order_time']}</td>";
                    echo "<td>{$row['dine_takeaway']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo "<td>
                            <button onclick=\"updateOrderStatus({$row['order_id']}, 'Confirmed')\" class='confirm'>Confirm</button>
                            <button onclick=\"updateOrderStatus({$row['order_id']}, 'Declined')\" class='decline'>Decline</button>
                          </td>";
                    echo "</tr>";
                }

                // Close the connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <a href="ContentStaff" class="back-btn">Back</a>
    
    <script src="../JS/stafforders.js"></script>
</body>
</html>
