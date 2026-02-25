<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
    header("Location: admin.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid admin credentials or unauthorized access.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Campus Event Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body style="display: block;">
    <div class="auth-wrapper" style="background: radial-gradient(circle at top right, rgba(239, 68, 68, 0.05), transparent);">
        <div class="auth-container">
            <a href="index.php" class="logo" style="text-align: center; margin-bottom: 30px;">CampusEvents</a>
            <h2 style="margin-bottom: 10px; text-align: center;">Staff Console</h2>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">Authorized Management Access</p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" name="email" required placeholder="admin@campus.edu">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">Access Panel</button>
            </form>
            
            <p style="text-align: center; margin-top: 30px; font-size: 0.9rem;">
                New Admin? <a href="admin_signup.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Apply for access</a>
            </p>
        </div>
    </div>
</body>
</html>
