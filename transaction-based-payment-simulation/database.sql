CREATE DATABASE IF NOT EXISTS payment_sim;
USE payment_sim;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    balance DECIMAL(10, 2) NOT NULL DEFAULT 0.00
);

CREATE TABLE IF NOT EXISTS merchants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    balance DECIMAL(10, 2) NOT NULL DEFAULT 0.00
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    merchant_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('success', 'failed') NOT NULL,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (merchant_id) REFERENCES merchants(id)
);

-- Seed data
INSERT INTO users (name, balance) VALUES ('Alice Johnson', 500.00);
INSERT INTO users (name, balance) VALUES ('Bob Smith', 100.00);

INSERT INTO merchants (name, balance) VALUES ('Tech Gadgets Store', 1000.00);
INSERT INTO merchants (name, balance) VALUES ('Coffee Shop', 50.00);
