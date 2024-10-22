<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('id', 'DESC')->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $authors = Author::all();
        $categories = Category::all();
        $books = Book::all();
        return view('admin.books.create', compact('authors', 'categories', 'books'));
    }

    public function store(Request $request) 
{
    // Validate the form, including the new fields
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'price' => 'required|numeric',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate the photo field
        'description' => 'required|string',
        'quantity' => 'integer|min:1', // Validate quantity
        'views' => 'nullable|integer', 
        'full' => 'required|string',
        'author_id' => 'required|exists:authors,id',
        'category_id' => 'required|exists:categories,id',
        'status' => 'nullable|string', // Adding validation for status
        'pages' => 'nullable|string|max:255', // Adding validation for pages
        'publishing_date' => 'nullable|string', // Adding validation for publishing date
        'cover' => 'nullable|string|max:255', // Adding validation for cover type
    ]);

    // Check if the file is being received correctly
    if ($request->hasFile('photo')) {
        $imageName = $request->file('photo')->hashName();
        
        // Store the file in the "uploads/books" directory within "public storage"
        $path = $request->file('photo')->storeAs('uploads/books', $imageName, 'public');
    
        // Check if file was stored
        if (!$path) {
            return back()->with('error', 'File could not be saved.');
        }
    
        // Store only the relative path in the database without "public/"
        $validatedData['photo'] = 'uploads/books/' . $imageName;
    }

    // Create a new book record with the validated data
    Book::create($validatedData);

    return redirect()->route('admin.books.index')->with('success', 'Book created successfully.');
}

    


    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'authors', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        // Validate the form input, including the new fields
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'quantity' => 'integer|min:1', // Validate quantity
            'views' => 'nullable|integer', 
            'full' => 'required|string',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|string', // Adding validation for status
            'pages' => 'nullable|string|max:255', // Adding validation for pages
            'publishing_date' => 'nullable|string', // Adding validation for publishing date
            'cover' => 'nullable|string|max:255', // Adding validation for cover type
        ]);
    
        // Handle file upload for new photo
        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($book->photo && Storage::disk('public')->exists($book->photo)) {
                Storage::disk('public')->delete($book->photo);
            }
            
            // Upload new photo
            $photoPath = $request->file('photo')->store('uploads/books', 'public');
            $validatedData['photo'] = $photoPath;  // Save the new photo path to the validated data
        }
    
        // Update the book record with the validated data
        $book->update($validatedData);
    
        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully.');
    }
    


    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
    }

    public function toggleVisibility($id)
{
    $book = Book::findOrFail($id);
    $book->hide = !$book->hide; // Toggle the hide status
    $book->save();

    return redirect()->back()->with('success', 'მასალა დამალულია.');
}
}
