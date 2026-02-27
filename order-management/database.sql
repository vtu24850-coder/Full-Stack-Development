CREATE DATABASE IF NOT EXISTS order_management_db;
USE order_management_db;

-- 1. Customers Table
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    city VARCHAR(50),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50),
    stock INT DEFAULT 0
);

-- 3. Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) DEFAULT 0,
    status ENUM('Pending', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- 4. Order Items Table (To demonstrate complex joins)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_time DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Seeding Sample Data
INSERT INTO customers (name, email, city, phone) VALUES
('Alex Johnson', 'alex@example.com', 'New York', '1234567890'),
('Sarah Smith', 'sarah@example.com', 'Los Angeles', '0987654321'),
('Michael Brown', 'michael@example.com', 'Chicago', '1122334455'),
('Emily Davis', 'emily@example.com', 'Houston', '5566778899'),
('Chris Wilson', 'chris@example.com', 'Phoenix', '6677889900');

INSERT INTO products (name, price, category, stock) VALUES
('Quantum Laptop', 1200.00, 'Electronics', 50),
('Nova Smartphone', 800.00, 'Electronics', 100),
('Aero Headphones', 150.00, 'Accessories', 200),
('Zenith Smartwatch', 250.00, 'Wearables', 75),
('Titan Keyboard', 100.00, 'Accessories', 150);

-- Seeding Orders
INSERT INTO orders (customer_id, order_date, total_amount, status) VALUES
(1, '2023-10-01 10:00:00', 1350.00, 'Delivered'),
(2, '2023-10-02 11:30:00', 800.00, 'Shipped'),
(1, '2023-10-05 14:20:00', 250.00, 'Delivered'),
(3, '2023-10-10 09:15:00', 1450.00, 'Pending'),
(4, '2023-10-12 16:45:00', 100.00, 'Delivered'),
(1, '2023-10-15 12:00:00', 150.00, 'Shipped');

-- Order Items (Linking everything)
INSERT INTO order_items (order_id, product_id, quantity, price_at_time) VALUES
(1, 1, 1, 1200.00), -- Alex bought Laptop
(1, 3, 1, 150.00),  -- Alex bought Headphones
(2, 2, 1, 800.00),  -- Sarah bought Phone
(3, 4, 1, 250.00),  -- Alex bought Smartwatch
(4, 1, 1, 1200.00), -- Michael bought Laptop
(4, 4, 1, 250.00),  -- Michael bought Smartwatch
(5, 5, 1, 100.00),  -- Emily bought Keyboard
(6, 3, 1, 150.00);  -- Alex bought Headphones
