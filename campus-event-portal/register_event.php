<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];

$stmt = $pdo->prepare("INSERT IGNORE INTO registrations (user_id, event_id) VALUES (?, ?)");
$stmt->execute([$user_id, $event_id]);

header("Location: dashboard.php");
exit;
?>
