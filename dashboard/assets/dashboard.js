// DASHBOARD-SPECIFIC JAVASCRIPT
class DashboardManager {
    constructor() {
        this.init();
    }

    init() {
        this.initMobileMenu();
        this.initCharts();
        this.initDataTables();
        this.initNotifications();
        this.initAutoRefresh();
    }

    // Mobile sidebar toggle
    initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const sidebar = document.querySelector('.dashboard-sidebar');
        
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('mobile-open');
            });
        }
    }

    // Initialize charts
    initCharts() {
        // This would integrate with Chart.js or similar library
        const chartPlaceholders = document.querySelectorAll('.chart-placeholder');
        
        chartPlaceholders.forEach(chart => {
            // Replace with actual chart implementation
            console.log('Initializing chart:', chart);
        });
    }

    // Initialize interactive data tables
    initDataTables() {
        const tables = document.querySelectorAll('.dashboard-table');
        
        tables.forEach(table => {
            // Add sorting, searching functionality
            this.enhanceTable(table);
        });
    }

    enhanceTable(table) {
        // Simple table enhancement - can be replaced with DataTables library
        const headers = table.querySelectorAll('th');
        
        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                this.sortTable(table, index);
            });
        });
    }

    sortTable(table, columnIndex) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const aText = a.cells[columnIndex].textContent.trim();
            const bText = b.cells[columnIndex].textContent.trim();
            
            // Simple numeric/string comparison
            if (!isNaN(aText) && !isNaN(bText)) {
                return aText - bText;
            }
            return aText.localeCompare(bText);
        });
        
        // Reverse if already sorted
        if (tbody.getAttribute('data-sorted') === columnIndex.toString()) {
            rows.reverse();
            tbody.removeAttribute('data-sorted');
        } else {
            tbody.setAttribute('data-sorted', columnIndex.toString());
        }
        
        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }

    // Notification system
    initNotifications() {
        // Listen for notification events
        document.addEventListener('dashboard:notification', (event) => {
            this.showNotification(event.detail.message, event.detail.type);
        });
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `dashboard-notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="bi bi-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        `;
        
        // Add styles if not already added
        if (!document.querySelector('#notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'notification-styles';
            styles.textContent = `
                .dashboard-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    border-left: 4px solid #3498db;
                    z-index: 10000;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    min-width: 300px;
                    animation: slideInRight 0.3s ease;
                }
                .notification-success { border-left-color: #27ae60; }
                .notification-error { border-left-color: #e74c3c; }
                .notification-warning { border-left-color: #f39c12; }
                .notification-content { display: flex; align-items: center; gap: 10px; flex: 1; }
                .notification-close { background: none; border: none; cursor: pointer; color: #95a5a6; }
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(styles);
        }
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    getNotificationIcon(type) {
        const icons = {
            'info': 'info-circle',
            'success': 'check-circle',
            'error': 'exclamation-circle',
            'warning': 'exclamation-triangle'
        };
        return icons[type] || 'info-circle';
    }

    // Auto-refresh stats
    initAutoRefresh() {
        // Refresh stats every 30 seconds
        setInterval(() => {
            this.refreshStats();
        }, 30000);
    }

    refreshStats() {
        // AJAX call to update stats
        fetch('includes/refresh_stats.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.updateStatsDisplay(data.stats);
                }
            })
            .catch(error => {
                console.error('Error refreshing stats:', error);
            });
    }

    updateStatsDisplay(stats) {
        // Update stat cards with new data
        Object.keys(stats).forEach(statKey => {
            const element = document.querySelector(`[data-stat="${statKey}"]`);
            if (element) {
                element.textContent = stats[statKey];
            }
        });
    }

    // Utility function to show loading state
    showLoading(element) {
        element.classList.add('loading');
        element.disabled = true;
    }

    hideLoading(element) {
        element.classList.remove('loading');
        element.disabled = false;
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.dashboard = new DashboardManager();
});

// Utility function to trigger notifications
function showDashboardNotification(message, type = 'info') {
    const event = new CustomEvent('dashboard:notification', {
        detail: { message, type }
    });
    document.dispatchEvent(event);
}