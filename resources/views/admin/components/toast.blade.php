<!-- Toast Component -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
    <!-- Toast messages will be inserted here via JavaScript -->
</div>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `flex items-center p-4 rounded-lg shadow-lg ${colors[type]} transform translate-x-full transition-transform duration-300 ease-in-out`;
    toast.innerHTML = `
        <i class="${icons[type]} mr-3"></i>
        <span class="flex-1">${message}</span>
        <button onclick="hideToast('${toastId}')" class="ml-3 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        hideToast(toastId);
    }, 5000);
}

function hideToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}

// Show Laravel session messages as toasts
@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => {
        showToast('{{ session('success') }}', 'success');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', () => {
        showToast('{{ session('error') }}', 'error');
    });
@endif

@if(session('warning'))
    document.addEventListener('DOMContentLoaded', () => {
        showToast('{{ session('warning') }}', 'warning');
    });
@endif

@if(session('info'))
    document.addEventListener('DOMContentLoaded', () => {
        showToast('{{ session('info') }}', 'info');
    });
@endif
</script>