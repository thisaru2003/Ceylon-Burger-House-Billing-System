<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Ceylon Burger House</title>
        <link rel ="icon" type="image" href="../Images/Icon.png"> 
        <link rel="stylesheet" href="../CSS/ContentAdmin.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
    <body>
        <header>
<?php
session_start(); 

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        include('AdminHeader.php');
    } elseif ($_SESSION['role'] === 'staff') {
        include('StaffHeader.php');
    } else {
        include('Header.php');
    }
} else {
    include('Header.php'); 
}
?>
        </header> 
        <div class="Mtext"><b>Staff Panel</b></div>
        <div class="main">
            <div class="profile-card">
                <div class="img">
                    <img src="../Images/checkout.png" alt="">
                </div>
                <div class="caption">
                    <div class="info">
                        <a href="../HTML/StaffOders.php" class="butn">Oders</a>
                    </div>                
                </div>
            </div>

            <div class="profile-card">
                <div class="img">
                    <img src="../Images/reserved.png" alt="">
                </div>
                <div class="caption">
                    <div class="info">
                        <a href="../HTML/StaffReservations.php" class="butn">Table Reservations</a>
                    </div>                  
                </div>
            </div>

            <div class="profile-card">
                <div class="img">
                    <img src="../Images/roomreservation.jpg" alt="">
                </div>
                <div class="caption">
                    <div class="info">
                        <a href="../HTML/staffroom_reservation.php" class="butn">Room Reservations</a>
                    </div>  
                </div>
            </div>

        </div> 
        <footer>
        <?php include('footer.php') ?>
        </footer> 
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        </body>
</html>