<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopHub - Online Shopping')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <style>
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
                                   id="search-input"
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
                        <div id="autocomplete-dropdown" class="autocomplete-dropdown hidden"></div>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 flex items-center relative">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Cart</span>
                        <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
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
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
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
        </div>
    </nav>

    <!-- Categories Bar -->
    @include('components.category-menu')

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold mb-4">About</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Careers</a></li>
                        <li><a href="#" class="hover:text-white">Press</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Support</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white">FAQs</a></li>
                        <li><a href="#" class="hover:text-white">Shipping</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Legal</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white">Terms of Service</a></li>
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
            if (menu) {
                menu.classList.toggle('active');
            }
        }
        
        // Autocomplete functionality
        let searchTimeout;
        const searchInput = document.getElementById('search-input');
        const autocompleteDropdown = document.getElementById('autocomplete-dropdown');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    autocompleteDropdown.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    fetchAutocompleteResults(query);
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
        
        function fetchAutocompleteResults(query) {
            fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayAutocompleteResults(data, query);
                })
                .catch(error => {
                    console.error('Autocomplete error:', error);
                });
        }
        
        function displayAutocompleteResults(results, query) {
            if (results.length === 0) {
                autocompleteDropdown.innerHTML = `
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
                
                autocompleteDropdown.innerHTML = html;
            }
            
            autocompleteDropdown.classList.remove('hidden');
            selectedIndex = -1;
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
    </script>
</body>
</html>