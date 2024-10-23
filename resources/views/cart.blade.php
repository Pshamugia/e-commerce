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
    <div class="empty-cart text-center" style="padding: 50px;">
        <i class="bi bi-cart-x-fill" style="font-size: 64px; color: #ff6b6b;"></i> <!-- Cart icon from Bootstrap Icons -->
        <h3 style="color: #2c3e50; margin-top: 20px;">შენი კალათა ცარიელია.</h3>
        <p style="color: #7f8c8d;">შეგიძლია დაამატო წიგნები კალათაში</p>
        <a href="{{ route('books') }}" class="btn btn-primary mt-3">
            წიგნების ნახვა
        </a>
    </div>
    @else
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th style="width: 80px; text-align: center; vertical-align: middle;">ყდა</th>
                <th style="text-align: center; vertical-align: middle;">დასახელება</th>
                <th style="text-align: center; vertical-align: middle;">ავტორი</th>
                 <th style="text-align: center; vertical-align: middle;">რაოდენობა</th>
                <th style="text-align: center; vertical-align: middle;">ფასი</th>
                <th style="text-align: center; vertical-align: middle;">ქმედება</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart->cartItems as $item)
                <tr>
                    <td style="text-align: center; vertical-align: middle;">
                        <img src="{{ asset('storage/' . $item->book->photo) }}" alt="{{ $item->book->title }}" width="80px" height="100px" class="img-fluid shadow">
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}" class="card-link">
                            {{ $item->book->title }}
                        </a>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">{{ $item->book->author->name }}</td>
                     <td style="text-align: center; vertical-align: middle;">{{ $item->quantity }}</td>
                    <td style="text-align: center; vertical-align: middle;">{{ number_format($item->price * $item->quantity) }} ლარი</td>
                    <td style="text-align: center; vertical-align: middle;">
                        <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST" onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">წაშლა კალათიდან</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <tr style="background-color: #c40b0b; color:white">
                <td colspan="5" style="text-align: right; vertical-align: middle; font-weight: bold;">სრული თანხა:</td>
                <td rowspan="{{ $cart->cartItems->count() }}" style="text-align: center; vertical-align: middle;">
                    <h3 style="text-align: center; vertical-align: middle; top:2px; position: relative; font-size: 20px">{{ number_format($total) }} ლარი</h3>
                </td>
            </tr>
        </tbody>
    </table>
    

      


        <div style="padding: 7px 33px 10px 33px; background-color: rgb(154, 181, 238); border:1px solid #837979"> 
        <!-- Payment Method and Personal Info Form -->
        <form action="{{ route('checkout') }}" method="POST">
            @csrf
            <h4 class="mt-4" style="bottom: -5px"> <strong> აირჩიე გადახდის მეთოდი: </strong></h4>

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
                <button type="submit" class="btn btn-danger" id="courier_order_btn" style="display: none;">კურიერთან გადახდა</button>
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
