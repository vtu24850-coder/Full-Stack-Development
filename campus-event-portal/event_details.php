<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT e.*, u.name as organizer FROM events e LEFT JOIN users u ON e.created_by = u.id WHERE e.id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    header("Location: index.php");
    exit;
}

$is_registered = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$_SESSION['user_id'], $id]);
    $is_registered = $stmt->fetch() ? true : false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?> | Campus Event Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="app-container">
            <div style="max-width: 900px; margin: 0 auto;">
                <a href="index.php" style="color: var(--primary); text-decoration: none; display: inline-block; margin-bottom: 30px; font-weight: 500;">&larr; Back to Events</a>
                
                <div class="card" style="border-radius: 30px;">
                    <div style="width: 100%; height: 350px; overflow: hidden;">
                        <img src="<?php echo htmlspecialchars($event['image_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    
                    <div class="card-content" style="padding: 50px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                            <span class="badge badge-primary"><?php echo date('F d, Y', strtotime($event['event_date'])); ?></span>
                            <span style="color: var(--text-muted); font-size: 0.9rem;">📍 <?php echo htmlspecialchars($event['location']); ?></span>
                        </div>
                        
                        <h1 style="font-size: 3rem; font-weight: 800; letter-spacing: -2px; line-height: 1.1; margin-bottom: 30px;">
                            <?php echo htmlspecialchars($event['title']); ?>
                        </h1>
                        
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 40px; padding: 15px; background: #f8fafc; border-radius: 15px; width: fit-content;">
                            <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                <?php echo strtoupper(substr($event['organizer'] ?? 'A', 0, 1)); ?>
                            </div>
                            <div>
                                <p style="font-size: 0.8rem; color: var(--text-muted);">Organized by</p>
                                <p style="font-weight: 600;"><?php echo htmlspecialchars($event['organizer'] ?? 'Campus Admin'); ?></p>
                            </div>
                        </div>

                        <div style="font-size: 1.15rem; color: var(--text-main); line-height: 1.8; margin-bottom: 50px;">
                            <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                        </div>

                        <div style="padding-top: 40px; border-top: 1px solid var(--border);">
                            <?php if(!isset($_SESSION['user_id'])): ?>
                                <div class="alert alert-primary" style="text-align: center;">
                                    Please <a href="login.php" style="font-weight: 700; color: var(--primary);">Login</a> or <a href="signup.php" style="font-weight: 700; color: var(--primary);">Sign up</a> to register for this event.
                                </div>
                            <?php elseif($is_registered): ?>
                                <div class="alert alert-success" style="text-align: center; font-weight: 700; padding: 20px;">
                                    ✓ Registration Confirmed! You are on the list.
                                </div>
                            <?php else: ?>
                                <form action="register_event.php" method="POST">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.2rem; border-radius: 15px;">Secure My Spot</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
