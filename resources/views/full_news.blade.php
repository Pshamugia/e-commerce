@extends('layouts.app')

@section('title', $booknews->title)  

@section('content')
<div class="container mt-5" style="position: relative; top: 53px; padding-bottom: 5%">
    <div class="row">
        <!-- Book Image -->
        <div class="col-md-5">
            @if($booknews->image)
            <img src="{{ asset('storage/' . $booknews->image) }}" alt="{{ $booknews->title }}" class="img-thumbnail cover image-and-share-container" id="im">
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
        <div class="col-md-7" style="position: relative; margin-top:22px">
            <h2>{{ $booknews->title }}</h2>
            
             <p>{{ \Carbon\Carbon::parse($booknews->date_added)->format('d/m/Y') }}</p>

            <!-- Quantity Selector -->
         

            <!-- Add to Cart Button -->
 

            <!-- Book Description -->
            <div class="mt-4">
                
                <p>{!! $booknews->full ?? 'ტექსტი არ არის დამატებული.' !!}</p>

                 

                

            </div>
        </div>
    </div>
</div>
@endsection
 
 