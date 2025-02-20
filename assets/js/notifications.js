class NotificationManager {
    constructor() {
        this.badge = document.getElementById('notification-badge');
        this.init();
    }

    init() {
        this.checkNotifications();
        setInterval(() => this.checkNotifications(), 15000);
    }

    async checkNotifications() {
        try {
            const response = await fetch('../api/notifications.php');
            const data = await response.json();
            
            this.badge.textContent = data.unread > 0 ? data.unread : '';
            this.updateDropdown(data.notifications);
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }

    updateDropdown(notifications) {
        const dropdown = document.getElementById('notifications-dropdown');
        dropdown.innerHTML = notifications.map(notif => `
            <div class="notification-item ${notif.is_read ? 'read' : 'unread'}">
                <p>${notif.message}</p>
                <small>${new Date(notif.created_at).toLocaleDateString()}</small>
            </div>
        `).join('');
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('notification-badge')) {
        new NotificationManager();
    }
});