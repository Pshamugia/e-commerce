@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')

<div class="container" style="position: relative; padding-top: 53px;">

    <h4><i class="bi bi-search-heart-fill"></i> ძიების შედეგები </h4>
    <p> @if ($search_count>0)
      მოიძებნა {{ $search_count }}
        
        @if ($search_count==1)
    
    მასალა  
    
        @else
    
    
      მასალა  
        @endif
        @else
    
        {{ "ბაზაში ვერ მოიძებნა"}} 
        <span style="background-color: red !important, color:white"> 
            <b><u> {{ $searchTerm }} </u></b> </span>
    
        @endif
    
    </p>
    <hr>
            @foreach ($books as $book)
           
           
                <a href="{{ route('full', ['id' => $book->id, 'title' => Str::slug($book->title)]) }}">
                    <h2 style="padding-top: 10px; font-size: 16px">     
                        {{ $book->author->name }} - {{ $book->title }}    </h2>
                </a>
             <!-- Display the category name -->
        
            @endforeach
            <br><br>
            {{ $books->links('pagination.custom-pagination') }}

</div>

@endsection