
<aside class="admin-sidebar">
    <div class="admin-sidebar-header">
        <a href="index.php">
            <span>PharmaCare</span> Admin
        </a>
    </div>
    
    <nav class="admin-menu">
        <a href="index.php" class="admin-menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <div class="admin-menu-icon">🏠</div>
            <span>Dashboard</span>
        </a>
        <a href="orders.php" class="admin-menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
            <div class="admin-menu-icon">📦</div>
            <span>Orders</span>
        </a>
        <a href="products.php" class="admin-menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
            <div class="admin-menu-icon">🛒</div>
            <span>Products</span>
        </a>
        <a href="users.php" class="admin-menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
            <div class="admin-menu-icon">👥</div>
            <span>Users</span>
        </a>
        <a href="../logout.php" class="admin-menu-item">
            <div class="admin-menu-icon">🚪</div>
            <span>Logout</span>
        </a>
    </nav>
</aside>
