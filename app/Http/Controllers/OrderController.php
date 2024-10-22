<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        // Check if the user has any orders
        $orders = Auth::user()->orders()->with('orderItems.book')->paginate(10);

        // Handle case where there are no orders
        if ($orders->isEmpty()) {
            return view('orders', ['orders' => []])->with('info', 'You have no orders yet.');
        }

        return view('orders', compact('orders'));
    }

    /**
     * Show the form for creating a new order (Checkout).
     */
    public function create()
    {
        // Ensure the user has a cart
        $cart = Auth::user()->cart;

        // Handle case where the cart is empty
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $shipping = 10.00; // Fixed shipping cost; adjust as needed
        $total = $subtotal + $shipping;

        return view('orders.create', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // Ensure the user has a cart
        $cart = Auth::user()->cart;

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $shipping = 10.00; // Fixed shipping cost; adjust as needed
        $total = $subtotal + $shipping;

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Create order items based on the cart items
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $cartItem->book_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ]);
        }

        // Clear the user's cart after the order is created
        $cart->cartItems()->delete();
        $cart->delete();

        return redirect()->route('orders.index')->with('success', 'Your order has been placed successfully.');
    }

    /**
     * Display the specified order details.
     */
    public function show($id)
    {
        // Ensure the user has access to the order
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('orderItems.book')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel (delete) an order.
     */
    public function destroy($id)
    {
        // Ensure the user has access to the order
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Cancel the order by deleting it
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Your order has been cancelled.');
    }


    // OrderController.php
    public function checkout(Request $request)
{
    // Validate user inputs
    $validatedData = $request->validate([
        'payment_method' => 'required|string',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'address' => 'required|string|max:255',
    ]);

    // Ensure the user has a cart
    $cart = Auth::user()->cart;

    if (!$cart || $cart->cartItems->isEmpty()) {
        return redirect()->route('cart.show')->with('error', 'Your cart is empty.');
    }

    // Calculate totals
    $subtotal = $cart->cartItems->sum(function ($item) {
        return $item->price * $item->quantity;
    });
    $shipping = 10.00;
    $total = $subtotal + $shipping;

    // Create the order
    $order = Order::create([
        'user_id' => Auth::id(),
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total' => $total,
        'status' => 'pending',  // Adjust status as needed
    ]);

    // Create order items
    foreach ($cart->cartItems as $cartItem) {
        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $cartItem->book_id,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->price,
        ]);
    }

    // Clear the user's cart
    $cart->cartItems()->delete();
    $cart->delete();

    // Redirect based on payment method
    if ($validatedData['payment_method'] === 'courier') {
        return redirect()->route('order_courier', ['order' => $order->id])->with('success', 'Your order has been received. Pay with the courier.');
    }

    return redirect()->back()->with('success', 'Proceed with bank transfer.');
}

public function orderCourier($orderId)
{
    // Retrieve the specific order by ID
    $order = Order::where('id', $orderId)
                  ->where('user_id', Auth::id())
                  ->with('orderItems.book') // Include order items and related books
                  ->firstOrFail();

    return view('order_courier', compact('order'));
}




}
