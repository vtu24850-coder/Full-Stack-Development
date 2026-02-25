<?php
$host = 'localhost';
$db   = 'campus_events';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Connection without database name first to check if DB exists
$dsn_no_db = "mysql:host=$host;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn_no_db, $user, $pass, $options);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$db` ");
    
    // Check if tables exist, if not, create them
    $query = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($query->rowCount() == 0) {
        $sql = file_get_contents(__DIR__ . '/database.sql');
        $pdo->exec($sql);
    } else {
        // Ensure default admin exists
        $adminEmail = 'admin@campus.edu';
        $checkAdmin = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $checkAdmin->execute([$adminEmail]);
        if (!$checkAdmin->fetch()) {
            $hashedPass = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES ('Admin User', ?, ?, 'admin')");
            $stmt->execute([$adminEmail, $hashedPass]);
        }
    }

    // Re-connect with the specific database for the rest of the app
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);

} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
