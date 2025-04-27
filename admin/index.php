
<?php
require_once '../config.php';

// Ensure user is logged in and is an admin
if (!isAdmin()) {
    showAlert('Access denied. Admin privileges required.', 'danger');
    redirect('../login.php');
}

// Get some stats for the dashboard
$orders_query = "SELECT COUNT(*) as total, 
                SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = 'Draft' THEN 1 ELSE 0 END) as draft,
                SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = 'Return' THEN 1 ELSE 0 END) as returned
                FROM orders";
$orders_result = $conn->query($orders_query);
$orders_stats = $orders_result->fetch_assoc();

$products_count = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$users_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'")->fetch_assoc()['count'];

// Get recent orders
$recent_orders_query = "SELECT o.id, o.total, o.status, o.order_date, u.name as customer_name
                       FROM orders o
                       JOIN users u ON o.user_id = u.id
                       ORDER BY o.order_date DESC
                       LIMIT 5";
$recent_orders_result = $conn->query($recent_orders_query);
$recent_orders = [];
while ($row = $recent_orders_result->fetch_assoc()) {
    $recent_orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PharmaCare</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-header-actions">
                    <span>Welcome, <?php echo $_SESSION['user_name']; ?>!</span>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-icon orders-icon">📦</div>
                    <div class="stat-card-content">
                        <h3>Total Orders</h3>
                        <p class="stat-number"><?php echo $orders_stats['total'] ?? 0; ?></p>
                        <div class="stat-details">
                            <span>Completed: <?php echo $orders_stats['completed'] ?? 0; ?></span>
                            <span>Processing: <?php echo $orders_stats['processing'] ?? 0; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-icon products-icon">🛒</div>
                    <div class="stat-card-content">
                        <h3>Products</h3>
                        <p class="stat-number"><?php echo $products_count; ?></p>
                        <div class="stat-details">
                            <a href="products.php" class="link-action">Manage Products</a>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-icon users-icon">👥</div>
                    <div class="stat-card-content">
                        <h3>Users</h3>
                        <p class="stat-number"><?php echo $users_count; ?></p>
                        <div class="stat-details">
                            <a href="users.php" class="link-action">Manage Users</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="recent-orders">
                <div class="section-header">
                    <h2>Recent Orders</h2>
                    <a href="orders.php" class="btn btn-primary btn-sm">View All</a>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recent_orders) > 0): ?>
                                <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-ghost btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No recent orders</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../js/admin.js"></script>
</body>
</html>
