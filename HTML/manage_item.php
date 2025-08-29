<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $image_url = $_POST['existing_image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_url = '../Images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_url);
    }

    // Update the menu_items table
    $sql = "UPDATE menu_items SET category=?, name=?, price=?, image_url=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $category, $name, $price, $image_url, $id);

    if ($stmt->execute()) {
        header('Location: ManageItem.php');
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM menu_items WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu Item</title>
    <link rel="stylesheet" href="../CSS/Manage_Items.css">
</head>
<body>
    <form action="manage_item.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($item['image_url']); ?>">

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="Burger" <?php echo ($item['category'] == 'Burger') ? 'selected' : ''; ?>>Burger</option>
            <option value="Submarine" <?php echo ($item['category'] == 'Submarine') ? 'selected' : ''; ?>>Submarine</option>
            <option value="Hotdog" <?php echo ($item['category'] == 'Hotdog') ? 'selected' : ''; ?>>Hotdog</option>
            <option value="Shawarma" <?php echo ($item['category'] == 'Shawarma') ? 'selected' : ''; ?>>Shawarma</option>
            <option value="Beverage" <?php echo ($item['category'] == 'Beverage') ? 'selected' : ''; ?>>Beverage</option>
        </select><br>
        
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($item['name']); ?>" required><br>

        <label for="price">Price:</label>
        <input type="text" name="price" id="price" value="<?php echo htmlspecialchars($item['price']); ?>" required><br>

        <label for="image">Image:</label>
        <input type="file" name="image" id="image"><br>
        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="100"><br>

        <input type="submit" value="Update Item">
    </form>
    <a href="ManageItem.php" class="back-btn">Back</a>
</body>
</html>
