@extends('layouts.app')

@section('content')
 
<div class="container">

    <h5 class="section-title" style="position: relative; padding-top:55px; padding-bottom:20px; align-items: left;
    justify-content: left;">
        <strong>
            <i class="bi bi-cart-check-fill"></i> შენი კალათა
        </strong>
    </h5>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(!$cart || $cart->cartItems->isEmpty())
    <div class="empty-cart text-center" style="padding: 50px;">
        <i class="bi bi-cart-x-fill" style="font-size: 64px; color: #ff6b6b;"></i> <!-- Cart icon from Bootstrap Icons -->
        <h3 style="color: #2c3e50; margin-top: 20px;">შენი კალათა ცარიელია.</h3>
        <p style="color: #7f8c8d;">შეგიძლია დაამატო წიგნები კალათაში</p>
        <a href="{{ route('books') }}" class="btn btn-primary mt-3">
            წიგნების ნახვა
        </a>
    </div>
    @else

    
     <!-- Make the table scrollable on mobile -->
     <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th style="text-align: left; vertical-align: middle;">პროდუქცია</th>
                    <th style="text-align: center; vertical-align: middle;">რაოდენობა</th>
                    <th style="text-align: center; vertical-align: middle;">ფასი</th>
                    <th class="d-none d-md-table-cell" style="text-align: center; vertical-align: middle;">ქმედება</th> <!-- Hidden on mobile -->
                </tr>
            </thead>
            <tbody>
                @foreach($cart->cartItems as $item)
                    <tr>
                        <td style="text-align: left; vertical-align: middle;">
                            <a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}" class="card-link" style="text-decoration: none; max-width: 150px; ">
                                <img src="{{ asset('storage/' . $item->book->photo) }}" alt="{{ $item->book->title }}" width="80px" height="100px" align="left" class="img-fluid shadow" style="margin-right: 10px">
                                {{ $item->book->title }}
                            </a>
                            <br>{{ $item->book->author->name }}

                              <!-- Add a new row for the delete button on mobile -->
         <div class="d-md-none" style="top: 15px; position:relative">
            <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST" onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
               @csrf
               <button type="submit" class="btn btn-danger btn-sm btn-block deletion">წაშლა</button>
           </form>
       </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <div class="input-group" style="width: 100px; margin: auto;">
                                <button class="btn btn-outline-secondary decrease-quantity btn-sm" type="button" data-book-id="{{ $item->book->id }}">-</button>
                                <input type="text" class="form-control form-control-sm text-center quantity-input" value="{{ $item->quantity }}" readonly>
                                <button class="btn btn-outline-secondary increase-quantity btn-sm" type="button" data-book-id="{{ $item->book->id }}">+</button>
                    </div>
       
                            
                        </td>
                        <td style="text-align: center; vertical-align: middle;">{{ number_format($item->price * $item->quantity) }} ლარი</td>
                        <!-- Remove button on larger screens, hidden on mobile -->
                        <td class="d-none d-md-table-cell" style="text-align: center; vertical-align: middle;">
                            <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST" onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">წაშლა კალათიდან</button>
                            </form>
                        </td>
                    </tr>
                   
                @endforeach
                <tr style="background-color: #c40b0b; color:white">
                    <td colspan="2" style="text-align: right; vertical-align: middle; font-weight: bold;">სრული თანხა:</td>
                    <td rowspan="{{ $cart->cartItems->count() }}" style="text-align: center; vertical-align: middle;">
                        <h3 style="text-align: center; vertical-align: middle; top:2px; position: relative; font-size: 20px">{{ number_format($total) }} ლარი</h3>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    

      


        <div style="padding: 7px 33px 10px 33px; background-color: rgb(154, 181, 238); border:1px solid #837979"> 
        <!-- Payment Method and Personal Info Form -->
        <form action="{{ route('checkout') }}" method="POST">
            @csrf
            <h4 class="mt-4" style="bottom: -5px"> <strong> მონიშნე გადახდის ფორმა </strong></h4>

            <!-- Radio buttons for payment -->
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="payment_courier" value="courier" required style="">
                <label class="form-check-label" for="payment_courier">
                    გადახდა კურიერთან
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="payment_bank" value="bank_transfer" required>
                <label class="form-check-label" for="payment_bank">
                    საბანკო გადარიცხვა
                </label>
            </div>

            <!-- User details -->
            <div class="mt-4" >
                 <div class="mb-3">
                    <label for="name" class="form-label" style="position: relative; top:10px"><h4><strong> სახელი და გვარი </strong> </h4></label>
                    <input type="text" class="form-control" placeholder="სახელი გვარი" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label" style="position: relative; top:10px"><h4><strong>  ტელეფონის ნომერი </strong></h4></label>
                    <input type="text" class="form-control" id="phone" placeholder="აქ ჩაწერე შენი მოქმედი ტელეფონი, რომ კურიერმა შეძლოს დაკავშირება" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label" style="position: relative; top:10px"><h4><strong> მისამართი </strong></h4></label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="აქ ჩაწერე ზუსტი მისამართი, სადაც პროდუქცია უნდა მოგიტანოთ" required>
                </div>
            </div>

            <!-- Conditional button for bank transfer -->
            <div class="mt-4">
                <button type="submit" class="btn btn-danger" id="courier_order_btn" style="display: none;">
                    <span style="top: 3px !important; position: relative;">კურიერთან გადახდა</span>
                </button>
                <button type="submit" class="btn btn-success" id="bank_transfer_btn" style="display: none;">
                    <span style="top: 3px !important; position: relative;"> საბანკო გადარიცხვა</span>
                </button>
            </div>
        </form>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const courierRadio = document.getElementById('payment_courier');
        const bankRadio = document.getElementById('payment_bank');
        const courierOrderBtn = document.getElementById('courier_order_btn');
        const bankTransferBtn = document.getElementById('bank_transfer_btn');

        courierRadio.addEventListener('change', function() {
            if (this.checked) {
                courierOrderBtn.style.display = 'inline-block';
                bankTransferBtn.style.display = 'none';
            }
        });

        bankRadio.addEventListener('change', function() {
            if (this.checked) {
                bankTransferBtn.style.display = 'inline-block';
                courierOrderBtn.style.display = 'none';
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Increase quantity
        document.querySelectorAll('.increase-quantity').forEach(function(button) {
            button.addEventListener('click', function () {
                var bookId = this.getAttribute('data-book-id');
                updateQuantity(bookId, 'increase');
            });
        });

        // Decrease quantity
        document.querySelectorAll('.decrease-quantity').forEach(function(button) {
            button.addEventListener('click', function () {
                var bookId = this.getAttribute('data-book-id');
                updateQuantity(bookId, 'decrease');
            });
        });

        function updateQuantity(bookId, action) {
            $.ajax({
                url: '{{ route("cart.updateQuantity") }}',  // You need to define this route in your web.php
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    book_id: bookId,
                    action: action  // Pass either "increase" or "decrease"
                },
                success: function(response) {
                    if (response.success) {
                        // Reload the page or update the cart summary without refreshing
                        location.reload();  // This is simple; you can replace this with a more elegant update
                    } else {
                        alert('Unable to update quantity.');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });
</script>
@endsection
