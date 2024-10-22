@extends('layouts.app')

@section('title', $newsItem->title)  
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<div class="container mt-5" style="position: relative; top: 53px;">
    <div class="row">
        <!-- Book Image -->
        <div class="col-md-5">
            @if($newsItem->image)
            <img src="{{ asset('storage/' . $newsItem->image) }}" alt="{{ $newsItem->title }}" class="img-thumbnail cover" id="im">
        @endif
        </div>

        <!-- Book Details -->
        <div class="col-md-7">
            <h2>{{ $newsItem->title }}</h2>
            
             <p>{{ \Carbon\Carbon::parse($newsItem->date_added)->format('d/m/Y') }}</p>

            <!-- Quantity Selector -->
            <div class="mb-3">
                 <div class="input-group" style="width: 150px;">
                     
                </div>
                
                 
            </div>

            <!-- Add to Cart Button -->
 

            <!-- Book Description -->
            <div class="mt-4">
                
                <p>{{ $newsItem->full ?? 'ტექსტი არ არის დამატებული.' }}</p>

                 

                

            </div>
        </div>
    </div>
</div>
@endsection
 
 