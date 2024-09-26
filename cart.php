<?php

session_start();
include('./functions/common_function.php');

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCartSession($product_id, $quantity);
    header("Location: cart.php");
    exit();
}

// Remove from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeFromCartSession($product_id);
    header("Location: cart.php");
    exit();
}

// Update cart
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateCartSession($product_id, $quantity);
    header("Location: cart.php");
    exit();
}

$cart = getCartSession();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Shopping Cart</h2>
    <table class="table table-bordered mt-3">
        <thead>
        <tr>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cart as $product_id => $quantity): ?>
            <tr>
                <td><?php echo $product_id; ?></td>
                <td><?php echo $quantity; ?></td>
                <td>
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1" required>
                        <button type="submit" name="update_cart" class="btn btn-primary btn-sm">Update</button>
                    </form>
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <button type="submit" name="remove_from_cart" class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
</div>
</body>
</html>
