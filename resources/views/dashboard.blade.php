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
                <a href="/" class="text-xl sm:text-2xl font-bold text-gray-800">E-Commerce</a>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <span class="text-gray-700 text-sm sm:text-base hidden sm:block">Welcome, {{ Auth::user()->name }}</span>
                    <span class="text-gray-700 text-sm sm:hidden">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 sm:px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors text-sm sm:text-base">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Dashboard Content -->
    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold mb-4">Welcome to Your Dashboard</h1>
                <div class="space-y-2 text-sm sm:text-base">
                    <p class="text-gray-600">Hello {{ Auth::user()->name }}, welcome back to your e-commerce dashboard!</p>
                    <p class="text-gray-600">Email: {{ Auth::user()->email }}</p>
                    @if(Auth::user()->phone)
                        <p class="text-gray-600">Phone: {{ Auth::user()->phone }}</p>
                    @endif
                    <p class="text-gray-600">Member since: {{ Auth::user()->created_at->format('F j, Y') }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md text-center">
                    <div class="text-gray-600 text-xs sm:text-sm mb-2">Total Orders</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">
                        {{ Auth::user()->orders()->count() }}
                    </div>
                </div>
                
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md text-center">
                    <div class="text-gray-600 text-xs sm:text-sm mb-2">Wishlist Items</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">0</div>
                </div>
                
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md text-center">
                    <div class="text-gray-600 text-xs sm:text-sm mb-2">Cart Items</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">
                        {{ Auth::user()->carts()->sum('quantity') }}
                    </div>
                </div>
                
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md text-center">
                    <div class="text-gray-600 text-xs sm:text-sm mb-2">Total Spent</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">
                        ${{ number_format(Auth::user()->orders()->sum('total'), 2) }}
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
                @php
                    $recentOrders = Auth::user()->orders()->latest()->limit(5)->get();
                @endphp
                
                @if($recentOrders->count() > 0)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->order_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->created_at->format('M j') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">${{ number_format($order->total, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('orders.show', $order->order_number) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-700">View All Orders →</a>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
                        No orders yet. <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-700">Start shopping</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>