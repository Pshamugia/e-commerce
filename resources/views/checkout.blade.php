@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mt-5">
    <h3>Checkout</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('checkout.create') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="amount" class="form-label">თანხა (GEL)</label>
            <input type="number" name="amount" id="amount" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">გადახდის გვერდზე გადასვლა</button>
    </form>
</div>
@endsection
