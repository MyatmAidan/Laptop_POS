<?php

$host = 'localhost';
$username = 'root';
$password = '';

$mysql = new mysqli($host, $username, $password);

if ($mysql->connect_errno) {
    echo "Failed to connect to MySQL." . $mysql->connect_error;
    exit();
}


create_database($mysql);

function create_database($mysql)
{
    try {
        $sql = "CREATE DATABASE IF NOT EXISTS 
        `laptop_pos` 
        DEFAULT CHARACTER SET utf8mb4
        COLLATE utf8mb4_general_ci";
        if ($mysql->query($sql)) {
            return true;
        }

        return false;
    } catch (Exception $e) {
        var_dump($e->getMessage());
    }
}

function select_database($mysql)
{
    return $mysql->select_db("laptop_pos");
}


select_database($mysql);
create_table($mysql);

function create_table($mysql)
{
    // user table
    $user_sql = "CREATE TABLE IF NOT EXISTS `user`
                (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    confirm_password VARCHAR(255) NOT NULL,
                    role ENUM('admin','customer') DEFAULT 'customer',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
    if ($mysql->query($user_sql) === false) return false;

    // category table
    $category_sql = "CREATE TABLE IF NOT EXISTS `category`
                (
                    category_id INT AUTO_INCREMENT PRIMARY KEY,
                    category_name VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
    if ($mysql->query($category_sql) === false) return false;

    // brand table
    $brand_sql = "CREATE TABLE IF NOT EXISTS `brand`
                (
                    brand_id INT AUTO_INCREMENT PRIMARY KEY,
                    brand_name VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
    if ($mysql->query($brand_sql) === false) return false;

    // product table
    $product_sql = "CREATE TABLE IF NOT EXISTS `product`
                (
                    product_id INT AUTO_INCREMENT PRIMARY KEY,
                    product_name VARCHAR(100) NOT NULL,
                    category_id INT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY(category_id) REFERENCES category(category_id) ON DELETE CASCADE
                )";
    if ($mysql->query($product_sql) === false) return false;

    // product_detail table
    $product_detail_sql = "CREATE TABLE IF NOT EXISTS `product_detail`
                (
                    product_detail_id INT AUTO_INCREMENT PRIMARY KEY,
                    product_id INT NOT NULL,
                    brand_id INT NOT NULL,
                    category_id INT NOT NULL,
                    description varchar(255),
                    qty INT NOT NULL,
                    price DECIMAL(10,2) NOT NULL,
                    img VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY(product_id) REFERENCES product(product_id) ON DELETE CASCADE,
                    FOREIGN KEY(category_id) REFERENCES category(category_id) ON DELETE CASCADE,
                    FOREIGN KEY(brand_id) REFERENCES brand(brand_id) ON DELETE CASCADE
                )";
    if ($mysql->query($product_detail_sql) === false) return false;


    // orders table
    $orders_sql = "CREATE TABLE IF NOT EXISTS `orders`
                (
                    order_id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    total_amount DECIMAL(10,2) NOT NULL,
                    shipping_address VARCHAR(255) NOT NULL,
                    shipping_city VARCHAR(100) NOT NULL,
                    shipping_state VARCHAR(100) NOT NULL,
                    shipping_zip VARCHAR(20) NOT NULL,
                    shipping_country VARCHAR(100) NOT NULL,
                    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE
                )";
    if ($mysql->query($orders_sql) === false) return false;

    // order_items table
    $order_items_sql = "CREATE TABLE IF NOT EXISTS `order_items`
                (
                    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
                    order_id INT NOT NULL,
                    product_detail_id INT NOT NULL,
                    quantity INT NOT NULL,
                    price DECIMAL(10,2) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY(order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
                    FOREIGN KEY(product_detail_id) REFERENCES product_detail(product_detail_id) ON DELETE CASCADE
                )";
    if ($mysql->query($order_items_sql) === false) return false;

    // category table
    $method_sql = "CREATE TABLE IF NOT EXISTS `method`
                (
                    method_id INT AUTO_INCREMENT PRIMARY KEY,
                    method_name VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
    if ($mysql->query($method_sql) === false) return false;

    // payment table - Updated to reference orders instead of cart
    $payment_sql = "CREATE TABLE IF NOT EXISTS `payment`
                (
                    payment_id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    order_id INT NOT NULL,
                    method_id int NOT NULL,
                    grand_total DECIMAL(10,2) NOT NULL,
                    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE,
                    FOREIGN KEY(method_id) REFERENCES method(method_id) ON DELETE CASCADE,
                    FOREIGN KEY(order_id) REFERENCES orders(order_id) ON DELETE CASCADE
                )";
    if ($mysql->query($payment_sql) === false) return false;

    return true;
}
