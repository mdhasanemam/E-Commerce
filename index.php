<?php
session_start();
include('db_connection.php');
include('functions/common_function.php');

// Check if the user is logging out
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
  session_unset();
  session_destroy();
  header("Location: ../index.php");
  exit();
}

// Handle user login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['user_email'];
  $password = $_POST['user_password'];

  // Check if email exists
  $check_email_query = "SELECT * FROM customer WHERE email = ?";
  $stmt = mysqli_prepare($conn, $check_email_query);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $hashed_password = $row['password'];

      // Verify the password
      if (password_verify($password, $hashed_password)) {
          // Set session variables
          $_SESSION['customer_id'] = $row['customer_id'];
          $_SESSION['user_email'] = $row['email'];
          $_SESSION['username'] = $row['name'];
          $_SESSION['image'] = $row['image']; // Store the user image
          $_SESSION['loggedin'] = true; // Set loggedin to true
          header('Location: ../index.php'); // Redirect to home page
          exit(); // Make sure to exit after redirect
      } else {
          echo "<script>alert('Incorrect password!');</script>";
      }
  } else {
      echo "<script>alert('Email does not exist!');</script>";
  }
  mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ecommerce</title>
  <!-- Bootstrap CSS link -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!-- Font Awesome link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Custom CSS link -->
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: linear-gradient(to right, #f8f9fa, #e2e2e2);
      color: #343a40;
      font-family: 'Arial', sans-serif;
    }

    .navbar {
      background-color: #343a40 !important;
      padding: 15px;
    }

    .navbar-brand {
      color: #fff;
      font-weight: bold;
    }

    .navbar-toggler {
      border-color: #fff;
    }

    .navbar-toggler-icon {
      background-color: #fff;
    }

    .navbar-nav .nav-link {
      color: #fff !important;
      padding: 10px 15px;
    }

    .navbar-nav .nav-link:hover {
      background-color: #495057;
      border-radius: 5px;
    }

    .card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.2);
      transition: transform 0.2s;
    }

    .card:hover {
      transform: scale(1.05);
    }

    .card-title,
    .card-text {
      color: #343a40;
    }

    .btn-info {
      background-color: #17a2b8 !important;
      border-color: #17a2b8 !important;
    }

    .btn-info:hover {
      background-color: #138496 !important;
      border-color: #138496 !important;
    }

    .btn-primary {
      background-color: #007bff !important;
      border-color: #007bff !important;
    }

    .btn-primary:hover {
      background-color: #0056b3 !important;
      border-color: #0056b3 !important;
    }

    .bg-light {
      background-color: #f8f9fa !important;
      padding: 20px;
    }

    .bg-secondary {
      background-color: #6c757d !important;
      color: #fff;
      border-radius: 10px;
      padding: 15px;
    }

    footer {
      background-color: #343a40;
      color: #fff;
      padding: 20px;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <i class="fa-brands fa-shopify"></i> Ecommerce
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="display_all.php">Products</a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="./customer/customer_reg.php">Register</a>
          </li>
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
            <!-- If logged in, show profile and logout links -->
            <li class="nav-item">
              <a class="nav-link" href="order_process.php">Order</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="wishlist.php"><i class="fa-solid fa-heart"></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i><sup></sup></a>
            </li>
            <li class="nav-item d-flex align-items-center">
              <img src="../ecommerce/image/ echo $_SESSION['image']; ?>" alt="Profile Image" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
              <span class="navbar-text text-white"><?php echo $_SESSION['username']; ?></span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./customer/customer_login.php?action=logout">Logout</a>
            </li>
          <?php else: ?>
            <!-- If not logged in, show login link -->
            <li class="nav-item">
              <a class="nav-link" href="./customer/customer_login.php">Login</a>
            </li>
          <?php endif; ?>
        </ul>
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="../ecommerce/staff/staff_login.php">Manager Login</a>
          </li>
        </ul> 
        <form class="d-flex" action="search_product.php" method="get">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_data">
          <input type="submit" value="Search" class="btn btn-outline-light" name="search_data_product">
        </form>
      </div>
    </div>
  </nav>

  <!-- 3rd child -->
  <div class="bg-light">
    <h3 class="text-center">Store</h3>
    <p class="text-center">Buy More</p>
  </div>

  <!-- Side navigation bar -->
  <div class="row">
    <div class="col-md-2 bg-secondary">
      <ul class="nav flex-column">
        <li class="nav-item bg-info">
          <a href="#" class="nav-link text-light">
            <h4>Brand</h4>
          </a>
        </li>
        <?php getbrand(); ?>
        <li class="nav-item bg-info">
          <a href="#" class="nav-link text-light">
            <h4>Categories</h4>
          </a>
        </li>
        <?php getcategories(); ?>
      </ul>
    </div>

    <!-- Products display area -->
    <div class="col-md-10">
      <div class="row px-3">
        <?php
        // Display products based on brand or category
        if (isset($_GET['brand'])) {
          $brand_id = $_GET['brand'];
          $query = "SELECT * FROM product WHERE brand_id = $brand_id";
        } elseif (isset($_GET['category'])) {
          $category_id = $_GET['category'];
          $query = "SELECT * FROM product WHERE category_id = $category_id";
        } else {
          // Display random product if not searching
          $query = "SELECT * FROM product ORDER BY RAND() LIMIT 6";
        }

        $result = mysqli_query($conn, $query);
        if (!$result) {
          die("Query failed: " . mysqli_error($conn));
        }
        while ($row = mysqli_fetch_assoc($result)) {
          $product_id = $row['product_id'];
          $product_name = $row['name'];
          $product_description = $row['description'];
          $product_image = $row['image'];
          $product_price = $row['price'];
          echo "
            <div class='col-md-4 mb-2'>
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
        ?>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>All Rights Reserved © 2024 by Md.Hasan Emam & Midhat Ratib</p>
  </footer>
</body>
</html>
