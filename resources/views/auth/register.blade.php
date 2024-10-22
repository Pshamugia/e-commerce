@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container mt-5 col-md-6" style="position: relative; padding-top:10%; padding-bottom:5%;">
    
    <div style="border-radius: 5px; border:1px solid #ccc; padding: 22px">

        <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mt-4">
             <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" placeholder="სახელი" aria-label="Name" name="name" :value="old('name')" required autofocus autocomplete="name">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
             <div class="input-group mb-3">
                <span class="input-group-text">@</span>
                <input type="email" class="form-control" placeholder="ელფოსტა" aria-label="Email" name="email" :value="old('email')" required autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
             <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control" placeholder="პაროლი" aria-label="Password" name="password" required autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
             <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control" placeholder="გაიმეორეთ პაროლი" aria-label="Confirm Password" name="password_confirmation" required autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('უკვე რეგისტრირებული ხარ?') }}
            </a>

            <button type="submit" class="btn btn-success" style="margin-left:5%">
                {{ __('რეგისტრაცია') }}
            </button>
        </div>
    </form> </div>
</div>
@endsection
