@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="/" class="text-gray-700 hover:text-blue-600">Home</a></li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('categories.show', $product->category->slug) }}" class="ml-1 text-gray-700 hover:text-blue-600">{{ $product->category->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Product Images -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="bg-gray-200 h-96 rounded-lg flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <span class="text-8xl">📦</span>
                        @endif
                    </div>
                    @if($product->gallery && count($product->gallery) > 0)
                        <div class="grid grid-cols-4 gap-2 mt-4">
                            @foreach($product->gallery as $image)
                                <div class="bg-gray-200 h-20 rounded flex items-center justify-center cursor-pointer hover:opacity-75">
                                    <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>
                    
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $product->rating)
                                    ⭐
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-600 ml-2">({{ $product->reviews_count }} reviews)</span>
                        <span class="text-gray-400 mx-2">|</span>
                        <span class="text-gray-600">{{ $product->views }} views</span>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-baseline mb-2">
                            <span class="text-3xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                            @if($product->compare_price)
                                <span class="text-xl text-gray-500 line-through ml-3">${{ number_format($product->compare_price, 2) }}</span>
                                <span class="ml-3 bg-red-500 text-white px-2 py-1 rounded text-sm">Save {{ $product->discount_percentage }}%</span>
                            @endif
                        </div>
                        @if($product->sku)
                            <p class="text-gray-600 text-sm">SKU: {{ $product->sku }}</p>
                        @endif
                    </div>

                    <div class="mb-6">
                        <p class="text-gray-700">{{ $product->description }}</p>
                    </div>

                    <div class="mb-6">
                        @if($product->isInStock())
                            <p class="text-green-600 font-semibold mb-4">✓ In Stock ({{ $product->quantity }} available)</p>
                            
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <label class="mr-2">Quantity:</label>
                                    <input type="number" id="quantity" value="1" min="1" max="{{ $product->quantity }}" 
                                           class="w-20 px-3 py-2 border rounded-md text-center">
                                </div>
                                
                                <button onclick="addToCart()" 
                                        class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 font-semibold">
                                    Add to Cart
                                </button>
                            </div>
                        @else
                            <p class="text-red-600 font-semibold">Out of Stock</p>
                            <button class="w-full bg-gray-400 text-white py-3 px-6 rounded-md cursor-not-allowed" disabled>
                                Out of Stock
                            </button>
                        @endif
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Category:</span>
                            <a href="{{ route('categories.show', $product->category->slug) }}" class="text-blue-600 hover:underline">
                                {{ $product->category->name }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-semibold mb-4">Why Buy From Us</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">🚚</span>
                            <div>
                                <p class="font-medium">Fast Delivery</p>
                                <p class="text-sm text-gray-600">Get it delivered within 2-3 business days</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">🔄</span>
                            <div>
                                <p class="font-medium">Easy Returns</p>
                                <p class="text-sm text-gray-600">30-day return policy</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">🛡️</span>
                            <div>
                                <p class="font-medium">Secure Payment</p>
                                <p class="text-sm text-gray-600">100% secure transactions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow">
                            <a href="{{ route('products.show', $related->slug) }}">
                                <div class="bg-gray-200 h-48 rounded-t-lg flex items-center justify-center">
                                    @if($related->image)
                                        <img src="{{ $related->image }}" alt="{{ $related->name }}" class="w-full h-full object-cover rounded-t-lg">
                                    @else
                                        <span class="text-4xl">📦</span>
                                    @endif
                                </div>