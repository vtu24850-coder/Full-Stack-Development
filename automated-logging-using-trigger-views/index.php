<?php
require_once 'db.php';

// Handle Add/Update Employee
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO employees (name, email, department, salary) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['email'], $_POST['department'], $_POST['salary']]);
        } elseif ($_POST['action'] === 'update') {
            $stmt = $pdo->prepare("UPDATE employees SET department = ?, salary = ?, status = ? WHERE id = ?");
            $stmt->execute([$_POST['department'], $_POST['salary'], $_POST['status'], $_POST['id']]);
        }
        header("Location: index.php");
        exit;
    }
}

// Fetch Data
$employees = $pdo->query("SELECT * FROM employees ORDER BY id DESC")->fetchAll();
$audit_logs = $pdo->query("SELECT * FROM audit_logs ORDER BY changed_at DESC LIMIT 20")->fetchAll();
$daily_reports = $pdo->query("SELECT * FROM daily_activity_report LIMIT 7")->fetchAll();

// Total actions for stats
$total_inserts = $pdo->query("SELECT COUNT(*) FROM audit_logs WHERE action = 'INSERT'")->fetchColumn();
$total_updates = $pdo->query("SELECT COUNT(*) FROM audit_logs WHERE action = 'UPDATE'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Audit | Automated Logging System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <div>
                <h1>Enterprise <span style="font-weight: 300;">Audit</span></h1>
                <p style="color: var(--text-dim); margin-top: 5px;">Automated Database Logging via Triggers & Views</p>
            </div>
            <div class="badge">
                <i class="fas fa-shield-halved"></i> System Integrity: Optimal
            </div>
        </header>

        <div class="grid">
            <!-- Left Sidebar: Controls & Reports -->
            <div class="sidebar">
                <div class="card" style="margin-bottom: 2rem;">
                    <div class="card-title"><i class="fas fa-plus-circle"></i> Register Employee</div>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="action" value="add">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="john@company.com" required>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department">
                                <option value="Engineering">Engineering</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Finance">Finance</option>
                                <option value="HR">HR</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Salary ($)</label>
                            <input type="number" name="salary" placeholder="75000" required>
                        </div>
                        <button type="submit">Complete Registration</button>
                    </form>
                </div>

                <div class="card">
                    <div class="card-title"><i class="fas fa-chart-line"></i> Daily Activity (from VIEW)</div>
                    <div class="report-summary">
                        <div class="stat-box">
                            <span class="stat-val"><?= $total_inserts ?></span>
                            <span class="stat-label">Inserts</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-val"><?= $total_updates ?></span>
                            <span class="stat-label">Updates</span>
                        </div>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daily_reports as $report): ?>
                                <tr>
                                    <td><?= date('M d', strtotime($report['activity_date'])) ?></td>
                                    <td><span class="status-chip status-<?= strtolower($report['action']) ?>"><?= $report['action'] ?></span></td>
                                    <td><?= $report['total_actions'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Main Content: Logs & User List -->
            <div class="main-content">
                <div class="card" style="height: 100%;">
                    <div class="card-title">
                        <i class="fas fa-list-check"></i> Employee Directory & Audit Logs
                    </div>

                    <div class="table-container" style="max-height: 400px; margin-bottom: 2rem;">
                        <h3>Active Directory</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Dept</th>
                                    <th>Status</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $emp): ?>
                                <tr>
                                    <td>#<?= $emp['id'] ?></td>
                                    <td><?= htmlspecialchars($emp['name']) ?></td>
                                    <td><?= $emp['department'] ?></td>
                                    <td>
                                        <span class="status-chip" style="background: <?= $emp['status'] == 'Active' ? 'rgba(0,255,157,0.1)' : 'rgba(255,0,122,0.1)' ?>; color: <?= $emp['status'] == 'Active' ? 'var(--success)' : 'var(--accent)' ?>">
                                            <?= $emp['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="index.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                                            <input type="hidden" name="department" value="<?= $emp['department'] ?>">
                                            <input type="hidden" name="salary" value="<?= $emp['salary'] + 1000 ?>"> <!-- Simulate raise -->
                                            <input type="hidden" name="status" value="<?= $emp['status'] == 'Active' ? 'Inactive' : 'Active' ?>">
                                            <button type="submit" class="update-btn">Toggle / Raise</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-container">
                        <h3 style="margin-top: 1rem; color: var(--primary-glow);"><i class="fas fa-history"></i> Automated Audit Trail</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                    <th>Details (Old -> New)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($audit_logs as $log): ?>
                                <tr>
                                    <td style="font-size: 0.8rem; color: var(--text-dim);"><?= date('H:i:s', strtotime($log['changed_at'])) ?></td>
                                    <td><span class="status-chip status-<?= strtolower($log['action']) ?>"><?= $log['action'] ?></span></td>
                                    <td class="log-entry">
                                        <?php if ($log['action'] === 'UPDATE'): ?>
                                            <span style="color: var(--accent)"><?= htmlspecialchars($log['old_value'] ?? 'N/A') ?></span>
                                            <i class="fas fa-arrow-right" style="margin: 0 5px; font-size: 0.7rem;"></i>
                                            <span style="color: var(--success)"><?= htmlspecialchars($log['new_value']) ?></span>
                                        <?php else: ?>
                                            <span style="color: var(--success)"><?= htmlspecialchars($log['new_value']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Simple UI enhancements
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.4s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, 50 * index);
            });

            // Pulse effect on the status badges
            const badges = document.querySelectorAll('.status-chip');
            badges.forEach(badge => {
                badge.addEventListener('mouseenter', () => {
                    badge.style.transform = 'scale(1.1)';
                    badge.style.boxShadow = '0 0 10px currentColor';
                });
                badge.addEventListener('mouseleave', () => {
                    badge.style.transform = 'scale(1)';
                    badge.style.boxShadow = 'none';
                });
            });
        });
    </script>
</body>
</html>
