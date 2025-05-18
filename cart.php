
<?php
require_once 'config.php';

// Redirect to login if not logged in
if (!isLoggedIn()) {
    showAlert('Please login to access your cart.', 'danger');
    redirect('login.php');
}

// Get user's ID
$user_id = $_SESSION['user_id'];

// Handle cart actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $product_id = $_GET['id'];
    
    if ($action === 'add') {
        // Check if product exists
        $product_sql = "SELECT * FROM products WHERE id = ?";
        $product_stmt = $conn->prepare($product_sql);
        $product_stmt->bind_param("i", $product_id);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        
        if ($product_result->num_rows > 0) {
            $product = $product_result->fetch_assoc();
            
            // For prescription products, redirect to product page
            if ($product['requires_prescription']) {
                showAlert('This product requires a prescription. Please upload from product page.', 'info');
                redirect("product.php?id=$product_id");
            }
            
            // Check if user already has an open draft order
            $order_sql = "SELECT id FROM orders WHERE user_id = ? AND status = 'Draft'";
            $order_stmt = $conn->prepare($order_sql);
            $order_stmt->bind_param("i", $user_id);
            $order_stmt->execute();
            $order_result = $order_stmt->get_result();
            
            if ($order_result->num_rows > 0) {
                // User has an existing draft order
                $order = $order_result->fetch_assoc();
                $order_id = $order['id'];
                
                // Check if this product is already in cart
                $item_sql = "SELECT * FROM order_items WHERE order_id = ? AND product_id = ?";
                $item_stmt = $conn->prepare($item_sql);
                $item_stmt->bind_param("ii", $order_id, $product_id);
                $item_stmt->execute();
                $item_result = $item_stmt->get_result();
                
                if ($item_result->num_rows > 0) {
                    // Product already in cart, update quantity
                    $item = $item_result->fetch_assoc();
                    $new_quantity = $item['quantity'] + 1;
                    
                    $update_sql = "UPDATE order_items SET quantity = ? WHERE id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ii", $new_quantity, $item['id']);
                    
                    if ($update_stmt->execute()) {
                        // Update order total
                        updateOrderTotal($conn, $order_id);
                        showAlert('Cart updated successfully.', 'success');
                    } else {
                        showAlert('Failed to update cart.', 'danger');
                    }
                } else {
                    // Add new product to cart
                    $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, 1, ?)";
                    $item_stmt = $conn->prepare($item_sql);
                    $item_stmt->bind_param("iid", $order_id, $product_id, $product['price']);
                    
                    if ($item_stmt->execute()) {
                        // Update order total
                        updateOrderTotal($conn, $order_id);
                        showAlert('Product added to cart.', 'success');
                    } else {
                        showAlert('Failed to add product to cart.', 'danger');
                    }
                }
            } else {
                // Create new draft order
                $order_sql = "INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'Draft')";
                $order_stmt = $conn->prepare($order_sql);
                $order_stmt->bind_param("id", $user_id, $product['price']);
                
                if ($order_stmt->execute()) {
                    $order_id = $conn->insert_id;
                    
                    // Add product to cart
                    $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, 1, ?)";
                    $item_stmt = $conn->prepare($item_sql);
                    $item_stmt->bind_param("iid", $order_id, $product_id, $product['price']);
                    
                    if ($item_stmt->execute()) {
                        showAlert('Product added to cart.', 'success');
                    } else {
                        showAlert('Failed to add product to cart.', 'danger');
                    }
                } else {
                    showAlert('Failed to create cart.', 'danger');
                }
            }
        } else {
            showAlert('Product not found.', 'danger');
        }
    } elseif ($action === 'remove') {
        // Remove item from cart
        $order_sql = "SELECT o.id FROM orders o 
                      JOIN order_items oi ON o.id = oi.order_id 
                      WHERE o.user_id = ? AND o.status = 'Draft' AND oi.product_id = ?";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("ii", $user_id, $product_id);
        $order_stmt->execute();
        $order_result = $order_stmt->get_result();
        
        if ($order_result->num_rows > 0) {
            $order = $order_result->fetch_assoc();
            $order_id = $order['id'];
            
            $delete_sql = "DELETE FROM order_items WHERE order_id = ? AND product_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ii", $order_id, $product_id);
            
            if ($delete_stmt->execute()) {
                // Update order total
                updateOrderTotal($conn, $order_id);
                showAlert('Product removed from cart.', 'success');
            } else {
                showAlert('Failed to remove product from cart.', 'danger');
            }
        }
    } elseif ($action === 'update') {
        $quantity = $_POST['quantity'] ?? 1;
        
        if ($quantity <= 0) {
            // Remove item if quantity is 0 or less
            redirect("cart.php?action=remove&id=$product_id");
            exit;
        }
        
        // Update item quantity
        $order_sql = "SELECT o.id FROM orders o 
                      JOIN order_items oi ON o.id = oi.order_id 
                      WHERE o.user_id = ? AND o.status = 'Draft' AND oi.product_id = ?";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("ii", $user_id, $product_id);
        $order_stmt->execute();
        $order_result = $order_stmt->get_result();
        
        if ($order_result->num_rows > 0) {
            $order = $order_result->fetch_assoc();
            $order_id = $order['id'];
            
            $update_sql = "UPDATE order_items SET quantity = ? WHERE order_id = ? AND product_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iii", $quantity, $order_id, $product_id);
            
            if ($update_stmt->execute()) {
                // Update order total
                updateOrderTotal($conn, $order_id);
                showAlert('Cart updated successfully.', 'success');
            } else {
                showAlert('Failed to update cart.', 'danger');
            }
        }
    }
    
    // Redirect back to cart
    redirect('cart.php');
}

