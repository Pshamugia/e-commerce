@extends('layouts.app')

@section('content')

<div class="container" style="position: relative; padding-top: 53px;">
    
    <h4> ძიების გაფილტვრა </h4>
    
    <!-- Filter Form -->
    <form action="{{ route('search') }}" method="GET" class="row g-3 mb-4">

        <div class="col-md-2">
            <input class="form-control me-2 styled-input" name="title" type="search" value="{{ request()->get('title') }}" placeholder="საძიებო სიტყვა..." aria-label="Search" id="searchInput">
        </div>

        <div class="col-md-2">
             <input type="number" name="price_from" class="form-control" id="price_from" placeholder="ფასი (დან)" value="{{ request('price_from') }}">
        </div>
        <div class="col-md-2">
             <input type="number" name="price_to" class="form-control" id="price_to" placeholder="ფასი (მდე)" value="{{ request('price_to') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="publishing_date" class="form-control" id="publishing_date" placeholder="გამოცემის წელი" min="1800" max="{{ date('Y') }}" value="{{ request('publishing_date') }}">
        </div>
        <div class="col-md-3">

            <select name="category_id" class="form-select" id="category_id">
                <option value="" style="color: #ccc"><span > --კატეგორია-- </span></option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ (request('category_id') == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
          
            
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-info"><span style="top: 3px !important; position: relative;">
                გაფილტრე </span>
            </button>
        </div>
    </form>

    <!-- Displaying Search Results -->
    <p> 
        @if ($search_count>0)
            მოიძებნა {{ $search_count }}
            @if ($search_count == 1)
                მასალა
            @else
                მასალა
            @endif
        @else
            {{ "ბაზაში ვერ მოიძებნა"}} 
            <span style="background-color: red !important; color:white">
                <b><u>{{ $searchTerm }}</u></b>
            </span>
        @endif
    </p>
    <hr>

    <div class="row">
        @foreach ($books as $book)
        <div class="col-md-6" style="position: relative; padding-bottom: 25px">
            <div class="card book-card">
                <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="card-link">
                    @if (isset($book->photo))
                        <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="cover" id="im">
                    @endif
                </a>
                <div class="card-body">
                    <h4><strong> {{ $book->title }} </strong></h4>
                    <p style="font-size: 14px">
                        <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => $book->author->name])}}" style="text-decoration: none">
                            {{ $book->author->name }}
                        </a> 
                    </p>
                    <p style="font-weight: bold; font-size: 18px" class="card-text">{{ number_format($book->price) }} <span style="color:#ccc"> &#x20BE; </span></p>
                    @if (in_array($book->id, $cartItemIds))
                        <button class="btn btn-success toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="true">
                            <span style="top: 3px !important; position: relative;">დამატებულია</span>
                        </button>
                    @else
                        <button class="btn btn-primary toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="false">
                            <span style="top: 3px !important; position: relative;">დაამატე კალათაში</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <br><br>
    {{ $books->links('pagination.custom-pagination') }}
</div>

@endsection

 
@section('scripts')
<script>
    // AJAX for adding/removing from cart
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
@endsection
