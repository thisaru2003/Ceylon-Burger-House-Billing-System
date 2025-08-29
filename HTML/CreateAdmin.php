<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_burger_house";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = $error_message = ""; // Initialize message variables

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $is_verified = 1; 

    if (isEmailExists($email, $conn)) {
        $error_message = "Email already exists.";
    } elseif (isUsernameExists($username, $conn)) {
        $error_message = "Username already exists.";
    } else {
        $profile_photo = handleProfilePhotoUpload();

        if (insertAdmin($full_name, $username, $email, $phone, $address, $password, $profile_photo, $is_verified, $conn)) {
            $success_message = "New admin added successfully.";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }

    $conn->close();
}

function isEmailExists($email, $conn) {
    $stmt = $conn->prepare("
        SELECT email FROM users WHERE email = ? 
        UNION 
        SELECT email FROM admin WHERE email = ? 
        UNION 
        SELECT email FROM staff WHERE email = ?
    ");
    $stmt->bind_param("sss", $email, $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

function isUsernameExists($username, $conn) {
    $stmt = $conn->prepare("
        SELECT username FROM users WHERE username = ? 
        UNION 
        SELECT username FROM admin WHERE username = ? 
        UNION 
        SELECT username FROM staff WHERE username = ?
    ");
    $stmt->bind_param("sss", $username, $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

function handleProfilePhotoUpload() {
    $profile_photo = null;
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $target_dir = "../Images/";
        $profile_photo = $target_dir . basename($_FILES['profile_photo']['name']);
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo);
    }
    return $profile_photo;
}

function insertAdmin($full_name, $username, $email, $phone, $address, $password, $profile_photo, $is_verified, $conn) {
    $stmt = $conn->prepare("INSERT INTO admin (full_name, username, email, phone, address, password, profile_photo, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $full_name, $username, $email, $phone, $address, $password, $profile_photo, $is_verified);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Burger House - Add Admin</title>
    <link rel="icon" type="image" href="../Images/Icon.png">
    <link rel="stylesheet" href="../CSS/createstaff.css">
</head>
<body>
<form action="CreateAdmin.php" method="POST" enctype="multipart/form-data">

    <h1>Add Admin</h1>

    <!-- Display success or error messages -->
    <?php if (!empty($success_message)) : ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if (!empty($error_message)) : ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="profile_photo">Profile Photo:</label>
        <input type="file" id="profile_photo" name="profile_photo"><br>

        <input type="submit" value="Add Admin">
    </form>

    <a href="ContentAdmin" class="back-btn">Back</a>
</body>
</html>
