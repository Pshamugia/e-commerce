@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')

     
<h5 class="section-title" style="position: relative; margin-bottom:25px; padding-top:55px; padding-bottom:25px; align-items: left;
    justify-content: left;">     <strong>
        <i class="bi bi-stack-overflow"></i> წიგნები
    </strong>
</h5>

    <!-- Featured Books -->
<div class="container mt-5" style="postion:relative; margin-top: -15px !important">
  
    <div class="row">
        @foreach ($books as $book)
    <div class="col-md-3" style="position: relative; padding-bottom: 25px;">
            <div class="card book-card">
                <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="card-link">

            @if (isset($book->photo))
                <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="cover" id="im">
            @endif
                </a>
            <div class="card-body">
                <h4 ><strong> {{ $book->title }} </strong></h4>
                <p style="font-weight: bold; font-size: 18px" class="card-text">{{ number_format($book->price) }} <span style="color:#ccc"> &#x20BE; </span></p>

                @if (in_array($book->id, $cartItemIds))
    <button class="btn btn-success toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="true">
        <span style="top: 3px !important; position: relative;"> დამატებულია </span>
    </button>
@else
    <button class="btn btn-primary toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="false">
        <span style="top: 3px !important; position: relative;"> დაამატე კალათაში </span>
    </button>
@endif
 

            </div>
        </div>
    </div>
@endforeach

       
    </div>
    {{ $books->links('pagination.custom-pagination') }}
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