import './bootstrap';

// Notification System - Add this to your main JS file
class NotificationSystem {
    constructor() {
        this.container = null;
        this.notificationCount = 0;
        this.maxNotifications = 5;
        this.init();
    }

    init() {
        // Create notification container if it doesn't exist
        if (!document.querySelector('#notification-container')) {
            this.container = document.createElement('div');
            this.container.id = 'notification-container';
            this.container.className = 'fixed top-0 right-0 z-50 max-w-sm w-full p-4 space-y-4';
            document.body.appendChild(this.container);
        } else {
            this.container = document.querySelector('#notification-container');
        }

        // Add CSS styles dynamically
        this.addStyles();
    }

    addStyles() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            @keyframes progress {
                from { width: 100%; }
                to { width: 0%; }
            }
            
            .notification-slide-in {
                animation: slideIn 0.3s ease-out forwards;
            }
            
            .notification-slide-out {
                animation: slideOut 0.3s ease-in forwards;
            }
            
            .notification-progress {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                border-radius: 0 0 0 0.75rem;
            }
            
            .notification-success-progress {
                animation: progress 5s linear forwards;
                background: linear-gradient(to right, #10b981, #34d399);
            }
            
            .notification-error-progress {
                animation: progress 8s linear forwards;
                background: linear-gradient(to right, #f43f5e, #fb7185);
            }
            
            .notification-warning-progress {
                animation: progress 6s linear forwards;
                background: linear-gradient(to right, #f59e0b, #fbbf24);
            }
            
            .notification-info-progress {
                animation: progress 4s linear forwards;
                background: linear-gradient(to right, #3b82f6, #60a5fa);
            }
        `;
        document.head.appendChild(style);
    }

    show(message, type = 'info', title = null, duration = null) {
        // Set default title based on type
        if (!title) {
            switch (type) {
                case 'success': title = 'Success!'; break;
                case 'error': title = 'Error!'; break;
                case 'warning': title = 'Warning!'; break;
                case 'info': title = 'Information'; break;
                default: title = 'Notification';
            }
        }

        // Set default duration based on type
        if (!duration) {
            switch (type) {
                case 'success': duration = 5000; break;
                case 'error': duration = 8000; break;
                case 'warning': duration = 6000; break;
                case 'info': duration = 4000; break;
                default: duration = 5000;
            }
        }

        // Create notification element
        const notificationId = 'notification-' + Date.now();
        const notification = document.createElement('div');
        notification.id = notificationId;
        notification.className = this.getNotificationClasses(type);
        
        // Set gradient colors based on type
        const gradient = this.getGradient(type);
        notification.style.background = gradient;

        // Create notification content
        notification.innerHTML = `
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    ${this.getIcon(type)}
                </div>
                <div class="ml-3 flex-1">
                    <p class="font-semibold">${title}</p>
                    <p class="text-sm opacity-90">${message}</p>
                </div>
                <button onclick="window.notificationSystem.remove('${notificationId}')" 
                        class="ml-4 text-white hover:opacity-80 focus:outline-none transition-opacity">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="notification-progress ${this.getProgressClass(type)}"></div>
        `;

        // Limit number of notifications
        if (this.notificationCount >= this.maxNotifications) {
            const oldest = this.container.children[0];
            if (oldest) this.remove(oldest.id);
        }

        // Add to container
        this.container.appendChild(notification);
        this.notificationCount++;

        // Auto remove after duration
        setTimeout(() => {
            this.remove(notificationId);
        }, duration);

        // Add slide out animation when removing
        notification.addEventListener('animationend', (e) => {
            if (e.animationName === 'slideOut') {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                    this.notificationCount--;
                }
            }
        });

        return notificationId;
    }

    remove(id) {
        const notification = document.getElementById(id);
        if (notification) {
            notification.classList.remove('notification-slide-in');
            notification.classList.add('notification-slide-out');
        }
    }

    clearAll() {
        const notifications = this.container.querySelectorAll('[id^="notification-"]');
        notifications.forEach(notification => {
            this.remove(notification.id);
        });
    }

    getNotificationClasses(type) {
        const baseClasses = 'rounded-xl shadow-lg overflow-hidden text-white notification-slide-in';
        return `${baseClasses} ${type}-notification`;
    }

    getGradient(type) {
        switch (type) {
            case 'success':
                return 'linear-gradient(to right, #10b981, #059669)';
            case 'error':
                return 'linear-gradient(to right, #ef4444, #dc2626)';
            case 'warning':
                return 'linear-gradient(to right, #f59e0b, #d97706)';
            case 'info':
                return 'linear-gradient(to right, #3b82f6, #2563eb)';
            default:
                return 'linear-gradient(to right, #6b7280, #4b5563)';
        }
    }

    getIcon(type) {
        const icons = {
            success: `<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>`,
            error: `<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>`,
            warning: `<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                      </svg>`,
            info: `<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                   </svg>`
        };
        return icons[type] || icons.info;
    }

    getProgressClass(type) {
        switch (type) {
            case 'success': return 'notification-success-progress';
            case 'error': return 'notification-error-progress';
            case 'warning': return 'notification-warning-progress';
            case 'info': return 'notification-info-progress';
            default: return 'notification-info-progress';
        }
    }
}

// Initialize notification system globally
window.notificationSystem = new NotificationSystem();

// Global showNotification function for easy access
window.showNotification = function(message, type = 'info', title = null, duration = null) {
    return window.notificationSystem.show(message, type, title, duration);
};

// Shortcut functions for common notification types
window.showSuccess = function(message, title = 'Success!', duration = 5000) {
    return showNotification(message, 'success', title, duration);
};

window.showError = function(message, title = 'Error!', duration = 8000) {
    return showNotification(message, 'error', title, duration);
};

window.showWarning = function(message, title = 'Warning!', duration = 6000) {
    return showNotification(message, 'warning', title, duration);
};

window.showInfo = function(message, title = 'Information', duration = 4000) {
    return showNotification(message, 'info', title, duration);
};

// Clear all notifications
window.clearNotifications = function() {
    window.notificationSystem.clearAll();
};