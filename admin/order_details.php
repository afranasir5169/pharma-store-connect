
<?php
require_once '../config.php';

// Ensure user is logged in and is an admin
if (!isAdmin()) {
    showAlert('Access denied. Admin privileges required.', 'danger');
    redirect('../login.php');
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('orders.php');
}

$order_id = $_GET['id'];

// Get order details
$order_sql = "SELECT o.*, u.name as customer_name, u.email as customer_email 
             FROM orders o
             JOIN users u ON o.user_id = u.id
             WHERE o.id = ?";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows == 0) {
    showAlert('Order not found.', 'danger');
    redirect('orders.php');
}

$order = $order_result->fetch_assoc();

// Get order items
$items_sql = "SELECT oi.*, p.name as product_name, p.requires_prescription 
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = ?";
$items_stmt = $conn->prepare($items_sql);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$items = [];

while ($item = $items_result->fetch_assoc()) {
    $items[] = $item;
}

// Handle status update
if (isset($_POST['update_status'])) {
    $new_status = $_POST['new_status'];
    
    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $order_id);
    
    if ($update_stmt->execute()) {
        showAlert('Order status updated successfully!', 'success');
        $order['status'] = $new_status;
    } else {
        showAlert('Failed to update order status.', 'danger');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details #<?php echo $order_id; ?> - PharmaCare Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Order #<?php echo $order_id; ?> Details</h1>
                <div class="admin-header-actions">
                    <a href="orders.php" class="btn btn-ghost">Back to Orders</a>
                </div>
            </div>
            
            <?php displayAlert(); ?>
            
            <div class="order-details">
                <div class="order-card">
                    <div class="order-card-header">
                        <h2>Order Information</h2>
                        <form method="post" class="status-form">
                            <select name="new_status" class="status-select status-<?php echo strtolower($order['status']); ?>">
                                <option value="Draft" <?php echo $order['status'] == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="Return" <?php echo $order['status'] == 'Return' ? 'selected' : ''; ?>>Return</option>
                                <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </div>
                    
                    <div class="order-info-grid">
                        <div class="order-info-item">
                            <span class="label">Order Date:</span>
                            <span class="value"><?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></span>
                        </div>
                        
                        <div class="order-info-item">
                            <span class="label">Customer:</span>
                            <span class="value"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                        </div>
                        
                        <div class="order-info-item">
                            <span class="label">Email:</span>
                            <span class="value"><?php echo htmlspecialchars($order['customer_email']); ?></span>
                        </div>
                        
                        <div class="order-info-item">
                            <span class="label">Total Amount:</span>
                            <span class="value">$<?php echo number_format($order['total'], 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($order['prescription_image']) && file_exists('../' . $order['prescription_image'])): ?>
                    <div class="order-card">
                        <div class="order-card-header">
                            <h2>Prescription</h2>
                        </div>
                        
                        <div class="prescription-display">
                            <?php $ext = pathinfo($order['prescription_image'], PATHINFO_EXTENSION); ?>
                            <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <img src="../<?php echo $order['prescription_image']; ?>" alt="Prescription">
                            <?php else: ?>
                                <a href="../<?php echo $order['prescription_image']; ?>" target="_blank" class="btn btn-primary">View Prescription PDF</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="order-card">
                    <div class="order-card-header">
                        <h2>Order Items</h2>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Prescription</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        <td>
                                            <?php echo $item['requires_prescription'] ? 'Required' : 'Not Required'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total:</th>
                                    <th>$<?php echo number_format($order['total'], 2); ?></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../js/admin.js"></script>
    
    <style>
        .order-details {
            display: grid;
            gap: 1.5rem;
        }
        
        .order-card {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .order-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-card-header h2 {
            font-size: 1.25rem;
            margin: 0;
        }
        
        .order-info-grid {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .order-info-item {
            display: flex;
            flex-direction: column;
        }
        
        .order-info-item .label {
            color: var(--gray-color);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .order-info-item .value {
            font-weight: 500;
        }
        
        .status-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .prescription-display {
            padding: 1.5rem;
            text-align: center;
        }
        
        .prescription-display img {
            max-width: 100%;
            max-height: 500px;
        }
        
        tfoot th {
            font-weight: 600;
        }
        
        .text-right {
            text-align: right;
        }
        
        @media (max-width: 768px) {
            .order-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
