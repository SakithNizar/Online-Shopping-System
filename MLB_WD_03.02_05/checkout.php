<?php
    @include 'config.php';

    session_start();

    $user_id = $_SESSION['user_id'];

    if(!isset($user_id)){
        header('location:login.php');
    }

    $message = [];

    if(isset($_POST['order'])){

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $method = mysqli_real_escape_string($conn, $_POST['method']);
        $flat = mysqli_real_escape_string($conn, $_POST['flat']);
        $street = mysqli_real_escape_string($conn, $_POST['street']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $state = mysqli_real_escape_string($conn, $_POST['state']);
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $pin_code = mysqli_real_escape_string($conn, $_POST['pin_code']);
        $address = 'flat no. '. $flat .', '. $street .', '. $city .', '. $state .', '. $country .' - '. $pin_code;
        $placed_on = date('d-M-Y');

        $cart_total = 0;
        $cart_products = [];

        $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($cart_query) > 0){
            while($cart_item = mysqli_fetch_assoc($cart_query)){
                $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
                $sub_total = ($cart_item['price'] * $cart_item['quantity']);
                $cart_total += $sub_total;
            }
        }

        $total_products = implode(', ',$cart_products);

        $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

        if($cart_total == 0){
            $message[] = 'Your cart is empty!';
        }elseif(mysqli_num_rows($order_query) > 0){
            $message[] = 'Order placed already!';
        }else{
            mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            $message[] = 'Order placed successfully!';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Checkout Order</h3>
    <p> <a href="home.php">Home</a> / Checkout </p>
</section>

<section class="display-order">
    <?php
    $grand_total = 0;
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if(mysqli_num_rows($select_cart) > 0){
        while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
            ?>
            <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '.$fetch_cart['quantity']  ?>)</span> </p>
            <?php
        }
    }else{
        echo '<p class="empty">Your cart is empty</p>';
    }
    ?>
    <div class="grand-total">Grand Total: <span>LKR.<?php echo $grand_total; ?>/-</span></div>
</section>

<section class="checkout">

    <form action="" method="POST" onsubmit="return validateForm()">

        <h3>Place Your Order</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Your Name :</span>
                <input type="text" id="name" name="name" placeholder="Enter your name">
            </div>
            <div class="inputBox">
                <span>Your Number :</span>
                <input type="text" id="number" name="number" placeholder="Enter your number">
            </div>
            <div class="inputBox">
                <span>Your Email :</span>
                <input type="email" id="email" name="email" placeholder="Enter your email">
            </div>
            <div class="inputBox">
                <span>Payment Method :</span>
                <select id="method" name="method">
                    <option value="cash on delivery">Cash on Delivery</option>
                    <option value="credit card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Address Line 01 :</span>
                <input type="text" id="flat" name="flat" placeholder="e.g. Flat No.">
            </div>
            <div class="inputBox">
                <span>Address Line 02 :</span>
                <input type="text" id="street" name="street" placeholder="e.g. Street Name">
            </div>
            <div class="inputBox">
                <span>City :</span>
                <input type="text" id="city" name="city" placeholder="e.g. Colombo">
            </div>
            <div class="inputBox">
                <span>Province :</span>
                <input type="text" id="state" name="state" placeholder="e.g.Western Province">
            </div>
            <div class="inputBox">
                <span>Country :</span>
                <input type="text" id="country" name="country" placeholder="e.g. Sri Laka">
            </div>
            <div class="inputBox">
                <span>Pin Code :</span>
                <input type="text" id="pin_code" name="pin_code" placeholder="e.g. 123456">
            </div>
        </div>

        <input type="submit" name="order" value="Order Now" class="btn">

    </form>

</section>

<?php @include 'footer.php'; ?>

<script>
    function validateForm() {
        let name = document.getElementById("name").value;
        let number = document.getElementById("number").value;
        let email = document.getElementById("email").value;
        let flat = document.getElementById("flat").value;
        let street = document.getElementById("street").value;
        let city = document.getElementById("city").value;
        let state = document.getElementById("state").value;
        let country = document.getElementById("country").value;
        let pin_code = document.getElementById("pin_code").value;

        if (name === "" || number === "" || email === "" || flat === "" || street === "" || city === "" || state === "" || country === "" || pin_code === "") {
            alert("All fields are required!");
            return false;
        }

        if (!/^[0-9]+$/.test(number)) {
            alert("Number must be a valid number!");
            return false;
        }

        if (!/^\d{6}$/.test(pin_code)) {
            alert("Pin code must be a valid 6-digit number!");
            return false;
        }

        return true;
    }
</script>

</body>
</html>
