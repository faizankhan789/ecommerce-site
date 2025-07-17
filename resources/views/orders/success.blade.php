@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Placed Successfully!</h1>
            
            <p class="text-gray-600 mb-6">
                Thank you for your order. We've received your order and will process it soon.
            </p>
            
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <p class="text-sm text-gray-600 mb-2">Order Number</p>
                <p class="text-2xl font-bold text-blue-600">{{ $order->order_number }}</p>
            </div>
            
            <div class="space-y-4 text-left max-w-md mx-auto mb-8">
                <div class="flex justify-between">
                    <span class="text-gray-600">Order Date:</span>
                    <span class="font-medium">{{ $order->created_at->format('F j, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Amount:</span>
                    <span class="font-medium">${{ number_format($order->total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Payment Method:</span>
                    <span class="font-medium">{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Credit Card' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Delivery To:</span>
                    <span class="font-medium">{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }}</span>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('orders.show', $order->order_number) }}" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                    View Order Details
                </a>
                <a href="{{ route('products.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium">
                    Continue Shopping
                </a>
            </div>
        </div>
        
        <div class="mt-8 text-center text-gray-600">
            <p class="mb-2">You will receive an order confirmation email shortly.</p>
            <p>If you have any questions, please contact our customer support.</p>
        </div>
    </div>
</div>
@endsection