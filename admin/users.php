
<?php
require_once '../config.php';

// Ensure user is logged in and is an admin
if (!isAdmin()) {
    showAlert('Access denied. Admin privileges required.', 'danger');
    redirect('../login.php');
}

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    
    // Prevent admin from deleting themselves
    if ($user_id == $_SESSION['user_id']) {
        showAlert('You cannot delete your own account.', 'danger');
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            showAlert('User deleted successfully!', 'success');
        } else {
            showAlert('Failed to delete user.', 'danger');
        }
    }
}

// Get all users
$sql = "SELECT id, name, email, join_date, role FROM users WHERE role = 'user' ORDER BY id DESC";
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Count orders for this user
        $order_count_query = "SELECT COUNT(*) as count FROM orders WHERE user_id = " . $row['id'];
        $order_count_result = $conn->query($order_count_query);
        $order_count = $order_count_result->fetch_assoc()['count'];
        
        $row['orders'] = $order_count;
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - PharmaCare Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Manage Users</h1>
            </div>
            
            <?php displayAlert(); ?>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Join Date</th>
                            <th class="text-center">Orders</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>#<?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['join_date'])); ?></td>
                                    <td class="text-center"><?php echo $user['orders']; ?></td>
                                    <td class="text-right">
                                        <a href="user_orders.php?user_id=<?php echo $user['id']; ?>" class="btn btn-ghost btn-sm">View Orders</a>
                                        <form method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" name="delete_user" class="btn btn-sm" style="background-color: var(--danger-color);">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script src="../js/admin.js"></script>
</body>
</html>
