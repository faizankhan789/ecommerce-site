<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        
        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        if ($product->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available'
            ], 400);
        }
        
        $cartIdentifier = $this->getCartIdentifier();
        
        $cartItem = Cart::where(function ($query) use ($cartIdentifier) {
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', $cartIdentifier);
            }
        })->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->price = $product->price;
            $cartItem->save();
        } else {
            Cart::create([
                'session_id' => Auth::check() ? null : $cartIdentifier,
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }
        
        $cartCount = $this->getCartCount();
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $cartCount
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cartItem = Cart::findOrFail($id);
        
        if ($cartItem->product->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available'
            ], 400);
        }
        
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'total' => $cartItem->total
        ]);
    }

    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function getCartCount()
    {
        return $this->getCartItems()->sum('quantity');
    }

    private function getCartIdentifier()
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', uniqid('cart_', true));
        }
        return Session::get('cart_session_id');
    }

    private function getCartItems()
    {
        $cartIdentifier = $this->getCartIdentifier();
        
        return Cart::with('product')
            ->where(function ($query) use ($cartIdentifier) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('session_id', $cartIdentifier);
                }
            })->get();
    }
}