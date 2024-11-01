<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TbcCheckoutController extends Controller
{
    public function initializePayment(Request $request)
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
        return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    }

    // Calculate totals
    $subtotal = $cart->cartItems->sum(fn($item) => $item->price * $item->quantity);
    $shipping = 10.00;
    $total = $subtotal + $shipping;

    // Create the order
    $order = Order::create([
        'user_id' => Auth::id(),
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total' => $total,
        'status' => 'pending',
        'order_id' => 'ORD-' . uniqid(),
        'payment_method' => $validatedData['payment_method'],
        'name' => $validatedData['name'],
        'phone' => $validatedData['phone'],
        'address' => $validatedData['address'],
    ]);

    // Handle payment redirection
    if ($validatedData['payment_method'] === 'courier') {
        return redirect()->route('order_courier', ['order' => $order->id])->with('success', 'Your order has been received. Pay with the courier.');
    } elseif ($validatedData['payment_method'] === 'bank_transfer') {
        return redirect()->route('tbc.show', ['order_id' => $order->order_id, 'total' => $total])->with('success', 'Proceeding to bank transfer.');
    }
}

    

     

    
    // Separate method to handle TBC bank transfer
    protected function redirectToTbcPayment($total, $order)
{
    Log::info("Initiating TBC payment for order: {$order->order_id}");

    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . base64_encode(config('services.tbc.api_key') . ':' . config('services.tbc.api_secret')),
        'Content-Type' => 'application/json',
    ])->post(config('services.tbc.api_url'), [
        'amount' => $total * 100, // Amount in cents
        'currency' => 'GEL',
        'returnUrl' => route('tbc.callback'),
        'orderId' => $order->order_id,
        'description' => 'Order from Bukinistebi',
    ]);

    // Debug the response from TBC
    if ($response->successful()) {
        redirect()->away($response->json('paymentUrl')); // Use this to check TBC's response.
    } else {
        Log::error('Payment initiation failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        return back()->withErrors(['message' => 'Payment initiation failed.']);
    }
}
    
    

    public function handleCallback(Request $request)
{
    Log::info("Callback received", [
        'order_id' => $request->query('order_id'),
        'status' => $request->query('status')
    ]);

    $orderId = $request->query('order_id');
    $status = $request->query('status');

    // Find the order by order_id
    $order = Order::where('order_id', $orderId)->first();

    if (!$order) {
        Log::error("Order not found for order_id: {$orderId}");
        return redirect()->route('welcome')->with('error', 'Order not found.');
    }

    if ($status === 'approved') {
        $order->update(['status' => 'approved']);

        // Clear the cart now that payment is successful
        $cart = Auth::user()->cart;
        if ($cart) {
            $cart->cartItems()->delete();
            $cart->delete();
        }

        return redirect()->route('welcome')->with('success', 'Payment successful!');
    } else {
        $order->update(['status' => 'failed']);
        return redirect()->route('tbc-checkout')->with('error', 'Payment failed or canceled.');
    }
}


public function showTbcCheckout($order_id, $total)
{
    $order = Order::where('order_id', $order_id)->first();
    
    if (!$order) {
        return redirect()->route('cart.index')->with('error', 'Order not found.');
    }

    // Render the tbc-checkout view and pass necessary data
    return view('tbc-checkout', compact('order', 'total'));
}


public function processPayment(Request $request)
{
    Log::info('ProcessPayment method called'); // Add this line to confirm method entry

    $orderId = $request->input('order_id');
    $total = $request->input('total');

    // Find the order
    $order = Order::where('order_id', $orderId)->first();

    if (!$order) {
        Log::error('Order not found'); // Add this line to log an error if order is not found
        return redirect()->route('cart.index')->with('error', 'Order not found.');
    }

    Log::info('Order found, initiating TBC API request'); // Log order found and API request start

    // Make the TBC Bank API request
    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . base64_encode(config('services.tbc.api_key') . ':' . config('services.tbc.api_secret')),
        'Content-Type' => 'application/json',
    ])->post(config('services.tbc.api_url'), [
        'amount' => $total * 100, // Amount in cents
        'currency' => 'GEL',
        'returnUrl' => route('tbc.callback'),
        'orderId' => $order->order_id,
        'description' => 'Order from Bukinistebi',
    ]);

    Log::info('TBC API response', ['status' => $response->status(), 'body' => $response->body()]);

    if ($response->successful()) {
        Log::info('Redirecting to payment URL'); // Log successful response
        return redirect()->away($response->json('paymentUrl'));
    } else {
        Log::error('Payment initiation failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        return back()->withErrors(['message' => 'Payment initiation failed.']);
    }
}





public function startPayment(Request $request, $order_id)
{
    $order = Order::where('order_id', $order_id)->first();

    if (!$order) {
        return redirect()->route('cart.index')->withErrors('Order not found.');
    }

    $total = $request->input('total');

    // TBC Bank API request
    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . base64_encode(config('services.tbc.api_key') . ':' . config('services.tbc.api_secret')),
        'Content-Type' => 'application/json',
    ])->post(config('services.tbc.api_url'), [
        'amount' => $total * 100, // Amount in cents
        'currency' => 'GEL',
        'returnUrl' => route('tbc.callback'),
        'orderId' => $order->order_id,
        'description' => 'Order from Bukinistebi',
    ]);

    if ($response->successful()) {
        // Redirect user to the payment URL
        return redirect()->away($response->json('paymentUrl'));
    } else {
        Log::error('Payment initiation failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        return back()->withErrors(['message' => 'Payment initiation failed.']);
    }
}




}
