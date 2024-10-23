@extends('layouts.app')

@section('title', $newsItem->title)  
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<div class="container mt-5" style="position: relative; top: 53px; padding-bottom: 5%">
    <div class="row">
        <!-- Book Image -->
        <div class="col-md-5">
            @if($newsItem->image)
            <img src="{{ asset('storage/' . $newsItem->image) }}" alt="{{ $newsItem->title }}" class="img-thumbnail cover image-and-share-container" id="im">
        @endif
        <div class="share-buttons col-md-12" style="text-align:left; margin-top: 20px;">
            <!-- Facebook -->
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" target="_blank" class="btn facebook-btn">
                <i class="bi bi-facebook"></i>  
            </a>
        
            <!-- Twitter -->
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}&text=Check this out!" target="_blank" class="btn twitter-btn">
                <i class="bi bi-twitter"></i>  
            </a>
        
            <!-- WhatsApp -->
            <a href="https://api.whatsapp.com/send?text=Check this out! {{ urlencode(Request::fullUrl()) }}" target="_blank" class="btn whatsapp-btn">
                <i class="bi bi-whatsapp"></i>  
            </a>
        </div>
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
                
                <p>{!! $newsItem->full ?? 'ტექსტი არ არის დამატებული.' !!}</p>

                 

                

            </div>
        </div>
    </div>
</div>
@endsection
 
 