// Function to update order total
function updateOrderTotal($conn, $order_id) {
    $total_sql = "SELECT SUM(quantity * price) AS total FROM order_items WHERE order_id = ?";
    $total_stmt = $conn->prepare($total_sql);
    $total_stmt->bind_param("i", $order_id);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total = $total_result->fetch_assoc()['total'] ?? 0;
    
    $update_sql = "UPDATE orders SET total = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("di", $total, $order_id);
    $update_stmt->execute();
    
    // Check if cart is empty, delete the order if it is
    $items_sql = "SELECT COUNT(*) AS count FROM order_items WHERE order_id = ?";
    $items_stmt = $conn->prepare($items_sql);
    $items_stmt->bind_param("i", $order_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();
    $items_count = $items_result->fetch_assoc()['count'] ?? 0;
    
    if ($items_count == 0) {
        $delete_sql = "DELETE FROM orders WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $order_id);
        $delete_stmt->execute();
    }
}

// Process checkout
if (isset($_POST['checkout'])) {
    $order_sql = "SELECT id FROM orders WHERE user_id = ? AND status = 'Draft'";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("i", $user_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    
    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
        $order_id = $order['id'];
        
        // Check for prescription items
        $rx_sql = "SELECT p.id, p.name FROM products p 
                   JOIN order_items oi ON p.id = oi.product_id 
                   WHERE oi.order_id = ? AND p.requires_prescription = 1";
        $rx_stmt = $conn->prepare($rx_sql);
        $rx_stmt->bind_param("i", $order_id);
        $rx_stmt->execute();
        $rx_result = $rx_stmt->get_result();
        
        if ($rx_result->num_rows > 0) {
            // Has prescription items, show message
            $rx_products = [];
            while ($rx = $rx_result->fetch_assoc()) {
                $rx_products[] = $rx['name'];
            }
            
            showAlert('Some products require a prescription. Please purchase them individually from the product page: ' . implode(', ', $rx_products), 'danger');
        } else {
            // Update order status
            $update_sql = "UPDATE orders SET status = 'Processing' WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $order_id);
            
            if ($update_stmt->execute()) {
                showAlert('Order placed successfully! Your order is now being processed.', 'success');
            } else {
                showAlert('Failed to place order.', 'danger');
            }
        }
    } else {
        showAlert('Your cart is empty.', 'danger');
    }
}

// Get cart items
$cart_items = [];
$cart_total = 0;

$order_sql = "SELECT o.id, o.total FROM orders o WHERE o.user_id = ? AND o.status = 'Draft'";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows > 0) {
    $order = $order_result->fetch_assoc();
    $order_id = $order['id'];
    $cart_total = $order['total'];
    
    $items_sql = "SELECT oi.*, p.name, p.image, p.requires_prescription 
                  FROM order_items oi 
                  JOIN products p ON oi.product_id = p.id 
                  WHERE oi.order_id = ?";
    $items_stmt = $conn->prepare($items_sql);
    $items_stmt->bind_param("i", $order_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();
    
    while ($item = $items_result->fetch_assoc()) {
        $cart_items[] = $item;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - PharmaCare</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="cart-section">
            <div class="container">
                <h1 class="page-title">Your Shopping Cart</h1>
                
                <?php displayAlert(); ?>
                
                <?php if (count($cart_items) > 0): ?>
                    <div class="cart-layout">
                        <div class="cart-items">
                            <div class="cart-header">
                                <div class="cart-product">Product</div>
                                <div class="cart-price">Price</div>
                                <div class="cart-quantity">Quantity</div>
                                <div class="cart-subtotal">Subtotal</div>
                                <div class="cart-actions">Actions</div>
                            </div>
                            
                            <?php foreach ($cart_items as $item): ?>
                                <div class="cart-item">
                                    <div class="cart-product">
                                        <div class="cart-product-image">
                                            <?php 
                                            // Fixed image display
                                            $imagePath = !empty($item['image']) ? $item['image'] : 'images/placeholder.jpg';
                                            // Ensure the image exists
                                            if (!file_exists($imagePath) && !filter_var($imagePath, FILTER_VALIDATE_URL)) {
                                                $imagePath = 'images/placeholder.jpg';
                                            }
                                            ?>
                                            <img src="<?php echo $imagePath; ?>" alt="<?php echo $item['name']; ?>">
                                        </div>
                                        <div class="cart-product-info">
                                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                            <?php if ($item['requires_prescription']): ?>
                                                <span class="prescription-note">Requires prescription</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="cart-price">
                                        $<?php echo number_format($item['price'], 2); ?>
                                    </div>
                                    <div class="cart-quantity">
                                        <form method="post" action="cart.php?action=update&id=<?php echo $item['product_id']; ?>" class="quantity-form">
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input-sm">
                                            <button type="submit" class="btn btn-sm">Update</button>
                                        </form>
                                    </div>
                                    <div class="cart-subtotal">
                                        $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </div>
                                    <div class="cart-actions">
                                        <a href="cart.php?action=remove&id=<?php echo $item['product_id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="cart-summary">
                            <h2>Order Summary</h2>
                            
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span>$<?php echo number_format($cart_total, 2); ?></span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Shipping:</span>
                                <span>Free</span>
                            </div>
                            
                            <div class="summary-row total">
                                <span>Total:</span>
                                <span>$<?php echo number_format($cart_total, 2); ?></span>
                            </div>
                            
                            <form method="post">
                                <button type="submit" name="checkout" class="btn btn-primary btn-block">Proceed to Checkout</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-cart">
                        <p>Your cart is empty.</p>
                        <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <style>
        .cart-section {
            padding: 2rem 0 4rem;
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            color: var(--dark-color);
        }
        
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
        }
        
        .cart-header {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr 1fr;
            background-color: var(--light-color);
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
        }
        
        .cart-item {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr 1fr;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            align-items: center;
        }
        
        .cart-product {
            display: flex;
            align-items: center;
        }
        
        .cart-product-image {
            width: 80px;
            height: 80px;
            margin-right: 1rem;
        }
        
        .cart-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .cart-product-info h3 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        
        .prescription-note {
            font-size: 0.8rem;
            color: var(--danger-color);
        }
        
        .quantity-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .quantity-input-sm {
            width: 60px;
            padding: 0.25rem 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
        
        .cart-summary {
            background-color: var(--light-color);
            padding: 1.5rem;
            border-radius: 8px;
            position: sticky;
            top: 2rem;
        }
        
        .cart-summary h2 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            text-align: center;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .summary-row.total {
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .btn-block {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .empty-cart {
            text-align: center;
            padding: 5rem 0;
        }
        
        .empty-cart p {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        @media (max-width: 991px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }
            
            .cart-header {
                display: none;
            }
            
            .cart-item {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
                border: 1px solid var(--border-color);
                margin-bottom: 1rem;
                border-radius: 8px;
            }
            
            .cart-product {
                border-bottom: 1px solid var(--border-color);
                padding-bottom: 1rem;
            }
            
            .cart-price::before,
            .cart-quantity::before,
            .cart-subtotal::before {
                content: attr(data-title);
                font-weight: 600;
                margin-right: 0.5rem;
            }
            
            .cart-price,
            .cart-quantity,
            .cart-subtotal {
                display: flex;
                justify-content: space-between;
            }
            
            .cart-actions {
                display: flex;
                justify-content: flex-end;
            }
        }
    </style>
    
    <script src="js/main.js"></script>
</body>
</html>
