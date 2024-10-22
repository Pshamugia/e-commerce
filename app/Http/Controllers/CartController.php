<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
{
    $cart = Auth::user()->cart()->with('cartItems.book.author')->first(); // Ensure you're getting a single cart instance

    $subtotal = 0;
    $shipping = 0; // Example fixed shipping cost

    if ($cart) {
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    $total = $subtotal + $shipping;

    return view('cart', compact('cart', 'subtotal', 'shipping', 'total'));
}


    /**
     * Add a book to the cart.
     */
    public function add(Request $request, Book $book)
{
    // Fetch the user's cart
    $cart = Auth::user()->cart()->firstOrCreate([
        'user_id' => Auth::id(),
    ]);

    // Check if the book is already in the cart
    $cartItem = $cart->cartItems()->where('book_id', $book->id)->first();

    if ($cartItem) {
        // If the book is already in the cart, update the quantity
        $cartItem->quantity += $request->input('quantity', 1);
        $cartItem->save();
    } else {
        // Otherwise, add the book to the cart
        $cart->cartItems()->create([
            'book_id' => $book->id,
            'quantity' => $request->input('quantity', 1),
            'price' => $book->price,
        ]);
    }

    // Return the updated cart count
    return response()->json([
        'status' => 'added',
        'cartCount' => $cart->cartItems->count(),
    ]);
}


    /**
     * Remove a book from the cart.
     */
    public function remove($bookId)
    {
        // Get the user's cart
        $cart = Auth::user()->cart;

        if (!$cart) {
            return redirect()->route('cart.show')->with('error', 'შნი კალათა ცარიელია.');
        }

        // Find the cart item by book ID and remove it
        $cartItem = $cart->cartItems()->where('book_id', $bookId)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart.show')->with('success', 'წიგნი წაშლილია კალათიდან.');
    }

    /**
     * Update the quantity of a book in the cart.
     */
    public function updateQuantity(Request $request, $bookId)
    {
        // Validate the input for quantity
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Get the user's cart
        $cart = Auth::user()->cart;

        if (!$cart) {
            return redirect()->route('cart.show')->with('error', 'შენი კალათ ცარიელია.');
        }

        // Find the cart item by book ID
        $cartItem = $cart->cartItems()->where('book_id', $bookId)->first();

        if ($cartItem) {
            $cartItem->quantity = $request->input('quantity');
            $cartItem->save();
        }

        return redirect()->route('cart.show')->with('success', 'Cart updated.');
    }

    public function toggle(Request $request)
    {
        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity', 1); // Get quantity or default to 1
        $cart = Auth::user()->cart;
    
        // Check if the book is already in the cart
        $cartItem = $cart->cartItems()->where('book_id', $bookId)->first();
    
        if ($cartItem) {
            // If it's in the cart, remove it
            $cartItem->delete();
            $action = 'removed';
        } else {
            // If it's not in the cart, add it with the quantity
            $cart->cartItems()->create([
                'book_id' => $bookId,
                'quantity' => $quantity,
                'price' => Book::find($bookId)->price,
            ]);
            $action = 'added';
        }
    
        return response()->json([
            'success' => true,
            'action' => $action,
            'cart_count' => $cart->cartItems->count(),
        ]);
    }
    

}
