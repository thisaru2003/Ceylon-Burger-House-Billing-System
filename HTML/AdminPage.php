<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>
    <link rel ="icon" type="image" href="../Images/Icon.png"> 
    <link rel="stylesheet" href="../CSS/HomePage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400" rel="stylesheet">
</head>
<body>
<?php
session_start(); // Start session

if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'admin') {
        include('AdminHeader.php');
    } elseif ($_SESSION['user_type'] === 'staff') {
        include('StaffHeader.php');
    } else {
        include('Header.php'); // default
    }
} else {
    include('Header.php'); // not logged in
}
?>

    <div class="Mtext"><b>Ceylon Burger House</b></div>
    <div class="Mbutton">
    <a href="../HTML/Gallery.php" class="button"><b>Gallery</b></a>
    </div>
    <div class="Stext1"><b><span3>#JuicyBurgers,HappyHearts #CeylonBurgerHouse</span3></b></div>
    <div class="container">      
    <div class="slider">
        <div class="slides">
            <input type="radio" name="radio-button" id="radio1">
            <input type="radio" name="radio-button" id="radio2">
            <input type="radio" name="radio-button" id="radio3">
            <input type="radio" name="radio-button" id="radio4">

            <div class="slide first">
                <img src="../Images/img 1.jpg" alt="">
            </div>
            <div class="slide">
                <img src="../Images/img 2.jpg" alt="">
            </div>
            <div class="slide">
                <img src="../Images/img 3.jpg" alt="">
            </div>
            <div class="slide">
                <img src="../Images/img 4.jpg" alt="">
            </div>
            <!--slide images end-->
            <!--automatic navigation start-->
            <div class="navigation-auto">
              <div class="auto-button1"></div>
              <div class="auto-button2"></div>
              <div class="auto-button3"></div>
              <div class="auto-button4"></div>
            </div>
            <!--automatic navigation end-->
        </div>
        <!--manual navigation start-->
        <div class="navigation-manual">
            <label for="radio1" class="manual-button"></label>
            <label for="radio2" class="manual-button"></label>
            <label for="radio3" class="manual-button"></label>
            <label for="radio4" class="manual-button"></label>
        </div>
        <!--manual navigation end-->
    
     <!--image slider end-->
      </div>
     </div>
    </div>
    <div class="para">Located along the scenic Matara Beach Road, Ceylon Burger House is your ultimate destination for delicious and freshly made fast food. Owned and operated by Sohan Dineth, we take pride in serving a variety of mouth-watering favorites that are sure to satisfy your cravings.<br><br>

From our juicy burgers and hearty hot dogs to flavorful shawarmas and submarines, we offer a wide range of options, each prepared with high-quality ingredients and bursting with taste.<br><br>

Whether you’re a local or just passing through, drop by and experience the perfect blend of flavors and friendly service at Ceylon Burger House!<br><br></div>
       
    <?php include('footer.php') ?>
    <script type="text/javascript">
        var counter =1;
        setInterval(function() {
            document.getElementById('radio'+counter).checked = true;
            counter++;
            if(counter >4){
                counter=1;
            }
        },5000);

    </script>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>