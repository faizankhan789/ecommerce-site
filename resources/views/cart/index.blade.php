@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>
        
        @if($cartItems->count() > 0)
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center py-4 border-b last:border-0" data-cart-item="{{ $item->id }}">
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    @if($item->product->image)
                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="text-2xl">📦</span>
                                    @endif
                                </div>
                                
                                <div class="flex-1 ml-4">
                                    <h3 class="font-semibold">{{ $item->product->name }}</h3>
                                    <p class="text-gray-600 text-sm">SKU: {{ $item->product->sku }}</p>
                                    <p class="text-blue-600 font-semibold">${{ number_format($item->price, 2) }}</p>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateQuantity({{ $item->id }}, -1)" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input type="number" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}" 
                                           class="w-16 text-center border rounded-md" 
                                           onchange="updateQuantity({{ $item->id }}, this.value, true)">
                                    <button onclick="updateQuantity({{ $item->id }}, 1)" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="ml-4 text-right">
                                    <p class="font-semibold" data-item-total="{{ $item->id }}">${{ number_format($item->total, 2) }}</p>
                                    <button onclick="removeFromCart({{ $item->id }})" class="text-red-500 hover:text-red-700 text-sm mt-1">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                        
                        <div class="space-y-2 border-b pb-4 mb-4">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span data-subtotal>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span>{{ $subtotal > 50 ? 'FREE' : '$10.00' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tax (10%)</span>
                                <span>${{ number_format($subtotal * 0.10, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between font-semibold text-lg mb-6">
                            <span>Total</span>
                            <span data-total>${{ number_format($subtotal + ($subtotal > 50 ? 0 : 10) + ($subtotal * 0.10), 2) }}</span>
                        </div>
                        
                        @auth
                            <a href="{{ route('checkout.index') }}" class="block w-full bg-blue-600 text-white text-center py-3 rounded-md hover:bg-blue-700">
                                Proceed to Checkout
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-blue-600 text-white text-center py-3 rounded-md hover:bg-blue-700">
                                Login to Checkout
                            </a>
                        @endauth
                        
                        <a href="{{ route('products.index') }}" class="block text-center mt-4 text-blue-600 hover:text-blue-700">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-semibold mb-4">Your cart is empty</h2>
                <p class="text-gray-600 mb-8">Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function updateQuantity(itemId, change, absolute = false) {
    const input = document.querySelector(`[data-cart-item="${itemId}"] input[type="number"]`);
    let newQuantity = absolute ? parseInt(change) : parseInt(input.value) + change;
    
    if (newQuantity < 1) return;
    
    fetch(`/cart/update/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = newQuantity;
            document.querySelector(`[data-item-total="${itemId}"]`).textContent = '$' + data.total.toFixed(2);
            updateOrderSummary();
        } else {
            alert(data.message);
        }
    });
}

function removeFromCart(itemId) {
    if (confirm('Are you sure you want to remove this item?')) {
        fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-cart-item="${itemId}"]`).remove();
                updateCartCount(data.cart_count);
                updateOrderSummary();
                
                // Reload if cart is empty
                if (data.cart_count === 0) {
                    location.reload();
                }
            }
        });
    }
}

function updateOrderSummary() {
    // Recalculate totals
    let subtotal = 0;
    document.querySelectorAll('[data-item-total]').forEach(el => {
        subtotal += parseFloat(el.textContent.replace('$', ''));
    });
    
    const shipping = subtotal > 50 ? 0 : 10;
    const tax = subtotal * 0.10;
    const total = subtotal + shipping + tax;
    
    document.querySelector('[data-subtotal]').textContent = '$' + subtotal.toFixed(2);
    document.querySelector('[data-total]').textContent = '$' + total.toFixed(2);
}

function updateCartCount(count) {
    const cartBadge = document.querySelector('.cart-count');
    if (cartBadge) {
        cartBadge.textContent = count;
    }
}
</script>
@endsection