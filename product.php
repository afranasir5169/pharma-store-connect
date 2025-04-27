
<?php
require_once 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('shop.php');
}

$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    showAlert('Product not found.', 'danger');
    redirect('shop.php');
}

$product = $result->fetch_assoc();

// Handle add to cart
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isLoggedIn()) {
        showAlert('Please login to add items to cart.', 'danger');
        redirect('login.php');
    }
    
    $quantity = $_POST['quantity'] ?? 1;
    
    // If prescription is required, check for upload
    if ($product['requires_prescription'] == 1) {
        if (!isset($_FILES['prescription']) || $_FILES['prescription']['error'] != 0) {
            $error = 'This product requires a prescription. Please upload one.';
        } else {
            // Handle prescription upload
            $target_dir = "uploads/prescriptions/";
            
            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['prescription']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'pdf');
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['prescription']['tmp_name'], $target_file)) {
                    $prescription_path = 'uploads/prescriptions/' . $new_filename;
                    
                    // Create order (simplified - in a real app you'd have a cart system)
                    $user_id = $_SESSION['user_id'];
                    $total = $product['price'] * $quantity;
                    
                    // Insert order
                    $order_sql = "INSERT INTO orders (user_id, total, prescription_image) VALUES (?, ?, ?)";
                    $order_stmt = $conn->prepare($order_sql);
                    $order_stmt->bind_param("ids", $user_id, $total, $prescription_path);
                    
                    if ($order_stmt->execute()) {
                        $order_id = $conn->insert_id;
                        
                        // Insert order item
                        $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                        $item_stmt = $conn->prepare($item_sql);
                        $item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $product['price']);
                        
                        if ($item_stmt->execute()) {
                            $success = 'Order placed successfully! Your prescription will be reviewed.';
                        } else {
                            $error = 'Failed to add item to order.';
                        }
                    } else {
                        $error = 'Failed to create order.';
                    }
                } else {
                    $error = 'Failed to upload prescription.';
                }
            } else {
                $error = 'Invalid file type. Only JPG, JPEG, PNG and PDF are allowed.';
            }
        }
    } else {
        // No prescription required, just create order
        $user_id = $_SESSION['user_id'];
        $total = $product['price'] * $quantity;
        
        // Insert order
        $order_sql = "INSERT INTO orders (user_id, total) VALUES (?, ?)";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("id", $user_id, $total);
        
        if ($order_stmt->execute()) {
            $order_id = $conn->insert_id;
            
            // Insert order item
            $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $item_stmt = $conn->prepare($item_sql);
            $item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $product['price']);
            
            if ($item_stmt->execute()) {
                $success = 'Order placed successfully!';
            } else {
                $error = 'Failed to add item to order.';
            }
        } else {
            $error = 'Failed to create order.';
        }
    }
}

// Check if product is in user's wishlist
$in_wishlist = false;
if (isLoggedIn()) {
    $wishlist_sql = "SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?";
    $wishlist_stmt = $conn->prepare($wishlist_sql);
    $wishlist_stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
    $wishlist_stmt->execute();
    $wishlist_result = $wishlist_stmt->get_result();
    $in_wishlist = ($wishlist_result->num_rows > 0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - PharmaCare</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="product-detail-section">
            <div class="container">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <div class="product-detail">
                    <div class="product-detail-image">
                        <img src="<?php echo !empty($product['image']) ? $product['image'] : 'images/placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php if ($product['requires_prescription']): ?>
                            <span class="prescription-badge">Prescription Required</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-detail-content">
                        <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                        <p class="product-detail-price">$<?php echo number_format($product['price'], 2); ?></p>
                        <div class="product-detail-category">Category: <?php echo htmlspecialchars($product['category']); ?></div>
                        
                        <div class="product-detail-description">
                            <h3>Description</h3>
                            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                        </div>
                        
                        <div class="product-detail-actions">
                            <form method="post" enctype="multipart/form-data">
                                <div class="quantity-input">
                                    <label for="quantity">Quantity:</label>
                                    <input type="number" id="quantity" name="quantity" min="1" value="1" max="<?php echo $product['stock']; ?>">
                                </div>
                                
                                <?php if ($product['requires_prescription']): ?>
                                    <div class="prescription-upload">
                                        <label for="prescription">Upload Prescription (required):</label>
                                        <input type="file" id="prescription" name="prescription" accept=".jpg, .jpeg, .png, .pdf" required>
                                        <p class="upload-note">Accepted formats: JPG, PNG, PDF</p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="action-buttons">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                    
                                    <?php if (isLoggedIn()): ?>
                                        <a href="wishlist.php?action=<?php echo $in_wishlist ? 'remove' : 'add'; ?>&id=<?php echo $product['id']; ?>" class="btn <?php echo $in_wishlist ? 'btn-primary' : 'btn-ghost'; ?>">
                                            <?php echo $in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                        
                        <?php if ($product['stock'] < 5 && $product['stock'] > 0): ?>
                            <div class="stock-warning">Only <?php echo $product['stock']; ?> items left in stock!</div>
                        <?php elseif ($product['stock'] == 0): ?>
                            <div class="out-of-stock">Out of Stock</div>
                        <?php else: ?>
                            <div class="in-stock">In Stock</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <style>
        .product-detail-section {
            padding: 3rem 0;
        }
        
        .product-detail {
            display: flex;
            gap: 2rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .product-detail-image {
            flex: 1;
            position: relative;
            max-width: 500px;
        }
        
        .product-detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-detail-content {
            flex: 1;
            padding: 2rem;
        }
        
        .product-detail-title {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .product-detail-price {
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .product-detail-category {
            color: var(--gray-color);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        
        .product-detail-description {
            margin-bottom: 2rem;
        }
        
        .product-detail-description h3 {
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .product-detail-actions {
            margin-bottom: 1.5rem;
        }
        
        .quantity-input {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .quantity-input input {
            width: 80px;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
        
        .prescription-upload {
            margin-bottom: 1.5rem;
        }
        
        .prescription-upload label {
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .upload-note {
            font-size: 0.8rem;
            color: var(--gray-color);
            margin-top: 0.25rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .prescription-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background-color: var(--danger-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .stock-warning {
            margin-top: 1rem;
            padding: 0.5rem;
            background-color: rgba(244, 162, 97, 0.1);
            color: var(--warning-color);
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .out-of-stock {
            margin-top: 1rem;
            padding: 0.5rem;
            background-color: rgba(230, 57, 70, 0.1);
            color: var(--danger-color);
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .in-stock {
            margin-top: 1rem;
            padding: 0.5rem;
            background-color: rgba(42, 157, 143, 0.1);
            color: var(--success-color);
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        @media (max-width: 991px) {
            .product-detail {
                flex-direction: column;
            }
            
            .product-detail-image {
                max-width: 100%;
            }
        }
    </style>
    
    <script src="js/main.js"></script>
</body>
</html>
