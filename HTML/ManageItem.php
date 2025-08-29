<?php

$conn = new mysqli('localhost', 'root', '', 'ceylon_burger_house');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Gallery Café - Manage Menu Items</title>
    <link rel="icon" type="image" href="../Images/Icon.png">
    <link rel="stylesheet" href="../CSS/Managerooms.css">
</head>
<body>
<div class="Mtext"><b>Manage Menu Items</b></div>

    <table>
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Manage</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <?php if($row['image_url']): ?>
                            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" width="100">
                        <?php else: ?>
                            <img src="../Images/default_food.png" alt="No Image" width="100">
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <a href="manage_item.php?id=<?php echo $row['id']; ?>" class="btn-update">Update</a>
                    </td>
                    <td>
                        <a href="delete_item.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this menu item?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="ContentAdmin.php" class="back-btn">Back</a>
</body>
</html>

<?php
$conn->close();
?>
