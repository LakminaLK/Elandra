<!-- Page Header Component -->
<div class="bg-white border-b border-gray-200">
    <div class="px-4 lg:px-6 py-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">{{ $title ?? 'Page Title' }}</h1>
                @if(isset($subtitle))
                    <p class="mt-1 text-sm text-gray-600">{{ $subtitle }}</p>
                @endif
                
                <!-- Breadcrumb for mobile -->
                <nav class="flex mt-3 lg:hidden" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-home"></i>
                            </a>
                        </li>
                        @if(!request()->routeIs('admin.dashboard'))
                            <li>
                                <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                                <span class="text-gray-900 font-medium">{{ $title ?? 'Current Page' }}</span>
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>

            @if(isset($actions))
                <div class="mt-4 lg:mt-0 lg:ml-6">
                    <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-2 sm:space-y-0">
                        {!! $actions !!}
                    </div>
                </div>
            @endif
        </div>
        
        @if(isset($tabs))
            <div class="mt-6">
                <nav class="flex space-x-8" aria-label="Tabs">
                    {!! $tabs !!}
                </nav>
            </div>
        @endif
    </div>
</div>