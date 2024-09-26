<?php
include('db_connection.php');
include('functions/common_function.php');
//session_start();
// Check if the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    // If not logged in, redirect to the login page or show an error message
    header("Location: customer_login.php");
    exit(); // Stop further script execution
}

$customer_id = $_SESSION['customer_id'];

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'add') {
    $product_id = intval($_GET['product_id']);
    addToWishlist($product_id, $customer_id);
    header("Location: wishlist.php?message=Product added to wishlist");
    exit();
} elseif ($action == 'remove') {
    $wishlist_id = intval($_GET['wishlist_id']);
    removeFromWishlist($wishlist_id);
    header("Location: wishlist.php?message=Product removed from wishlist");
    exit();
}

// Function to display wishlist items
$wishlist_items = getWishlistItems($customer_id);
foreach ($wishlist_items as $item) {
    // Display each item
    echo "Product ID: " . $item['product_id'] . "<br>";
    // More code to display the item
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- ... -->
</head>
<body>
  <!-- Navbar ... -->

  <div class="container mt-5">
    <h3>Your Wishlist</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($wishlist_items as $item) : ?>
          <tr>
            <td><?php echo $item['product_id']; ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="wishlist_id" value="<?php echo $item['wishlist_id']; ?>">
                <button type="submit" name="remove" class="btn btn-danger">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
