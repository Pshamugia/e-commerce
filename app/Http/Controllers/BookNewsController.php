<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookNews; 
use App\Models\Author; 
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;

class BookNewsController extends Controller
{
    public function index()
    {
        $news = BookNews::latest()->paginate(6);
        return view('book_news.index', compact('news'));
    }

    public function show($id)
    {
        $newsItem = BookNews::findOrFail($id);
        return view('book_news.show', compact('newsItem'));
    }

    public function full_news($title, $id)
{
    // Fetch the news article by ID
    $newsItem = BookNews::findOrFail($id);  // Change $book to $newsItem

    // Optionally, ensure the title in the URL matches the news item's actual title
    $slug = Str::slug($newsItem->title);

    if ($slug !== $title) {
        // Redirect to the correct URL if the slug in the URL doesn't match the actual news title
        return redirect()->route('full_news', ['title' => $slug, 'id' => $newsItem->id]);
    }

    // Pass the newsItem to the view
    return view('full_news', compact('newsItem'));
}
}
