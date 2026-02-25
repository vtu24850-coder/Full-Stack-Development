<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user registrations
$stmt = $pdo->prepare("
    SELECT e.*, r.registration_date 
    FROM events e 
    JOIN registrations r ON e.id = r.event_id 
    WHERE r.user_id = ?
    ORDER BY registration_date DESC
");
$stmt->execute([$user_id]);
$my_events = $stmt->fetchAll();

// Get available events (not registered)
$stmt = $pdo->prepare("
    SELECT * FROM events 
    WHERE id NOT IN (SELECT event_id FROM registrations WHERE user_id = ?)
    AND event_date >= CURDATE()
    ORDER BY event_date ASC
");
$stmt->execute([$user_id]);
$available_events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Campus Event Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="app-container">
            <div style="margin-bottom: 50px;">
                <h1 style="font-size: 2.5rem; margin-bottom: 5px;">Welcome Back, <?php echo explode(' ', htmlspecialchars($_SESSION['name']))[0]; ?>! 👋</h1>
                <p style="color: var(--text-muted);">You have <?php echo count($my_events); ?> active registrations.</p>
            </div>

            <section style="margin-bottom: 60px;">
                <h2 style="font-size: 1.6rem; margin-bottom: 25px;">My Registered Events</h2>
                <div class="grid">
                    <?php foreach($my_events as $event): ?>
                        <div class="card">
                            <div class="card-image">
                                <span class="badge badge-success" style="position: absolute; top: 15px; left: 15px; z-index: 10;">✓ Registered</span>
                                <img src="<?php echo htmlspecialchars($event['image_url']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                            </div>
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                                <div class="card-footer" style="border: none; padding-top: 0;">
                                    <span class="location-tag">📅 <?php echo date('M d, Y', strtotime($event['event_date'])); ?></span>
                                    <a href="event_details.php?id=<?php echo $event['id']; ?>" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">View</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if(empty($my_events)): ?>
                        <div style="grid-column: 1/-1; padding: 40px; background: white; border-radius: 20px; text-align: center; border: 1px dashed var(--border);">
                            <p style="color: var(--text-muted);">You haven't registered for any events yet.</p>
                            <a href="index.php" class="btn btn-primary" style="margin-top: 15px;">Explore Events</a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section>
                <h2 style="font-size: 1.6rem; margin-bottom: 25px;">Recommended for You</h2>
                <div class="grid">
                    <?php foreach($available_events as $event): ?>
                        <div class="card">
                            <div class="card-image">
                                <img src="<?php echo htmlspecialchars($event['image_url']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                            </div>
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                                <div class="card-footer">
                                    <span class="location-tag">📍 <?php echo htmlspecialchars($event['location']); ?></span>
                                    <form action="register_event.php" method="POST">
                                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Quick Register</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>
