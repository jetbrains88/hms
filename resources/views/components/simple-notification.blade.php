@if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
<div class="fixed top-0 right-0 z-50 max-w-sm w-full p-4 space-y-4">
    @if(session('success'))
    <div class="animate-slide-in bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-semibold">Success!</p>
                <p class="text-sm opacity-90">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="ml-4 text-white hover:text-emerald-100 focus:outline-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="h-1 bg-emerald-400 animate-progress-5s"></div>
    </div>
    @endif

    @if(session('error'))
    <div class="animate-slide-in bg-gradient-to-r from-rose-500 to-rose-600 text-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-semibold">Error!</p>
                <p class="text-sm opacity-90">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="ml-4 text-white hover:text-rose-100 focus:outline-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="h-1 bg-rose-400 animate-progress-8s"></div>
    </div>
    @endif

    @if(session('warning'))
    <div class="animate-slide-in bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-semibold">Warning!</p>
                <p class="text-sm opacity-90">{{ session('warning') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="ml-4 text-white hover:text-amber-100 focus:outline-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="h-1 bg-amber-400 animate-progress-6s"></div>
    </div>
    @endif

    @if(session('info'))
    <div class="animate-slide-in bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-semibold">Information</p>
                <p class="text-sm opacity-90">{{ session('info') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="ml-4 text-white hover:text-blue-100 focus:outline-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="h-1 bg-blue-400 animate-progress-4s"></div>
    </div>
    @endif

    @foreach($errors->all() as $error)
    <div class="animate-slide-in bg-gradient-to-r from-rose-500 to-rose-600 text-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="font-semibold">Validation Error</p>
                <p class="text-sm opacity-90">{{ $error }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="ml-4 text-white hover:text-rose-100 focus:outline-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="h-1 bg-rose-400 animate-progress-7s"></div>
    </div>
    @endforeach
</div>

<style>
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

@keyframes progress5s {
    from { width: 100%; }
    to { width: 0%; }
}

@keyframes progress4s {
    from { width: 100%; }
    to { width: 0%; }
}

@keyframes progress6s {
    from { width: 100%; }
    to { width: 0%; }
}

@keyframes progress7s {
    from { width: 100%; }
    to { width: 0%; }
}

@keyframes progress8s {
    from { width: 100%; }
    to { width: 0%; }
}

.animate-slide-in {
    animation: slideIn 0.3s ease-out forwards;
}

.animate-progress-5s {
    animation: progress5s 5s linear forwards;
}

.animate-progress-4s {
    animation: progress4s 4s linear forwards;
}

.animate-progress-6s {
    animation: progress6s 6s linear forwards;
}

.animate-progress-7s {
    animation: progress7s 7s linear forwards;
}

.animate-progress-8s {
    animation: progress8s 8s linear forwards;
}

/* Notification durations */
.notification-success {
    animation: slideIn 0.3s ease-out forwards, 
               slideOut 0.3s ease-in 5s forwards;
}

.notification-error {
    animation: slideIn 0.3s ease-out forwards, 
               slideOut 0.3s ease-in 8s forwards;
}

.notification-warning {
    animation: slideIn 0.3s ease-out forwards, 
               slideOut 0.3s ease-in 6s forwards;
}

.notification-info {
    animation: slideIn 0.3s ease-out forwards, 
               slideOut 0.3s ease-in 4s forwards;
}

.notification-validation {
    animation: slideIn 0.3s ease-out forwards, 
               slideOut 0.3s ease-in 7s forwards;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-remove notifications based on their type
    const notifications = document.querySelectorAll('.animate-slide-in');
    
    notifications.forEach(notification => {
        let duration = 5000; // Default 5 seconds
        
        // Set different durations based on notification type
        if (notification.innerHTML.includes('Success!')) {
            duration = 10000;
            notification.classList.add('notification-success');
        } else if (notification.innerHTML.includes('Error!')) {
            duration = 10000;
            notification.classList.add('notification-error');
        } else if (notification.innerHTML.includes('Warning!')) {
            duration = 10000;
            notification.classList.add('notification-warning');
        } else if (notification.innerHTML.includes('Information')) {
            duration = 10000;
            notification.classList.add('notification-info');
        } else if (notification.innerHTML.includes('Validation Error')) {
            duration = 10000;
            notification.classList.add('notification-validation');
        }
        
        // Auto remove after duration
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-in forwards';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, duration);
    });
    
    // Close button functionality
    document.querySelectorAll('.animate-slide-in button').forEach(button => {
        button.addEventListener('click', function() {
            const notification = this.closest('.animate-slide-in');
            notification.style.animation = 'slideOut 0.3s ease-in forwards';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        });
    });
});
</script>
@endif