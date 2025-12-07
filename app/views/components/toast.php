<?php
/**
 * Toast Notification Component
 * 
 * Usage: component('toast')
 * 
 * JavaScript API:
 * - showToast(message, type, duration)
 *   - message: string - The message to display
 *   - type: 'success' | 'error' | 'warning' | 'info'
 *   - duration: number - Duration in ms (default: 3000)
 */
?>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-6 right-6 z-[100] flex flex-col gap-3"></div>

<!-- Toast Script -->
<script>
(function() {
    const toastContainer = document.getElementById('toast-container');
    
    const toastIcons = {
        success: `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="1.5"/>
            <path d="M7 10L9 12L13 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>`,
        error: `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="1.5"/>
            <path d="M12.5 7.5L7.5 12.5M7.5 7.5L12.5 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>`,
        warning: `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 7V10.5M10 13.5V13M3.5 17H16.5C17.3284 17 17.8552 16.1062 17.4472 15.3819L11.1972 3.88197C10.7972 3.17204 9.80278 3.17204 9.40278 3.88197L3.15278 15.3819C2.74478 16.1062 3.27157 17 4.1 17H3.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>`,
        info: `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="currentColor" stroke-width="1.5"/>
            <path d="M10 9V14M10 6.5V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>`
    };
    
    const toastStyles = {
        success: {
            bg: 'bg-green-50',
            border: 'border-green-200',
            text: 'text-green-800',
            icon: 'text-green-600'
        },
        error: {
            bg: 'bg-red-50',
            border: 'border-red-200',
            text: 'text-red-800',
            icon: 'text-red-600'
        },
        warning: {
            bg: 'bg-yellow-50',
            border: 'border-yellow-200',
            text: 'text-yellow-800',
            icon: 'text-yellow-600'
        },
        info: {
            bg: 'bg-blue-50',
            border: 'border-blue-200',
            text: 'text-blue-800',
            icon: 'text-blue-600'
        }
    };
    
    window.showToast = function(message, type = 'info', duration = 3000) {
        const styles = toastStyles[type] || toastStyles.info;
        const icon = toastIcons[type] || toastIcons.info;
        
        const toast = document.createElement('div');
        toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl border shadow-lg ${styles.bg} ${styles.border} transform translate-x-full opacity-0 transition-all duration-300`;
        toast.innerHTML = `
            <span class="${styles.icon} flex-shrink-0">${icon}</span>
            <span class="${styles.text} text-sm font-medium">${message}</span>
            <button onclick="this.parentElement.remove()" class="${styles.text} hover:opacity-70 ml-2 flex-shrink-0">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Trigger animation
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });
        
        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
        
        return toast;
    };
})();
</script>
