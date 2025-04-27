
<?php
require_once '../config.php';

// Ensure user is logged in and is an admin
if (!isAdmin()) {
    showAlert('Access denied. Admin privileges required.', 'danger');
    redirect('../login.php');
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $requires_prescription = isset($_POST['requires_prescription']) ? 1 : 0;
    
    if (empty($name) || empty($price)) {
        $error = 'Product name and price are required.';
    } else {
        $image_path = $product['image'];
        
        // Handle image upload if a new image is provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../uploads/";
            
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
                    // Delete old image if it exists
                    if (!empty($product['image']) && file_exists('../' . $product['image'])) {
                        unlink('../' . $product['image']);
                    }
                    
                    $image_path = 'uploads/' . $new_filename;
                } else {
                    $error = 'Failed to upload image.';
                }
            } else {
                $error = 'Invalid file type. Only JPG, JPEG, PNG and GIF are allowed.';
            }
        }
        
        if (empty($error)) {
            $sql = "UPDATE products 
                   SET name = ?, description = ?, price = ?, image = ?, category = ?, stock = ?, requires_prescription = ? 
                   WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssiis", $name, $description, $price, $image_path, $category, $stock, $requires_prescription, $product_id);
            
            if ($stmt->execute()) {
                showAlert('Product updated successfully!', 'success');
                
                // Refresh product data after update
                $product_stmt->execute();
                $product = $product_stmt->get_result()->fetch_assoc();
            } else {
                $error = 'Failed to update product: ' . $conn->error;
            }
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
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php displayAlert(); ?>
            
            <form class="admin-form" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <option value="Prescription Medicines" <?php echo $product['category'] == 'Prescription Medicines' ? 'selected' : ''; ?>>Prescription Medicines</option>
                                <option value="Over-the-Counter" <?php echo $product['category'] == 'Over-the-Counter' ? 'selected' : ''; ?>>Over-the-Counter</option>
                                <option value="Health Supplements" <?php echo $product['category'] == 'Health Supplements' ? 'selected' : ''; ?>>Health Supplements</option>
                                <option value="Personal Care" <?php echo $product['category'] == 'Personal Care' ? 'selected' : ''; ?>>Personal Care</option>
                                <option value="First Aid" <?php echo $product['category'] == 'First Aid' ? 'selected' : ''; ?>>First Aid</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="stock">Stock Quantity</label>
                            <input type="number" id="stock" name="stock" min="0" value="<?php echo $product['stock']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <?php if (!empty($product['image']) && file_exists('../' . $product['image'])): ?>
                        <div class="current-image">
                            <img src="../<?php echo $product['image']; ?>" alt="Current Image" width="150">
                            <p>Current image. Upload a new one to replace it.</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image">
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="requires_prescription" <?php echo $product['requires_prescription'] ? 'checked' : ''; ?>>
                        Requires Prescription
                    </label>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </main>
    </div>
    
    <script src="../js/admin.js"></script>
    
    <style>
        .current-image {
            margin-bottom: 1rem;
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .current-image img {
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .current-image p {
            font-size: 0.9rem;
            color: var(--gray-color);
            margin: 0;
        }
    </style>
</body>
</html>
