<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>about us</h3>
    <p> <a href="home.php">home</a> / about </p>
</section>

<section class="about">

    <div class="flex">

        <div class="image">
            <img src="images/about-image1.jpg" alt="">
        </div>

        <div class="content">
            <h3>why choose us?</h3>
            <p>
            We are committed to providing exceptional products and services that exceed your expectations. With a focus on quality and customer satisfaction, we ensure a seamless shopping experience every time.
            </p>
            <a href="shop.php" class="btn">shop now</a>
        </div>

    </div>

    <div class="flex">

        <div class="content">
            <h3>what we provide?</h3>
            <p>Our online store offers a wide range of products carefully curated to meet your needs. From trendy fashion pieces to essential everyday items, we provide high-quality products at competitive prices.</p>
            <a href="contact.php" class="btn">contact us</a>
        </div>

        <div class="image">
            <img src="images/about-image2.jpg" alt="">
        </div>

    </div>

    <div class="flex">

        <div class="image">
            <img src="images/about-image3.jpg" alt="">
        </div>

        <div class="content">
            <h3>who we are?</h3>
            <p>We are a team of dedicated individuals who are passionate about what we do. Our goal is to make online shopping convenient, enjoyable, and reliable for all our customers.</p>
        </div>

    </div>

</section>













<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>