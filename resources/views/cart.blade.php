@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

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
        <p>შენი კალათა ცარიელია.</p>
    @else
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th style="width: 80px">ყდა</th>
                    <th>დასახელება</th>
                    <th>ავტორი</th>
                    <th>ფასი</th>
                    <th>რაოდენობა</th>
                    <th>სრული ფასი</th>
                    <th>ქმედება</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart->cartItems as $item)
                    <tr>
                        <td><img src="{{ asset('storage/' . $item->book->photo) }}" alt="{{ $item->book->title }}" width="80px" height="100px" class="img-fluid shadow"></td>
                        <td>{{ $item->book->title }}</td>
                        <td>{{ $item->book->author->name }}</td>
                        <td>{{ number_format($item->price) }} ლარი</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity) }} ლარი</td>
                        <td>
                        

                            <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST" onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">წაშლა კალათიდან</button>
                            </form>
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4" style="position: relative; top:-10px">
             
            <h4>სრული თანხა: {{ number_format($total) }} ლარი</h4>
        </div>



        <div style="padding: 7px 33px 10px 33px; background-color: rgb(154, 181, 238); border:1px solid #837979"> 
        <!-- Payment Method and Personal Info Form -->
        <form action="{{ route('checkout') }}" method="POST">
            @csrf
            <h5 class="mt-4" style="padding-bottom: 15px">აირჩიე გადახდის მეთოდი:</h5>

            <!-- Radio buttons for payment -->
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="payment_courier" value="courier" required>
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
                    <label for="name" class="form-label">სახელი და გვარი</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">ტელეფონის ნომერი</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">მისამართი</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
            </div>

            <!-- Conditional button for bank transfer -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary" id="courier_order_btn" style="display: none;">კურიერთან გადახდა</button>
                <button type="submit" class="btn btn-success" id="bank_transfer_btn" style="display: none;">საბანკო გადარიცხვა</button>
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

@endsection
