<?php
namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;

class PublisherAuthorController extends Controller
{
     // Display the form to create a new author
     public function create()
     {
         return view('publisher.authors.create'); // Points to the view we'll create next
     }

     public function store(Request $request)
     {
         // Validate the request and set a custom message if the author exists
         $validatedData = $request->validate([
             'name' => 'required|string|max:255|unique:authors,name', 
         ], [
             'name.unique' => 'ეს ავტორი უკვე დარეგისტრირებულია.', // Custom message
         ]);
     
         // Create a new author if validation passes
         Author::create($validatedData);
     
         // Redirect back with a success message
         return redirect()->route('publisher.dashboard')->with('success', 'ავტორი წარმატებით შეიქმნა.');
     }
     
}
