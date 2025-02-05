document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu functionality
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

    function toggleMobileMenu() {
        mobileMenu.classList.toggle('translate-x-0');
        mobileMenu.classList.toggle('-translate-x-full');
        mobileMenuOverlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', toggleMobileMenu);
    }

    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', toggleMobileMenu);
    }

    // Notifications functionality
    const notificationButton = document.getElementById('notification-button');
    const notificationDropdown = document.getElementById('notification-dropdown');
    let isNotificationOpen = false;

    function toggleNotifications(event) {
        event.stopPropagation();
        isNotificationOpen = !isNotificationOpen;
        
        if (isNotificationOpen) {
            notificationDropdown.classList.remove('hidden');
            // Fetch notifications via AJAX
            fetchNotifications();
        } else {
            notificationDropdown.classList.add('hidden');
        }
    }

    async function fetchNotifications() {
        try {
            const response = await fetch('/api/notifications');
            const notifications = await response.json();
            
            if (notifications.length > 0) {
                renderNotifications(notifications);
            } else {
                showEmptyNotifications();
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
            showErrorNotifications();
        }
    }

    function renderNotifications(notifications) {
        const container = document.getElementById('notification-list');
        container.innerHTML = notifications.map(notification => `
            <div class="px-4 py-3 hover:bg-gray-100 border-b last:border-b-0">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas ${getNotificationIcon(notification.type)} text-${getNotificationColor(notification.type)}-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                        <p class="text-sm text-gray-500">${notification.message}</p>
                        <p class="text-xs text-gray-400 mt-1">${formatTimestamp(notification.created_at)}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function showEmptyNotifications() {
        const container = document.getElementById('notification-list');
        container.innerHTML = `
            <div class="px-4 py-6 text-center text-gray-500">
                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                <p>No new notifications</p>
            </div>
        `;
    }

    function showErrorNotifications() {
        const container = document.getElementById('notification-list');
        container.innerHTML = `
            <div class="px-4 py-6 text-center text-red-500">
                <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                <p>Error loading notifications</p>
            </div>
        `;
    }

    function getNotificationIcon(type) {
        const icons = {
            'campaign': 'fa-paper-plane',
            'template': 'fa-file-alt',
            'user-list': 'fa-users',
            'settings': 'fa-cog',
            'default': 'fa-bell'
        };
        return icons[type] || icons.default;
    }

    function getNotificationColor(type) {
        const colors = {
            'campaign': 'green',
            'template': 'blue',
            'user-list': 'purple',
            'settings': 'yellow',
            'default': 'gray'
        };
        return colors[type] || colors.default;
    }

    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000); // difference in seconds

        if (diff < 60) {
            return 'Just now';
        } else if (diff < 3600) {
            const minutes = Math.floor(diff / 60);
            return `${minutes}m ago`;
        } else if (diff < 86400) {
            const hours = Math.floor(diff / 3600);
            return `${hours}h ago`;
        } else {
            return date.toLocaleDateString();
        }
    }

    if (notificationButton) {
        notificationButton.addEventListener('click', toggleNotifications);
    }

    // Close notifications when clicking outside
    document.addEventListener('click', function(event) {
        if (isNotificationOpen && !notificationDropdown.contains(event.target)) {
            isNotificationOpen = false;
            notificationDropdown.classList.add('hidden');
        }
    });

    // Handle notification badge updates via WebSocket (if implemented)
    function initializeWebSocket() {
        const ws = new WebSocket(`ws://${window.location.host}/ws`);
        
        ws.onmessage = function(event) {
            const data = JSON.parse(event.data);
            if (data.type === 'notification') {
                updateNotificationBadge(data.count);
            }
        };

        ws.onerror = function(error) {
            console.error('WebSocket error:', error);
        };
    }

    function updateNotificationBadge(count) {
        const badge = document.getElementById('notification-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    // Initialize WebSocket if supported
    if ('WebSocket' in window) {
        initializeWebSocket();
    }
}); 