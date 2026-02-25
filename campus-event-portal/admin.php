<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['event_date'];
    $loc = $_POST['location'];
    $img = $_POST['image_url'];
    $admin_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, image_url, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$title, $desc, $date, $loc, $img, $admin_id])) {
        $message = "Event created successfully!";
    }
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $message = "Event removed successfully!";
}

$stmt = $pdo->query("SELECT e.*, (SELECT COUNT(*) FROM registrations WHERE event_id = e.id) as reg_count FROM events e ORDER BY event_date DESC");
$events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Campus Event Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="app-container">
            <div style="margin-bottom: 40px;">
                <h1 style="font-size: 2.2rem; margin-bottom: 5px;">Admin Management Console</h1>
                <p style="color: var(--text-muted);">Coordinate campus activities and monitor engagement.</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 350px 1fr; gap: 40px;">
                <!-- Add Event Form -->
                <div class="card" style="padding: 30px; height: fit-content; border-radius: 20px;">
                    <h3 style="margin-bottom: 25px;">Create Event</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Event Title</label>
                            <input type="text" name="title" required placeholder="e.g. Hackathon 2026">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="3" required placeholder="Describe the mission and activities..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Date & Time</label>
                            <input type="date" name="event_date" required>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" required placeholder="Building or Hall">
                        </div>
                        <div class="form-group">
                            <label>Event Category / Image</label>
                            <select name="image_url" required style="cursor: pointer;">
                                <option value="assets/tech.png">Technology / Workshop</option>
                                <option value="assets/music.png">Festival / Arts</option>
                                <option value="assets/science.png">Science / Exhibition</option>
                                <option value="assets/quiz.png">Academic / Competition</option>
                                <option value="assets/gd_pro.png">GD Pro / Discussion</option>
                            </select>
                        </div>
                        <input type="hidden" name="add_event" value="1">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Publish Event</button>
                    </form>
                </div>

                <!-- Events List -->
                <div>
                    <h3 style="margin-bottom: 25px;">Live Events Tracking</h3>
                    <div style="background: white; border-radius: 20px; overflow: hidden; border: 1px solid var(--border); box-shadow: var(--shadow);">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background: #f8fafc; border-bottom: 2px solid var(--border);">
                                <tr>
                                    <th style="padding: 18px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted);">Preview</th>
                                    <th style="padding: 18px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted);">Title</th>
                                    <th style="padding: 18px; text-align: left; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted);">Registrations</th>
                                    <th style="padding: 18px; text-align: center; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted);">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($events as $event): ?>
                                    <tr style="border-bottom: 1px solid var(--border);">
                                        <td style="padding: 15px;">
                                            <img src="<?php echo htmlspecialchars($event['image_url']); ?>" style="width: 60px; height: 40px; border-radius: 8px; object-fit: cover;">
                                        </td>
                                        <td style="padding: 15px;">
                                            <p style="font-weight: 600;"><?php echo htmlspecialchars($event['title']); ?></p>
                                            <p style="font-size: 0.75rem; color: var(--text-muted);"><?php echo date('M d, Y', strtotime($event['event_date'])); ?></p>
                                        </td>
                                        <td style="padding: 15px;">
                                            <span class="badge badge-primary"><?php echo $event['reg_count']; ?> Student(s)</span>
                                        </td>
                                        <td style="padding: 15px; text-align: center;">
                                            <a href="admin.php?delete=<?php echo $event['id']; ?>" onclick="return confirm('Archive this event?')" style="color: var(--error); text-decoration: none; font-size: 0.8rem; font-weight: 600;">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
