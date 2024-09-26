<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php"); // Redirect to cart page if cart is empty
    exit();
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Checkout</h2>
    <form action="payment.php" method="post">
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
