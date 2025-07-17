@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Order Details</h1>
                    <p class="text-gray-600">Order #{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        {{ ucfirst($order->status) }}
                    </span>
                    <p class="text-sm text-gray-600 mt-2">{{ $order->created_at->format('F j, Y g:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Order Items</h2>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center py-4 border-b last:border-0">
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">📦</span>
                                </div>
                                
                                <div class="flex-1 ml-4">
                                    <h3 class="font-semibold">{{ $item->product_name }}</h3>
                                    <p class="text-gray-600 text-sm">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-gray-600 text-sm">Price: ${{ number_format($item->price, 2) }}</p>
                                </div>
                                
                                <div class="text-right">
                                    <p class="font-semibold">${{ number_format($item->total, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Order Totals -->
                    <div class="mt-6 pt-6 border-t">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span>${{ number_format($order->shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tax</span>
                                <span>${{ number_format($order->tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-lg pt-2 border-t">
                                <span>Total</span>
                                <span>${{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Information -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-3">Shipping Address</h3>
                    <div class="text-gray-600 text-sm space-y-1">
                        <p>{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</p>
                        <p>{{ $order->shipping_address['address'] }}</p>
                        <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}</p>
                        <p>{{ $order->shipping_address['phone'] }}</p>
                        <p>{{ $order->shipping_address['email'] }}</p>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-3">Payment Information</h3>
                    <div class="text-gray-600 text-sm space-y-1">
                        <p>Method: {{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Credit Card' }}</p>
                        <p>Status: <span class="font-medium">{{ ucfirst($order->payment_status) }}</span></p>
                    </div>
                </div>
                
                @if($order->notes)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-3">Order Notes</h3>
                        <p class="text-gray-600 text-sm">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Actions -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('orders.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                View All Orders
            </a>
            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection