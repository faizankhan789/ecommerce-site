<!-- Categories Bar with Hover Menu -->
<div class="bg-gray-100 border-b relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-4 sm:space-x-6 py-3 text-sm justify-start md:justify-center flex-wrap">
            @php
                $categories = App\Models\Category::where('is_active', true)
                    ->whereNull('parent_id')
                    ->with('activeChildren')
                    ->orderBy('order')
                    ->get();
            @endphp
            
            @foreach($categories as $category)
                <div class="category-item relative group">
                    <a href="{{ route('categories.show', $category->slug) }}" 
                       class="text-gray-700 hover:text-blue-600 whitespace-nowrap pb-3 inline-block transition-colors duration-200 relative">
                        @if($category->icon)
                            <span class="mr-1">{{ $category->icon }}</span>
                        @endif
                        {{ $category->name }}
                        @if($category->activeChildren->count() > 0)
                            <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        @endif
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    
                    @if($category->activeChildren->count() > 0)
                        <!-- Dropdown Menu -->
                        <div class="absolute left-0 top-full mt-0 w-64 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform translate-y-2 group-hover:translate-y-0">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-3">{{ $category->name }}</h3>
                                <ul class="space-y-2">
                                    @foreach($category->activeChildren as $subcategory)
                                        <li>
                                            <a href="{{ route('categories.show', $subcategory->slug) }}" 
                                               class="block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded transition-colors duration-200">
                                                @if($subcategory->icon)
                                                    <span class="mr-2">{{ $subcategory->icon }}</span>
                                                @endif
                                                {{ $subcategory->name }}
                                                @if($subcategory->products->count() > 0)
                                                    <span class="text-xs text-gray-400 ml-2">({{ $subcategory->products->count() }})</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                @if($category->products->count() > 0)
                                    <div class="mt-4 pt-4 border-t">
                                        <a href="{{ route('categories.show', $category->slug) }}" 
                                           class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                            View all {{ $category->name }} →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .category-item:hover .group-hover\:visible {
        visibility: visible;
        opacity: 1;
    }
    
    .category-item {
        position: relative;
    }
    
    /* Keep dropdown visible when hovering over it */
    .category-item:hover > div,
    .category-item > div:hover {
        visibility: visible;
        opacity: 1;
    }
</style>