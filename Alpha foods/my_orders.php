<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];

// Get all orders for the user
$orders_query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders - Alpha Canteen</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .order {
            margin-bottom: 30px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 8px;
        }
        .order h3 {
            margin: 0;
            color: #ff3838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #f44336;
            color: white;
        }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">
    <h2>My Orders</h2>

    <?php if ($orders_result->num_rows > 0): ?>
        <?php while ($order = $orders_result->fetch_assoc()): ?>
            <div class="order">
                <h3>Order ID: #<?= $order['id'] ?> | ₹<?= number_format($order['total_price'], 2) ?> | <?= $order['payment_method'] ?> | <?= $order['status'] ?></h3>
                <p><strong>Phone:</strong> <?= $order['phone'] ?> | <strong>Address:</strong> <?= $order['address'] ?></p>
                <p><strong>Date:</strong> <?= date("d-m-Y H:i", strtotime($order['created_at'])) ?></p>

                <?php
                $order_id = $order['id'];
                $items_query = "SELECT * FROM order_items WHERE order_id = ?";
                $item_stmt = $conn->prepare($items_query);
                $item_stmt->bind_param("i", $order_id);
                $item_stmt->execute();
                $items_result = $item_stmt->get_result();
                ?>

                <table>
                    <tr>
                        <th>Food</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['food_name']) ?></td>
                        <td>₹<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center">No orders yet. <a href="order.php">Start ordering!</a></p>
    <?php endif; ?>
</div>

</body>
</html>