<?php
session_start();
include 'db.php';

if (!isset($_GET['razorpay_payment_id']) || !isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    echo "Invalid access.";
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$total = 0;

foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// These should ideally be stored in session when the checkout page is filled
$phone = $_SESSION['checkout_phone'] ?? '';
$address = $_SESSION['checkout_address'] ?? '';
$payment_method = 'Online';
$status = 'Completed';

// Save order
$query = "INSERT INTO orders (user_id, total_price, phone, address, payment_method, status, created_at) 
          VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("idssss", $user_id, $total, $phone, $address, $payment_method, $status);

if ($stmt->execute()) {
    $order_id = $stmt->insert_id;
    $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, food_name, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $item_stmt->bind_param("isid", $order_id, $item['name'], $item['quantity'], $item['price']);
        $item_stmt->execute();
    }
    $_SESSION['cart'] = [];
    echo "<h2 style='color:green;text-align:center;'>Thank you! Your payment was successful and order has been placed.</h2>";
    echo "<p style='text-align:center;'><a href='my_orders.php'>View My Orders</a></p>";
} else {
    echo "<p style='color:red;text-align:center;'>Something went wrong while placing your order. Please contact support.</p>";
}
?>