<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; // Use the full namespace for Log

class AdminPublisherController extends Controller
{
    public function activity()
    {
        $publishers = User::where('role', 'publisher')->with('books')->get();
    
        // Log publisher data for debugging
        Log::info('Publishers and Books:', $publishers->toArray());
    
        return view('admin.publishers.activity', compact('publishers'));
    }


    public function toggleVisibility($id)
    {
        $book = Book::findOrFail($id);
    
        // Toggle the hide status
        $book->hide = !$book->hide;
        $book->save();
    
        return response()->json([
            'success' => true,
            'hide' => $book->hide,
            'message' => $book->hide ? 'Your book is hidden' : 'Your book is visible for website users',
        ]);
    }
    
    
}

