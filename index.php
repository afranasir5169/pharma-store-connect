
<?php
require_once 'config.php';

// Fetch featured products
$sql = "SELECT * FROM products ORDER BY id DESC LIMIT 8";
$result = $conn->query($sql);
$featuredProducts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featuredProducts[] = $row;
    }
}

// Categories for display
$categories = [
    [
        'title' => 'Prescription Medicines',
        'subtitle' => 'Doctor\'s order',
        'price' => '$9.99',
        'image' => 'images/category-prescription.jpg',
        'link' => 'shop.php?category=prescription'
    ],
    [
        'title' => 'Health Supplements',
        'subtitle' => 'Stay healthy',
        'price' => '$5.99',
        'image' => 'images/category-supplements.jpg',
        'link' => 'shop.php?category=supplements'
    ],
    [
        'title' => 'Personal Care',
        'subtitle' => 'Self care',
        'price' => '$3.99',
        'image' => 'images/category-personal.jpg',
        'link' => 'shop.php?category=personal'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaCare - Your Health Partner</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Your Health is Our Priority</h1>
                <p>Quality healthcare products delivered to your doorstep</p>
                <a href="shop.php" class="btn btn-primary">Shop Now</a>
            </div>
        </section>
        
        <!-- Categories Section -->
        <section class="categories">
            <div class="container">
                <h2 class="section-title">Shop by Category</h2>
                <div class="category-grid">
                    <?php foreach ($categories as $category): ?>
                        <div class="category-card" style="background-image: url('<?php echo $category['image']; ?>')">
                            <div class="category-content">
                                <p class="category-subtitle"><?php echo $category['subtitle']; ?></p>
                                <h3 class="category-title"><?php echo $category['title']; ?></h3>
                                <p class="category-price">Starting with <?php echo $category['price']; ?></p>
                                <a href="<?php echo $category['link']; ?>" class="btn btn-light">SHOP NOW</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <!-- Featured Products Section -->
        <section class="featured-products">
            <div class="container">
                <h2 class="section-title">Featured Products</h2>
                <div class="product-grid">
                    <?php if (count($featuredProducts) > 0): ?>
                        <?php foreach ($featuredProducts as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?php echo !empty($product['image']) ? $product['image'] : 'images/placeholder.jpg'; ?>" alt="<?php echo $product['name']; ?>">
                                    <?php if (isLoggedIn()): ?>
                                        <button class="wishlist-btn" data-id="<?php echo $product['id']; ?>">❤</button>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name"><?php echo $product['name']; ?></h3>
                                    <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No products available at this time.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        
        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <div class="feature-grid">
                    <div class="feature">
                        <div class="feature-icon">🚚</div>
                        <h3>Free Delivery</h3>
                        <p>On all orders over $50</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🔒</div>
                        <h3>Secure Payment</h3>
                        <p>100% secure payment</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🔄</div>
                        <h3>Easy Returns</h3>
                        <p>10 day return policy</p>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">💬</div>
                        <h3>24/7 Support</h3>
                        <p>Dedicated support</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
</body>
</html>
