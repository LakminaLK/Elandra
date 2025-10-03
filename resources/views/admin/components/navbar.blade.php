<!-- Admin Top Navigation Bar -->
<nav class="bg-white h-20 px-4 lg:px-6 flex justify-between items-center shadow-lg fixed top-0 right-0 left-0 lg:left-64 z-30 border-b border-gray-100">
    <!-- Left Side - Mobile Menu Only -->
    <div class="flex items-center">
        <!-- Mobile Menu Button -->
        <button class="lg:hidden p-3 rounded-lg hover:bg-gray-100 transition-all duration-200 text-gray-700" id="mobile-menu-btn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Right Side - Notifications & Profile -->
    <div class="flex items-center space-x-4">
        <!-- Notification Bell -->
        <div class="relative">
            <button class="relative p-2 hover:bg-gray-100 focus:outline-none transition-colors duration-200">
                <span style="font-size: 20px; color: #000000; display: inline-block; font-weight: bold;">🔔</span>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">3</span>
            </button>
        </div>

        <!-- Profile Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <!-- Profile Button -->
            <button @click="open = !open" 
                    class="flex items-center justify-center p-2 hover:bg-gray-100 focus:outline-none transition-all duration-200">
                <span style="font-size: 20px; color: #000000; display: inline-block; font-weight: bold;">👤</span>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false" 
                 x-transition 
                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                
                <!-- Menu Items -->
                <a href="{{ route('admin.profile.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-user mr-3 text-gray-500"></i>
                    Profile
                </a>
                
                <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    <span class="flex items-center">
                        <i class="fas fa-moon mr-3 text-gray-500"></i>
                        Dark Mode
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="darkModeToggle" class="sr-only peer" onchange="toggleDarkMode()">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center text-left px-4 py-3 text-red-600 hover:bg-red-50 transition-all duration-200 border-t border-gray-100">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleDarkMode() {
        const toggle = document.getElementById('darkModeToggle');
        const isDark = toggle.checked;
        
        // Store preference in localStorage
        localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        
        // Apply dark mode (basic implementation)
        if (isDark) {
            document.documentElement.classList.add('dark');
            showNotification('Dark mode enabled', 'info');
        } else {
            document.documentElement.classList.remove('dark');
            showNotification('Dark mode disabled', 'info');
        }
    }

    // Load dark mode preference on page load
    document.addEventListener('DOMContentLoaded', function() {
        const darkMode = localStorage.getItem('darkMode');
        const toggle = document.getElementById('darkModeToggle');
        
        if (darkMode === 'enabled') {
            toggle.checked = true;
            document.documentElement.classList.add('dark');
        }
    });

    // Simple notification function
    function showNotification(message, type = 'info') {
        // Create notification if it doesn't exist already
        let notification = document.getElementById('dark-mode-notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'dark-mode-notification';
            notification.className = 'fixed top-4 right-4 z-50 p-3 rounded-lg shadow-lg transform transition-all duration-500 translate-x-full bg-blue-500 text-white';
            document.body.appendChild(notification);
        }
        
        notification.textContent = message;
        notification.classList.remove('translate-x-full');
        
        // Hide after 2 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
        }, 2000);
    }
</script>