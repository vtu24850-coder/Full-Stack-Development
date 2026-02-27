-- Create Database
CREATE DATABASE IF NOT EXISTS audit_system;
USE audit_system;

-- 1. Main Table: Employees
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    department VARCHAR(50),
    salary DECIMAL(10, 2),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Audit Logs Table
CREATE TABLE IF NOT EXISTS audit_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(50),
    record_id INT,
    action VARCHAR(10), -- INSERT, UPDATE
    old_value TEXT,
    new_value TEXT,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Triggers
DELIMITER //

-- Trigger for INSERT
CREATE TRIGGER after_employee_insert
AFTER INSERT ON employees
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (table_name, record_id, action, old_value, new_value)
    VALUES ('employees', NEW.id, 'INSERT', NULL, 
            CONCAT('Name: ', NEW.name, ', Dept: ', NEW.department, ', Status: ', NEW.status));
END //

-- Trigger for UPDATE
CREATE TRIGGER after_employee_update
AFTER UPDATE ON employees
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (table_name, record_id, action, old_value, new_value)
    VALUES ('employees', NEW.id, 'UPDATE', 
            CONCAT('Name: ', OLD.name, ', Dept: ', OLD.department, ', Status: ', OLD.status),
            CONCAT('Name: ', NEW.name, ', Dept: ', NEW.department, ', Status: ', NEW.status));
END //

DELIMITER ;

-- 4. View for Daily Activity Report
CREATE OR REPLACE VIEW daily_activity_report AS
SELECT 
    DATE(changed_at) as activity_date,
    action,
    COUNT(*) as total_actions
FROM audit_logs
GROUP BY activity_date, action
ORDER BY activity_date DESC;

-- Sample Data
INSERT INTO employees (name, email, department, salary) VALUES 
('Alice Johnson', 'alice@company.com', 'Engineering', 85000),
('Bob Smith', 'bob@company.com', 'Marketing', 65000),
('Charlie Davis', 'charlie@company.com', 'HR', 70000);
