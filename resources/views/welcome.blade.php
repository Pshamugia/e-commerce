@extends('layouts.app')

@section('title', 'Bukinistebi.ge - Online Bookshop')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
@section('content')
<!-- Hero Section -->
<div class="hero-section" style="background: url('{{ asset('uploads/book9.jpg') }}') no-repeat center center; background-size: cover; background-attachment: fixed;">
    <div class="hero-content" style="position: relative; padding-top: 10px;">
        <h1>ბუკინისტური მაღაზია</h1>
        <p>რასაც ეძებ - აქაა</p>
        <a href="#" class="btn btn-outline-light">იყიდე</a>
    </div>
</div>
 
<!-- Featured Books -->
<div class="container mt-5">
        
            <div class="hr-with-text">
                <h2 style="position: relative; font-size: 26px; ">
                    
               ახალი დამატებული  </h2>
                </div>
            
            
        
  
    <div class="row">
        @foreach ($books as $book)
    <div class="col-md-3" style="position: relative; padding-bottom: 25px">
            <div class="card book-card">
                <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="card-link">

                    @if (isset($book->photo))
                    <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="cover" id="im" style="border-radius: 5px 5px 0 0;">
            @endif
                </a>
            <div class="card-body">
                <h4 ><strong> {{ $book->title }} </strong></h4>
                <p style="font-size: 14px">
                    <a href="{{ route('full_author', ['id' =>$book->author_id, 'name' => $book->author->name])}}" style="text-decoration: none">
                        {{ $book->author->name }} 
                    </a> 
                </p>
                <p style="font-weight: bold; font-size: 18px" class="card-text">{{ number_format($book->price) }} <span style="color:#ccc"> &#x20BE; </span></p>

                @if (in_array($book->id, $cartItemIds))
    <button class="btn btn-success toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="true">
        <span style="top: 3px !important; position: relative;">  დამატებულია </span>
    </button>
@else
    <button class="btn btn-primary toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="false">
        <span style="top: 3px !important; position: relative;"> დაამატე კალათაში </span>
    </button>
@endif
<!--
<a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="read-more-link">
    ვრცლად <i class="bi bi-arrow-right-circle"></i>
</a> -->

            </div>
        </div>
    </div>
@endforeach
 
    </div>
 
</div>

<!-- Overlay Section -->

<div class="overlay-section">
    <div class="fixed-background" style="background: url('{{ asset('uploads/book1.jpg') }}') no-repeat center center; background-size: cover; background-attachment: fixed;"></div>
    <div class="overlay-content">
        <h2>გახდი ჩვენი პარტნიორი</h2>
        <p>გაყიდე ბუკინისტური წიგნები ჩვენი პლატფორმიდან</p>  
        <h2><a href="#" class="btn btn-outline-light" style="font-size: 2vw ">დაგვიკავშირდი</a></h2>
    </div>
</div>
 

<!-- New Sections: Book News and Popular Books -->
<div class="container mt-5">
    <div class="row">
        <!-- Book News (Two Columns) -->
        <div class="col-md-8">
            
<div class="hr-with-text">
    <h2 style="position: relative; font-size: 26px;">
        
ბუკინისტური ამბები  </h2>
    </div>            <div class="row">
                @foreach($news as $item)
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{ route('full_news', ['title' => Str::slug($item->title), 'id' => $item->id]) }}" class="card-link">

                            @if (isset($item->image))
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="cover img-fluid mb-2" id="im">
                            @endif
                            </a>
                            <h5 class="card-title">{{ $item->title }}</h5>


                            <p class="card-text">{{ $item->description }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Popular Books (One Column) -->
        <!-- Popular Books (One Block) -->
<div class="col-md-4">
    <h5 class="section-title" style="position: relative; margin-bottom: 20px; padding-bottom:20px; align-items: left;
    justify-content: left;"> 
     <i class="bi bi-stickies-fill"></i> ხშირად ნანახი </h5>
    <div class="card mb-3 p-3">
        @foreach($popularBooks as $index => $book)
            <div class="popular-book-item mb-2">
                 <span class="book-title">{{ $book->title }}</span> <br>
                <a href="{{ route('full_author', ['id' =>$book->author_id, 'name' => $book->author->name])}}" style="text-decoration: none">
                    <span>
                        {{ $book->author->name }}
                    </span>
                </a>
                <div class="book-details">
                    <span><i class="bi bi-eye"></i> {{ $book->views }} </span>   
                    <span><i class="bi bi-credit-card-2-front"></i> {{ number_format($book->price) }} &#x20BE;</span>
                </div>
            </div>
            @if (!$loop->last)
                <hr> <!-- Add horizontal line between books -->
            @endif
        @endforeach
    </div>
</div>

        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

 



@endsection

<script>
    $(document).ready(function() {
      $('.toggle-cart-btn').click(function() {
          var button = $(this);
          var bookId = button.data('product-id');
          var inCart = button.data('in-cart');
  
          $.ajax({
              url: '{{ route("cart.toggle") }}',
              method: 'POST',
              data: {
                  _token: '{{ csrf_token() }}',
                  book_id: bookId
              },
              success: function(response) {
                  if (response.success) {
                      if (response.action === 'added') {
                          button.removeClass('btn-primary').addClass('btn-success');
                          button.text('დამატებულია');
                          button.data('in-cart', true);
                      } else if (response.action === 'removed') {
                          button.removeClass('btn-success').addClass('btn-primary');
                          button.text('დაამატე კალათაში');
                          button.data('in-cart', false);
                      }
  
                      // Update the cart count in the navbar
                      $('#cart-count').text(response.cart_count);
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX Error:', error);
                  alert('კალათის გამოსაყენებლად გაიარეთ ავტორიზაცია');
              }
          });
      });
  });
  
  
  
  </script>