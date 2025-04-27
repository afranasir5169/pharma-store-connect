
<header class="admin-top-header">
    <div class="admin-top-header-left">
        <button id="toggle-sidebar" class="btn btn-ghost btn-sm">
            ☰
        </button>
    </div>
    
    <div class="admin-top-header-right">
        <a href="../index.php" target="_blank" class="btn btn-ghost btn-sm">Visit Site</a>
        
        <div class="admin-user-dropdown">
            <span><?php echo $_SESSION['user_name']; ?></span>
        </div>
    </div>
</header>
