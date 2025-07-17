<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Commerce Site - Shop Online for Electronics, Fashion & More</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <style>
        .mobile-menu {
            display: none;
        }
        .mobile-menu.active {
            display: block;
        }
        .scroll-smooth {
            scroll-behavior: smooth;
        }
        @keyframes slide {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-slide {
            animation: slide 20s linear infinite;
        }
        
        /* Autocomplete styles */
        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 0.5rem 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .autocomplete-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .autocomplete-item:hover {
            background-color: #f3f4f6;
        }
        
        .autocomplete-item:last-child {
            border-bottom: none;
        }
        
        .autocomplete-category {
            background-color: #eff6ff;
            font-weight: 600;
        }
        
        .search-highlight {
            background-color: #fef3c7;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Top Bar -->


    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl sm:text-2xl font-bold text-gray-800">🛍️ ShopHub</a>
                </div>
                
                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <form action="{{ route('search') }}" method="GET" class="w-full">
                            <input type="text" 
                                   name="q" 
                                   id="home-search-input"
                                   placeholder="Search for products..." 
                                   value="{{ request('q') }}"
                                   autocomplete="off"
                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500">
                            <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                        <div id="home-autocomplete-dropdown" class="autocomplete-dropdown hidden"></div>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="relative">
                            Cart
                            <span class="cart-count absolute -top-2 -right-4 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                @php
                                    $cartCount = 0;
                                    if (auth()->check()) {
                                        $cartCount = auth()->user()->carts()->sum('quantity');
                                    } else {
                                        $sessionId = session()->get('cart_session_id');
                                        if ($sessionId) {
                                            $cartCount = App\Models\Cart::where('session_id', $sessionId)->sum('quantity');
                                        }
                                    }
                                @endphp
                                {{ $cartCount }}
                            </span>
                        </span>
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Account</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('auth.login-register') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                        <a href="{{ route('auth.login-register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Sign Up</a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Search -->
            <div class="md:hidden pb-3 relative">
                <form action="{{ route('search') }}" method="GET" class="w-full">
                    <input type="text" 
                           name="q" 
                           id="home-search-input-mobile"
                           placeholder="Search products..." 
                           autocomplete="off"
                           class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500">
                </form>
                <div id="home-autocomplete-dropdown-mobile" class="autocomplete-dropdown hidden"></div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu md:hidden border-t">
            <div class="px-4 py-3 space-y-3">
                <a href="{{ route('cart.index') }}" class="block text-gray-700 hover:text-blue-600">
                    Cart ({{ $cartCount ?? 0 }})
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-blue-600">My Account</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left text-gray-700 hover:text-red-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('auth.login-register') }}" class="block text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('auth.login-register') }}" class="block text-center px-4 py-2 bg-blue-600 text-white rounded-md">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Categories Bar -->
    @include('components.category-menu')

    <!-- Hero Banner Slider -->
    <section class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">
                        Big Sale! Up to 50% Off
                    </h1>
                    <p class="text-lg sm:text-xl mb-6 opacity-90">
                        Shop the latest trends in electronics, fashion, and more
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#featured" class="px-6 py-3 bg-white text-blue-600 rounded-md font-semibold hover:bg-gray-100 text-center">
                            Shop Now
                        </a>
                        <a href="#deals" class="px-6 py-3 border-2 border-white text-white rounded-md font-semibold hover:bg-white hover:text-blue-600 text-center">
                            View Deals
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="text-center">
                        <div class="inline-block bg-white rounded-lg p-8 shadow-xl">
                            <span class="text-6xl">🛒</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-3xl mb-2">🚚</div>
                    <h3 class="font-semibold text-sm sm:text-base">Free Shipping</h3>
                    <p class="text-xs sm:text-sm text-gray-600">On orders over $50</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl mb-2">💰</div>
                    <h3 class="font-semibold text-sm sm:text-base">Money Back</h3>
                    <p class="text-xs sm:text-sm text-gray-600">30-day guarantee</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl mb-2">🔒</div>
                    <h3 class="font-semibold text-sm sm:text-base">Secure Payment</h3>
                    <p class="text-xs sm:text-sm text-gray-600">100% protected</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl mb-2">📞</div>
                    <h3 class="font-semibold text-sm sm:text-base">24/7 Support</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Ready to help</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Flash Deals -->
    <section id="deals" class="py-12 bg-red-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold">⚡ Flash Deals</h2>
                <div class="text-sm sm:text-base">
                    Ends in: <span class="font-bold text-red-600">12:34:56</span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                @php
                    $flashProducts = App\Models\Product::where('is_active', true)
                        ->where('compare_price', '>', 0)
                        ->orderBy('created_at', 'desc')
                        ->limit(4)
                        ->get();
                @endphp
                
                @foreach($flashProducts as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow">
                        <div class="relative">
                            <div class="bg-gray-200 h-40 sm:h-48 rounded-t-lg flex items-center justify-center">
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
                        <div class="p-4">
                            <h3 class="font-semibold text-sm sm:text-base mb-2">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                    @if($product->compare_price)
                                        <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($product->compare_price, 2) }}</span>
                                    @endif
                                </div>
                                <button onclick="addToCart({{ $product->id }})" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured" class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold mb-8">Featured Products</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @php
                    $featuredProducts = App\Models\Product::where('is_active', true)
                        ->where('is_featured', true)
                        ->limit(8)
                        ->get();
                    
                    if ($featuredProducts->count() < 8) {
                        $additionalProducts = App\Models\Product::where('is_active', true)
                            ->where('is_featured', false)
                            ->limit(8 - $featuredProducts->count())
                            ->get();
                        $featuredProducts = $featuredProducts->concat($additionalProducts);
                    }
                @endphp
                
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <div class="bg-gray-200 h-40 sm:h-48 rounded-t-lg flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-t-lg">
                                @else
                                    <span class="text-4xl">🛍️</span>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold text-sm sm:text-base mb-2">{{ $product->name }}</h3>
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
                                <span class="text-lg font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                <button onclick="addToCart({{ $product->id }})" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 inline-block">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- Shop by Category -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold mb-8">Shop by Category</h2>
            
            <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                @php
                    $mainCategories = App\Models\Category::where('is_active', true)
                        ->whereNull('parent_id')
                        ->orderBy('order')
                        ->limit(6)
                        ->get();
                @endphp
                
                @foreach($mainCategories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="bg-gray-100 rounded-lg p-4 text-center hover:bg-gray-200 transition">
                        <div class="text-3xl mb-2">{{ $category->icon }}</div>
                        <p class="text-sm font-medium">{{ $category->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Brand Showcase -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold mb-8 text-center">Popular Brands</h2>
            
            <div class="overflow-hidden">
                <div class="flex animate-slide">
                    @for ($i = 1; $i <= 10; $i++)
                    <div class="flex-shrink-0 mx-4 bg-white rounded-lg p-6 shadow-md">
                        <div class="text-2xl font-bold text-gray-600">Brand {{ $i }}</div>
                    </div>
                    @endfor
                    <!-- Duplicate for continuous scroll -->
                    @for ($i = 1; $i <= 10; $i++)
                    <div class="flex-shrink-0 mx-4 bg-white rounded-lg p-6 shadow-md">
                        <div class="text-2xl font-bold text-gray-600">Brand {{ $i }}</div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-12 bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold mb-4">Get Exclusive Offers</h2>
            <p class="mb-6">Subscribe to our newsletter and get 10% off your first purchase!</p>
            <form class="max-w-md mx-auto flex flex-col sm:flex-row gap-4">
                <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-md text-gray-900 focus:outline-none">
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white rounded-md hover:bg-gray-800 font-semibold">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold mb-4">About</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Careers</a></li>
                        <li><a href="#" class="hover:text-white">Press</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Support</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white">FAQs</a></li>
                        <li><a href="#" class="hover:text-white">Shipping</a></li>
                        <li><a href="#" class="hover:text-white">Returns</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Legal</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white">Cookie Policy</a></li>
                    </ul>
                </div>
                <div>
                <h3 class="font-bold mb-4">Connect</h3>
                <div class="flex space-x-4 text-2xl">
                    <!-- Facebook -->
                    <a href="#" class="hover:text-blue-600" title="Facebook">📘</a>
                    <!-- LinkedIn -->
                    <a href="#" class="hover:text-blue-500" title="LinkedIn">💼</a>
                    <!-- WhatsApp -->
                    <a href="#" class="hover:text-green-500" title="WhatsApp">💬</a>
                </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2024 ShopHub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.querySelector('.mobile-menu');
            menu.classList.toggle('active');
        }
        
        // Add to cart function
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
                    // Update all cart count badges
                    const cartBadges = document.querySelectorAll('.cart-count');
                    cartBadges.forEach(badge => {
                        badge.textContent = data.cart_count;
                    });
                    
                    // Update mobile menu cart count
                    const mobileCartLink = document.querySelector('.mobile-menu a[href*="cart"]');
                    if (mobileCartLink) {
                        mobileCartLink.textContent = `Cart (${data.cart_count})`;
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
        
        // Autocomplete functionality for homepage
        function initializeAutocomplete(inputId, dropdownId) {
            let searchTimeout;
            const searchInput = document.getElementById(inputId);
            const autocompleteDropdown = document.getElementById(dropdownId);
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    
                    if (query.length < 2) {
                        autocompleteDropdown.classList.add('hidden');
                        return;
                    }
                    
                    searchTimeout = setTimeout(() => {
                        fetchAutocompleteResults(query, autocompleteDropdown);
                    }, 300);
                });
                
                // Hide dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !autocompleteDropdown.contains(e.target)) {
                        autocompleteDropdown.classList.add('hidden');
                    }
                });
                
                // Handle arrow key navigation
                let selectedIndex = -1;
                searchInput.addEventListener('keydown', function(e) {
                    const items = autocompleteDropdown.querySelectorAll('.autocomplete-item');
                    
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                        updateSelection(items, selectedIndex);
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        selectedIndex = Math.max(selectedIndex - 1, -1);
                        updateSelection(items, selectedIndex);
                    } else if (e.key === 'Enter' && selectedIndex >= 0) {
                        e.preventDefault();
                        items[selectedIndex].click();
                    }
                });
            }
        }
        
        function fetchAutocompleteResults(query, dropdown) {
            fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayAutocompleteResults(data, query, dropdown);
                })
                .catch(error => {
                    console.error('Autocomplete error:', error);
                });
        }
        
        function displayAutocompleteResults(results, query, dropdown) {
            if (results.length === 0) {
                dropdown.innerHTML = `
                    <div class="p-4 text-gray-500 text-center">
                        No results found for "${query}"
                    </div>
                `;
            } else {
                let html = '';
                
                results.forEach(item => {
                    if (item.type === 'category') {
                        html += `
                            <a href="${item.url}" class="autocomplete-item autocomplete-category block">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium">${highlightMatch(item.name, query)}</div>
                                        <div class="text-xs text-gray-500">${item.breadcrumb}</div>
                                    </div>
                                    <div class="text-sm text-gray-500">${item.product_count} products</div>
                                </div>
                            </a>
                        `;
                    } else {
                        html += `
                            <a href="${item.url}" class="autocomplete-item block">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                        ${item.image ? `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover rounded">` : '<span class="text-xl">📦</span>'}
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium">${highlightMatch(item.name, query)}</div>
                                        <div class="text-xs text-gray-500">${item.breadcrumb}</div>
                                        <div class="text-sm font-semibold text-blue-600">${item.price.toFixed(2)}</div>
                                    </div>
                                </div>
                            </a>
                        `;
                    }
                });
                
                html += `
                    <a href="/search?q=${encodeURIComponent(query)}" class="autocomplete-item block text-center text-blue-600 font-medium">
                        View all results for "${query}"
                    </a>
                `;
                
                dropdown.innerHTML = html;
            }
            
            dropdown.classList.remove('hidden');
        }
        
        function highlightMatch(text, query) {
            const regex = new RegExp(`(${query})`, 'gi');
            return text.replace(regex, '<span class="search-highlight">$1</span>');
        }
        
        function updateSelection(items, index) {
            items.forEach((item, i) => {
                if (i === index) {
                    item.style.backgroundColor = '#f3f4f6';
                    item.scrollIntoView({ block: 'nearest' });
                } else {
                    item.style.backgroundColor = '';
                }
            });
        }
        
        // Initialize autocomplete on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize for desktop search
            initializeAutocomplete('home-search-input', 'home-autocomplete-dropdown');
            
            // Initialize for mobile search
            initializeAutocomplete('home-search-input-mobile', 'home-autocomplete-dropdown-mobile');
            
            // Simple countdown timer for flash deals
            function updateCountdown() {
                const countdownElements = document.querySelectorAll('.countdown');
                // Add countdown logic here if needed
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>
</body>
</html>