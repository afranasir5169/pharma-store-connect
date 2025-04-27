
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
});

// Add smooth transition for alerts
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .alert {
            transition: opacity 0.5s ease-out;
        }
    </style>
`);
