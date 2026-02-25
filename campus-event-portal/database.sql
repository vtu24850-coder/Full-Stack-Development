-- Create Database
CREATE DATABASE IF NOT EXISTS campus_events;
USE campus_events;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Events Table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    location VARCHAR(200) NOT NULL,
    image_url VARCHAR(255) DEFAULT 'assets/default.png',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Registrations Table
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE(user_id, event_id)
);

INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@campus.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE id=id;

-- Sample Events
INSERT INTO events (title, description, event_date, location, image_url, created_by) VALUES
('Tech Convergence 2026', 'A massive gathering of technology enthusiasts, featuring keynote speakers from top tech giants, hands-on workshops on AI and Blockchain, and a coding hackathon.', '2026-04-15', 'Main Auditorium', 'assets/tech.png', 1),
('Spring Music Festival', 'Enjoy a day filled with live music performances from campus bands and local artists. Food stalls, games, and merchandise available throughout the day.', '2026-05-20', 'Central Lawn', 'assets/music.png', 1),
('Annual Science Expo', 'Students showcase their innovative research projects and experiments across various fields of science and engineering. Guest lectures from renowned scientists included.', '2026-06-10', 'Science Block, Hall B', 'assets/science.png', 1),
('Quiz Mania', 'Test your knowledge across various domains in this high-intensity quiz competition. Prizes for top teams!', '2026-04-24', 'Vel Murgan Auditorium', 'assets/quiz.png', 1);
