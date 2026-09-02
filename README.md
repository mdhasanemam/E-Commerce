# E-Commerce Web Application

A full-featured web-based E-Commerce application developed for **CSE 311: Database Systems**. This platform enables customers to browse products, filter by category/brand, search items, manage shopping carts and wishlists, place orders, and process payments. It also includes store management capabilities for staff members.

---

## Table of Contents
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Database Architecture](#database-architecture)
- [Project Structure](#project-structure)
- [Installation & Setup](#installation--setup)
- [Usage Guide](#usage-guide)
- [Authors & License](#authors--license)

---

## Features

### User & Customer Capabilities
- **Product Catalog & Discovery**: Browse featured and all products with responsive card layouts.
- **Categorization & Filtering**: Filter items dynamically by Brand or Category.
- **Search System**: Search products using parameterized SQL queries.
- **Shopping Cart**: Add, update quantities, or remove products seamlessly stored in session memory.
- **Wishlist**: Save favorite items to a personalized wishlist (database persistent).
- **Checkout & Order Processing**: Complete order summaries and securely process multi-step payments with installment support.
- **User Authentication**: Register and log in securely with password hashing (`password_hash` & `password_verify`).

### Administrative & Management Features
- **Role-Based Access**: Specialized manager/staff login workflow.
- **Inventory & Store Tracking**: Relational management of brands, categories, store locations, and inventory counts.

---

## Tech Stack

- **Frontend**: HTML5, CSS3, Bootstrap 5, Font Awesome 6
- **Backend**: PHP 8 (Procedural & Object-Oriented patterns)
- **Database**: MySQL / MariaDB
- **Server**: Apache / Nginx / PHP Built-in CLI Server

---

## Database Architecture

The application relies on a relational database (`E-Commerce.sql`) structured with primary keys, auto-increments, and cascading foreign keys across the following tables:

| Table | Description |
| :--- | :--- |
| `customer` | Stores customer credentials, type, contact information, and profiles |
| `staff` | Manager and delivery staff authentication details |
| `product` | Product details including price, available stock, image, category, and brand links |
| `category` | Product category taxonomy |
| `brand` | Product brand classification |
| `inventory` | Stock management per product |
| `cart` | Persistent shopping cart table |
| `wishlist` | Customer saved wishlist items |
| `order` | Order records linking customer, store, product, and delivery personnel |
| `payment` | Payment transactions associated with orders |
| `installment` | Payment schedule records for installment options |
| `store` & `delivery_man` | Store location and order delivery tracking |

---

## Project Structure

```
├── E-Commerce.sql         # SQL Database export schema and sample data
├── README.md              # Project documentation
├── add_to_cart.php        # Controller to handle adding products to cart
├── cart.php               # Shopping cart view and management page
├── checkout.php           # Order checkout interface
├── db_connection.php      # MySQL database connection configuration
├── display_all.php        # Full product catalog view
├── functions/
│   └── common_function.php# Global application logic, DB helpers, & query functions
├── index.php              # Application home landing page
├── order_process.php      # Order payment processing workflow
├── payment.php            # Transaction & installment handler
├── search_product.php     # Search results display page
├── style.css              # Custom styling definitions
├── thank_you.php          # Order completion confirmation page
└── wishlist.php           # Customer wishlist view & manager
```

---

## Installation & Setup

### Prerequisites
- PHP >= 8.0 with `mysqli` extension enabled
- MySQL / MariaDB Server (e.g. via XAMPP, WAMP, or standalone MySQL service)

### Step-by-step Setup
1. **Clone the Repository**:
   ```bash
   git clone <repository-url>
   cd E-Commerce
   ```

2. **Configure Database**:
   - Start your MySQL server.
   - Create a database named `ecommerce`:
     ```sql
     CREATE DATABASE ecommerce;
     ```
   - Import `E-Commerce.sql` into the database:
     ```bash
     mysql -u root -p ecommerce < E-Commerce.sql
     ```

3. **Configure Database Connection**:
   - Inspect `db_connection.php` and update database credentials (`$servername`, `$username`, `$password`, `$dbname`) if needed:
     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "ecommerce";
     ```

4. **Run the Application**:
   - Using PHP built-in web server:
     ```bash
     php -S localhost:8000
     ```
   - Access the application in your browser at `http://localhost:8000`.

---

## Usage Guide

1. **Browsing**: Visit `index.php` or `display_all.php` to view available products.
2. **Filtering**: Use the left sidebar to filter items by category or brand.
3. **Cart Management**: Click **Add to Cart** on any item and navigate to `cart.php` to adjust quantities or proceed to checkout.
4. **Checkout**: Provide shipping information and proceed to complete payment on `order_process.php`.

---

## Authors & License

- **Md. Hasan Emam** & **Midhat Ratib**
- Course Project for **CSE 311 Database Systems**
- All Rights Reserved © 2024
