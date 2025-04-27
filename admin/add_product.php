
<?php
require_once '../config.php';

// Ensure user is logged in and is an admin
if (!isAdmin()) {
    showAlert('Access denied. Admin privileges required.', 'danger');
    redirect('../login.php');
}

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
        $image_path = '';
        
        // Handle image upload
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
                    $image_path = 'uploads/' . $new_filename;
                } else {
                    $error = 'Failed to upload image.';
                }
            } else {
                $error = 'Invalid file type. Only JPG, JPEG, PNG and GIF are allowed.';
            }
        }
        
        if (empty($error)) {
            $sql = "INSERT INTO products (name, description, price, image, category, stock, requires_prescription) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssis", $name, $description, $price, $image_path, $category, $stock, $requires_prescription);
            
            if ($stmt->execute()) {
                showAlert('Product added successfully!', 'success');
                redirect('products.php');
            } else {
                $error = 'Failed to add product: ' . $conn->error;
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
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form class="admin-form" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <option value="Prescription Medicines">Prescription Medicines</option>
                                <option value="Over-the-Counter">Over-the-Counter</option>
                                <option value="Health Supplements">Health Supplements</option>
                                <option value="Personal Care">Personal Care</option>
                                <option value="First Aid">First Aid</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="stock">Stock Quantity</label>
                            <input type="number" id="stock" name="stock" min="0" value="0">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image">
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="requires_prescription">
                        Requires Prescription
                    </label>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </main>
    </div>
    
    <script src="../js/admin.js"></script>
</body>
</html>
