<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] == 'admin' ? 'admin.php' : 'dashboard.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $admin_code = $_POST['admin_code'];

    if ($admin_code !== 'CAMPUS2026') {
        $error = "Invalid Admin Verification Code!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
            if ($stmt->execute([$name, $email, $password])) {
                header("Location: admin_login.php?signup=success");
                exit;
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up | Campus Event Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <a href="index.php" class="logo" style="text-align: center; margin-bottom: 30px;">CampusEvents</a>
            <h2 style="margin-bottom: 10px; text-align: center;">Admin Registration</h2>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">Create a management account</p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required placeholder="Administrator Name">
                </div>
                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" name="email" required placeholder="admin@campus.edu">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label>Admin Verification Code</label>
                    <input type="text" name="admin_code" required placeholder="Enter secret code">
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Hint: CAMPUS2026</small>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">Create Admin Account</button>
            </form>
            
            <p style="text-align: center; margin-top: 30px; font-size: 0.9rem;">
                Already have an admin account? <a href="admin_login.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>
