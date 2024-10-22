@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Order Received</h1>
    <p>Your order has been received. Pay with the courier upon delivery.</p>

    <!-- Display order details -->
    <h3>Order Details</h3>
    <p>Order ID: {{ $order->id }}</p>
    <p>Name: {{ $order->user->name }}</p>
    <p>Total: {{ number_format($order->total, 2) }} ლარი</p>

    <h4>Items:</h4>
    <ul>
        @foreach ($order->orderItems as $item)
            <li>{{ $item->book->title }} - {{ $item->quantity }} x {{ number_format($item->price, 2) }} ლარი</li>
        @endforeach
    </ul>
</div>
@endsection
