
<?php
require_once 'config.php';

// Filters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'latest';

// Build query
$sql = "SELECT * FROM products WHERE 1=1";

if (!empty($category)) {
    $sql .= " AND category = '$category'";
}

if (!empty($search)) {
    $sql .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
}

// Sorting
switch ($sort) {
    case 'price_low':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY price DESC";
        break;
    case 'popularity':
        // We would need a view or order count field for this, using ID as a placeholder
        $sql .= " ORDER BY id DESC";
        break;
    default:
        $sql .= " ORDER BY id DESC";
        break;
}

$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Get categories for sidebar
$categories_query = "SELECT category, COUNT(*) as count FROM products GROUP BY category ORDER BY count DESC";
$categories_result = $conn->query($categories_query);
$categories = [];

if ($categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - PharmaCare</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="shop-section">
            <div class="container">
                <h1 class="page-title">Browse Products</h1>
                
                <div class="shop-layout">
                    <aside class="shop-sidebar">
                        <div class="sidebar-widget">
                            <h3>Categories</h3>
                            <ul class="category-list">
                                <li>
                                    <a href="shop.php" class="<?php echo empty($category) ? 'active' : ''; ?>">
                                        All Products
                                    </a>
                                </li>
                                <?php foreach ($categories as $cat): ?>
                                    <li>
                                        <a href="shop.php?category=<?php echo urlencode($cat['category']); ?>" 
                                           class="<?php echo $category == $cat['category'] ? 'active' : ''; ?>">
                                            <?php echo htmlspecialchars($cat['category']); ?> (<?php echo $cat['count']; ?>)
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="sidebar-widget">
                            <h3>Filter by Price</h3>
                            <div class="price-links">
                                <a href="shop.php?category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>&sort=price_low">Price Low to High</a>
                                <a href="shop.php?category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>&sort=price_high">Price High to Low</a>
                            </div>
                        </div>
                    </aside>
                    
                    <div class="shop-content">
                        <div class="shop-header">
                            <div class="shop-results">
                                <?php echo count($products); ?> products found
                                <?php if (!empty($search)): ?>
                                    for "<?php echo htmlspecialchars($search); ?>"
                                <?php endif; ?>
                                <?php if (!empty($category)): ?>
                                    in <?php echo htmlspecialchars($category); ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="shop-sort">
                                <span>Sort by:</span>
                                <select onchange="window.location = 'shop.php?category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>&sort=' + this.value">
                                    <option value="latest" <?php echo $sort == 'latest' ? 'selected' : ''; ?>>Latest</option>
                                    <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                    <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                    <option value="popularity" <?php echo $sort == 'popularity' ? 'selected' : ''; ?>>Popularity</option>
                                </select>
                            </div>
                        </div>
                        
                        <?php if (count($products) > 0): ?>
                            <div class="product-grid">
                                <?php foreach ($products as $product): ?>
                                    <div class="product-card">
                                        <div class="product-image">
                                            <img src="<?php echo !empty($product['image']) ? $product['image'] : 'images/placeholder.jpg'; ?>" alt="<?php echo $product['name']; ?>">
                                            <?php if (isLoggedIn()): ?>
                                                <button class="wishlist-btn" data-id="<?php echo $product['id']; ?>">❤</button>
                                            <?php endif; ?>
                                            <?php if ($product['requires_prescription']): ?>
                                                <span class="prescription-badge">Rx</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-info">
                                            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                            <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-products">
                                <p>No products found. Try a different search or browse all products.</p>
                                <a href="shop.php" class="btn btn-primary">Browse All Products</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <style>
        .page-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            color: var(--dark-color);
        }
        
        .shop-section {
            padding: 2rem 0 4rem;
        }
        
        .shop-layout {
            display: flex;
            gap: 2rem;
        }
        
        .shop-sidebar {
            width: 250px;
            flex-shrink: 0;
        }
        
        .shop-content {
            flex: 1;
        }
        
        .sidebar-widget {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-widget h3 {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
        }
        
        .category-list {
            list-style: none;
            padding: 0;
        }
        
        .category-list li {
            margin-bottom: 0.5rem;
        }
        
        .category-list a {
            display: block;
            padding: 0.5rem 0;
            color: var(--text-color);
            transition: color 0.3s;
        }
        
        .category-list a:hover, .category-list a.active {
            color: var(--primary-color);
        }
        
        .price-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .price-links a {
            padding: 0.5rem 0;
            color: var(--text-color);
        }
        
        .shop-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .shop-sort {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .shop-sort select {
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
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
        
        .no-products {
            text-align: center;
            padding: 3rem 0;
        }
        
        .no-products p {
            margin-bottom: 1rem;
            color: var(--gray-color);
        }
        
        @media (max-width: 991px) {
            .shop-layout {
                flex-direction: column;
            }
            
            .shop-sidebar {
                width: 100%;
            }
        }
    </style>
    
    <script src="js/main.js"></script>
</body>
</html>
