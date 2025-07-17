@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Search Results</h1>
            <p class="text-gray-600">{{ $products->count() }} results found for "{{ $query }}"</p>
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
                                <h3 class="font-semibold text-sm mb-1 hover:text-blue-600">{{ $product->name }}</h3>
                            </a>
                            <p class="text-xs text-gray-500 mb-2">
                                @php
                                    $breadcrumb = [];
                                    $current = $product->category;
                                    while ($current) {
                                        array_unshift($breadcrumb, $current->name);
                                        $current = $current->parent;
                                    }
                                    echo implode(' → ', $breadcrumb);
                                @endphp
                            </p>
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
                                        class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-6xl mb-4">🔍</div>
                <h2 class="text-2xl font-semibold mb-2">No products found</h2>
                <p class="text-gray-600 mb-4">We couldn't find any products matching "{{ $query }}"</p>
                <p class="text-gray-500 mb-8">Try checking your spelling or using different keywords</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
                    Browse All Products
                </a>
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
            const cartBadges = document.querySelectorAll('.cart-count');
            cartBadges.forEach(badge => {
                badge.textContent = data.cart_count;
            });
            
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