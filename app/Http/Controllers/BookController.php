<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\BookNews;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the books on the welcome page.
     */
    public function welcome()
    {
        $books = Book::orderBy('id','DESC')
        ->where('hide', '0')->paginate(8);
    
        // Get the cart item IDs for the authenticated user, if logged in
        $cartItemIds = [];
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItemIds = $cart->cartItems->pluck('book_id')->toArray(); // Get all book IDs in the user's cart
            }
        }

        $news = BookNews::latest()->paginate(4);
        $popularBooks = Book::orderBy('views', 'desc')->take(5)->get(); // Fetch top 3 most viewed books

    
        return view('welcome', compact('books', 'cartItemIds', 'news', 'popularBooks'));
    }


    public function books()
    {
        $books = Book::orderBy('id','DESC')->where('hide', '0')->paginate(12);
    
        // Get the cart item IDs for the authenticated user, if logged in
        $cartItemIds = [];
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItemIds = $cart->cartItems->pluck('book_id')->toArray(); // Get all book IDs in the user's cart
            }
        }

        $news = BookNews::latest()->paginate(4);
        $popularBooks = Book::orderBy('views', 'desc')->take(5)->get(); // Fetch top 3 most viewed books

    
        return view('books', compact('books', 'cartItemIds', 'news', 'popularBooks'));
    }




    
    

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('books.create', compact('authors', 'categories'));
    }



    
    public function full($title, $id)
{
    // Fetch the book by ID
    $book = Book::with('author')->where('hide', '0')->findOrFail($id);
    $book->increment('views'); // Increment views by 1

    // Optionally, ensure the title in the URL matches the book's actual title
    $slug = Str::slug($book->title);

    if ($slug !== $title) {
        // Redirect to the correct URL if the slug in the URL doesn't match the actual book title
        return redirect()->route('full', ['title' => $slug, 'id' => $book->id]);
    }

    $cartItemIds = [];
    if (Auth::check()) {
        $cart = Auth::user()->cart;
        if ($cart) {
            $cartItemIds = $cart->cartItems->pluck('book_id')->toArray(); // Get all book IDs in the user's cart
        }
    }

    $full_author = Author::first();

    // Pass the book to the view
    return view('full', compact('book', 'full_author', 'cartItemIds'));
}




 

    /**
     * Display the specified book's details.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('books.edit', compact('book', 'authors', 'categories'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|unique:books,title,' . $book->id,
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
            'full' => 'required',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'price' => 'required|numeric|min:0',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($book->photo && file_exists(storage_path('app/public/' . $book->photo))) {
                unlink(storage_path('app/public/' . $book->photo));
            }

            $path = $request->file('photo')->store('photos', 'public');
        } else {
            $path = $book->photo;
        }

        $book->update([
            'title' => $request->title,
            'photo' => $path,
            'description' => $request->description,
            'full' => $request->full,
            'category_id' => $request->category_id,
            'author_id' => $request->author_id,
            'price' => $request->price,
        ]);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        // Delete photo if exists
        if ($book->photo && file_exists(storage_path('app/public/' . $book->photo))) {
            unlink(storage_path('app/public/' . $book->photo));
        }

        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    /**
     * Search for books based on query.
     */
    public function search(Request $request)
{
    $searchTerm = $request->get('title', '');

    $books = Book::where('hide', 0) // Add this condition to only fetch visible books
        ->where(function ($query) use ($searchTerm) {
            $query->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('full', 'like', '%' . $searchTerm . '%');
        })
        ->orWhereHas('author', function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        })
        ->where('hide', 0) // Also apply it to author-related results
        ->orderBy('id', 'DESC')
        ->paginate(10)
        ->appends(['title' => $searchTerm]);

    $search_count = $books->total();

    $author = Author::get();

    $cartItemIds = [];
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItemIds = $cart->cartItems->pluck('book_id')->toArray(); // Get all book IDs in the user's cart
            } 
        }

    return view('search', compact('books', 'author', 'searchTerm', 'cartItemIds', 'search_count'));
}

    
    
    
}
