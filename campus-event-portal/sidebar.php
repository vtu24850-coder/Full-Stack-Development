<div class="app-layout">
<div class="sidebar">
    <a href="index.php" class="logo">CampusEvents</a>
    
    <div class="sidebar-nav">
        <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            Home
        </a>
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">
                Login
            </a>
            <a href="signup.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'signup.php' ? 'active' : ''; ?>">
                Student Sign Up
            </a>
        <?php else: ?>
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                My Dashboard
            </a>
            <?php if($_SESSION['role'] == 'admin'): ?>
                <a href="admin.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : ''; ?>">
                    Admin Access
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="sidebar-footer">
        <?php if(isset($_SESSION['user_id'])): ?>
            <div style="padding: 15px; background: var(--bg-main); border-radius: 12px; margin-bottom: 15px;">
                <p style="font-size: 0.85rem; font-weight: 600;"><?php echo htmlspecialchars($_SESSION['name']); ?></p>
                <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: capitalize;"><?php echo $_SESSION['role']; ?></p>
            </div>
            <a href="logout.php" class="btn btn-outline" style="width: 100%;">Logout</a>
        <?php else: ?>
            <a href="admin_login.php" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none;">Admin Staff Login</a>
        <?php endif; ?>
    </div>
</div>
