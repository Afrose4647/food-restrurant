<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $food_name = $_POST['food_name'];
    $quantity = $_POST['quantity'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $query = "INSERT INTO orders (user_id, food_name, quantity, phone, address, created_at) 
              VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isiss", $user_id, $food_name, $quantity, $phone, $address);

    if ($stmt->execute()) {
        $message = "Order placed successfully!";
    } else {
        $message = "Error placing order.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alpha Canteen - Order Food</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: your style file -->
    <style>
        .food-box { border: 1px solid #ccc; padding: 20px; margin: 15px; display: inline-block; }
        .order-form { margin-top: 10px; }
        .success { color: green; }
    </style>
</head> 
<?php include 'includes\navbar.php'; ?>
<body>
<h2>Welcome, <?= $_SESSION['name']; ?> | <a href="logout.php">Logout</a></h2>
    <h1>Order Food</h1>
    <?php if ($message): ?>
        <p class="success"><?= $message ?></p>
    <?php endif; ?>

    <    <section class="gallery" id="gallery">

<h1 class="heading"> Order <span>Food</span> </h1>

<div class="box-container">

    <div class="box">
        <img src="images/g-1.jpg" alt="">
        <div class="content">
            <h3>Chicken Burger</h3>
            <p>Indian chicken tasty Burger .</p>
            <form action="cart.php?action=add" method="POST">
                <input type="hidden" name="food_name" value="Chicken Burger">
                <input type="hidden" name="image" value="images/g-1.jpg">
                <input type="hidden" name="price" value="99">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>
     </div>
    </div>
    <div class="box">
        <img src="images/g-2.jpg" alt="">
        <div class="content">
            <h3>Veg sanwich</h3>
            <p>Cheesy Veg Sandwich Recipe! Easy and tasty Sandwich.</p>
            <form action="cart.php?action=add" method="POST">
                <input type="hidden" name="food_name" value="Veg sanwich">
                <input type="hidden" name="image" value="images/g-2.jpg">
                <input type="hidden" name="price" value="79">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>
        </div>class
    </div>
    <div class="box">
        <img src="images/g-3.jpg" alt="">
        <div class="content">
            <h3>Chicken Roll</h3>
            <p>Chicken Wrap Spring Roll! .</p>
            <form action="cart.php?action=add" method="POST">
                <input type="hidden" name="food_name" value="Chicken Roll">
                <input type="hidden" name="image" value="images/g-3.jpg">
                <input type="hidden" name="price" value="49">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>        </div>
    </div>
    <div class="box">
        <img src="images/g-4.jpg" alt="">
        <div class="content">
            <h3>Chocolate Brownie</h3>
            <p>Home Made Style Chocolate brownie.</p>
            <form action="cart.php?action=add" method="POST">
                <input type="hidden" name="food_name" value="Chocolate Brownie">
                <input type="hidden" name="image" value="images/g-4.jpg">
                <input type="hidden" name="price" value="99">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>         </div>
    </div>
    <div class="box">
        <img src="images/g-5.jpg" alt="">
        <div class="content">
            <h3>Sweety Sweets</h3>
            <p>India's No.1 Sweets .</p>
            <form action="cart.php?action=add" method="POST">
                <input type="hidden" name="food_name" value="Sweety Sweets">
                <input type="hidden" name="image" value="images/g-5.jpg">
                <input type="hidden" name="price" value="199">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>         </div>
    </div>
    <div class="box">
        <img src="images/g-6.jpg" alt="">
        <div class="content">
            <h3>Tasty Crispy Chicken</h3>
            <p>KFC Style Home Made Chicken.</p>
            <form action="cart.php?action=add" method="POST">
                <input type="hidden" name="food_name" value="Tasty Crispy Chicken">
                <input type="hidden" name="image" value="images/g-6.jpg">
                <input type="hidden" name="price" value="99">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>         </div>
    </div>
    <div class="box">
        <img src="images/g-7.jpg" alt="">
        <div class="content">
            <h3>Bread Omelette</h3>
            <p>Weat Bread and Porating Vegetables! Bread Omelette.</p>
            <form action="cart.php?action=add" method="POST">
                <input type="hidden" name="food_name" value="Bread Omelette">
                <input type="hidden" name="image" value="images/g-7.jpg">
                <input type="hidden" name="price" value="49">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>         </div>
    </div>
    <div class="box">
        <img src="images/g-8.jpg" alt="">
        <div class="content">
            <h3>Dark Chocolate</h3>
            <p>Dark Chocolate and Milk Contain.</p>
            <form action="cart.php?action=add" method="POST">
                <input type="hidden" name="food_name" value="Chicken Roll">
                <input type="hidden" name="image" value="images/g-8.jpg">
                <input type="hidden" name="price" value="99">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>         </div>
    </div>
    <div class="box">
        <img src="images/g-9.jpg" alt="">
        <div class="content">
            <h3>Blueberry Banana Bread</h3>
            <p>Buleberry Banana Bread Topped With Oats.</p>
            <form action="cart.php?action=add" method="POST">
                <input type="hidden" name="food_name" value="Blueberry Banana Bread">
                <input type="hidden" name="image" value="images/g-9.jpg">
                <input type="hidden" name="price" value="49">
                Quantity: <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="btn">Add to Cart</button>
            </form>         </div>
    </div>

</div>

</section>

        <!-- Add more food items as needed... -->
    </div>
    <?php include 'includes\footer.php'; ?>

</body>
</html>
