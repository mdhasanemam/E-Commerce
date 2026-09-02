<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once(__DIR__ . '/../db_connection.php');

// Fetch and display brands
function getbrand() {
    global $conn;
    $select_brands = "SELECT * FROM `brand`";
    $result_brands = mysqli_query($conn, $select_brands);
    if ($result_brands) {
        while ($row_data = mysqli_fetch_assoc($result_brands)) {
            $brand_title = $row_data['name'];
            $brand_id = $row_data['brand_id'];
            echo "<li class='nav-item'>
                    <a href='index.php?brand=$brand_id' class='nav-link text-light'>$brand_title</a>
                  </li>";
        }
    }
}

// Fetch and display categories
function getcategories() {
    global $conn;
    $select_categories = "SELECT * FROM `category`";
    $result_categories = mysqli_query($conn, $select_categories);
    if ($result_categories) {
        while ($row_data = mysqli_fetch_assoc($result_categories)) {
            $category_title = $row_data['name'];
            $category_id = $row_data['category_id'];
            echo "<li class='nav-item'>
                    <a href='index.php?category=$category_id' class='nav-link text-light'>$category_title</a>
                  </li>";
        }
    }
}

// Fetch and display all products
function get_all_products() {
    global $conn;
    $select_products = "SELECT * FROM `product` ORDER BY RAND()";
    $result_products = mysqli_query($conn, $select_products);
    if ($result_products) {
        while ($row = mysqli_fetch_assoc($result_products)) {
            $product_id = $row['product_id'];
            $product_name = $row['name'];
            $product_description = $row['description'];
            $product_image = $row['image'];
            $product_price = $row['price'];
            echo "<div class='col-md-4 mb-2'>
                    <div class='card'>
                      <img src='./staff/product_images/$product_image' class='card-img-top' alt='$product_name'>
                      <div class='card-body'>
                        <h5 class='card-title'>$product_name</h5>
                        <p class='card-text'>$product_description</p>
                        <p class='card-text'>Price: $$product_price</p>
                        <a href='index.php?product_id=$product_id' class='btn btn-info'>View Details</a>
                        <a href='add_to_cart.php?product_id=$product_id' class='btn btn-primary'>Add to Cart</a>
                      </div>
                    </div>
                  </div>";
        }
    }
}

// Search products by name or description
function search_product() {
    global $conn;
    if (isset($_GET['search_data']) && $_GET['search_data'] !== '') {
        $search_user_data = $_GET['search_data'];
        $search_param = "%" . $search_user_data . "%";
        $stmt = mysqli_prepare($conn, "SELECT * FROM `product` WHERE name LIKE ? OR description LIKE ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
            mysqli_stmt_execute($stmt);
            $result_query = mysqli_stmt_get_result($stmt);
            if ($result_query && mysqli_num_rows($result_query) > 0) {
                while ($row = mysqli_fetch_assoc($result_query)) {
                    $product_id = $row['product_id'];
                    $product_name = $row['name'];
                    $product_description = $row['description'];
                    $product_image = $row['image'];
                    $product_price = $row['price'];
                    echo "<div class='col-md-4 mb-2'>
                            <div class='card'>
                              <img src='./staff/product_images/$product_image' class='card-img-top' alt='$product_name'>
                              <div class='card-body'>
                                <h5 class='card-title'>$product_name</h5>
                                <p class='card-text'>$product_description</p>
                                <p class='card-text'>Price: $$product_price</p>
                                <a href='index.php?product_id=$product_id' class='btn btn-info'>View Details</a>
                                <a href='add_to_cart.php?product_id=$product_id' class='btn btn-primary'>Add to Cart</a>
                              </div>
                            </div>
                          </div>";
                }
            } else {
                echo "<h2 class='text-center text-danger'>No products found for '" . htmlspecialchars($search_user_data, ENT_QUOTES, 'UTF-8') . "'</h2>";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Cart session management functions
function addToCartSession($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function removeFromCartSession($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function updateCartSession($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

function getCartSession() {
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
}

function clearCart() {
    $_SESSION['cart'] = array();
}

// Auth check helper
function isLoggedIn() {
    return (isset($_SESSION['customer_id']) || (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true));
}

// Wishlist functions
function addToWishlist($product_id, $customer_id) {
    global $conn;
    $date = date('Y-m-d H:i:s');
    $stmt = mysqli_prepare($conn, "INSERT INTO `wishlist` (`customer_id`, `product_id`, `date`) VALUES (?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iis", $customer_id, $product_id, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function removeFromWishlist($wishlist_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM `wishlist` WHERE `wishlist_id` = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $wishlist_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function getWishlistItems($customer_id) {
    global $conn;
    $items = array();
    $stmt = mysqli_prepare($conn, "SELECT * FROM `wishlist` WHERE `customer_id` = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $customer_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
    return $items;
}

// Product helper functions
function getProductPrice($product_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT `price` FROM `product` WHERE `product_id` = ?");
    $price = 0;
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $price);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
    return $price;
}

// Order & Payment functions
function createOrder($customer_id, $store_id, $delivery_man_id, $total_amount, $product_id = null) {
    global $conn;
    $order_date = date('Y-m-d H:i:s');
    $stmt = mysqli_prepare($conn, "INSERT INTO `order` (`customer_id`, `store_id`, `delivery_man_id`, `order_date`, `total_amount`, `product_id`, `quantity`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $quantity = 1;
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiisdii", $customer_id, $store_id, $delivery_man_id, $order_date, $total_amount, $product_id, $quantity);
        mysqli_stmt_execute($stmt);
        $order_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        return $order_id;
    }
    return 0;
}

function addOrderItem($order_id, $product_id, $quantity) {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE `order` SET `product_id` = ?, `quantity` = ? WHERE `order_id` = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iii", $product_id, $quantity, $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

function recordPayment($order_id, $payment_amount) {
    global $conn;
    $payment_date = date('Y-m-d H:i:s');
    $stmt = mysqli_prepare($conn, "INSERT INTO `payment` (`order_id`, `payment_amount`, `payment_date`) VALUES (?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ids", $order_id, $payment_amount, $payment_date);
        mysqli_stmt_execute($stmt);
        $payment_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        return $payment_id;
    }
    return 0;
}

function addInstallment($payment_id, $installment_number, $amount) {
    global $conn;
    $payment_date = date('Y-m-d H:i:s');
    $stmt = mysqli_prepare($conn, "INSERT INTO `installment` (`payment_id`, `installment_number`, `amount`, `payment_date`) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iids", $payment_id, $installment_number, $amount, $payment_date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
