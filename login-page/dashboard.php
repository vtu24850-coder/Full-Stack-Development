<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .welcome-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 50px;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }
        .logout-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="welcome-card">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>You have successfully logged into the Nexus portal.</p>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>
