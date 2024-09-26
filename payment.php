<?php
session_start();
include('./functions/common_function.php');
include('./db_connection.php'); // Include your database connection file

if (!isLoggedIn()) {
    header("Location: ../customer_login.php");
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php"); // Redirect to cart page if cart is empty
    exit();
}

$cart = $_SESSION['cart'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create order
    $customer_id = $_SESSION['customer_id'];
    $store_id = 1; // Example store ID
    $delivery_man_id = 1; // Example delivery man ID
    $total_amount = calculateTotalAmount($cart); // Function to calculate total amount based on the cart
    $product_id = key($cart); // Assuming $cart is an associative array with product_id as keys

    $order_id = createOrder($customer_id, $store_id, $delivery_man_id, $total_amount, $product_id);

    // Add items to the order
    foreach ($cart as $product_id => $quantity) {
        addOrderItem($order_id, $product_id, $quantity);
    }

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

// Function to calculate total amount based on the cart
function calculateTotalAmount($cart) {
    global $conn;
    $total_amount = 0;
    foreach ($cart as $product_id => $quantity) {
        $product_price = getProductPrice($product_id);
        $total_amount += $product_price * $quantity;
    }
    return $total_amount;
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
    <form action="payment.php" method="post">
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
        <h4 class="mt-4">Order Total: $<?php echo number_format(calculateTotalAmount($cart), 2); ?></h4>
        <button type="submit" class="btn btn-primary mt-3">Make Payment</button>
    </form>
</div>
</body>
</html>
