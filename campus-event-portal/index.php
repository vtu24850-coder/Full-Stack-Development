<?php
session_start();
require_once 'config.php';

// Fetch events
$stmt = $pdo->query("SELECT e.*, u.name as organizer FROM events e LEFT JOIN users u ON e.created_by = u.id ORDER BY event_date DESC");
$events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Event Portal | Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="app-container">
            <section class="hero-compact">
                <h1>Level Up Your <br>Campus Life</h1>
                <p>Discover, register, and attend the most happening events around campus. Your journey to growth starts here.</p>
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="signup.php" class="btn" style="background: white; color: var(--primary); margin-top: 20px;">Join the Community</a>
                <?php endif; ?>
            </section>

            <h2 style="font-size: 1.8rem; font-weight: 700;">Upcoming Events</h2>
            
            <div class="grid">
                <?php foreach($events as $event): ?>
                    <div class="card">
                        <div class="card-image">
                            <span class="card-date"><?php echo date('M d', strtotime($event['event_date'])); ?></span>
                            <img src="<?php echo htmlspecialchars($event['image_url'] ?? 'assets/default.png'); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                        </div>
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?></p>
                            
                            <div class="card-footer">
                                <span class="location-tag">📍 <?php echo htmlspecialchars($event['location']); ?></span>
                                <a href="event_details.php?id=<?php echo $event['id']; ?>" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if(empty($events)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px; background: white; border-radius: 20px; border: 1px dashed var(--border);">
                        <p style="color: var(--text-muted); font-size: 1.1rem;">No events found yet. Stay tuned!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>
