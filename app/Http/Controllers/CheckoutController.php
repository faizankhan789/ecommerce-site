<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
            
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        
        $shipping = $subtotal > 50 ? 0 : 10;
        $tax = $subtotal * 0.10; // 10% tax
        $total = $subtotal + $shipping + $tax;
        
        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'payment_method' => 'required|in:cod,card'
        ]);
        
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
            
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        DB::beginTransaction();
        
        try {
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });
            
            $shipping = $subtotal > 50 ? 0 : 10;
            $tax = $subtotal * 0.10;
            $total = $subtotal + $shipping + $tax;
            
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method == 'cod' ? 'pending' : 'pending',
                'shipping_address' => [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                ],
                'notes' => $request->notes
            ]);
            
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->quantity * $item->price
                ]);
                
                // Decrease product stock
                $item->product->decrementStock($item->quantity);
            }
            
            // Clear cart
            Cart::where('user_id', Auth::id())->delete();
            
            DB::commit();
            
            if ($request->payment_method == 'card') {
                // Redirect to payment gateway
                return redirect()->route('payment.process', $order->id);
            }
            
            return redirect()->route('orders.success', $order->order_number);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}