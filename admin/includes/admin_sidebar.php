
<aside class="admin-sidebar" id="admin-sidebar">
    <div class="admin-sidebar-header">
        <h3>Admin Panel</h3>
    </div>
    
    <nav class="admin-sidebar-nav">
        <ul>
            <li>
                <a href="index.php" class="<?php echo basename($_SERVER['SCRIPT_FILENAME']) == 'index.php' ? 'active' : ''; ?>">
                    <span class="icon">📊</span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="products.php" class="<?php echo basename($_SERVER['SCRIPT_FILENAME']) == 'products.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'add_product.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'edit_product.php' ? 'active' : ''; ?>">
                    <span class="icon">📦</span>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="categories.php" class="<?php echo basename($_SERVER['SCRIPT_FILENAME']) == 'categories.php' ? 'active' : ''; ?>">
                    <span class="icon">📑</span>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="orders.php" class="<?php echo basename($_SERVER['SCRIPT_FILENAME']) == 'orders.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'order_details.php' ? 'active' : ''; ?>">
                    <span class="icon">🛒</span>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <a href="users.php" class="<?php echo basename($_SERVER['SCRIPT_FILENAME']) == 'users.php' ? 'active' : ''; ?>">
                    <span class="icon">👥</span>
                    <span>Users</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
