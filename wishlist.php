
<?php
require_once 'config.php';

if (!isLoggedIn()) {
    showAlert('Please login to view your wishlist.', 'danger');
    redirect('login.php');
}

// Handle add to wishlist
if (isset($_GET['action']) && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    if ($_GET['action'] == 'add') {
        // Check if already in wishlist
        $check_sql = "SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows == 0) {
            $sql = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $product_id);
            
            if ($stmt->execute()) {
                showAlert('Product added to wishlist!', 'success');
            } else {
                showAlert('Failed to add product to wishlist.', 'danger');
            }
        }
    } elseif ($_GET['action'] == 'remove') {
        $sql = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        
        if ($stmt->execute()) {
            showAlert('Product removed from wishlist!', 'success');
        } else {
            showAlert('Failed to remove product from wishlist.', 'danger');
        }
    }
    
    // Redirect to remove query params
    redirect('wishlist.php');
}

// Get user's wishlist
$user_id = $_SESSION['user_id'];
$sql = "SELECT p.* FROM products p 
        JOIN wishlist w ON p.id = w.product_id 
        WHERE w.user_id = ? 
        ORDER BY w.added_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wishlist = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $wishlist[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - PharmaCare</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="wishlist-section">
            <div class="container">
                <h1 class="page-title">My Wishlist</h1>
                
                <?php displayAlert(); ?>
                
                <?php if (count($wishlist) > 0): ?>
                    <div class="wishlist-grid">
                        <?php foreach ($wishlist as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?php echo !empty($product['image']) ? $product['image'] : 'images/placeholder.jpg'; ?>" alt="<?php echo $product['name']; ?>">
                                    <button class="remove-wishlist-btn" onclick="window.location='wishlist.php?action=remove&id=<?php echo $product['id']; ?>'">×</button>
                                    <?php if ($product['requires_prescription']): ?>
                                        <span class="prescription-badge">Rx</span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                                    <div class="wishlist-actions">
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-wishlist">
                        <p>Your wishlist is empty.</p>
                        <a href="shop.php" class="btn btn-primary">Browse Products</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <style>
        .wishlist-section {
            padding: 3rem 0;
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            color: var(--dark-color);
        }
        
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .remove-wishlist-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .remove-wishlist-btn:hover {
            opacity: 1;
            color: var(--danger-color);
        }
        
        .wishlist-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
        }
        
        .empty-wishlist {
            text-align: center;
            padding: 3rem 0;
        }
        
        .empty-wishlist p {
            margin-bottom: 1rem;
            color: var(--gray-color);
        }
        
        .prescription-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: var(--danger-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
        }
    </style>
    
    <script src="js/main.js"></script>
</body>
</html>
