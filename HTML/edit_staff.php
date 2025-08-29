<?php
$conn = new mysqli('localhost', 'root', '', 'ceylon_burger_house');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$staff_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM staff WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Staff not found.");
}

$staff = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $profile_photo = $_FILES['profile_photo']['name'] ? $_FILES['profile_photo']['name'] : $staff['profile_photo'];

    $check_sql = "SELECT id FROM users WHERE (username = ? OR email = ?) 
                  UNION 
                  SELECT id FROM admin WHERE (username = ? OR email = ?)
                  UNION 
                  SELECT id FROM staff WHERE (username = ? OR email = ?) AND id != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ssssssi", $username, $email, $username, $email, $username, $email, $staff_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_message = "Username or email already exists in another record.";
    } else {
        if ($_FILES['profile_photo']['name']) {
            $target_dir = "../Images/";
            $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);
            if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
                $profile_photo = $_FILES["profile_photo"]["name"];
            } else {
                $error_message = "There was an error uploading the profile photo.";
            }
        }

        if (!isset($error_message)) {
            $update_sql = "UPDATE staff SET full_name = ?, username = ?, email = ?, phone = ?, address = ?, profile_photo = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssssi", $full_name, $username, $email, $phone, $address, $profile_photo, $staff_id);

            if ($update_stmt->execute()) {
                $success_message = "Staff updated successfully!";
            } else {
                $error_message = "Error updating staff: " . $conn->error;
            }
        }
    }

    $check_stmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link rel="stylesheet" href="../CSS/editStaff.css">
</head>
<body>

<header>
    <?php include('Header.php') ?>
</header>

<main>
<form action="edit_staff.php?id=<?php echo $staff['id']; ?>" method="POST" enctype="multipart/form-data">

    <h2>Edit Staff</h2>

    <?php if (isset($success_message)) : ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)) : ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <label for="full_name">Full Name</label>
    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($staff['full_name']); ?>" required>

    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($staff['username']); ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>

    <label for="phone">Phone</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($staff['phone']); ?>" required>

    <label for="address">Address</label>
    <textarea id="address" name="address" required><?php echo htmlspecialchars($staff['address']); ?></textarea>

    <label for="profile_photo">Profile Photo</label>
    <input type="file" id="profile_photo" name="profile_photo">
    <?php if ($staff['profile_photo']) : ?>
        <p>Current Photo: <img src="../Images/<?php echo $staff['profile_photo']; ?>" width="100" /></p>
    <?php endif; ?>

    <button type="submit">Update Staff</button>
</form>

<a href="StaffManagement.php" class="back-btn">Back</a>

</body>
</html>
