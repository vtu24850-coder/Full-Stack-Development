<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $merchantId = $_POST['merchant_id'];
    $amount = (float)$_POST['amount'];

    try {
        // Start the Atomic Transaction
        $pdo->beginTransaction();

        // 1. Fetch user balance (locking the row for update to prevent race conditions)
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("User not found.");
        }

        if ($user['balance'] < $amount) {
            throw new Exception("Insufficient balance.");
        }

        // 2. Deduct from user
        $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $userId]);

        // 3. Add to merchant
        $stmt = $pdo->prepare("UPDATE merchants SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $merchantId]);

        // 4. Record the transaction (Success)
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, merchant_id, amount, status) VALUES (?, ?, ?, 'success')");
        $stmt->execute([$userId, $merchantId, $amount]);

        // 5. COMMIT all changes
        $pdo->commit();

        header("Location: index.php?status=success&message=" . urlencode("Payment of $$amount processed successfully."));
        exit;

    } catch (Exception $e) {
        // ROLLBACK all changes on failure
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        // Log the failure to the database (in a separate transaction so it's not rolled back)
        try {
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, merchant_id, amount, status, error_message) VALUES (?, ?, ?, 'failed', ?)");
            $stmt->execute([$userId, $merchantId, $amount, $e->getMessage()]);
        } catch (Exception $logError) {
            // Silently fail if logging fails
        }

        header("Location: index.php?status=error&message=" . urlencode("Transaction Failed: " . $e->getMessage()));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
