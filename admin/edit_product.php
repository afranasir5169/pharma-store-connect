
<?php
require_once '../config.php';

// Ensure user is logged in and is an admin
if (!isAdmin()) {
    showAlert('Access denied. Admin privileges required.', 'danger');
    redirect('../login.php');
}

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    showAlert('Product ID is required.', 'danger');
    redirect('products.php');
}

$product_id = $_GET['id'];

// Get product details
$product_sql = "SELECT * FROM products WHERE id = ?";
$product_stmt = $conn->prepare($product_sql);
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows == 0) {
    showAlert('Product not found.', 'danger');
    redirect('products.php');
}

$product = $product_result->fetch_assoc();

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
        $image_path = $product['image']; // Default to existing image
        
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
        
        // Update product
        $update_sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ?, category = ?, stock = ?, requires_prescription = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssdssiis", $name, $description, $price, $image_path, $category, $stock, $requires_prescription, $product_id);
        
        if ($update_stmt->execute()) {
            showAlert('Product updated successfully!', 'success');
            redirect('products.php');
        } else {
            showAlert('Failed to update product.', 'danger');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - PharmaCare Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Edit Product</h1>
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
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price ($)</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <?php if (count($categories) > 0): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category['name']); ?>" <?php echo $product['category'] == $category['name'] ? 'selected' : ''; ?>>
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
                            <input type="number" id="stock" name="stock" min="0" value="<?php echo htmlspecialchars($product['stock']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="6"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <?php if (!empty($product['image']) && file_exists('../' . $product['image'])): ?>
                            <div class="current-image">
                                <img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="Current product image" width="100">
                                <p>Current image</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="image" name="image" accept="image/*">
                        <p class="form-note">Leave empty to keep the current image.</p>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="requires_prescription" name="requires_prescription" <?php echo $product['requires_prescription'] ? 'checked' : ''; ?>>
                        <label for="requires_prescription">Requires Prescription</label>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="products.php" class="btn btn-ghost">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script src="../js/admin.js"></script>
</body>
</html>
