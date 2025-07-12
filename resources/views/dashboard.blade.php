<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Commerce</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="text-2xl font-bold text-gray-800">E-Commerce</a>
                
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Dashboard Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow-md mb-8">
                <h1 class="text-3xl font-bold mb-4">Welcome to Your Dashboard</h1>
                <p class="text-gray-600 mb-2">Hello {{ Auth::user()->name }}, welcome back to your e-commerce dashboard!</p>
                <p class="text-gray-600 mb-2">Email: {{ Auth::user()->email }}</p>
                @if(Auth::user()->phone)
                    <p class="text-gray-600 mb-2">Phone: {{ Auth::user()->phone }}</p>
                @endif
                <p class="text-gray-600">Member since: {{ Auth::user()->created_at->format('F j, Y') }}</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="text-gray-600 text-sm mb-2">Total Orders</div>
                    <div class="text-3xl font-bold text-blue-600">0</div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="text-gray-600 text-sm mb-2">Wishlist Items</div>
                    <div class="text-3xl font-bold text-blue-600">0</div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="text-gray-600 text-sm mb-2">Cart Items</div>
                    <div class="text-3xl font-bold text-blue-600">0</div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="text-gray-600 text-sm mb-2">Total Spent</div>
                    <div class="text-3xl font-bold text-blue-600">$0</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>