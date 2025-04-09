<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$message = '';
$cart = $_SESSION['cart'] ?? [];
$total = 0;

foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_method'])) {
    $user_id = $_SESSION['user_id'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    if ($payment_method === "COD") {
        $query = "INSERT INTO orders (user_id, total_price, phone, address, payment_method, status, created_at)
                  VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("idsss", $user_id, $total, $phone, $address, $payment_method);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;
            $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, food_name, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cart as $item) {
                $item_stmt->bind_param("isid", $order_id, $item['name'], $item['quantity'], $item['price']);
                $item_stmt->execute();
            }
            $message = "Order placed successfully with Cash on Delivery!";
            $_SESSION['cart'] = [];
        } else {
            $message = "Error placing order: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Alpha Canteen</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        .container { max-width: 800px; margin: 100px auto; background: white; padding: 25px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #ff3838; color: white; }
        input, select, textarea { width: 100%; padding: 10px; margin-bottom: 10px; }
        .btn { background: black; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; }
        .success { color: green; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">
    <h2>Checkout</h2>
    <?php if ($message): ?>
        <p class="success"><?= $message ?></p>
    <?php elseif (count($cart) === 0): ?>
        <p>Your cart is empty. <a href="order.php">Go back to order</a></p>
    <?php else: ?>

        <table>
            <tr>
                <th>Image</th>
                <th>Food</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($cart as $item): ?>
            <tr>
                <td><img src="<?= $item['image'] ?>" width="60"></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>₹<?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="4" style="text-align: right">Total:</th>
                <th>₹<?= number_format($total, 2) ?></th>
            </tr>
        </table>

        <form id="checkout-form" method="POST">
            <input type="text" name="phone" placeholder="Phone Number" required>
            <textarea name="address" placeholder="Delivery Address" rows="3" required></textarea>

            <select name="payment_method" id="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="COD">Cash on Delivery</option>
                <option value="Online">Online Payment</option>
            </select>

            <button type="submit" class="btn">Place Order</button>
        </form>

        <script>
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            var paymentMethod = document.getElementById('payment_method').value;
            if (paymentMethod === 'Online') {
                e.preventDefault();
                var options = {
                    key: 'rzp_test_hx0iKfup5dpdhj',
                    amount: <?= $total * 100 ?>,
                    currency: 'INR',
                    name: 'Alpha Canteen',
                    description: 'Order Payment',
                    handler: function (response) {
                        // Redirect to success page with parameters
                        window.location.href = 'razorpay_success.php?razorpay_payment_id=' + response.razorpay_payment_id;
                    },
                    prefill: {
                        name: '<?= $_SESSION['name'] ?>',
                        contact: document.querySelector('[name="phone"]').value
                    }
                };
                var rzp = new Razorpay(options);
                rzp.open();
            }
        });
        </script>

    <?php endif; ?>
</div>

</body>
</html>
