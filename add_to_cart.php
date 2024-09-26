<?php

session_start();
include('./functions/common_function.php');

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $quantity = 1; // Default quantity, you can modify this if needed

    addToCartSession($product_id, $quantity);
    header("Location: cart.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}

?>
