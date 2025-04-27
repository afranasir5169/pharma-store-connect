
<header class="header">
    <div class="container">
        <div class="header-top">
            <div class="logo">
                <a href="index.php">PharmaCare</a>
            </div>
            <div class="search-bar">
                <form action="shop.php" method="get">
                    <input type="text" name="search" placeholder="Search products...">
                    <button type="submit">🔍</button>
                </form>
            </div>
            <div class="header-actions">
                <?php if (isLoggedIn()): ?>
                    <a href="wishlist.php" class="action-link">❤ Wishlist</a>
                    <a href="account.php" class="action-link">👤 Account</a>
                    <a href="logout.php" class="action-link">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="action-link">Login</a>
                    <a href="register.php" class="action-link">Register</a>
                <?php endif; ?>
            </div>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isAdmin()): ?>
                    <li><a href="admin/index.php">Admin Panel</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php displayAlert(); ?>
</header>
