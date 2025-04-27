
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const toggleSidebarBtn = document.getElementById('toggle-sidebar');
    
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', function() {
            const sidebar = document.querySelector('.admin-sidebar');
            sidebar.classList.toggle('mobile-visible');
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
});

// Add CSS for mobile sidebar
document.head.insertAdjacentHTML('beforeend', `
    <style>
        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
            }
            
            .admin-sidebar.mobile-visible {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .admin-top-header {
                left: 0;
            }
        }
    </style>
`);
