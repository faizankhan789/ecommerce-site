@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="text-gray-700 hover:text-blue-600">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        @if($category->parent)
                            <a href="{{ route('categories.show', $category->parent->slug) }}" class="ml-1 text-gray-700 hover:text-blue-600">{{ $category->parent->name }}</a>
                        @endif
                    </div>
                </li>
                @if($category->parent)
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500">{{ $category->name }}</span>
                    </div>
                </li>
                @else
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500">{{ $category->name }}</span>
                    </div>
                </li>
                @endif
            </ol>
        </nav>

        <!-- Category Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                @if($category->icon)
                    <span class="mr-2">{{ $category->icon }}</span>
                @endif
                {{ $category->name }}
            </h1>
            @if($category->description)
                <p class="text-gray-600">{{ $category->description }}</p>
            @endif
        </div>

        <!-- Subcategories if any -->
        @if($category->activeChildren->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Subcategories</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($category->activeChildren as $subcategory)
                        <a href="{{ route('categories.show', $subcategory->slug) }}" 
                           class="bg-white rounded-lg p-4 text-center hover:shadow-md transition-shadow">
                            @if($subcategory->icon)
                                <div class="text-3xl mb-2">{{ $subcategory->icon }}</div>
                            @endif
                            <p class="text-sm font-medium">{{ $subcategory->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">({{ $subcategory->products->count() }} items)</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Products Grid -->
        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-600">Showing {{ $products->count() }} of {{ $products->total() }} products</p>
            
            <div class="flex items-center space-x-4">
                <label class="text-sm text-gray-600">Sort by:</label>
                <select onchange="window.location.href=this.value" class="border rounded-md px-3 py-1 text-sm">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <div class="relative">
                                <div class="bg-gray-200 h-48 rounded-t-lg flex items-center justify-center">
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-t-lg">
                                    @else
                                        <span class="text-4xl">📦</span>
                                    @endif
                                </div>
                                @if($product->discount_percentage > 0)
                                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">-{{ $product->discount_percentage }}%</span>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <h3 class="font-semibold text-sm mb-2 hover:text-blue-600">{{ $product->name }}</h3>
                            </a>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $product->rating)
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-600 ml-2">({{ $product->reviews_count }})</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                    @if($product->compare_price)
                                        <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($product->compare_price, 2) }}</span>
                                    @endif
                                </div>
                                <button onclick="addToCart({{ $product->id }})" 
                                        class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition-colors"
                                        title="Add to Cart">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-6xl mb-4">📦</div>
                <h2 class="text-2xl font-semibold mb-2">No products found</h2>
                <p class="text-gray-600">Check back later for new products in this category.</p>
            </div>
        @endif
    </div>
</div>

<script>
function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            const cartBadge = document.querySelector('.cart-count');
            if (cartBadge) {
                cartBadge.textContent = data.cart_count;
            }
            
            // Show success message
            alert(data.message);
        } else {
            alert(data.message || 'Failed to add item to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
@endsection