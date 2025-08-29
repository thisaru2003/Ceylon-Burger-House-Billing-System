<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<header>
        <div class="brand">
            <span2><b><img src="../Images/logo.jpg" alt="Logo"></b></span2><span1><b>Ceylon Burger House</b></span1>
        </div>
        <div class="navigation">
            <div class="navigation-items">
                <a href="../HTML/index.php">Home</a>
                <a href="../HTML/Menu.php">Menu</a>
            </div>
        </div>
        <div class="user-info">
            <?php
            if (isset($_SESSION['username'])) {
                echo '<a href="login.php"><span>Welcome, ' . htmlspecialchars($_SESSION['username']) . '!</span></a>';
                echo '<a href="logout.php">Logout</a>';
            } else {
                echo '<a href="../HTML/login.php">Login</a>';
            }
            ?>
        </div>
    </header> 
</body>
</html>