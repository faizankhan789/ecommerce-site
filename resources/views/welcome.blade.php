<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Commerce Site</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="text-2xl font-bold text-gray-800">E-Commerce</a>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Home</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Products</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">About</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Contact</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700 font-medium">Welcome, {{ Auth::user()->name }}</span>
                            <a href="{{ route('dashboard') }}" class="px-6 py-2 border-2 border-blue-600 text-blue-600 rounded-md hover:bg-blue-600 hover:text-white transition-all">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('auth.login-register') }}" class="px-6 py-2 border-2 border-blue-600 text-blue-600 rounded-md hover:bg-blue-600 hover:text-white transition-all">Login</a>
                        <a href="{{ route('auth.login-register') }}" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="py-20 bg-gradient-to-br from-purple-600 to-purple-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-4">Welcome to Our E-Commerce Store</h1>
            <p class="text-xl mb-8 opacity-90">Discover amazing products at unbeatable prices</p>
            @guest
                <a href="{{ route('auth.login-register') }}" class="inline-block px-8 py-3 bg-blue-600 text-white text-lg rounded-md hover:bg-blue-700 transition-colors">
                    Get Started
                </a>
            @endguest
        </div>
    </section>
    
    
</body>
</html>