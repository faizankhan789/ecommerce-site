@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>
        
        <form action="{{ route('checkout.process') }}" method="POST" class="grid lg:grid-cols-3 gap-8">
            @csrf
            
            <!-- Shipping Information -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->name ? explode(' ', Auth::user()->name)[0] : '') }}" 
                                   class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->name ? explode(' ', Auth::user()->name)[1] ?? '' : '') }}" 
                                   class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                                   class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                                   class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" 
                                   class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" name="city" value="{{ old('city') }}" 
                                   class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <input type="text" name="state" value="{{ old('state') }}" 
                                   class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                            @error('state')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code</label>
                            <input type="text" name="zip" value="{{ old('zip') }}" 
                                   class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                            @error('zip')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Notes (Optional)</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border rounded-md cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cod" class="mr-3" checked>
                            <div>
                                <p class="font-medium">Cash on Delivery</p>
                                <p class="text-sm text-gray-600">Pay when you receive your order</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border rounded-md cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="card" class="mr-3">
                            <div>
                                <p class="font-medium">Credit/Debit Card</p>
                                <p class="text-sm text-gray-600">Secure payment with your card</p>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    
                    <!-- Cart Items -->
                    <div class="space-y-3 border-b pb-4 mb-4">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between text-sm">
                                <div class="flex-1">
                                    <p class="font-medium">{{ $item->product->name }}</p>
                                    <p class="text-gray-600">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                                </div>
                                <span class="font-medium">${{ number_format($item->total, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Totals -->
                    <div class="space-y-2 border-b pb-4 mb-4">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span>{{ $shipping > 0 ? '$' . number_format($shipping, 2) : 'FREE' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between font-semibold text-lg mb-6">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 font-semibold">
                        Place Order
                    </button>
                    
                    <p class="text-xs text-gray-600 mt-4 text-center">
                        By placing this order, you agree to our Terms of Service and Privacy Policy
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection