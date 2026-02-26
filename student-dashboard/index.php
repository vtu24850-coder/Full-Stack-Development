<?php
require_once 'db.php';

// Get filter/sort parameters
$dept_filter = isset($_GET['department']) ? $_GET['department'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Prepare query for students
$query = "SELECT s.*, d.name as department_name 
          FROM students s 
          JOIN departments d ON s.department_id = d.id 
          WHERE 1=1";

if ($dept_filter) {
    $query .= " AND d.id = :dept_id";
}

// Sorting logic
$allowed_sorts = ['name' => 's.name', 'date' => 's.enrollment_date'];
$sort_col = isset($allowed_sorts[$sort_by]) ? $allowed_sorts[$sort_by] : 's.name';
$query .= " ORDER BY $sort_col $order";

$stmt = $pdo->prepare($query);
if ($dept_filter) {
    $stmt->bindParam(':dept_id', $dept_filter);
}
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch departments for filter
$dept_stmt = $pdo->query("SELECT * FROM departments");
$departments = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch counts per department
$counts_stmt = $pdo->query("SELECT d.name, COUNT(s.id) as count 
                            FROM departments d 
                            LEFT JOIN students s ON d.id = s.department_id 
                            GROUP BY d.id");
$dept_counts = $counts_stmt->fetchAll(PDO::FETCH_ASSOC);

$total_students = count($students);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data Dashboard</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>EduDash</span>
        </div>
        <nav>
            <a href="#" class="nav-item active">
                <i class="fas fa-users"></i>
                <span>Students</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-building"></i>
                <span>Departments</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="header">
            <div class="welcome-text">
                <h1>Student Directory</h1>
                <p>Manage and retrieve student records with ease.</p>
            </div>
            <div class="user-profile">
                <!-- Profile placeholder -->
            </div>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Students</h3>
                <div class="value"><?php echo $total_students; ?></div>
            </div>
            <?php foreach ($dept_counts as $dept): ?>
                <?php if ($dept['count'] > 0): ?>
                    <div class="stat-card">
                        <h3><?php echo htmlspecialchars($dept['name']); ?></h3>
                        <div class="value"><?php echo $dept['count']; ?></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <section class="controls">
            <form action="" method="GET" style="display: flex; gap: 1rem; width: 100%; flex-wrap: wrap;">
                <div class="form-group">
                    <label>Filter by Department</label>
                    <select name="department">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept['id']; ?>" <?php echo $dept_filter == $dept['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort">
                        <option value="name" <?php echo $sort_by == 'name' ? 'selected' : ''; ?>>Name</option>
                        <option value="date" <?php echo $sort_by == 'date' ? 'selected' : ''; ?>>Enrollment Date</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Order</label>
                    <select name="order">
                        <option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                        <option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Descending</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary">Apply Filters</button>
                <a href="index.php" style="margin-top: auto; padding: 0.5rem; color: var(--text-muted); text-decoration: none; font-size: 0.875rem;">Reset</a>
            </form>
        </section>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Enrollment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">No records found matching your criteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td>
                                <div class="student-info">
                                    <img src="<?php echo htmlspecialchars($student['image_url']); ?>" alt="" class="student-img">
                                    <div style="font-weight: 600;"><?php echo htmlspecialchars($student['name']); ?></div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td>
                                <span class="badge"><?php echo htmlspecialchars($student['department_name']); ?></span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($student['enrollment_date'])); ?></td>
                            <td>
                                <button style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
