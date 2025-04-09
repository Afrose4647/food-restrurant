<?php
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>😋Team website!-Apollo Team </title>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="style.css">

</head>
<header>

<a href="#" class="logo"><i class="fas fa-utensils"></i>Alpha  food</a>

<div id="menu-bar" class="fas fa-bars"></div>

<nav class="navbar">
    <a href="index.php">home</a>
    <a href="order.php">Products</a>
    <a href="cart.php">Cart</a>
    <a href="my_orders.php">My Orders</a><div class="nav-right">
    <div>
            <?php if (isset($_SESSION['name'])): ?>
                <a href="logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn-logout">Login</a>
            <?php endif; ?>
    </div>
</nav>
</header>
<script src="script.js"></script>
