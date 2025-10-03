<!-- Success Message Component -->
@if(session('success'))
    <div class="success-message mx-4 lg:mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mx-4 lg:mx-6 mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-lg"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="mx-4 lg:mx-6 mt-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2 text-lg"></i>
                <span class="font-medium">{{ session('warning') }}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-yellow-600 hover:text-yellow-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="mx-4 lg:mx-6 mt-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg animate-fade-in">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2 text-lg"></i>
                <span class="font-medium">{{ session('info') }}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif