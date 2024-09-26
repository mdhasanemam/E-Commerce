<?php
session_start();
include('./functions/common_function.php');
include('./db_connection.php'); // Include your database connection file

if (!isLoggedIn()) {
    header("Location: ../coustomer_loging.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    header("Location: cart.php");
    exit();
}

$order_id = $_GET['order_id'];

// Retrieve order details
$query = "SELECT total_amount FROM `order` WHERE order_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $total_amount);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Record payment
    $payment_id = recordPayment($order_id, $total_amount);

    // Optional: Handle installments
    $installment_number = 1; // Example installment number
    $installment_amount = $total_amount / 3; // Example installment amount, modify as needed
    addInstallment($payment_id, $installment_number, $installment_amount);

    // Clear the cart after successful payment
    clearCart();

    // Redirect to a thank you page or order summary
    header("Location: thank_you.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Payment</h2>
    <form action="payment.php?order_id=<?php echo $order_id; ?>" method="post">
        <div class="mb-3">
            <label for="card_number" class="form-label">Card Number</label>
            <input type="text" class="form-control" id="card_number" name="card_number" required>
        </div>
        <div class="mb-3">
            <label for="card_expiry" class="form-label">Expiry Date</label>
            <input type="text" class="form-control" id="card_expiry" name="card_expiry" required>
        </div>
        <div class="mb-3">
            <label for="card_cvv" class="form-label">CVV</label>
            <input type="text" class="form-control" id="card_cvv" name="card_cvv" required>
        </div>
        <h4 class="mt-4">Order Total: $<?php echo number_format($total_amount, 2); ?></h4>
        <button type="submit" class="btn btn-primary mt-3">Make Payment</button>
    </form>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Process</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Checkout</h2>
    <form action="order_process.php" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="phone" name="phone" required>
        </div>
        <h4 class="mt-4">Order Summary</h4>
        <ul class="list-group">
            <?php foreach ($cart as $product_id => $quantity): ?>
                <li class="list-group-item">
                    Product ID: <?php echo $product_id; ?>, Quantity: <?php echo $quantity; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <button type="submit" class="btn btn-primary mt-3">Proceed to Payment</button>
    </form>
</div>
</body>
</html>
