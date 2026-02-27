<?php
require_once 'db.php';

// 1. Fetch Order History using JOIN
// Joining Orders with Customers to get customer names
$stmtOrders = $pdo->query("
    SELECT o.id, c.name as customer_name, c.email, o.order_date, o.total_amount, o.status
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    ORDER BY o.order_date DESC
");
$orders = $stmtOrders->fetchAll();

// 2. Subquery: Highest Value Order
$stmtHighestOrder = $pdo->query("
    SELECT o.*, c.name as customer_name 
    FROM orders o 
    JOIN customers c ON o.customer_id = c.id
    WHERE o.total_amount = (SELECT MAX(total_amount) FROM orders)
    LIMIT 1
");
$highestOrder = $stmtHighestOrder->fetch();

// 3. Subquery: Most Active Customer
$stmtMostActive = $pdo->query("
    SELECT *, (SELECT COUNT(*) FROM orders WHERE orders.customer_id = customers.id) as order_count
    FROM customers 
    WHERE id = (
        SELECT customer_id 
        FROM orders 
        GROUP BY customer_id 
        ORDER BY COUNT(*) DESC 
        LIMIT 1
    )
");
$mostActive = $stmtMostActive->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexGen | Order Management Dashboard</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Order Management</h1>
            <p class="subtitle">E-commerce Insights & Order History</p>
        </header>

        <div class="stats-grid">
            <!-- Highest Value Order (Subquery Result) -->
            <div class="stat-card" style="animation-delay: 0.1s;">
                <h3>Highest Value Order</h3>
                <?php if ($highestOrder): ?>
                    <div class="stat-value">$<?= number_format($highestOrder['total_amount'], 2) ?></div>
                    <div class="stat-meta">Ordered by <?= htmlspecialchars($highestOrder['customer_name']) ?></div>
                <?php else: ?>
                    <div class="stat-value">N/A</div>
                <?php endif; ?>
            </div>

            <!-- Most Active Customer (Subquery Result) -->
            <div class="stat-card" style="animation-delay: 0.2s;">
                <h3>Most Active Customer</h3>
                <?php if ($mostActive): ?>
                    <div class="stat-value"><?= htmlspecialchars($mostActive['name']) ?></div>
                    <div class="stat-meta"><?= $mostActive['order_count'] ?> orders completed from <?= htmlspecialchars($mostActive['city']) ?></div>
                <?php else: ?>
                    <div class="stat-value">N/A</div>
                <?php endif; ?>
            </div>

            <div class="stat-card" style="animation-delay: 0.3s;">
                <h3>Total Revenue</h3>
                <div class="stat-value">
                    <?php
                        $totalRev = array_sum(array_column($orders, 'total_amount'));
                        echo '$' . number_format($totalRev, 2);
                    ?>
                </div>
                <div class="stat-meta">Across <?= count($orders) ?> total orders</div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <div><strong><?= htmlspecialchars($order['customer_name']) ?></strong></div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);"><?= htmlspecialchars($order['email']) ?></div>
                            </td>
                            <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                            <td><strong>$<?= number_format($order['total_amount'], 2) ?></strong></td>
                            <td>
                                <span class="badge badge-<?= strtolower($order['status']) ?>">
                                    <?= $order['status'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
