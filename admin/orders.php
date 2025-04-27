
<?php
require_once '../config.php';

// Ensure user is logged in and is an admin
if (!isAdmin()) {
    showAlert('Access denied. Admin privileges required.', 'danger');
    redirect('../login.php');
}

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        showAlert('Order status updated successfully!', 'success');
    } else {
        showAlert('Failed to update order status.', 'danger');
    }
}

// Filter
$status_filter = $_GET['status'] ?? '';
$where_clause = "";
if (!empty($status_filter)) {
    $where_clause = " WHERE o.status = '$status_filter'";
}

// Get all orders
$sql = "SELECT o.id, o.total, o.status, o.order_date, 
               u.name as customer,
               COUNT(oi.id) as items
        FROM orders o
        JOIN users u ON o.user_id = u.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        $where_clause
        GROUP BY o.id
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
$orders = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - PharmaCare Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Manage Orders</h1>
                <div class="admin-header-actions">
                    <div class="filter-dropdown">
                        <select id="status-filter" onchange="window.location = '?status=' + this.value">
                            <option value="" <?php echo $status_filter == '' ? 'selected' : ''; ?>>All Orders</option>
                            <option value="Draft" <?php echo $status_filter == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="Processing" <?php echo $status_filter == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="Completed" <?php echo $status_filter == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Return" <?php echo $status_filter == 'Return' ? 'selected' : ''; ?>>Return</option>
                            <option value="Cancelled" <?php echo $status_filter == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <?php displayAlert(); ?>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($orders) > 0): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['customer']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo $order['items']; ?></td>
                                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <select name="new_status" onchange="this.form.submit()" class="status-select status-<?php echo strtolower($order['status']); ?>">
                                                <option value="Draft" <?php echo $order['status'] == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                                                <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                                <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="Return" <?php echo $order['status'] == 'Return' ? 'selected' : ''; ?>>Return</option>
                                                <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                    <td>
                                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-ghost btn-sm">View Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No orders found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script src="../js/admin.js"></script>
    
    <style>
        .filter-dropdown {
            min-width: 150px;
        }
        
        .filter-dropdown select {
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            width: 100%;
        }
        
        .status-select {
            padding: 0.4rem;
            border: none;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
            width: 130px;
        }
        
        .status-draft {
            background-color: rgba(108, 117, 125, 0.1);
            color: var(--gray-color);
        }
        
        .status-processing {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        .status-completed {
            background-color: rgba(42, 157, 143, 0.1);
            color: var(--success-color);
        }
        
        .status-cancelled {
            background-color: rgba(230, 57, 70, 0.1);
            color: var(--danger-color);
        }
        
        .status-return {
            background-color: rgba(244, 162, 97, 0.1);
            color: var(--warning-color);
        }
    </style>
</body>
</html>
