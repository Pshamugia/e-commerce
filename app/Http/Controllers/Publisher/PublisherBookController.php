<?php

namespace App\Http\Controllers\Publisher;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class PublisherBookController extends Controller
{
    public function create()
{
    $authors = Author::all(); // Fetch authors
    $categories = Category::all(); // Fetch categories
    $isHomePage = false;
    return view('publisher.create', compact('authors', 'categories', 'isHomePage'));
}


    public function store(Request $request)
{
    Log::info('PublisherBookController store method called');

    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'price' => 'required|numeric',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'description' => 'required|string',
        'quantity' => 'integer|min:1',
        'views' => 'nullable|integer',
        'full' => 'required|string',
        'author_id' => 'required|exists:authors,id',
        'category_id' => 'required|exists:categories,id',
        'status' => 'nullable|string',
        'pages' => 'nullable|string|max:255',
        'publishing_date' => 'nullable|string',
        'cover' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed:', $validator->errors()->toArray());
        return back()->withErrors($validator)->withInput();
    }

    $validatedData = $validator->validated();

    if ($request->hasFile('photo')) {
        Log::info('Photo is being received');
        $imageName = $request->file('photo')->hashName();
        $path = $request->file('photo')->storeAs('uploads/books', $imageName, 'public');
        if (!$path) {
            Log::error('File could not be saved');
            return back()->with('error', 'File could not be saved.');
        }
        $validatedData['photo'] = 'uploads/books/' . $imageName;
    }

    $validatedData['hide'] = 1;
    $validatedData['uploader_id'] = Auth::id();

    Log::info('Validated data:', $validatedData);

    Book::create($validatedData);
    

    return redirect()->route('publisher.dashboard')->with('success', 'თქვენი წიგნი აიტვირთა და მოდერაციის დასრულების შემდეგ გამოჩნდება ვებგვერდზე.');
}

public function myBooks()
    {
        // Retrieve books uploaded by the authenticated publisher
        $books = Book::where('uploader_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        $isHomePage = false;
        // Return view with the books data
        return view('publisher.books.my_books', compact('books', 'isHomePage'));
    }

    
}
