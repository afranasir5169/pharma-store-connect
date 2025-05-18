
<?php
require_once '../config.php';

// Ensure user is logged in and is an admin
if (!isAdmin()) {
    showAlert('Access denied. Admin privileges required.', 'danger');
    redirect('../login.php');
}

// Handle category operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new category
    if (isset($_POST['add_category'])) {
        $category_name = trim($_POST['category_name']);
        
        if (empty($category_name)) {
            showAlert('Category name cannot be empty.', 'danger');
        } else {
            // Check if category exists
            $check_sql = "SELECT * FROM categories WHERE name = ?";
            
            // Create categories table if it doesn't exist
            $conn->query("CREATE TABLE IF NOT EXISTS categories (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL UNIQUE,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $category_name);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                showAlert('This category already exists.', 'danger');
            } else {
                $description = $_POST['category_description'] ?? '';
                
                $insert_sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("ss", $category_name, $description);
                
                if ($insert_stmt->execute()) {
                    showAlert('Category added successfully!', 'success');
                } else {
                    showAlert('Failed to add category.', 'danger');
                }
            }
        }
    }
    
    // Delete category
    if (isset($_POST['delete_category'])) {
        $category_id = $_POST['category_id'];
        
        $delete_sql = "DELETE FROM categories WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $category_id);
        
        if ($delete_stmt->execute()) {
            showAlert('Category deleted successfully!', 'success');
        } else {
            showAlert('Failed to delete category.', 'danger');
        }
    }
    
    // Edit category
    if (isset($_POST['edit_category'])) {
        $category_id = $_POST['category_id'];
        $category_name = trim($_POST['category_name']);
        $description = $_POST['category_description'] ?? '';
        
        if (empty($category_name)) {
            showAlert('Category name cannot be empty.', 'danger');
        } else {
            $update_sql = "UPDATE categories SET name = ?, description = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssi", $category_name, $description, $category_id);
            
            if ($update_stmt->execute()) {
                showAlert('Category updated successfully!', 'success');
            } else {
                showAlert('Failed to update category.', 'danger');
            }
        }
    }
}

// Get all categories
// Create categories table if it doesn't exist first
$conn->query("CREATE TABLE IF NOT EXISTS categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$sql = "SELECT * FROM categories ORDER BY name ASC";
$result = $conn->query($sql);
$categories = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Get category for editing
$edit_category = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_sql = "SELECT * FROM categories WHERE id = ?";
    $edit_stmt = $conn->prepare($edit_sql);
    $edit_stmt->bind_param("i", $edit_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    
    if ($edit_result->num_rows > 0) {
        $edit_category = $edit_result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - PharmaCare Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Manage Categories</h1>
            </div>
            
            <?php displayAlert(); ?>
            
            <div class="admin-grid">
                <div class="admin-form-card">
                    <h2><?php echo $edit_category ? 'Edit Category' : 'Add New Category'; ?></h2>
                    <form method="post">
                        <?php if ($edit_category): ?>
                            <input type="hidden" name="category_id" value="<?php echo $edit_category['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="category_name">Category Name</label>
                            <input type="text" id="category_name" name="category_name" value="<?php echo $edit_category ? htmlspecialchars($edit_category['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category_description">Description (Optional)</label>
                            <textarea id="category_description" name="category_description" rows="3"><?php echo $edit_category ? htmlspecialchars($edit_category['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-buttons">
                            <?php if ($edit_category): ?>
                                <button type="submit" name="edit_category" class="btn btn-primary">Update Category</button>
                                <a href="categories.php" class="btn btn-ghost">Cancel</a>
                            <?php else: ?>
                                <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                
                <div class="admin-table-card">
                    <h2>Categories List</h2>
                    
                    <?php if (count($categories) > 0): ?>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td><?php echo $category['id']; ?></td>
                                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                                            <td><?php echo htmlspecialchars($category['description'] ?? ''); ?></td>
                                            <td>
                                                <a href="categories.php?edit=<?php echo $category['id']; ?>" class="btn btn-sm btn-ghost">Edit</a>
                                                <form method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this category? This may affect products associated with it.');">
                                                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                                    <button type="submit" name="delete_category" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="no-data">No categories found. Add your first category above.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <style>
        .admin-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1.5rem;
        }
        
        .admin-form-card,
        .admin-table-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }
        
        .admin-form-card h2,
        .admin-table-card h2 {
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .form-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .no-data {
            color: var(--gray-color);
            text-align: center;
            padding: 2rem 0;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        @media (max-width: 991px) {
            .admin-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <script src="../js/admin.js"></script>
</body>
</html>
