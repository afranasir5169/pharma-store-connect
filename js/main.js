
document.addEventListener('DOMContentLoaded', function() {
    // Add to wishlist functionality
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-id');
            
            // Simple AJAX request to add to wishlist
            fetch(`wishlist.php?action=add&id=${productId}`)
                .then(response => {
                    // Reload page to show the alert
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
    
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.getAttribute('data-id');
            
            // Simple AJAX request to add to cart
            fetch(`cart.php?action=add&id=${productId}`)
                .then(response => response.text())
                .then(data => {
                    showNotification('Product added to cart!', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to add product to cart', 'danger');
                });
        });
    });
    
    // Quantity buttons in product detail
    const quantityDecrease = document.querySelector('.quantity-decrease');
    const quantityIncrease = document.querySelector('.quantity-increase');
    const quantityInput = document.querySelector('.quantity-input');
    
    if (quantityDecrease && quantityIncrease && quantityInput) {
        quantityDecrease.addEventListener('click', function() {
            let currentVal = parseInt(quantityInput.value);
            if (currentVal > 1) {
                quantityInput.value = currentVal - 1;
            }
        });
        
        quantityIncrease.addEventListener('click', function() {
            let currentVal = parseInt(quantityInput.value);
            let max = parseInt(quantityInput.getAttribute('max') || 100);
            if (currentVal < max) {
                quantityInput.value = currentVal + 1;
            }
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
    
    // Custom notification function
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Add to body
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Hide after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 500);
        }, 3000);
    }
});

// Add smooth transition for alerts
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .alert {
            transition: opacity 0.5s ease-out;
        }
        
        /* Custom Notification Styles */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            color: white;
            transform: translateY(100px);
            opacity: 0;
            transition: transform 0.3s, opacity 0.3s;
            z-index: 1000;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            max-width: 300px;
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .notification-success {
            background-color: var(--success-color);
        }
        
        .notification-danger {
            background-color: var(--danger-color);
        }
        
        .notification-info {
            background-color: var(--primary-color);
        }
    </style>
`);

// Add to cart buttons
document.querySelectorAll('a[href^="cart.php?action=add"]').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        
        const url = this.getAttribute('href');
        
        fetch(url)
            .then(response => response.text())
            .then(data => {
                const notification = document.createElement('div');
                notification.className = 'notification notification-success show';
                notification.textContent = 'Product added to cart!';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 500);
                }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
