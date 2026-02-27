<?php
require_once 'db.php';

// Fetch users and merchants for the dropdowns
$users = $pdo->query("SELECT * FROM users")->fetchAll();
$merchants = $pdo->query("SELECT * FROM merchants")->fetchAll();

// Fetch transaction history
$history = $pdo->query("
    SELECT t.*, u.name as user_name, m.name as merchant_name 
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    JOIN merchants m ON t.merchant_id = m.id
    ORDER BY t.created_at DESC
    LIMIT 10
")->fetchAll();

$status = isset($_GET['status']) ? $_GET['status'] : null;
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PaySim | Atomic Transactions</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Left Column: Payment Form & Balances -->
        <div class="left-col">
            <div class="card">
                <h2>Secure Pay</h2>
                <p class="section-title">Transfer Funds</p>
                
                <form action="process_payment.php" method="POST">
                    <div class="input-group">
                        <label for="user_id">From User</label>
                        <select name="user_id" id="user_id" required>
                            <option value="" disabled selected>Select User</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= $user['name'] ?> ($<?= number_format($user['balance'], 2) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="merchant_id">To Merchant</label>
                        <select name="merchant_id" id="merchant_id" required>
                            <option value="" disabled selected>Select Merchant</option>
                            <?php foreach ($merchants as $merchant): ?>
                                <option value="<?= $merchant['id'] ?>"><?= $merchant['name'] ?> ($<?= number_format($merchant['balance'], 2) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="amount">Amount ($)</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0.01" placeholder="0.00" required>
                    </div>

                    <button type="submit">Process Payment</button>
                </form>

                <?php if ($status): ?>
                    <div class="status-msg <?= $status ?>">
                        <?= $message ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card" style="margin-top: 2rem;">
                <p class="section-title">Quick Balances</p>
                <div class="account-info">
                    <?php foreach ($users as $user): ?>
                        <div class="account-item">
                            <span><?= $user['name'] ?></span>
                            <span class="balance">$<?= number_format($user['balance'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Transaction History -->
        <div class="right-col">
            <div class="card">
                <h2>Transaction Logs</h2>
                <p class="section-title">Recent Activity (Atomic Transactions)</p>
                
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>From / To</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($history)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2rem;">No transactions yet.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($history as $row): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 500;"><?= $row['user_name'] ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">→ <?= $row['merchant_name'] ?></div>
                                </td>
                                <td style="font-weight: 600;">$<?= number_format($row['amount'], 2) ?></td>
                                <td>
                                    <span class="status-badge badge-<?= $row['status'] ?>">
                                        <?= strtoupper($row['status']) ?>
                                    </span>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.75rem;">
                                    <?= date('H:i:s', strtotime($row['created_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
