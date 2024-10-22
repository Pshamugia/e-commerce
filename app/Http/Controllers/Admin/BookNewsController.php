<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BookNewsController extends Controller
{
    public function index()
    {
        $news = BookNews::latest()->paginate(10);
        return view('admin.book-news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.book-news.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'full' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image upload
     // Check if the file is being received correctly
    if ($request->hasFile('image')) {
        $imageName = $request->file('image')->hashName();
        
        // Store the file in the "uploads/books" directory within "public"
        $path = $request->file('image')->storeAs('uploads/books', $imageName, 'public');

        // Check if file was stored
        if (!$path) {
            return back()->with('error', 'File could not be saved.');
        }

        $validatedData['image'] = 'uploads/books/' . $imageName; // Save the full path to the database
    } else {
        return back()->with('error', 'No photo uploaded.');
    }

        

        BookNews::create($validatedData);

        return redirect()->route('admin.book-news.index')->with('success', 'Book News created successfully.');
    }

    public function edit(BookNews $bookNews)
    {
        return view('admin.book-news.edit', compact('bookNews'));
    }

  

public function update(Request $request, BookNews $bookNews)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'full' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Handle image upload and delete old image
    if ($request->hasFile('image')) {
        Log::info('Image upload detected.');

        // Delete old image if exists
        if ($bookNews->image && Storage::disk('public')->exists($bookNews->image)) {
            Storage::disk('public')->delete($bookNews->image);
            Log::info('Old image deleted: ' . $bookNews->image);
        }

        // Upload new image
        $imageName = time() . '.' . $request->image->extension();
        $request->image->storeAs('uploads/book_news', $imageName, 'public');
        $validatedData['image'] = 'uploads/book_news/' . $imageName;

        Log::info('New image uploaded: ' . $validatedData['image']);
    }

    // Update the book news with the new data
    $bookNews->update($validatedData);
    Log::info('Book News updated successfully.');

    return redirect()->route('admin.book-news.index')->with('success', 'Book News updated successfully.');
}



    public function destroy(BookNews $bookNews)
    {
        if ($bookNews->image && Storage::disk('public')->exists('uploads/book_news/' . $bookNews->image)) {
            Storage::disk('public')->delete('uploads/book-news/' . $bookNews->image);
        }

        $bookNews->delete();
        return redirect()->route('admin.book-news.index')->with('success', 'Book News deleted successfully.');
    }

    
}
