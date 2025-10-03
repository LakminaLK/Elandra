<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-white to-cyan-50">
    <div class="mb-8">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/90 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20">
        {{ $slot }}
    </div>
    
    <!-- Decorative elements -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-blue-200/30 rounded-full blur-xl"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 bg-cyan-200/30 rounded-full blur-xl"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-purple-200/20 rounded-full blur-lg"></div>
</div>
