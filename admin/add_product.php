
<?php
require_once '../config.php';

// Ensure user is logged in and is an admin
if (!isAdmin()) {
    showAlert('Access denied. Admin privileges required.', 'danger');
    redirect('../login.php');
}

// Get categories
$categories_sql = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);
$categories = [];

if ($categories_result && $categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $stock = intval($_POST['stock']);
    $requires_prescription = isset($_POST['requires_prescription']) ? 1 : 0;
    
    // Validate required fields
    if (empty($name) || $price <= 0) {
        showAlert('Name and price are required. Price must be greater than zero.', 'danger');
    } else {
        // Handle image upload
        $image_path = ''; // Default to empty (placeholder will be used)
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../uploads/products/";
            
            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_path = 'uploads/products/' . $new_filename;
                }
            }
        }
        
        // Insert product
        $insert_sql = "INSERT INTO products (name, description, price, image, category, stock, requires_prescription) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssdssii", $name, $description, $price, $image_path, $category, $stock, $requires_prescription);
        
        if ($insert_stmt->execute()) {
            showAlert('Product added successfully!', 'success');
            redirect('products.php');
        } else {
            showAlert('Failed to add product.', 'danger');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - PharmaCare Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Add New Product</h1>
                <div class="admin-header-actions">
                    <a href="products.php" class="btn btn-ghost">Back to Products</a>
                </div>
            </div>
            
            <?php displayAlert(); ?>
            
            <div class="admin-form-card">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price ($)</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <?php if (count($categories) > 0): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category['name']); ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="General">General</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" id="stock" name="stock" min="0" value="0">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="6"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="requires_prescription" name="requires_prescription">
                        <label for="requires_prescription">Requires Prescription</label>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">Add Product</button>
                        <a href="products.php" class="btn btn-ghost">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script src="../js/admin.js"></script>
</body>
</html>
