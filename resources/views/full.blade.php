@extends('layouts.app')

@section('title', $book->title)  
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<div class="container mt-5" style="position: relative; top: 53px; padding-bottom: 5%">
    <div class="row">
        <!-- Book Image -->
        <div class="col-md-5">
            @if($book->photo)
            <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="img-fluid" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal" id="thumbnailImage">
            @else
                <img src="{{ asset('public/uploads/default-book.jpg') }}" alt="Default Image" class="img-fluid rounded shadow">
            @endif
        </div>

        <!-- Book Details -->
        <div class="col-md-7">
            <h2>{{ $book->title }}</h2>
            <p class="text-muted">ავტორი:
                <a href="{{ route('full_author', ['id' =>$book->author_id, 'name' => $book->author->name])}}" style="text-decoration: none"> 
                {{ $book->author->name }} 
            </a></p>
            <p><strong>ფასი:</strong> <span id="price">{{ number_format($book->price) }}</span></p>
            <p>{{ \Carbon\Carbon::parse($book->date_added)->format('d/m/Y') }}</p>

            <!-- Quantity Selector -->
            <div class="mb-3">
                 <div class="input-group" style="width: 150px;">
                    <button class="btn btn-outline-secondary decrease-quantity" type="button">-</button>
                    <input type="text" class="form-control form-control-sm text-center quantity-input" id="quantity" value="1" readonly>
                    <button class="btn btn-outline-secondary increase-quantity" type="button">+</button>
                </div>
                
                <input type="hidden" id="max-quantity" value="{{ $book->quantity }}"> <!-- Hidden input for max quantity -->
                
            </div>

            <!-- Add to Cart Button -->
<!-- Add to Cart Button -->
@if (in_array($book->id, $cartItemIds))
    <button class="btn btn-success toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="true">
        დამატებულია
    </button>
@else
    <button class="btn btn-primary toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="false">
        დაამატე კალათაში
    </button>
@endif

            <!-- Book Description -->
            <div class="mt-4">
                <h4>აღწერა</h4>
                <p>{{ $book->description ?? 'აღწერა არ არის დამატებული.' }}</p>

                <h4> დეტალები </h4>

                <table class="table table-striped table-hover">
                     
                    <tbody>
                      <tr>
                        <td>გვერდების რაოდენობა</td>
                         <td>{{ $book->pages }}</td>
                      </tr>
                      <tr>
                         <td>გამოცემის თარიღი</td>
                         <td>{{ $book->publishing_date }} წელი </td>
                      </tr>
                      <tr>
                         <td>ყდა</td>
                         <td>{{ $book->cover }}</td>
                      </tr>
                      <tr>
                        <td>მდგომარეობა</td>
                        <td>{{ $book->status }}</td>
                     </tr>
                    </tbody>
                  </table>

            </div>
        </div>
    </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">{{ $book->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="img-fluid" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal" id="thumbnailImage">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


<script> 
// quantity function
document.addEventListener('DOMContentLoaded', function () {
    const maxQuantity = {{ $book->quantity }}; // Maximum quantity from the database
    const pricePerUnit = {{ $book->price }}; // The price per unit from the database

    const quantityInput = document.querySelector('.quantity-input');
    const priceElement = document.getElementById('price');
    const decreaseButton = document.querySelector('.decrease-quantity');
    const increaseButton = document.querySelector('.increase-quantity');

    function updatePrice() {
        const quantity = parseInt(quantityInput.value);
        const totalPrice = pricePerUnit * quantity;
        priceElement.textContent = totalPrice.toFixed(2);
    }

    increaseButton.addEventListener('click', function () {
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity < maxQuantity) {
            currentQuantity += 1;
            quantityInput.value = currentQuantity;
            updatePrice();
        }
    });

    decreaseButton.addEventListener('click', function () {
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
            currentQuantity -= 1;
            quantityInput.value = currentQuantity;
            updatePrice();
        }
    });

    // Initial price calculation (if the quantity is set initially)
    updatePrice();
});


</script>

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
        var quantity = parseInt($('#quantity').val()); // Get the selected quantity

        $.ajax({
            url: '{{ route("cart.toggle") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                book_id: bookId,
                quantity: quantity // Send the quantity to the server
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