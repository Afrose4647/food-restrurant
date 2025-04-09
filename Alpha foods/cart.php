<?php
session_start();

// Initialize cart session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart logic
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_POST['food_name'])) {
    $food = [
        'name' => $_POST['food_name'],
        'image' => $_POST['image'],
        'price' => floatval($_POST['price']),
        'quantity' => intval($_POST['quantity'])
    ];
    $_SESSION['cart'][] = $food;
    header("Location: cart.php");
    exit();
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $index = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header("Location: cart.php");
    exit();
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart - Alpha Canteen</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-container {
            max-width: 800px;
            margin: 100px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #ff3838;
            color: white;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
        }
        .btn {
            padding: 10px 20px;
            background-color: black;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
       
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="cart-container">
    <h2>My Cart</h2>

    <?php if (count($_SESSION['cart']) > 0): ?>
    <table>
        <tr>
            <th>Image</th>
            <th>Food</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
        <tr>
            <td><img src="<?= $item['image'] ?>" width="80"></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>₹<?= number_format($item['price'], 2) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            <td><a href="cart.php?remove=<?= $index ?>" class="btn">Remove</a></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="total">Total: ₹<?= number_format($total, 2) ?></div>
    <br>
    <a href="checkout.php" class="btn">Proceed to Checkout</a>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>
