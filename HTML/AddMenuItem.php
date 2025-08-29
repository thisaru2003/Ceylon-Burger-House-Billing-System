<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_burger_house"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    // File upload logic
    $target_dir = "../Images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is valid
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $error_message = "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["image"]["size"] > 500000) { // 500KB
        $error_message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow specific file formats
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_extensions)) {
        $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;

            // Insert into menu_items table
            $stmt = $conn->prepare("INSERT INTO menu_items (category, name, price, image_url) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $category, $name, $price, $image_url);

            if ($stmt->execute()) {
                $success_message = "New menu item added successfully!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Burger House</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/addcelebrations.css">
</head>
<body>
    <header>
        <?php include('Header.php') ?>
    </header>

    <main>
        <h1>Add New Menu Item</h1>
        
        <?php if (isset($success_message)) : ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (isset($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="AddMenuItem.php" method="post" enctype="multipart/form-data">
            
            <div>
                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <option value="Burger">Burger</option>
                    <option value="Pasta">Pasta</option>
                    <option value="Hot Dog">Hot Dog</option>
                    <option value="Submarine">Submarine</option>
                    <option value="Beverage">Beverage</option>
                </select><br>
            </div>
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div>
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div>
                <button type="submit">Add Menu Item</button>
            </div>
        </form>
    </main>
    <a href="ContentAdmin.php" class="back-btn">Back</a>
</body>
</html>
