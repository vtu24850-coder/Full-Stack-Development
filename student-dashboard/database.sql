CREATE DATABASE IF NOT EXISTS student_dashboard;
USE student_dashboard;

CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    department_id INT,
    enrollment_date DATE NOT NULL,
    image_url VARCHAR(255),
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- Insert Sample Departments
INSERT INTO departments (name) VALUES 
('Computer Science'),
('Mechanical Engineering'),
('Electrical Engineering'),
('Business Administration'),
('Arts & Design');

-- Insert Sample Students
INSERT INTO students (name, email, department_id, enrollment_date, image_url) VALUES 
('Alice Johnson', 'alice@example.com', 1, '2023-09-01', 'https://i.pravatar.cc/150?u=alice'),
('Bob Smith', 'bob@example.com', 2, '2023-08-15', 'https://i.pravatar.cc/150?u=bob'),
('Charlie Brown', 'charlie@example.com', 1, '2023-10-10', 'https://i.pravatar.cc/150?u=charlie'),
('Diana Prince', 'diana@example.com', 4, '2023-07-20', 'https://i.pravatar.cc/150?u=diana'),
('Ethan Hunt', 'ethan@example.com', 3, '2023-11-05', 'https://i.pravatar.cc/150?u=ethan'),
('Fiona Gallagher', 'fiona@example.com', 5, '2023-12-12', 'https://i.pravatar.cc/150?u=fiona'),
('George Miller', 'george@example.com', 1, '2024-01-15', 'https://i.pravatar.cc/150?u=george'),
('Hannah Abbott', 'hannah@example.com', 4, '2024-02-20', 'https://i.pravatar.cc/150?u=hannah');
